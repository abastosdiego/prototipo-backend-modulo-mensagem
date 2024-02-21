<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\MensagemRepository;
use App\Repository\UnidadeRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mensagem/comentario')]
class ComentarioController extends AbstractController
{
    private Usuario $usuarioLogado;
    
    public function __construct(private EntityManagerInterface $entityManager, private MensagemRepository $mensagemRepository, private UnidadeRepository $unidadeRepository, private UsuarioRepository $usuarioRepository) {
        // Pegar usuário logado //
        $idUsuario = 12;
        $this->usuarioLogado = $this->usuarioRepository->find($idUsuario);
        //$this->usuarioLogado->getUnidade()->getId()
    }

    #[Route('/{idMensagem}', name: 'app_comentario_new', methods: ['POST'])]
    public function new(Request $request, int $idMensagem): JsonResponse
    {
        $mensagem = $this->mensagemRepository->find($idMensagem);

        $texto = $request->toArray()['texto'];

        $comentario = $mensagem->addComentario($texto, $this->usuarioLogado);

        ///// Informa ao Doctrine que você deseja salvar esse novo objeto, quando for efetuado o flush.
        $this->entityManager->persist($mensagem);

        ///// Efetua as alterações no banco de dados
        $this->entityManager->flush();

        return $this->json(
            ['mensagem' => 'Comentário cadastrado com sucesso!',
            'idComentario' => $comentario->getId()]
        );
    }

    #[Route('/{idMensagem}/{idComentario}', name: 'app_comentario_edit', methods: ['PUT'])]
    public function edit(Request $request, int $idMensagem, int $idComentario): JsonResponse
    {
        $mensagem = $this->mensagemRepository->find($idMensagem);
        $comentario = $mensagem->getComentario($idComentario);
        $comentario->alterarTexto($request->toArray()['texto']);

        // Efetua as alterações no banco de dados
        $this->entityManager->flush();

        return $this->json(
            ['mensagem' => 'Comentário alterado com sucesso!']
        );
    }

    #[Route('/{idMensagem}/{idComentario}', name: 'app_comentario_delete', methods: ['DELETE'])]
    public function delete(int $idMensagem, int $idComentario): JsonResponse
    {
        $mensagem = $this->mensagemRepository->find($idMensagem);
        $mensagem->removeComentario($idComentario);

        // Efetua as alterações no banco de dados
        $this->entityManager->flush();

        return $this->json(
            ['mensagem' => 'Comentário excluído com sucesso!']
        );
    }

}
