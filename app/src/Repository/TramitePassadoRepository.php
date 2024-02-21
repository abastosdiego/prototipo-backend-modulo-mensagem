<?php

namespace App\Repository;

use App\Entity\TramitePassado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TramitePassado>
 *
 * @method TramitePassado|null find($id, $lockMode = null, $lockVersion = null)
 * @method TramitePassado|null findOneBy(array $criteria, array $orderBy = null)
 * @method TramitePassado[]    findAll()
 * @method TramitePassado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TramitePassadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TramitePassado::class);
    }

//    /**
//     * @return TramitePassado[] Returns an array of TramitePassado objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TramitePassado
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
