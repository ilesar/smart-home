<?php

namespace App\Repository;

use App\Entity\ConfigurationTemplateItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ConfigurationTemplateItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfigurationTemplateItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfigurationTemplateItem[]    findAll()
 * @method ConfigurationTemplateItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigurationTemplateItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfigurationTemplateItem::class);
    }

    // /**
    //  * @return ConfigurationTemplateItem[] Returns an array of ConfigurationTemplateItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ConfigurationTemplateItem
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
