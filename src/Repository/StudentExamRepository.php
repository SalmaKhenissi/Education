<?php

namespace App\Repository;

use App\Entity\StudentExam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StudentExam|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentExam|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentExam[]    findAll()
 * @method StudentExam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentExamRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StudentExam::class);
    }

    public function findNoteTable($exams,$student , $quarter)
    { 
        $tabC=[]; 
        foreach ($exams as $e)
        {   $c=$e->getCourse();
            if (!in_array($c,$tabC))
            {
                $tabC[]=$c;
            }    
        }

        $tab=[];
        foreach($tabC as $c)
        {   $tabN=[];
            foreach($c->getExams() as $e)
            {  if($e->getQuarter()->getNumber()== $quarter)
                {   $t=[];
                    $t[]=$e->getCoefficient();
                    $t[]=$e->getType();
                    if(count($e->getStudentExams())!=0)
                    {
                        foreach($e->getStudentExams() as $se)
                        {   if($se->getStudent()==$student)
                            {
                                $t[]=$se->getNote();
                                $t[]=true;
                            }
                            else
                            {
                                $t[]=" ";
                                $t[]=false;
                            }
                            
                        }
                    }
                    else {
                        $t[]=" ";
                        $t[]=false;
                    }
                    
                }
                $tabN[]=$t;
            }
            $tab[$c->getLibelle().','.$c->getCoefficient()]=$tabN;
            
        }

        return $tab ;
        
    }

    public function countAverage($noteTable , $courses)
    {   $SM=0;
        $SC=0;
        $M=0;
        $coef=0;
        
        foreach($courses as $c)
        {
            $coef=$coef+$c->getCoefficient();
        }
        foreach($noteTable as $k => $t)
        { $c=substr($k , -1 );
            $s=0;
            $l=0;
            $sub=0;
            foreach($t as $e)
            {
                if($e[3] == 1 )
                {
                    $f=$e[2]*$e[0];
                    $s=$s+$f;
                    $l=$l + 1 ;
                    $sub=$sub+$e[0];
                    
                }
            }
            
            if( $l == count($t))
            {
                $m=$s/$sub;
                $SM=$m*$c+$SM;
                $SC=$c+$SC;
            }
        }
        if( $SC == $coef)
        {
            $M= $SM / $SC ;
        }
    
        return $M;
    }

    public function findRank($student,$section ,$exams,$courses ,$q)
    {
        $tabA=[];
        foreach($section->getStudents() as $s)
        {   
            $nt=$this->findNoteTable($exams,$s, $q);
            $tabA[$s->getId()]=$this->countAverage($nt ,$courses);  
        }
        asort($tabA);
        $r=1;
        foreach($tabA as $k => $a)
        {
            if($k=$student->getId())
            {
                $rank=$r;
            }
            else{ $r = $r +1 ;}
        }
        return $rank;
    }

    public function findRankG($student,$section ,$exams,$courses)
    {
        $tabAG=[];
        foreach($section->getStudents() as $s)
        {   $ag=0;
            for( $i=1; $i<4; $i++ )
            {
                $nt=$this->findNoteTable($exams,$s, $i);
                $a=$this->countAverage($nt ,$courses); 
                if($i==2 || $i==3)
                {$ag=$ag+$a*2;}
                else{ $ag=$ag+$a;} 
            } 
            $tabAG[$s->getId()]=$ag/5 ;
        }
        asort($tabAG);
        $r=1;
        foreach($tabAG as $k => $a)
        {
            if($k=$student->getId())
            {
                $rank=$r;
            }
            else{ $r = $r +1 ;}
        }
        return $rank;
    }
    
    // /**
    //  * @return StudentExam[] Returns an array of StudentExam objects
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
    public function findOneBySomeField($value): ?StudentExam
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
