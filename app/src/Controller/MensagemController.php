<?php

namespace App\Controller;

use App\Entity\Mensagem;
use App\Entity\Usuario;
use App\Repository\MensagemRepository;
use App\Repository\UnidadeRepository;
use App\Repository\UsuarioRepository;
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

        foreach($mensagens as $mensagem) {

            //Remove do objeto $mensagem os trâmites que não são da OM do usuário. Pois ele não tem acesso de visualização desses tramites.
            $this->entityManager->detach($mensagem);
            foreach($mensagem->getTramites() as $tramite) {
                if ($tramite->getUnidade()->getId() !== $this->usuarioLogado->getUnidade()->getId()) {
                    $mensagem->getTramites()->removeElement($tramite);
                }
            }
        }

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('show_mensagem')
            ->toArray();

        return JsonResponse::fromJsonString($this->serializer->serialize($mensagens, 'json', $context));
    }

    #[Route('/{idMensagem}', name: 'app_mensagem_show', methods: ['GET'])]
    public function show(int $idMensagem): JsonResponse
    {
        $mensagem = $this->mensagemRepository->find($idMensagem);

        //Remove do objeto $mensagem os trâmites que não são da OM do usuário. Pois ele não tem acesso de visualização desses tramites.
        $this->entityManager->detach($mensagem);
        foreach($mensagem->getTramites() as $tramite) {
            if ($tramite->getUnidade()->getId() !== $this->usuarioLogado->getUnidade()->getId()) {
                $mensagem->getTramites()->removeElement($tramite);
            }
        }

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('show_mensagem')
            ->toArray();

        return JsonResponse::fromJsonString($this->serializer->serialize($mensagem, 'json', $context));
    }

    #[Route('', name: 'app_mensagem_new', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $unidadesDestino = $this->getUnidadesDestinoByRequest($request);
        $unidadesInformacao = $this->getUnidadesInformacaoByRequest($request);
        
        $mensagem = new Mensagem($request->toArray(), $this->usuarioLogado->getUnidade(), $unidadesDestino, $unidadesInformacao);

        // Informa ao Doctrine que você deseja salvar esse novo objeto, quando for efetuado o flush.
        $this->entityManager->persist($mensagem);

        // Efetua as alterações no banco de dados
        $this->entityManager->flush();

        return $this->json(
            ['mensagem' => 'cadastrado com sucesso!',
            'idMensagem' => $mensagem->getId()]
        );
    }

    #[Route('/{idMensagem}', name: 'app_mensagem_edit', methods: ['PUT'])]
    public function edit(Request $request, int $idMensagem): JsonResponse
    {
        $mensagem = $this->mensagemRepository->find($idMensagem);

        $unidadesDestino = $this->getUnidadesDestinoByRequest($request);
        $unidadesInformacao = $this->getUnidadesInformacaoByRequest($request);

        $mensagem->carregarValores($request->toArray(), $unidadesDestino, $unidadesInformacao);

        // Efetua as alterações no banco de dados
        $this->entityManager->flush();

        return $this->json(
            ['mensagem' => 'atualizado com sucesso!']
        );
    }

    #[Route('/{idMensagem}', name: 'app_mensagem_delete', methods: ['DELETE'])]
    public function delete(int $idMensagem): JsonResponse
    {
        $mensagem = $this->mensagemRepository->find($idMensagem);

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