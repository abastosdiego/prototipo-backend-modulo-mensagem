<?php

namespace App\Controller;

use App\Entity\Mensagem;
use App\Entity\Unidade;
use App\Entity\Usuario;
use App\Repository\MensagemRepository;
use App\Repository\UnidadeRepository;
use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/mensagem')]
class MensagemController extends AbstractController
{
    private Usuario $usuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private SerializerInterface $serializer, private MensagemRepository $mensagemRepository, private UnidadeRepository $unidadeRepository, private UsuarioRepository $usuarioRepository, private LoggerInterface $logger) {
        // Pegar usuário logado //
        $idUsuario = 12;
        $this->usuarioLogado = $this->usuarioRepository->find($idUsuario);
        //$this->usuarioLogado->getUnidade()->getId()
    }

    #[Route('/', name: 'app_mensagem_index', methods: ['GET'])]
    public function index() : JsonResponse
    {
        $mensagens = $this->mensagemRepository->findBy(['unidadeOrigem' => $this->usuarioLogado->getUnidade()->getId()]);
        
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
        $unidadesDestino = $this->getUnidadesDestinoByRequest($request);
        $unidadesInformacao = $this->getUnidadesInformacaoByRequest($request);
        
        $mensagem = new Mensagem($request->toArray(), $this->usuarioLogado->getUnidade(), $unidadesDestino, $unidadesInformacao);

        // Informa ao Doctrine que você deseja salvar esse novo objeto, quando for efetuado o flush.
        $entityManager->persist($mensagem);

        // Efetua as alterações no banco de dados
        $entityManager->flush();

        return $this->json(
            ['mensagem' => 'cadastrado com sucesso!',
            'idMensagem' => $mensagem->getId()]
        );
    }

    #[Route('/{mensagem}', name: 'app_mensagem_edit', methods: ['PUT'])]
    public function edit(Request $request, Mensagem $mensagem): JsonResponse
    {
        $unidadesDestino = $this->getUnidadesDestinoByRequest($request);
        $unidadesInformacao = $this->getUnidadesInformacaoByRequest($request);

        $mensagem->carregarValores($request->toArray(), $unidadesDestino, $unidadesInformacao);

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

    private function getUnidadesDestinoByRequest(Request $request) : array {
        $unidadesDestinoSiglas = $request->toArray()['unidadesDestinoSiglas'];
        $unidadesDestino = [];

        if (isset($unidadesDestinoSiglas)) {
            foreach($unidadesDestinoSiglas as $sigla) {

                //$this->logger->debug('sigla: '.$sigla);

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