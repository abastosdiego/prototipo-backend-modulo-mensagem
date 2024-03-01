<?php

namespace App\Controller;

use App\UseCase\Mensagem\Comentario\CadastrarComentario;
use App\UseCase\Mensagem\Comentario\AlterarComentario;
use App\UseCase\Mensagem\Comentario\ExcluirComentario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mensagem/comentario')]
class ComentarioController extends AbstractController
{

    #[Route('/{idMensagem}', name: 'app_comentario_new', methods: ['POST'])]
    public function new(Request $request, int $idMensagem, CadastrarComentario $cadastrarComentario): JsonResponse
    {
        $idComentario = $cadastrarComentario->executar($idMensagem, $request->toArray());

        return $this->json(
            ['mensagem' => 'Comentário cadastrado com sucesso!',
            'idComentario' => $idComentario]
        );
    }

    #[Route('/{idMensagem}/{idComentario}', name: 'app_comentario_edit', methods: ['PUT'])]
    public function edit(Request $request, int $idMensagem, int $idComentario, AlterarComentario $alterarComentario): JsonResponse
    {
        $inputData = $request->toArray();

        $alterarComentario->executar($idMensagem, $idComentario, $inputData);

        return $this->json(
            ['mensagem' => 'Comentário alterado com sucesso!']
        );
    }

    #[Route('/{idMensagem}/{idComentario}', name: 'app_comentario_delete', methods: ['DELETE'])]
    public function delete(int $idMensagem, int $idComentario, ExcluirComentario $excluirComentario): JsonResponse
    {
        $excluirComentario->executar($idMensagem, $idComentario);

        return $this->json(
            ['mensagem' => 'Comentário excluído com sucesso!']
        );
    }

}
