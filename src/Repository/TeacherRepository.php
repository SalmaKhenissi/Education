<?php

namespace App\Repository;

use App\Entity\Teacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Teacher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Teacher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Teacher[]    findAll()
 * @method Teacher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeacherRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Teacher::class);
    }

   /* public function countBySection($section)
    {
        return $this->createQueryBuilder('t')
                    ->Join('t.sections', 's')
                    ->where('s.id = :section')
                    ->setParameter('section', $section)
                    ->getQuery()
                    ->getResult();

        
    }*/

    public function findByPram($firstName , $lastName , $section)
    {
        
       if ($firstName &&  $lastName && $section )
        {  
             $query=$this->createQueryBuilder('s')
                         ->Join('s.section', 'sec')
                         ->where('sec.name like :section')
                         ->andWhere('s.firstName like :firstName')
                         ->andWhere('s.lastName like :lastName')
                         ->setParameter('section', $section)
                         ->setParameter('firstName', $firstName)
                         ->setParameter('lastName', $lastName)
                         ->getQuery();
            
        }
       
        else if ($firstName &&  $section )
        {
            $query=$this->createQueryBuilder('s')
                        ->Join('s.section', 'sec')
                        ->where('sec.name like :section')
                        ->andWhere('s.firstName like :firstName')
                        ->setParameter('section', $section)
                        ->setParameter('firstName', $firstName)
                        ->getQuery();
            
        }
       
        else if ($section &&  $lastName )
        {
            $query=$this->createQueryBuilder('s')
                        ->Join('s.section', 'sec')
                        ->where('sec.name like :section')
                        ->andWhere('s.lastName like :lastName')
                        ->setParameter('section', $section)
                        ->setParameter('lastName', $lastName)
                        ->getQuery();
             
        }
        else if ($firstName &&  $lastName )
        {
            $query=$this->createQueryBuilder('s')
                        ->where('s.firstName like :firstName')
                        ->andWhere('s.lastName like :lastName')
                        ->setParameter('firstName', $firstName)
                        ->setParameter('lastName', $lastName)
                        ->getQuery();
            
        }
        
        else if ($firstName  )
        {
            $query=$this->createQueryBuilder('s')
                        ->where('s.firstName like :firstName')
                        ->setParameter('firstName', $firstName)
                        ->getQuery();
        }
        else if ( $lastName )
        {
            $query=$this->createQueryBuilder('s')
                        ->Where('s.lastName like :lastName')
                        ->setParameter('lastName', $lastName)
                        ->getQuery();
        }
        else if ($section  )
        {
            $query=$this->createQueryBuilder('s')
                        ->Join('s.section', 'sec')
                        ->where('sec.name like :section')
                        ->setParameter('section', $section)
                        ->getQuery();
            
        }
        
        else 
        {
            $query=$this->createQueryBuilder('s')
                        ->getQuery();
            /*$dql = "SELECT s FROM App\Entity\Student s  ";
            $query = $em->createQuery($dql);*/
        }
         return $query->getResult() ;
    }

    

    


    // /**
    //  * @return Teacher[] Returns an array of Teacher objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Teacher
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
