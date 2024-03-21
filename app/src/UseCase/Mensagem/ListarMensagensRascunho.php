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
        $mensagens = $this->mensagemRepository->listarMensagensRascunho($this->usuarioLogado->getUnidade()->getId(), $this->usuarioLogado->getId());

        foreach($mensagens as $mensagem) {

            //Remove do objeto $mensagem os trâmites que não são da OM do usuário. Pois ele não tem acesso de visualização desses tramites.
            $this->entityManager->detach($mensagem);
            foreach($mensagem->getTramites() as $tramite) {
                if ($tramite->getUnidade()->getId() !== $this->usuarioLogado->getUnidade()->getId()) {
                    $mensagem->getTramites()->removeElement($tramite);
                }
            }
        }

        return $mensagens;
    }
}