<?php

namespace App\UseCase;

use App\Repository\MensagemRepository;
use Doctrine\ORM\EntityManagerInterface;

class AlterarComentario {
    public function __construct(private EntityManagerInterface $entityManager, private MensagemRepository $mensagemRepository)
    {
        
    }

    public function executar(int $idMensagem, int $idComentario, array $inputData) {
        $mensagem = $this->mensagemRepository->find($idMensagem);
        $comentario = $mensagem->getComentario($idComentario);
        $comentario->alterarTexto($inputData['texto']);

        // Efetua as alterações no banco de dados
        $this->entityManager->flush();
    }
}