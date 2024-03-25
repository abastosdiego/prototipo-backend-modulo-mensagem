<?php

namespace App\UseCase\Mensagem;

use App\Entity\Usuario;
use App\Repository\MensagemRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ListarMensagensRascunho {
    private Usuario $usuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private Security $security, private MensagemRepository $mensagemRepository, private UsuarioRepository $usuarioRepository) {
        if ($this->security->getUser() instanceof Usuario) {
            $this->usuarioLogado = $this->security->getUser();
        } else {
            throw new \DomainException('Usuário logado não encontrado!');
        }
    }

    public function executar(): array {

        //RN001
        return $this->mensagemRepository->listarMensagensRascunho($this->usuarioLogado->getUnidade()->getId(), $this->usuarioLogado->getId());
        
    }
}