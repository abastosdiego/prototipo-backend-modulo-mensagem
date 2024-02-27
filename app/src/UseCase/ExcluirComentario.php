<?php

namespace App\UseCase;

use App\Repository\MensagemRepository;
use Doctrine\ORM\EntityManagerInterface;

class ExcluirComentario {


    public function __construct(private EntityManagerInterface $entityManager, private MensagemRepository $mensagemRepository)
    {
        
    }

    public function executar(int $idMensagem, int $idComentario): void {
        $mensagem = $this->mensagemRepository->find($idMensagem);
        $mensagem->removeComentario($idComentario);

        // Efetua as alterações no banco de dados
        $this->entityManager->flush();
    }
}