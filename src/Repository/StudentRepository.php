<?php

namespace App\Repository;

use App\Entity\Section;
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
    public function findByLevel($section ,$schoolYear)
    {
        $students=[];
        $i=0;   
        $level=$section->getLevel()->getNumber();
        $query=$this->createQueryBuilder('s')
                    ->Where('s.level like :level')
                    ->setParameter('level', $level-1)
                    ->getQuery()
                    ->getResult() ;
        foreach($query as $student)
        {   $affected=false;
            $sections=$student->getSections();
             
            for($j=0;$j<count($sections); $j++)
            {   if($sections[$j]->getSchoolYear()->getLibelle()==$schoolYear )
                { 
                      $affected=true;  
                     
                 }
            }
            
            if($affected==false)
            {
                $students[$i]=$student; $i++;
            }
        }
    
        return $students;
    }
    public function findByLevelAffected($section ,$schoolYear)
    {
        $students=[];
        $i=0;   
        $level=$section->getLevel()->getNumber();
        $query=$this->createQueryBuilder('s')
                    ->Where('s.level like :level')
                    ->setParameter('level', $level-1)
                    ->getQuery()
                    ->getResult() ;
        foreach($query as $student)
        {   $affected=false;
            $sections=$student->getSections();
             
            for($j=0;$j<count($sections); $j++)
            {   if($sections[$j]->getSchoolYear()->getLibelle()==$schoolYear )
                { 
                    if($sections[$j]==$section)
                    {
                      $affected=true;  
                    } 
                 }
            }
            
            if($affected==true)
            {
                $students[$i]=$student; $i++;
            }
        }
    
        return $students;
    }

    public function findByGuardian($guardian ,$schoolYear)
    {
        $students=[];
        $i=0;   
        
        $query=$this->createQueryBuilder('s')
                    ->Join('s.guardian', 'g')
                    ->Where('g.id like :guardian')
                    ->setParameter('guardian', $guardian->getId())
                    ->getQuery()
                    ->getResult() ;
        foreach($query as $student)
        {   $affected=false;
            $sections=$student->getSections();
             
            for($j=0;$j<count($sections); $j++)
            {   if($sections[$j]->getSchoolYear()->getLibelle()==$schoolYear )
                { 
                     $affected=true;  
                     
                 }
            }
            
            if($affected=true)
            {
                $students[$i]=$student; $i++;
            }
        }
    
        return $students;
    }

    

    
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
