<?php

namespace App\UseCase;

use App\Entity\Usuario;
use App\Repository\MensagemRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;

class CadastrarComentario {
    private Usuario $usuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private MensagemRepository $mensagemRepository, private UsuarioRepository $usuarioRepository)
    {
        // Pegar usuário logado //
        $idUsuario = 12;
        $this->usuarioLogado = $this->usuarioRepository->find($idUsuario);
    }

    public function executar(int $idMensagem, array $inputData): int {
        $mensagem = $this->mensagemRepository->find($idMensagem);

        $texto = $inputData['texto'];

        $comentario = $mensagem->addComentario($texto, $this->usuarioLogado);

        ///// Informa ao Doctrine que você deseja salvar esse novo objeto, quando for efetuado o flush.
        $this->entityManager->persist($mensagem);

        ///// Efetua as alterações no banco de dados
        $this->entityManager->flush();

        return $comentario->getId();
    }
}