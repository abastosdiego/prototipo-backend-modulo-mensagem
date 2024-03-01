<?php

namespace App\UseCase\Mensagem;

use App\Repository\MensagemRepository;
use Doctrine\ORM\EntityManagerInterface;

class ExcluirMensagem {
    //private Unidade $unidadeUsuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private MensagemRepository $mensagemRepository)
    {
        // Pegar usuário logado //
        //$idUsuario = 12;
        //$this->unidadeUsuarioLogado = $this->usuarioRepository->find($idUsuario)->getUnidade();
    }

    public function executar(int $idMensagem): void {
        $mensagem = $this->mensagemRepository->find($idMensagem);

        if ($mensagem){
            $this->entityManager->remove($mensagem);
            $this->entityManager->flush();
        }
    }
}