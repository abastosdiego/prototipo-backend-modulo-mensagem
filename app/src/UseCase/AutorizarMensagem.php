<?php

namespace App\UseCase;

use App\Repository\MensagemRepository;
use Doctrine\ORM\EntityManagerInterface;

class AutorizarMensagem {

    public function __construct(private EntityManagerInterface $entityManager, private MensagemRepository $mensagemRepository) {}

    public function executar(int $idMensagem) {
        $mensagem = $this->mensagemRepository->find($idMensagem);
        $mensagem->autorizar();

        // Efetua as alterações no banco de dados
        $this->entityManager->flush();
    }
}