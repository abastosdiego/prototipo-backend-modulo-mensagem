<?php

namespace App\UseCase\Mensagem\ParaConhecimento;

use App\Entity\Usuario;
use App\Repository\MensagemRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CadastrarParaConhecimento {
    private Usuario $usuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private Security $security, private MensagemRepository $mensagemRepository, private UsuarioRepository $usuarioRepository){
        if ($this->security->getUser() instanceof Usuario) {
            $this->usuarioLogado = $this->security->getUser();
        } else {
            throw new \DomainException('Usuário logado não encontrado!');
        }
    }

    public function executar($idMensagem, array $inputData) {
        $mensagem = $this->mensagemRepository->find($idMensagem);

        if(isset($inputData['para_conhecimento'])) {

            foreach($inputData['para_conhecimento'] as $nip) {
                $usuariosParaConhecimento[] = $this->usuarioRepository->findOneBy(['nip' => $nip]);
            }

            $mensagem->criarParaConhecimento($usuariosParaConhecimento);

            // Efetua as alterações no banco de dados
            $this->entityManager->flush();

        } else {
            throw new \DomainException('para_conhecimento não informado');
        }
    }
}