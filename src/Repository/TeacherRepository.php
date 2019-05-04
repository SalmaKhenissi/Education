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
    public function findBySection($seances,$repoT)
    {
            $tabId=[];
            for($i=0;$i<count($seances);$i++)
            {
                $tabId[$i]=$seances[$i]->getTeacher()->getId();
            }
            $tabId=array_unique($tabId); 
            $teachers=[];
            for($i=0;$i<count($tabId);$i++)
            {
                $teachers[$i]=$repoT->find($tabId[$i]);
            }
            return $teachers;
    }
    public function findByPram($firstName , $lastName , $specialty)
    {
        
       if ($firstName &&  $lastName && $specialty )
        {
            $query=$this->createQueryBuilder('t')               
                         ->where('t.specialty like :specialty')
                         ->andWhere('t.firstName like :firstName')
                         ->andWhere('t.lastName like :lastName')
                         ->setParameter('specialty', $specialty)
                         ->setParameter('firstName', $firstName)
                         ->setParameter('lastName', $lastName)
                         ->getQuery();
            
        }
       
        else if ($firstName &&  $specialty )
        {
            $query=$this->createQueryBuilder('t')
                        ->where('t.firstName like :firstName')
                        ->andWhere('t.specialty like :specialty')
                        ->setParameter('firstName', $firstName)
                        ->setParameter('specialty', $specialty)
                        ->getQuery();
            
        }
       
        else if ($specialty &&  $lastName )
        {
            $query=$this->createQueryBuilder('t')
                        ->where('t.specialty like :specialty')
                        ->andWhere('t.lastName like :lastName')
                        ->setParameter('specialty', $specialty)
                        ->setParameter('lastName', $lastName)
                        ->getQuery();
             
        }
        else if ($firstName &&  $lastName )
        {
            $query=$this->createQueryBuilder('t')
                        ->where('t.firstName like :firstName')
                        ->andWhere('t.lastName like :lastName')
                        ->setParameter('firstName', $firstName)
                        ->setParameter('lastName', $lastName)
                        ->getQuery();
            
        }
        
        else if ($firstName  )
        {
            $query=$this->createQueryBuilder('t')
                        ->where('t.firstName like :firstName')
                        ->setParameter('firstName', $firstName)
                        ->getQuery();
        }
        else if ( $lastName )
        {
            $query=$this->createQueryBuilder('t')
                        ->Where('t.lastName like :lastName')
                        ->setParameter('lastName', $lastName)
                        ->getQuery();
        }
        else if ($specialty  )
        {
            $query=$this->createQueryBuilder('t')
                        ->Where('t.specialty like :specialty')
                        ->setParameter('specialty', $specialty)
                        ->getQuery();
            
        }
        
        else 
        {
            $query=$this->createQueryBuilder('t')
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
