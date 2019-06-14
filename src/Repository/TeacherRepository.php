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
    public function findBySection($seances)
    {
            $teachers=[];
            foreach($seances as $s)
            {  
                $id=$s->getTeacher()->getId();
                $teachers[$id]=$s->getTeacher();
            }
           
            $tab=[];
            foreach($teachers as $t)
            {
                $tab[$t->getId()]=$t->getLastName();
            }
            asort($tab);
            $sorted=[];
            foreach($tab as $k => $v )
            {
                $sorted[]=$this->findById($k)[0];
            }
            
            return $sorted ;
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

        $tab=[];
        foreach($query->getResult() as $t)
        {
            $tab[$t->getId()]=$t->getLastName();
        }
        asort($tab);
        $sorted=[];
        foreach($tab as $k => $v )
        {
            $sorted[]=$this->findById($k)[0];
        }
        
         return $sorted ;
    }

    public function findBySpecialty($specialty)
    {
        $query=$this->createQueryBuilder('t')
                        ->Where('t.specialty like :specialty')
                        ->setParameter('specialty', $specialty)
                        ->getQuery();
         return $query->getResult() ;
            
    }

    public function findByAvailability($exam , $teachers ,$examsPerDate)
    { 
        $start=$exam->getStartAt();
        $finish=$exam->getFinishAt();
        $teacherExams=[];
        foreach($examsPerDate as $e) 
        {   
            if($e->getStartAt()>=$start && $e->getStartAt()<$finish )
            {
                foreach($e->getTeachers() as $t) 
                {   
                    $teacherExams[]=$t;
                }
            }
        }

        $UniqueTab=array_unique($teacherExams);
        $tab=array_diff($teachers,$UniqueTab);
        
        if($exam->getTeachers()!=null)
        {
            foreach($exam->getTeachers() as $t) 
                {   
                    $tab[]=$t;
                }
        }
        

        return ($tab);

    }

    public function findTeacherByAvailability($seance ,$teachers ,$seancesPerDay)
    {
        
        $start=$seance->getStartAt();
        $finish=$seance->getFinishAt();
        $teacherSeances=[];
        foreach($seancesPerDay as $s) 
        {   
            if($s->getStartAt()>=$start && $s->getStartAt()<$finish )
            {
                $teacherSeances[]=$s->getTeacher();
            }
        }

        $UniqueTab=array_unique($teacherSeances);
        $tab=array_diff($teachers,$UniqueTab);
        
        if($seance->getTeacher()!=null)
        {
            $tab[]=$seance->getTeacher();
        }
        

        return ($tab);
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
