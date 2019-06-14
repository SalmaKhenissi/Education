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
    public function findByOptions($course , $typeO , $quarter , $section)
    {
        $em = $this->getEntityManager();

        if($typeO=='Orale') {$type =0 ;}
        else if($typeO=='TP'){ $type =1 ;}
        else if($typeO=='Controle1') { $type =2 ;}
        else if($typeO=='Controle2'){ $type =3 ;}
        else if($typeO=='Synthése1'){ $type =4  ;}
        else if($typeO=='Synthése2'){ $type =5 ;}
        else {$type=$typeO;}
    
        
        if ($course && $type)
        {
            $query=$this->createQueryBuilder('e')
                        ->Join('e.section', 'S')
                        ->Join('e.course', 'C')
                        ->Join('e.quarter', 'Q')
                        ->where('S.id like :s')
                        ->andWhere('C.libelle like :c')
                        ->andWhere('Q.number like :q')
                        ->andWhere('e.type like :t')
                        ->setParameter('q', $quarter)
                        ->setParameter('c', $course)
                        ->setParameter('t', $type)
                        ->setParameter('s', $section->getId())
                        ->getQuery();
        }
        else if ($course )
        {
            $query=$this->createQueryBuilder('e')
                        ->Join('e.section', 'S')
                        ->Join('e.quarter', 'Q')
                        ->Join('e.course', 'C')
                        ->where('Q.number like :q')
                        ->andWhere('S.id like :s')
                        ->andWhere('C.libelle like :c')
                        ->setParameter('q', $quarter)
                        ->setParameter('c', $course)
                        ->setParameter('s', $section->getId())
                        ->getQuery();
        }
        else if ($type)
        {
            $query=$this->createQueryBuilder('e')
                        ->Join('e.section', 'S')
                        ->Join('e.quarter', 'Q')
                        ->where('S.id like :s')
                        ->andWhere('Q.number like :q')
                        ->andWhere('e.type like :t')
                        ->setParameter('q', $quarter)
                        ->setParameter('t', $type)
                        ->setParameter('s', $section->getId())
                        ->getQuery();
        }
        else 
        { 
            $query=$this->createQueryBuilder('e')
                        ->Join('e.section', 'S')
                        ->Join('e.quarter', 'Q')
                        ->where('S.id like :s')
                        ->andWhere('Q.number like :q')
                        ->setParameter('s', $section->getId())
                        ->setParameter('q', $quarter)
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

    public function findByTeacher($section , $teacher ,$search)
    {
        $exams=[];
        $tab=[];
            foreach($section->getExams() as $ex)
            {
                $exams[]=$ex;
            }
        
       
        foreach($exams as $e)
        {   
            $course= $e->getCourse()->getLibelle() ;
            if($course == $teacher->getSpecialty())
            {
                $k=strtotime($e->getPassAt()->format('Y-m-d H:i:s')).strtotime($e->getStartAt()->format('H:i'));
                $tab[$k]=$e;
            }
        }

        
        krsort($tab);


        $tab2=[];
        $quarter=$search->getQuarter();

         if( $quarter)
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

    public function findByQuarter($section , $quarter)
    {
        $q='Trimestre'.$quarter;
        $query=$this->createQueryBuilder('e')
                        ->Join('e.section', 'S')
                        ->Join('e.quarter', 'Q')
                        ->where('S.id like :s')
                        ->andWhere('Q.libelle like :q')
                        ->andWhere('e.type like :c1 Or e.type like :c2')
                        ->setParameter('s', $section->getId())
                        ->setParameter('q', $q)
                        ->setParameter('c1', 2)
                        ->setParameter('c2', 3)
                        ->getQuery()
                        ->getResult();
        $tab=[];
        foreach($query as $q)
        {
            $k=strtotime($q->getPassAt()->format('Y-m-d H:i:s')).strtotime($q->getStartAt()->format('H:i'));
            $tab[$k]=$q;
        }
        krsort($tab);

        return $tab ;
    }

    public function findBySynthese( $quarter ,$section)
    {
        $q='Trimestre'.$quarter;
        $query=$this->createQueryBuilder('e')
                        ->Join('e.section', 'S')
                        ->Join('e.quarter', 'Q')
                        ->where('S.id like :s')
                        ->andWhere('Q.libelle like :q')
                        ->andWhere('e.type like :s1 Or e.type like :s2')
                        ->setParameter('s', $section->getId())
                        ->setParameter('q', $q)
                        ->setParameter('s1', 4)
                        ->setParameter('s2', 5)
                        ->getQuery()
                        ->getResult();
        

        return $query ;
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
