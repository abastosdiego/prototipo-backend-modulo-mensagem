<?php

namespace App\Controller;

use App\Entity\Mensagem;
use App\Entity\Unidade;
use App\Repository\MensagemRepository;
use App\Repository\UnidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/mensagem')]
class MensagemController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private SerializerInterface $serializer, private MensagemRepository $mensagemRepository, private UnidadeRepository $unidadeRepository){}

    #[Route('/unidade/{siglaUnidade}', name: 'app_mensagem_index', methods: ['GET'])]
    public function index(string $siglaUnidade) : JsonResponse
    {
        $unidade = $this->unidadeRepository->findOneBy(['sigla' => $siglaUnidade]);

        $mensagens = $this->mensagemRepository->findBy(['unidadeOrigem' => $unidade->getId()]);
        
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('show_mensagem')
            ->toArray();

        return JsonResponse::fromJsonString($this->serializer->serialize($mensagens, 'json', $context));
    }

    #[Route('/{id}', name: 'app_mensagem_show', methods: ['GET'])]
    public function show(Mensagem $mensagem): JsonResponse
    {
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('show_mensagem')
            ->toArray();

        return JsonResponse::fromJsonString($this->serializer->serialize($mensagem, 'json', $context));
    }

    #[Route('', name: 'app_mensagem_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {

        $unidadeOrigem = $this->getUnidadeOrigemByRequest($request);
        $unidadesDestino = $this->getUnidadesDestinoByRequest($request);
        $unidadesInformacao = $this->getUnidadesInformacaoByRequest($request);

        $mensagem = new Mensagem($request->toArray(), $unidadeOrigem, $unidadesDestino, $unidadesInformacao);

        // Informa ao Doctrine que você deseja salvar esse novo objeto, quando for efetuado o flush.
        $entityManager->persist($mensagem);

        // Efetua as alterações no banco de dados
        $entityManager->flush();

        return $this->json(
            ['mensagem' => 'cadastrado com sucesso!']
        );
    }

    #[Route('/{mensagem}', name: 'app_mensagem_edit', methods: ['PUT'])]
    public function edit(Request $request, Mensagem $mensagem): JsonResponse
    {
        $unidadeOrigem = $this->getUnidadeOrigemByRequest($request);
        $unidadesDestino = $this->getUnidadesDestinoByRequest($request);
        $unidadesInformacao = $this->getUnidadesInformacaoByRequest($request);

        $mensagem->alterarValores($request->toArray(), $unidadeOrigem, $unidadesDestino, $unidadesInformacao);

        // Efetua as alterações no banco de dados
        $this->entityManager->flush();

        return $this->json(
            ['mensagem' => 'atualizado com sucesso!']
        );
    }

    #[Route('/{mensagem}', name: 'app_mensagem_delete', methods: ['DELETE'])]
    public function delete(Request $request, Mensagem $mensagem): JsonResponse
    {
        if ($mensagem){

            $this->entityManager->remove($mensagem);
            $this->entityManager->flush();

            return $this->json(
                ['mensagem' => 'excluído com sucesso!']
            );
        }
    }

    private function getUnidadeOrigemByRequest(Request $request) : Unidade | null {
        $unidadeOrigem = null;
        if (isset($request->toArray()['unidadeOrigemSigla'])) {
            $unidadeOrigem = $this->unidadeRepository->findOneBy(['sigla' => $request->toArray()['unidadeOrigemSigla']]);
        }
        return $unidadeOrigem;
    }

    private function getUnidadesDestinoByRequest(Request $request) : array {
        $unidadesDestinoSiglas = $request->toArray()['unidadesDestinoSiglas'];
        $unidadesDestino = [];

        if (isset($unidadesDestinoSiglas)) {
            foreach($unidadesDestinoSiglas as $sigla) {
                $unidade = $this->unidadeRepository->findOneBy(['sigla' => $sigla]);
                array_push($unidadesDestino, $unidade);
            }
        }
        return $unidadesDestino;
    }

    private function getUnidadesInformacaoByRequest(Request $request) : array {
        $unidadesInformacaoSiglas = $request->toArray()['unidadesInformacaoSiglas'];
        $unidadesInformacao = [];

        if (isset($unidadesInformacaoSiglas)) {
            foreach($unidadesInformacaoSiglas as $sigla) {
                $unidade = $this->unidadeRepository->findOneBy(['sigla' => $sigla]);
                array_push($unidadesInformacao, $unidade);
            }
        }
        return $unidadesInformacao;
    }
}