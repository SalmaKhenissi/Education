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
        {   $tabN=[];$eq=[];
            foreach($c->getExams() as $e)
            {
                if($e->getQuarter()->getNumber()== $quarter)
                {
                    $eq[]=$e;
                }
            }
            foreach($eq as $e)
            {   
                if($e->getQuarter()->getNumber()== $quarter)
                {   $t=[];
                    $t[]=$e->getCoefficient();
                    if($e->getType()== 0){$t[]='Orale';}
                    else if ($e->getType()== 1){$t[]='TP';}
                    else if ($e->getType()== 2){$t[]='Controle1';}
                    else if ($e->getType()== 3){$t[]='Controle2';}
                    else if ($e->getType()== 4){$t[]='Synthése1';}
                    else {$t[]='Synthése2';}
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
                $tabN[$t[1]]=$t;
            }
            $tab[$c->getLibelle().','.$c->getCoefficient().','.$c->getNbrExams()]=$tabN;
            
        }

        return $tab ;
        
    }

    public function countAverage($noteTable , $courses)
    {   $SM=0;
        $SCourseCoeff=0;
        $M=0;
        $SommeCoef=0;
        
        foreach($courses as $c)
        {
            $SommeCoef=$SommeCoef+$c->getCoefficient();
        }
        foreach($noteTable as $k => $examsByCourses)
        {   $coeff=substr($k , -3 ,1);
            $ne=substr($k , -1 );
            $s=0;
            $l=0;
            $sub=0;
            foreach($examsByCourses as $e)
            {
                if($e[3] == 1 )
                {
                    $cn=$e[2]*$e[0];
                    $s=$s+$cn;
                    $l=$l + 1 ;
                    $sub=$sub+$e[0];
                    
                }
            }
            
            if( $l == $ne)
            {
                $m=$s/$sub;
                $SM=$m*$coeff+$SM; 
                $SCourseCoeff=$coeff+$SCourseCoeff;
            }
        }
        if( $SCourseCoeff == 8 /* $SommeCoef*/)
        {
            $M= $SM / $SCourseCoeff ;
        }
        else{$M=" ";}
    
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
