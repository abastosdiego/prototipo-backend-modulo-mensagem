<?php

namespace App\UseCase\Mensagem;

use App\Entity\Usuario;
use App\Repository\MensagemRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ContarMensagensUsuario {
    private Usuario $usuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private Security $security, private MensagemRepository $mensagemRepository, private UsuarioRepository $usuarioRepository) {
        if ($this->security->getUser() instanceof Usuario) {
            $this->usuarioLogado = $this->security->getUser();
        } else {
            throw new \DomainException('Usuário logado não encontrado!');
        }
    }

    public function executar(): array {

        $arrayQtde = [];

        $qtdeRascunho = $this->mensagemRepository->contarMensagensRascunho($this->usuarioLogado->getUnidade()->getId(),$this->usuarioLogado->getId());
        $qtdeAguardandoTransmissao = $this->mensagemRepository->contarMensagensAguardandoTransmissao($this->usuarioLogado->getUnidade()->getId(),$this->usuarioLogado->getId());
        $qtdeEnviadas = $this->mensagemRepository->contarMensagensEnviadas($this->usuarioLogado->getUnidade()->getId(),$this->usuarioLogado->getId());
        $qtdeParaConhecimento = $this->mensagemRepository->contarMensagensParaConhecimento($this->usuarioLogado->getUnidade()->getId(),$this->usuarioLogado->getId());
        $qtdeRecebidas = $this->mensagemRepository->contarMensagensRecebidas($this->usuarioLogado->getUnidade()->getId(),$this->usuarioLogado->getId());
        
        $arrayQtde["qtde-rascunho"] = $qtdeRascunho;
        $arrayQtde["qtde-aguardando-transmissao"] = $qtdeAguardandoTransmissao;
        $arrayQtde["qtde-enviadas"] = $qtdeEnviadas;
        $arrayQtde["qtde-para-conhecimento"] = $qtdeParaConhecimento;
        $arrayQtde["qtde-recebidas"] = $qtdeRecebidas;
        $arrayQtde["qtde-total"] = $qtdeRascunho + $qtdeAguardandoTransmissao + $qtdeEnviadas + $qtdeParaConhecimento + $qtdeRecebidas;

        return $arrayQtde;
    }
}