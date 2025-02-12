<?php

namespace App\UseCase\Mensagem;

use App\Entity\Mensagem;
use App\Entity\Unidade;
use App\Entity\Usuario;
use App\Repository\MensagemRepository;
use App\Repository\UnidadeRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CadastrarMensagem {
    private Usuario $usuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private Security $security,  private MensagemRepository $mensagemRepository, private UsuarioRepository $usuarioRepository, private UnidadeRepository $unidadeRepository) {
        if (!$this->security->getUser() instanceof Usuario) throw new \DomainException('Usuário logado não encontrado!');
        $this->usuarioLogado = $this->security->getUser();
    }

    public function executar(array $inputData): int {
        
        $unidadesDestino = $this->unidadeRepository->getUnidadesPelasSiglas($inputData['unidadesDestinoSiglas']);
        $unidadesInformacao = $this->unidadeRepository->getUnidadesPelasSiglas($inputData['unidadesInformacaoSiglas']);

        //RN001
        $mensagem = new Mensagem($inputData, $this->usuarioLogado, $this->usuarioLogado->getUnidade(), $unidadesDestino, $unidadesInformacao);

        // Informa ao Doctrine que você deseja salvar esse novo objeto, quando for efetuado o flush.
        $this->entityManager->persist($mensagem);

        // Efetua as alterações no banco de dados
        $this->entityManager->flush();

        return $mensagem->getId();
    }
    
}