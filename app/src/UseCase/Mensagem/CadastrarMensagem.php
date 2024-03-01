<?php

namespace App\UseCase\Mensagem;

use App\Entity\Mensagem;
use App\Entity\Unidade;
use App\Repository\MensagemRepository;
use App\Repository\UnidadeRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;

class CadastrarMensagem {
    private Unidade $unidadeUsuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private MensagemRepository $mensagemRepository, private UsuarioRepository $usuarioRepository, private UnidadeRepository $unidadeRepository)
    {
        // Pegar usuário logado //
        $idUsuario = 12;
        $this->unidadeUsuarioLogado = $this->usuarioRepository->find($idUsuario)
                                            ->getUnidade();
    }

    public function executar(array $inputData): int {
        
        $unidadesDestino = $this->unidadeRepository->getUnidadesPelasSiglas($inputData['unidadesDestinoSiglas']);
        $unidadesInformacao = $this->unidadeRepository->getUnidadesPelasSiglas($inputData['unidadesInformacaoSiglas']);
        
        $mensagem = new Mensagem($inputData, $this->unidadeUsuarioLogado, $unidadesDestino, $unidadesInformacao);

        // Informa ao Doctrine que você deseja salvar esse novo objeto, quando for efetuado o flush.
        $this->entityManager->persist($mensagem);

        // Efetua as alterações no banco de dados
        $this->entityManager->flush();

        return $mensagem->getId();
    }
    
}