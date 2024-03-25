<?php

namespace App\Repository;

use App\Entity\Mensagem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mensagem>
 *
 * @method Mensagem|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mensagem|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mensagem[]    findAll()
 * @method Mensagem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MensagemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mensagem::class);
    }

    /**
     * @return Mensagem[] Returns an array of Mensagem objects
     */
    public function listarMensagensRascunho(int $idUnidadeOrigem, int $idUsuario): array
    {
        return $this->createQueryBuilder('mensagem')
            ->join('mensagem.usuario_autor', 'usuario_autor')
            ->andWhere('mensagem.unidade_origem = :id_unidade_origem')
            ->andWhere('mensagem.rascunho = :rascunho')
            ->andWhere('usuario_autor.id = :idUsuario')
            ->setParameter('id_unidade_origem', $idUnidadeOrigem)
            ->setParameter('rascunho', true)
            ->setParameter('idUsuario', $idUsuario)
            ->orderBy('mensagem.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Mensagem[] Returns an array of Mensagem objects
     */
    public function listarMensagensAguardandoTransmissao(int $idUnidadeOrigem, int $idUsuario): array
    {
        return $this->createQueryBuilder('mensagem')
            ->join('mensagem.tramites', 'tramites')
            ->join('tramites.usuario_atual', 'usuario_atual')
            ->join('tramites.tramites_passado', 'tramites_passados')
            ->join('tramites_passados.usuario', 'usuario_tramites_passados')
            ->join('tramites.tramites_futuro', 'tramites_futuro')
            ->join('tramites_futuro.usuario', 'usuario_tramites_futuro')
            ->where('mensagem.unidade_origem = :id_unidade_origem and mensagem.rascunho = :rascunho and mensagem.data_autorizacao is NULL')
            ->andWhere('usuario_atual.id = :idUsuario or usuario_tramites_passados.id = :idUsuario or usuario_tramites_futuro.id = :idUsuario')
            ->setParameter('id_unidade_origem', $idUnidadeOrigem)
            ->setParameter('rascunho', false)
            ->setParameter('idUsuario', $idUsuario)
            ->orderBy('mensagem.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;

    }

    /**
     * @return Mensagem[] Returns an array of Mensagem objects
     */
    public function listarMensagensEnviadas(int $idUnidadeOrigem, int $idUsuario): array
    {
        return $this->createQueryBuilder('mensagem')
            ->join('mensagem.tramites', 'tramites')
            ->join('tramites.usuario_atual', 'usuario_atual')
            ->join('tramites.tramites_passado', 'tramites_passados')
            ->join('tramites_passados.usuario', 'usuario_tramites_passados')
            ->join('tramites.tramites_futuro', 'tramites_futuro')
            ->join('tramites_futuro.usuario', 'usuario_tramites_futuro')
            ->where('mensagem.unidade_origem = :id_unidade_origem and mensagem.rascunho = :rascunho and mensagem.data_autorizacao is not NULL')
            ->andWhere('usuario_atual.id = :idUsuario or usuario_tramites_passados.id = :idUsuario or usuario_tramites_futuro.id = :idUsuario')
            ->setParameter('id_unidade_origem', $idUnidadeOrigem)
            ->setParameter('rascunho', false)
            ->setParameter('idUsuario', $idUsuario)
            ->orderBy('mensagem.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        
    }

    /**
     * @return Mensagem[] Returns an array of Mensagem objects
     */
    public function listarMensagensParaConhecimento(int $idUnidadeOrigem, int $idUsuario): array
    {
        return $this->createQueryBuilder('mensagem')
            ->join('mensagem.paraConhecimentos', 'paraConhecimentos')
            ->join('paraConhecimentos.usuario', 'usuario')
            ->where('mensagem.unidade_origem = :id_unidade_origem and mensagem.rascunho = :rascunho')
            ->andWhere('usuario.id = :idUsuario and paraConhecimentos.ciente = false')
            ->setParameter('id_unidade_origem', $idUnidadeOrigem)
            ->setParameter('rascunho', false)
            ->setParameter('idUsuario', $idUsuario)
            ->orderBy('mensagem.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        
    }

}
