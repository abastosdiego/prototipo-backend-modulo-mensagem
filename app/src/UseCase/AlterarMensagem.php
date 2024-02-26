<?php

namespace App\UseCase;

use App\Entity\Unidade;
use App\Repository\MensagemRepository;
use App\Repository\UnidadeRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;

class AlterarMensagem {

    public function __construct(private EntityManagerInterface $entityManager, private MensagemRepository $mensagemRepository, private UsuarioRepository $usuarioRepository, private UnidadeRepository $unidadeRepository)
    {

    }
    public function executar(int $idMensagem, array $inputData): void {
        $mensagem = $this->mensagemRepository->find($idMensagem);

        $unidadesDestino = $this->unidadeRepository->getUnidadesPelasSiglas($inputData['unidadesDestinoSiglas']);
        $unidadesInformacao = $this->unidadeRepository->getUnidadesPelasSiglas($inputData['unidadesInformacaoSiglas']);

        $mensagem->carregarValores($inputData, $unidadesDestino, $unidadesInformacao);

        // Efetua as alterações no banco de dados
        $this->entityManager->flush();
    }
}