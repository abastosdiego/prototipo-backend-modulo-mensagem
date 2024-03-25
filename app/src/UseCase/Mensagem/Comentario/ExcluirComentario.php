<?php

namespace App\UseCase\Mensagem\Comentario;

use App\Entity\Usuario;
use App\Repository\MensagemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ExcluirComentario {
    private Usuario $usuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private Security $security, private MensagemRepository $mensagemRepository){
        if ($this->security->getUser() instanceof Usuario) {
            $this->usuarioLogado = $this->security->getUser();
        } else {
            throw new \DomainException('Usuário logado não encontrado!');
        }
    }

    public function executar(int $idMensagem, int $idComentario): void {
        $mensagem = $this->mensagemRepository->find($idMensagem);
        $comentario = $mensagem->getComentario($idComentario);
        
        //RN013
        if ($comentario->getUsuario()->getId() !== $this->usuarioLogado->getId()) { throw new \DomainException("Somente quem realizou o comentário pode excluí-lo!");}

        //RN014
        if (!$comentario->rascunho()) { throw new \DomainException('Este comentário não pode ser excluído, pois não é mais um rascunho!'); }

        $mensagem->removeComentario($idComentario);

        // Efetua as alterações no banco de dados
        $this->entityManager->flush();
    }
}