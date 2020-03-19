<?php

namespace App\Repository;

use App\Entity\GroceryItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GroceryItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroceryItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroceryItem[]    findAll()
 * @method GroceryItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroceryItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroceryItem::class);
    }

    // /**
    //  * @return GroceryItem[] Returns an array of GroceryItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroceryItem
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
