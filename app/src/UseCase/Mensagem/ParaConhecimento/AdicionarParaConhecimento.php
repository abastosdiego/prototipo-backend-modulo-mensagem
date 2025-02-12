<?php

namespace App\UseCase\Mensagem\ParaConhecimento;

use App\Entity\Usuario;
use App\Repository\MensagemRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class AdicionarParaConhecimento {
    private Usuario $usuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private Security $security, private MensagemRepository $mensagemRepository, private UsuarioRepository $usuarioRepository){
        if (!$this->security->getUser() instanceof Usuario) throw new \DomainException('Usuário logado não encontrado!');
        $this->usuarioLogado = $this->security->getUser();
    }

    public function executar($idMensagem, array $inputData) {
        $mensagem = $this->mensagemRepository->find($idMensagem);

        if(isset($inputData['nip'])) {

            $usuario = $this->usuarioRepository->findOneBy(['nip' => $inputData['nip']]);

            $mensagem->adicionarParaConhecimento($usuario);

            // Efetua as alterações no banco de dados
            $this->entityManager->flush();

        } else {
            throw new \DomainException('nip não informado');
        }
    }
}