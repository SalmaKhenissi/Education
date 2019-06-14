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

    

    
    public function findByPram($firstName , $lastName , $section , $schoolYear )
    {
        
       if ($firstName &&  $lastName && $section && $schoolYear )
        {  
             $query=$this->createQueryBuilder('s')
                         ->andWhere('s.firstName like :firstName')
                         ->andWhere('s.lastName like :lastName')
                         ->setParameter('firstName', $firstName)
                         ->setParameter('lastName', $lastName)
                         ->getQuery()
                         ->getResult();
            $tabST=[];
            foreach($query as $s)
            {
                $sections=$s->getSections();
                foreach($sections as $sec)
                {
                    if($sec->getLibelle()==$section && $sec->getSchoolYear()->getLibelle()==$schoolYear)
                    {
                        $tabST[]=$s;
                        break;
                    }
                }
            }
            
        }
       else if ($firstName &&  $lastName && $schoolYear )
        {  
             $query=$this->createQueryBuilder('s')
                         ->andWhere('s.firstName like :firstName')
                         ->andWhere('s.lastName like :lastName')
                         ->setParameter('firstName', $firstName)
                         ->setParameter('lastName', $lastName)
                         ->getQuery()
                         ->getResult();
            $tabST=[];
            foreach($query as $s)
            {
                $sections=$s->getSections();
                foreach($sections as $sec)
                {
                    if( $sec->getSchoolYear()->getLibelle()==$schoolYear)
                    {
                        $tabST[]=$s;
                        break;
                    }
                }
            }
            
        }
        else if ( $lastName && $section && $schoolYear )
        {  
             $query=$this->createQueryBuilder('s')
                         ->andWhere('s.lastName like :lastName')
                         ->setParameter('lastName', $lastName)
                         ->getQuery()
                         ->getResult();
            $tabST=[];
            foreach($query as $s)
            {
                $sections=$s->getSections();
                foreach($sections as $sec)
                {
                    if($sec->getLibelle()==$section && $sec->getSchoolYear()->getLibelle()==$schoolYear)
                    {
                        $tabST[]=$s;
                        break;
                    }
                }
            }
            
        }

        else if ($firstName  && $section && $schoolYear )
        {  
             $query=$this->createQueryBuilder('s')
                         ->andWhere('s.firstName like :firstName')
                         ->setParameter('firstName', $firstName)
                         ->getQuery()
                         ->getResult();
            $tabST=[];
            foreach($query as $s)
            {
                $sections=$s->getSections();
                foreach($sections as $sec)
                {
                    if($sec->getLibelle()==$section && $sec->getSchoolYear()->getLibelle()==$schoolYear)
                    {
                        $tabST[]=$s;
                        break;
                    }
                }
            }
            
        }

        else if ($firstName &&  $lastName && $section )
        {  
             $query=$this->createQueryBuilder('s')
                         ->andWhere('s.firstName like :firstName')
                         ->andWhere('s.lastName like :lastName')
                         ->setParameter('firstName', $firstName)
                         ->setParameter('lastName', $lastName)
                         ->getQuery()
                         ->getResult();
            $tabST=[];
            foreach($query as $s)
            {
                $sections=$s->getSections();
                foreach($sections as $sec)
                {
                    if($sec->getLibelle()==$section)
                    {
                        $tabST[]=$s;
                        break;
                    }
                }
            }
            
        }
        else if ($firstName  && $schoolYear )
        {  
             $query=$this->createQueryBuilder('s')
                         ->andWhere('s.firstName like :firstName')
                         ->setParameter('firstName', $firstName)
                         ->getQuery()
                         ->getResult();
            $tabST=[];
            foreach($query as $s)
            {
                $sections=$s->getSections();
                foreach($sections as $sec)
                {
                    if( $sec->getSchoolYear()->getLibelle()==$schoolYear)
                    {
                        $tabST[]=$s;
                        break;
                    }
                }
            }
            
        }
        else if (  $lastName  && $schoolYear )
        {  
             $query=$this->createQueryBuilder('s')
                         ->andWhere('s.lastName like :lastName')
                         ->setParameter('lastName', $lastName)
                         ->getQuery()
                         ->getResult();
            $tabST=[];
            foreach($query as $s)
            {
                $sections=$s->getSections();
                foreach($sections as $sec)
                {
                    if( $sec->getSchoolYear()->getLibelle()==$schoolYear)
                    {
                        $tabST[]=$s;
                        break;
                    }
                }
            }
            
        }
       
        else if ($firstName &&  $section )
        {
            $query=$this->createQueryBuilder('s')
                        ->andWhere('s.firstName like :firstName')
                        ->setParameter('firstName', $firstName)
                        ->getQuery()
                        ->getResult();
            $tabST=[];
            foreach($query as $s)
            {
                $sections=$s->getSections();
                foreach($sections as $sec)
                {
                    if($sec->getLibelle()==$section)
                    {
                        $tabST[]=$s;
                        break;
                    }
                }
            }
            
        }
       
        else if ($section &&  $lastName )
        {
            $query=$this->createQueryBuilder('s')
                        ->andWhere('s.lastName like :lastName')
                        ->setParameter('lastName', $lastName)
                        ->getQuery()
                        ->getResult();
            $tabST=[];
            foreach($query as $s)
            {
                $sections=$s->getSections();
                foreach($sections as $sec)
                {
                    if($sec->getLibelle()==$section)
                    {
                        $tabST[]=$s;
                        break;
                    }
                }
            }
                         
             
        }
        else if ($firstName &&  $lastName )
        {
            $tabST=$this->createQueryBuilder('s')
                        ->where('s.firstName like :firstName')
                        ->andWhere('s.lastName like :lastName')
                        ->setParameter('firstName', $firstName)
                        ->setParameter('lastName', $lastName)
                        ->getQuery()
                        ->getResult();
            
        }
        
        else if ($firstName  )
        {
            $tabST=$this->createQueryBuilder('s')
                        ->where('s.firstName like :firstName')
                        ->setParameter('firstName', $firstName)
                        ->getQuery()
                        ->getResult();
        }
        else if ( $lastName )
        {
            $tabST=$this->createQueryBuilder('s')
                        ->Where('s.lastName like :lastName')
                        ->setParameter('lastName', $lastName)
                        ->getQuery()
                        ->getResult();
        }
        else if ($section  )
        {

            $query=$this->createQueryBuilder('s')
                        ->getQuery()
                        ->getResult();
            $tabST=[];
            foreach($query as $s)
            {
                $sections=$s->getSections();
                foreach($sections as $sec)
                {
                    if($sec->getLibelle()==$section)
                    {
                        $tabST[]=$s;
                        break;
                    }
                } 
            }
            
        }

        else if ($section && $schoolYear )
        {

            $query=$this->createQueryBuilder('s')
                        ->getQuery()
                        ->getResult();
            $tabST=[];
            foreach($query as $s)
            {
                $sections=$s->getSections();
                foreach($sections as $sec)
                {
                    if($sec->getLibelle()==$section && $sec->getSchoolYear->getLibelle()==$schoolYear)
                    {
                        $tabST[]=$s;
                        break;
                    }
                } 
            }
            
        }

        else if ( $schoolYear )
        {

            $query=$this->createQueryBuilder('s')
                        ->getQuery()
                        ->getResult();
            $tabST=[];
            foreach($query as $s)
            {
                $sections=$s->getSections();
                foreach($sections as $sec)
                {
                    if( $sec->getSchoolYear()->getLibelle()==$schoolYear)
                    {
                        $tabST[]=$s;
                        break;
                    }
                } 
            }
            
        }
        
        else 
        {
            $tabST=$this->createQueryBuilder('s')
                        ->getQuery()
                        ->getResult();
        }


        $tab=[];
        foreach($tabST as $s)
        {
            $tab[$s->getId()]=$s->getLastName();
        }
        asort($tab);
        $sorted=[];
        foreach($tab as $k => $v )
        {
            $sorted[]=$this->findById($k)[0];
        }
        
         return $sorted ;
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
