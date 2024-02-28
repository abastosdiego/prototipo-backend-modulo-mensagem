<?php

namespace App\UseCase;

use App\Entity\Usuario;
use App\Repository\MensagemRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

class CadastrarTramite {
    private Usuario $usuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private MensagemRepository $mensagemRepository, private UsuarioRepository $usuarioRepository){
        // Pegar usuário logado //
        $idUsuario = 12;
        $this->usuarioLogado = $this->usuarioRepository->find($idUsuario);
        //$usuario->getUnidade()
    }

    public function executar($idMensagem, array $inputData) {
        $mensagem = $this->mensagemRepository->find($idMensagem);

        if(isset($inputData['tramite_futuro'])) {

            foreach($inputData['tramite_futuro'] as $nip) {
                $usuariosTramiteFuturo[] = $this->usuarioRepository->findOneBy(['nip' => $nip]);
            }

            $mensagem->criarTramite($this->usuarioLogado->getUnidade(), $this->usuarioLogado, $usuariosTramiteFuturo);

            // Efetua as alterações no banco de dados
            $this->entityManager->flush();

        } else {
            throw new \DomainException('tramite_futuro não informado');
        }
    }
}