<?php

namespace App\Repository;

use App\Entity\Exam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Exam|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exam|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exam[]    findAll()
 * @method Exam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Exam::class);
    }
    public function findByOptions($course , $type , $quarter)
    {
        $em = $this->getEntityManager();
        
        if ($course && $type && $quarter)
        {
            $query=$this->createQueryBuilder('e')
                        ->Join('e.course', 'C')
                        ->Join('e.quarter', 'Q')
                        ->where('C.libelle like :c')
                        ->where('Q.number like :q')
                        ->andWhere('e.type like :t')
                        ->setParameter('c', $course)
                        ->setParameter('q', $quarter)
                        ->setParameter('t', $type)
                        ->getQuery();
        }
        else if ( $type && $quarter)
        {
            $query=$this->createQueryBuilder('e')
                        ->Join('e.quarter', 'Q')
                        ->where('Q.number like :q')
                        ->andWhere('e.type like :t')
                        ->setParameter('q', $quarter)
                        ->setParameter('t', $type)
                        ->getQuery();
        }
        else if ($course  && $quarter)
        {
            $query=$this->createQueryBuilder('e')
                        ->Join('e.course', 'C')
                        ->Join('e.quarter', 'Q')
                        ->where('C.libelle like :c')
                        ->where('Q.number like :q')
                        ->setParameter('c', $course)
                        ->setParameter('q', $quarter)
                        ->getQuery();
        }
        else if ($course && $type)
        {
            $query=$this->createQueryBuilder('e')
                        ->Join('e.course', 'C')
                        ->where('C.libelle like :c')
                        ->andWhere('e.type like :t')
                        ->setParameter('c', $course)
                        ->setParameter('t', $type)
                        ->getQuery();
        }
        else if ($quarter)
        {
            $query=$this->createQueryBuilder('e')
                        ->Join('e.quarter', 'Q')
                        ->where('Q.number like :q')
                        ->setParameter('q', $quarter)
                        ->getQuery();
        }
        else if ($course )
        {
            $query=$this->createQueryBuilder('e')
                        ->Join('e.course', 'C')
                        ->where('C.libelle like :c')
                        ->setParameter('c', $course)
                        ->getQuery();
        }
        else if ($type)
        {
            $query=$this->createQueryBuilder('e')
                        ->andWhere('e.type like :t')
                        ->setParameter('t', $type)
                        ->getQuery();
        }
        else 
        {
            $query=$this->createQueryBuilder('e')
                        ->getQuery();
        }
         return $query->getResult() ;
    }

    public function findTimeTable($exams)
    {
        $week=['Monday','Tuesday' ,'Wednesday' , 'Thursday' ,'Friday','Saturday'];
        $timetable=[];
        for($i=0; $i<6; $i++)
        {   $day=[];
            $k=0;
            foreach ($exams as $e)
            {
               if(date('l',strtotime($e->getPassAt()->format('Y-m-d'))) == $week[$i] )
                {   
                   
                    $day[$k]=$e;
                    $k++;
                }
            }
            for($j=0; $j< sizeof($day); $j++)
            { 
                for($k=0; $k< sizeof($day)-1; $k++)
                {
                    $start1=$day[$k]->getStartAt()->format('H');
                    if($start1 < 8) {
                        $start1=$start1+12;
                    }
                    $start2=$day[$k+1]->getStartAt()->format('H');
                    if($start2 < 8) {
                     $start2=$start2+12;
                    }

                    if( $start1 > $start2 )
                    {
                        $aux=$day[$k+1];
                        $day[$k+1]=$day[$k];
                        $day[$k]=$aux;
                    }
                }
            }
            $timetable[$i]=$day;
           
            
        }
        return $timetable ;
        
    ;
    }

    public function findByTeacher($sections , $teacher ,$search)
    {
        $exams=[];
        $tab=[];
        foreach($sections as $s)
        {
            foreach($s->getExams() as $ex)
            {
                $exams[]=$ex;
            }
        }
       
        foreach($exams as $e)
        {   
            $course= $e->getCourse()->getLibelle() ;
            if($course == $teacher->getSpecialty())
            {
                $tab[]=$e;
            }
        }



        $tab2=[];
        $section=$search->getSection();
        $quarter=$search->getQuarter();

        if($section && $quarter)
        {
            foreach($tab as $e)
            {
                if($e->getSection()==$section && $e->getQuarter()==$quarter)
                { $tab2[]=$e;}
            }
            return $tab2;
        }
        
        else if($section  )
        {
            foreach($tab as $e)
            {
                if($e->getSection()==$section )
                { $tab2[]=$e;}
            }
            return $tab2;
        }
        else if( $quarter)
        {
            foreach($tab as $e)
            {
                if( $e->getQuarter()==$quarter)
                { $tab2[]=$e;}
            }
            return $tab2;
        }
        else {
            return $tab;
        }

    }

    // /**
    //  * @return Exam[] Returns an array of Exam objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Exam
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
