<?php

namespace App\Controller;

use App\Entity\Comentario;
use App\Repository\MensagemRepository;
use App\Repository\UnidadeRepository;
use App\Repository\UsuarioRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comentario')]
class ComentarioController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private MensagemRepository $mensagemRepository, private UnidadeRepository $unidadeRepository, private UsuarioRepository $usuarioRepository){}

    #[Route('/{idMensagem}/{idUsuario}', name: 'app_comentario_new', methods: ['POST'])]
    public function new(Request $request, int $idMensagem, int $idUsuario): JsonResponse
    {
        $mensagem = $this->mensagemRepository->find($idMensagem);
        $usuario = $this->usuarioRepository->find($idUsuario);
        $unidade = $usuario->getUnidade();

        $comentario = new Comentario($request->toArray(), $mensagem, $unidade, $usuario);

        $mensagem->addComentario($comentario);

        ///// Informa ao Doctrine que você deseja salvar esse novo objeto, quando for efetuado o flush.
        $this->entityManager->persist($mensagem);

        ///// Efetua as alterações no banco de dados
        $this->entityManager->flush();

        return $this->json(
            ['mensagem' => 'cadastrado com sucesso!']
        );
    }

    #[Route('/{comentario}', name: 'app_comentario_edit', methods: ['PUT'])]
    public function edit(Request $request, Comentario $comentario): JsonResponse
    {
        $comentario->setTexto($request->toArray()['texto']);
        $comentario->setDataHora(new DateTimeImmutable("now"));

        // Efetua as alterações no banco de dados
        $this->entityManager->flush();

        return $this->json(
            ['mensagem' => 'atualizado com sucesso!']
        );
    }

    #[Route('/{comentario}', name: 'app_comentario_delete', methods: ['DELETE'])]
    public function delete(Comentario $comentario): JsonResponse
    {
        if ($comentario){

            $this->entityManager->remove($comentario);
            $this->entityManager->flush();

            return $this->json(
                ['mensagem' => 'excluído com sucesso!']
            );
        }
    }

}
