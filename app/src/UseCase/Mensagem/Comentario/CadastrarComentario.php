<?php

namespace App\UseCase\Mensagem\Comentario;

use App\Entity\Usuario;
use App\Repository\MensagemRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CadastrarComentario {
    private Usuario $usuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private Security $security, private MensagemRepository $mensagemRepository, private UsuarioRepository $usuarioRepository)
    {
        if ($this->security->getUser() instanceof Usuario) {
            $this->usuarioLogado = $this->security->getUser();
        } else {
            throw new \DomainException('Usuário logado não encontrado!');
        }
    }

    public function executar(int $idMensagem, array $inputData): int {
        $mensagem = $this->mensagemRepository->find($idMensagem);

        $texto = $inputData['texto'];

        $comentario = $mensagem->addComentario($texto, $this->usuarioLogado);

        ///// Informa ao Doctrine que você deseja salvar esse novo objeto, quando for efetuado o flush.
        $this->entityManager->persist($mensagem);

        ///// Efetua as alterações no banco de dados
        $this->entityManager->flush();

        return $comentario->getId();
    }
}