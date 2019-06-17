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

    public function findNoteTable($exams,$student , $quarter , $repoE )
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
            ///
            $tabeq=[];
            foreach($eq as $e)
            {
                $typeq=$e->getType();
                if($typeq==0){$T='Orale';}
                else if($typeq==1){$T='TP';}
                else if($typeq==2){$T='Controle1';}
                else if($typeq==3){$T='Controle2';}
                else if($typeq==4){$T='Synthése1';}
                else if($typeq==5){$T='Synthése2';}

                $tabeq[$e->getId()]=$T;
            }
            asort($tabeq);
            $sorted=[];
            foreach($tabeq as $k => $v )
            {
                $sorted[]=$repoE->findById($k)[0];
            }
            ///
            foreach($sorted as $e)
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
                        $se=$this->search($e->getId() , $student->getId() ); 
                        if($se != null)
                        {
                            $t[]=$se[0]->getNote();
                            $t[]=true;
                        }
                        else
                        {
                            $t[]=" ";
                            $t[]=false;
                        }
                        
                        
                        /*foreach($e->getStudentExams() as $se)
                        {   dump($se);
                            if($se->getStudent() == $student)
                            { 
                                $t[]=$se->getNote();
                                $t[]=true;
                            }
                            else
                            {
                                $t[]=" ";
                                $t[]=false;
                            }
                            if(count($t) == 4)
                            {
                                break;
                            }
                            
                        }*/
                    }
                    else {
                        $t[]=" ";
                        $t[]=false;
                    }
                    
                }
                dump($t);
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
        {   $param=explode(",",$k);
            $coeff=$param[1];
                /*$coeff=substr($k , -3 ,1);*/
            
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
        if( $SCourseCoeff ==  $SommeCoef )
        {
            $M= $SM / $SCourseCoeff ;
        }
        else{$M=null;}
    
        return $M;
    }

    public function findRank($student,$section ,$exams,$courses ,$q,$repoE)
    {
        $tabA=[];
        foreach($section->getStudents() as $s)
        {   
            $nt=$this->findNoteTable($exams,$s, $q , $repoE);
            $tabA[$s->getId()]=$this->countAverage($nt ,$courses);  
        }
        arsort($tabA);
        
        $nt=$this->findNoteTable($exams,$student, $q, $repoE);
        $av=$this->countAverage($nt ,$courses);  

        if($av==null)
        {
            $rank=null;
        }
        else{
            $rank=0;
            foreach($tabA as $k => $a)
            {
                
                if($k==$student->getId() )
                {
                    break;
                }
                else
                {
                    $rank= 1 +$rank ;
                }
            }
        }
        
        return $rank;
    }

    public function findRankG($student,$section ,$exams,$courses, $repoE)
    {
        $tabAG=[];
        foreach($section->getStudents() as $s)
        {   $ag=0;
            for( $i=1; $i<4; $i++ )
            {
                $nt=$this->findNoteTable($exams,$s, $i, $repoE);
                $a=$this->countAverage($nt ,$courses); 
                if($i==2 || $i==3)
                {$ag=$ag+$a*2;}
                else{ $ag=$ag+$a;} 
            } 
            $tabAG[$s->getId()]=$ag/5 ;
        }
        arsort($tabAG);


        $ag=0;
        for( $i=1; $i<4; $i++ )
        {
            $nt=$this->findNoteTable($exams,$student, $i, $repoE);
            $a=$this->countAverage($nt ,$courses); 
            if($i==2 || $i==3)
            {$ag=$ag+$a*2;}
            else{ $ag=$ag+$a;} 
        } 
         $avg=$ag/5 ;



        
        if($avg==null)
        {
            $rank=null;
        }
        else{
            $rank=0;
            foreach($tabAG as $k => $a)
            {
                 
                if($k==$student->getId() )
                {
                    break;
                }
                else
                {
                    $rank= 1 +$rank ;
                }
            }
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

    
    public function findOneByForeignKey($student , $exam): ?StudentExam
    {
        return $this->createQueryBuilder('s')
            ->join('s.Exam', 'E')
            ->Join('s.student', 'St')
            ->where('E.id like :idE')
            ->andWhere('St.id like :idSt')
            ->setParameter('idE', $exam)
            ->setParameter('idSt', $student)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    public function search($exam , $student )
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT s FROM App\Entity\StudentExam s JOIN s.Exam e JOIN s.student st WHERE st.id = $student And e.id = $exam ");
        return $query->getResult();
    }
}
