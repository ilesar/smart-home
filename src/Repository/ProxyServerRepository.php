<?php

namespace App\Repository;

use App\Entity\ProxyServer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProxyServer|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProxyServer|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProxyServer[]    findAll()
 * @method ProxyServer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProxyServerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProxyServer::class);
    }

    public function findBestKnownProxy(): ?ProxyServer
    {
        return $this->findOneBy([
            'isWhitelisted' => true,
        ], [
            'attempts' => 'asc',
        ]);
    }

    public function findProxyWithLeastAttempts(): ?ProxyServer
    {
        return $this->findOneBy([], [
            'attempts' => 'asc',
        ]);
    }

    // /**
    //  * @return ProxyServer[] Returns an array of ProxyServer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProxyServer
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
