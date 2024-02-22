<?php

namespace App\UseCase;

use App\Entity\Usuario;
use App\Repository\MensagemRepository;
use Doctrine\ORM\EntityManagerInterface;

class ListarMensagens {
        
    public function __construct(private Usuario $usuarioLogado, private EntityManagerInterface $entityManager, private MensagemRepository $mensagemRepository)
    {
        
    }

    public function Executar() {
        /*$mensagens = $this->mensagemRepository->findBy(['unidade_origem' => $this->usuarioLogado->getUnidade()->getId()]);

        foreach($mensagens as $mensagem) {

            //Remove do objeto $mensagem os trâmites que não são da OM do usuário. Pois ele não tem acesso de visualização desses tramites.
            $this->entityManager->detach($mensagem);
            foreach($mensagem->getTramites() as $tramite) {
                if ($tramite->getUnidade()->getId() !== $this->usuarioLogado->getUnidade()->getId()) {
                    $mensagem->getTramites()->removeElement($tramite);
                }
            }
        }

        return $mensagens;*/
    }
}