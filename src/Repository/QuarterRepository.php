<?php

namespace App\Repository;

use App\Entity\Quarter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Quarter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quarter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quarter[]    findAll()
 * @method Quarter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuarterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Quarter::class);
    }

    public function findQuarter($year ,$number)
    {
        return $this->createQueryBuilder('q')
                    ->Join('q.schoolYear', 's')
                    ->Where('s.libelle like :y')
                    ->andWhere('q.number = :n')
                    ->setParameter('y', $year)
                    ->setParameter('n', $number)
                    ->getQuery()
                    ->getOneOrNullResult()
    ;

    }

    // /**
    //  * @return Quarter[] Returns an array of Quarter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Quarter
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
