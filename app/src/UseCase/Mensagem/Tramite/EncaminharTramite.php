<?php

namespace App\UseCase\Mensagem\Tramite;

use App\Entity\Usuario;
use App\Repository\MensagemRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;

class EncaminharTramite {
    private Usuario $usuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private MensagemRepository $mensagemRepository, private UsuarioRepository $usuarioRepository){
        // Pegar usuário logado //
        $idUsuario = 12;
        $this->usuarioLogado = $this->usuarioRepository->find($idUsuario);
        //$usuario->getUnidade()
    }

    public function executar($idMensagem) {
        $mensagem = $this->mensagemRepository->find($idMensagem);
        $tramite = $mensagem->getTramite($this->usuarioLogado->getUnidade());
        $tramite->encaminhar();

        // Efetua as alterações no banco de dados
        $this->entityManager->flush();
    }
}