<?php

namespace App\Repository;

use App\Entity\TramiteFuturo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TramiteFuturo>
 *
 * @method TramiteFuturo|null find($id, $lockMode = null, $lockVersion = null)
 * @method TramiteFuturo|null findOneBy(array $criteria, array $orderBy = null)
 * @method TramiteFuturo[]    findAll()
 * @method TramiteFuturo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TramiteFuturoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TramiteFuturo::class);
    }

//    /**
//     * @return TramiteFuturo[] Returns an array of TramiteFuturo objects
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

//    public function findOneBySomeField($value): ?TramiteFuturo
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
