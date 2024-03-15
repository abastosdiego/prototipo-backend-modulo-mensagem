<?php

namespace App\UseCase\Mensagem\Tramite;

use App\Entity\Usuario;
use App\Repository\MensagemRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class AlterarTramite {
    private Usuario $usuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private Security $security, private MensagemRepository $mensagemRepository, private UsuarioRepository $usuarioRepository){
        if ($this->security->getUser() instanceof Usuario) {
            $this->usuarioLogado = $this->security->getUser();
        } else {
            throw new \DomainException('Usuário logado não encontrado!');
        }
    }

    public function executar(int $idMensagem, array $inputData) {
        $mensagem = $this->mensagemRepository->find($idMensagem);

        if(isset($inputData['tramite_futuro'])) {

            foreach($inputData['tramite_futuro'] as $nip) {
                $usuariosTramiteFuturo[] = $this->usuarioRepository->findOneBy(['nip' => $nip]);
            }

            $tramite = $mensagem->getTramite($this->usuarioLogado->getUnidade());
            $tramite->criarTramiteFuturo($usuariosTramiteFuturo);

            // Efetua as alterações no banco de dados
            $this->entityManager->flush();

        } else {
            throw new \DomainException('tramite_futuro não informado');
        }
    }
}