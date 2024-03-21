<?php

namespace App\UseCase\Mensagem;

use App\Repository\MensagemRepository;
use Doctrine\ORM\EntityManagerInterface;

class ExcluirMensagem {

    public function __construct(private EntityManagerInterface $entityManager, private MensagemRepository $mensagemRepository) {}

    public function executar(int $idMensagem): void {
        $mensagem = $this->mensagemRepository->find($idMensagem);

        if ($mensagem){
            $this->entityManager->remove($mensagem);
            $this->entityManager->flush();
        }
    }
}