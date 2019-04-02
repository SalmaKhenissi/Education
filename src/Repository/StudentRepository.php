<?php

namespace App\Repository;

use App\Entity\Student;
use App\Entity\Guardian;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function countBySection($id)
    {
        return $this->createQueryBuilder('s')
        ->select('COUNT(s)')
        ->where('s.section = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getSingleScalarResult();
    }

    

    
    public function findByName($firstName , $lastName)
    {
        $em = $this->getEntityManager();
        
        if ($firstName &&  $lastName )
        {
              $dql = "SELECT s FROM App\Entity\Student s where s.firstName like :firstName and s.lastName like :lastName ";
              $query = $em->createQuery($dql);
              $query->setParameter('firstName', $firstName)
                    ->setParameter('lastName', $lastName);
        }
        
        else if ($firstName  )
        {
              $dql = "SELECT s FROM App\Entity\Student s where s.firstName like :firstName  ";
              $query = $em->createQuery($dql);
              $query->setParameter('firstName', $firstName);
        }
        else if ( $lastName )
        {
              $dql = "SELECT s FROM App\Entity\Student s where  s.lastName like :lastName ";
              $query = $em->createQuery($dql);
              $query->setParameter('lastName', $lastName);
        }
       
         else 
        {
            $dql = "SELECT s FROM App\Entity\Student s  ";
            $query = $em->createQuery($dql);
        }
         return $query->getResult() ;
    }

    

    

   



    // /**
    //  * @return Student[] Returns an array of Student objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Student
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
