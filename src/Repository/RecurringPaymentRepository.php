<?php

namespace App\Repository;

use App\Entity\RecurringPayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RecurringPayment|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecurringPayment|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecurringPayment[]    findAll()
 * @method RecurringPayment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecurringPaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecurringPayment::class);
    }

    // /**
    //  * @return RecurringPayment[] Returns an array of RecurringPayment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RecurringPayment
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
