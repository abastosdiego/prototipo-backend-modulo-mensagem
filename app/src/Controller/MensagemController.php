<?php

namespace App\Controller;

use App\UseCase\Mensagem\ListarMensagens;
use App\UseCase\Mensagem\BuscarMensagemPeloId;
use App\UseCase\Mensagem\CadastrarMensagem;
use App\UseCase\Mensagem\AlterarMensagem;
use App\UseCase\Mensagem\AutorizarMensagem;
use App\UseCase\Mensagem\ExcluirMensagem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/mensagem')]
class MensagemController extends AbstractController
{
    private array $contextJsonSerialize;

    public function __construct(private SerializerInterface $serializer) {        
        $this->contextJsonSerialize = (new ObjectNormalizerContextBuilder())
                                        ->withGroups('show_mensagem')
                                        ->toArray();
    }

    #[Route('/', name: 'app_mensagem_index', methods: ['GET'])]
    public function index(ListarMensagens $listarMensagens) : JsonResponse
    {
        $mensagens = $listarMensagens->executar();

        return JsonResponse::fromJsonString($this->serializer->serialize($mensagens, 'json', $this->contextJsonSerialize));
    }

    #[Route('/{idMensagem}', name: 'app_mensagem_show', methods: ['GET'])]
    public function show(int $idMensagem, BuscarMensagemPeloId $buscarMensagemPeloId): JsonResponse
    {
        $mensagem = $buscarMensagemPeloId->executar($idMensagem);

        return JsonResponse::fromJsonString($this->serializer->serialize($mensagem, 'json', $this->contextJsonSerialize));
    }

    #[Route('', name: 'app_mensagem_new', methods: ['POST'])]
    public function new(Request $request, CadastrarMensagem $cadastrarMensagem): JsonResponse
    {
        $inputData = $request->toArray();
        $idMensagem = $cadastrarMensagem->executar($inputData);

        return $this->json(
            ['mensagem' => 'Mensagem cadastrada com sucesso!',
            'idMensagem' => $idMensagem]
        );
    }

    #[Route('/{idMensagem}', name: 'app_mensagem_edit', methods: ['PUT'])]
    public function edit(Request $request, int $idMensagem, AlterarMensagem $alterarMensagem): JsonResponse
    {
        $inputData = $request->toArray();
        $alterarMensagem->executar($idMensagem, $inputData);

        return $this->json(
            ['mensagem' => 'Mensagem atualizada com sucesso!']
        );
    }

    #[Route('/autorizar/{idMensagem}', name: 'app_mensagem_authorize', methods: ['PUT'])]
    public function autorizar(int $idMensagem, AutorizarMensagem $autorizarMensagem): JsonResponse
    {
        $autorizarMensagem->executar($idMensagem);

        return $this->json(
            ['mensagem' => 'Mensagem autorizada!']
        );
    }

    #[Route('/{idMensagem}', name: 'app_mensagem_delete', methods: ['DELETE'])]
    public function delete(int $idMensagem, ExcluirMensagem $excluirMensagem): JsonResponse
    {
        $excluirMensagem->executar($idMensagem);

        return $this->json(
            ['mensagem' => 'Mensagem excluída com sucesso!']
        );
    }

}