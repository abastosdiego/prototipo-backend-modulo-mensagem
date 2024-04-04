<?php

namespace App\Repository;

use App\Entity\Mensagem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
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
            ->leftJoin('tramites.tramites_futuro', 'tramites_futuro')
            ->leftJoin('tramites_futuro.usuario', 'usuario_tramites_futuro')
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
            ->leftJoin('tramites.tramites_futuro', 'tramites_futuro')
            ->leftJoin('tramites_futuro.usuario', 'usuario_tramites_futuro')
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


    /**
     * @return Mensagem[] Returns an array of Mensagem objects
     */
    public function listarMensagensRecebidas(int $idUnidade, int $idUsuario): array
    {
        return $this->createQueryBuilder('mensagem')

            ->join('mensagem.unidades_destino', 'unidades_destino')
            ->leftJoin('mensagem.unidades_informacao', 'unidades_informacao')
            ->where('mensagem.rascunho = false and mensagem.data_autorizacao is not NULL')
            ->andWhere('unidades_destino.id = :idUnidade or unidades_informacao.id = :idUnidade')
            ->setParameter('idUnidade', $idUnidade)
            ->orderBy('mensagem.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return int Qtde de mensagens Rascunho
     */
    public function contarMensagensRascunho(int $idUnidade, int $idUsuario): int
    {
         $conn = $this->getEntityManager()->getConnection();

        $sql = 'select count(m.id)
                from mensagem m
                where m.rascunho = true and m.unidade_origem_id = :idUnidade and m.usuario_autor_id = :idUsuario';

        //$resultSet = $conn->executeQuery($sql, ['price' => $price]);
        $resultSet = $conn->executeQuery($sql, ['idUnidade' => $idUnidade, 'idUsuario' => $idUsuario]);

        // returns an array of arrays (i.e. a raw data set)
        $result = $resultSet->fetchAllAssociative();

        if ($result) {
            return (int) $result[0]['count'];
        } else {
            throw new \DomainException('Erro ao buscar a quantidade!');
        }
        
    }

    /**
     * @return int Qtde de mensagens Aguardando Transmissao
     */
    public function contarMensagensAguardandoTransmissao(int $idUnidade, int $idUsuario): int
    {
         $conn = $this->getEntityManager()->getConnection();

        $sql = 'select count(m.id)
                from mensagem m
                where m.rascunho = false and m.data_autorizacao is NULL and m.unidade_origem_id = :idUnidade';

        //$resultSet = $conn->executeQuery($sql, ['price' => $price]);
        $resultSet = $conn->executeQuery($sql, ['idUnidade' => $idUnidade]);

        // returns an array of arrays (i.e. a raw data set)
        $result = $resultSet->fetchAllAssociative();

        if ($result) {
            return (int) $result[0]['count'];
        } else {
            throw new \DomainException('Erro ao buscar a quantidade!');
        }
        
    }

    /**
     * @return int Qtde de mensagens Enviadas
     */
    public function contarMensagensEnviadas(int $idUnidade, int $idUsuario): int
    {
         $conn = $this->getEntityManager()->getConnection();

        $sql = 'select count(m.id)
                from mensagem m
                where m.rascunho = false and m.data_autorizacao is not NULL and m.unidade_origem_id = :idUnidade';

        //$resultSet = $conn->executeQuery($sql, ['price' => $price]);
        $resultSet = $conn->executeQuery($sql, ['idUnidade' => $idUnidade]);

        // returns an array of arrays (i.e. a raw data set)
        $result = $resultSet->fetchAllAssociative();

        if ($result) {
            return (int) $result[0]['count'];
        } else {
            throw new \DomainException('Erro ao buscar a quantidade!');
        }
        
    }

    /**
     * @return int Qtde de mensagens Para Conhecimento
     */
    public function contarMensagensParaConhecimento(int $idUnidade, int $idUsuario): int
    {
         $conn = $this->getEntityManager()->getConnection();

        $sql = 'select count(m.id)
                from mensagem m
                inner join para_conhecimento c on m.id = c.mensagem_id and c.ciente = false
                where m.rascunho = false and c.usuario_id = :idUsuario';

        $resultSet = $conn->executeQuery($sql, ['idUsuario' => $idUsuario]);

        // returns an array of arrays (i.e. a raw data set)
        $result = $resultSet->fetchAllAssociative();

        if ($result) {
            return (int) $result[0]['count'];
        } else {
            throw new \DomainException('Erro ao buscar a quantidade!');
        }
        
    }

    /**
     * @return int Qtde de mensagens Recebidas
     */
    public function contarMensagensRecebidas(int $idUnidade, int $idUsuario): int
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'select count(id)
                from mensagem m
                left join mensagem_unidades_destino d on m.id = d.mensagem_id
                left join mensagem_unidades_informacao i on m.id = i.mensagem_id
                where m.rascunho = false and m.data_autorizacao is not NULL and (d.unidade_id = :idUnidade or i.unidade_id = :idUnidade)';

        //$resultSet = $conn->executeQuery($sql, ['price' => $price]);
        $resultSet = $conn->executeQuery($sql, ['idUnidade' => $idUnidade]);

        // returns an array of arrays (i.e. a raw data set)
        $result = $resultSet->fetchAllAssociative();

        if ($result) {
            return (int) $result[0]['count'];
        } else {
            throw new \DomainException('Erro ao buscar a quantidade!');
        }   
    }

}
