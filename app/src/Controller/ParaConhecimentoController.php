<?php

namespace App\Controller;

use App\UseCase\Mensagem\ParaConhecimento\AdicionarParaConhecimento;
use App\UseCase\Mensagem\ParaConhecimento\CadastrarParaConhecimento;
use App\UseCase\Mensagem\ParaConhecimento\CienteParaConhecimento;
use App\UseCase\Mensagem\ParaConhecimento\ListarMensagensParaConhecimento;
use App\UseCase\Mensagem\ParaConhecimento\RemoverParaConhecimento;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/mensagem/para-conhecimento')]
class ParaConhecimentoController extends AbstractController
{
    private array $contextJsonSerialize;

    public function __construct(private SerializerInterface $serializer) {        
        $this->contextJsonSerialize = (new ObjectNormalizerContextBuilder())
                                        ->withGroups('show_mensagem')
                                        ->toArray();
    }

    #[Route('/listar', name: 'app_para_conhecimento_list', methods: ['GET'])]
    public function listar(ListarMensagensParaConhecimento $listarMensagensParaConhecimento) : JsonResponse
    {
        $mensagens = $listarMensagensParaConhecimento->executar();

        return JsonResponse::fromJsonString($this->serializer->serialize($mensagens, 'json', $this->contextJsonSerialize));
    }

    #[Route('/{idMensagem}', name: 'app_para_conhecimento_new', methods: ['POST'])]
    public function criar(Request $request, int $idMensagem, CadastrarParaConhecimento $cadastrarParaConhecimento): JsonResponse
    {
        $inputData = $request->toArray();
        $cadastrarParaConhecimento->executar($idMensagem, $inputData);

        return $this->json(
            ['mensagem' => 'Para Conhecimento cadastrado/alterado com sucesso! ']
        );
    }

    #[Route('/adicionar/{idMensagem}', name: 'app_para_conhecimento_add', methods: ['PUT'])]
    public function adicionar(Request $request, int $idMensagem, AdicionarParaConhecimento $adicionarParaConhecimento): JsonResponse
    {
        $inputData = $request->toArray();
        $adicionarParaConhecimento->executar($idMensagem, $inputData);

        return $this->json(
            ['mensagem' => 'Para Conhecimento adicionado com sucesso! ']
        );
    }

    #[Route('/remover/{idMensagem}', name: 'app_para_conhecimento_delete', methods: ['DELETE'])]
    public function remover(Request $request, int $idMensagem, RemoverParaConhecimento $removerParaConhecimento): JsonResponse
    {
        $inputData = $request->toArray();
        $removerParaConhecimento->executar($idMensagem, $inputData);

        return $this->json(
            ['mensagem' => 'Para Conhecimento removido com sucesso! ']
        );
    }

    #[Route('/ciente/{idMensagem}', name: 'app_para_conhecimento_aware', methods: ['PUT'])]
    public function ciente(Request $request, int $idMensagem, CienteParaConhecimento $cienteParaConhecimento): JsonResponse
    {
        $inputData = $request->toArray();
        $cienteParaConhecimento->executar($idMensagem, $inputData);

        return $this->json(
            ['mensagem' => 'Usuário ciente! ']
        );
    }
}
