<?php

namespace App\UseCase\Mensagem;

use App\Repository\MensagemRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;

class BuscarMensagemPeloId {
    private int $idUnidadeUsuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private MensagemRepository $mensagemRepository, private UsuarioRepository $usuarioRepository)
    {
        // Pegar usuário logado //
        $idUsuario = 12;
        $this->idUnidadeUsuarioLogado = $this->usuarioRepository->find($idUsuario)
                                            ->getUnidade()
                                            ->getId();
    }

    public function executar(int $idMensagem) {
        //$this->entityManager = $entityManager;

        $mensagem = $this->mensagemRepository->find($idMensagem);

        //Remove do objeto $mensagem os trâmites que não são da OM do usuário. Pois ele não tem acesso de visualização desses tramites.
        $this->entityManager->detach($mensagem);
        foreach($mensagem->getTramites() as $tramite) {
            if ($tramite->getUnidade()->getId() !== $this->idUnidadeUsuarioLogado) {
                $mensagem->getTramites()->removeElement($tramite);
            }
        }

        return $mensagem;
    }
}