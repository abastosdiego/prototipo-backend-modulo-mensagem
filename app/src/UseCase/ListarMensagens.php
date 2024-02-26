<?php

namespace App\UseCase;

use App\Repository\MensagemRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;

class ListarMensagens {
    private int $idUnidadeUsuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private MensagemRepository $mensagemRepository, private UsuarioRepository $usuarioRepository)
    {
        // Pegar usuário logado //
        $idUsuario = 12;
        $this->idUnidadeUsuarioLogado = $this->usuarioRepository->find($idUsuario)
                                            ->getUnidade()
                                            ->getId();
    }

    public function executar(): array {

        $mensagens = $this->mensagemRepository->findBy(['unidade_origem' => $this->idUnidadeUsuarioLogado]);

        foreach($mensagens as $mensagem) {

            //Remove do objeto $mensagem os trâmites que não são da OM do usuário. Pois ele não tem acesso de visualização desses tramites.
            $this->entityManager->detach($mensagem);
            foreach($mensagem->getTramites() as $tramite) {
                if ($tramite->getUnidade()->getId() !== $this->idUnidadeUsuarioLogado) {
                    $mensagem->getTramites()->removeElement($tramite);
                }
            }
        }

        return $mensagens;
    }
}