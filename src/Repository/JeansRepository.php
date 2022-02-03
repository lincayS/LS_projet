<?php

namespace App\Repository;

use App\Entity\Jeans;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Jeans|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jeans|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jeans[]    findAll()
 * @method Jeans[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JeansRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jeans::class);
    }

    // /**
    //  * @return Jeans[] Returns an array of Jeans objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Jeans
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findBySearch(string $value): array
    {

        return $this->createQueryBuilder('d')
            ->andWhere('d.name = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
        ;
    }

}
