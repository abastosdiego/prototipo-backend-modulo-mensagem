<?php

namespace App\UseCase\Mensagem\Tramite;

use App\Entity\Usuario;
use App\Repository\MensagemRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class EncaminharTramite {
    private Usuario $usuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private Security $security, private MensagemRepository $mensagemRepository, private UsuarioRepository $usuarioRepository){
        if ($this->security->getUser() instanceof Usuario) {
            $this->usuarioLogado = $this->security->getUser();
        } else {
            throw new \DomainException('Usuário logado não encontrado!');
        }
    }

    public function executar($idMensagem) {
        $mensagem = $this->mensagemRepository->find($idMensagem);
        $tramite = $mensagem->getTramite($this->usuarioLogado->getUnidade());
        $tramite->encaminhar();

        // Efetua as alterações no banco de dados
        $this->entityManager->flush();
    }
}