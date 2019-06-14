<?php

namespace App\Repository;

use App\Entity\Seance;
use App\Entity\Section;
use App\Entity\Parameter;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Seance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Seance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Seance[]    findAll()
 * @method Seance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeanceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Seance::class);
    }

    public function findBySection(Section $section)
    {
        return $this->createQueryBuilder('s')
        ->Join('s.section', 'section')
        ->where('section.id = :section_id')
        ->setParameter('section_id', $section->getId())
        ->getQuery()
        ->getResult()
        
    ;
    }


    public function findByDay($d, $id)
    { 
        if($d=='Lundi'){$day=0;}
        else if($d=='Mardi'){$day=1;}
        else if($d=='Mercredi'){$day=2;}
        else if($d=='Jeudi'){$day=3;}
        else if($d=='Vendredi'){$day=4;}
        else if($d=='Samedi'){$day=5;}
        
         


        return $this->createQueryBuilder('s')
                    ->Join('s.section', 'section')
                    ->where('section.id = :section_id')
                    ->andWhere('s.day = :day')
                    ->setParameter('section_id', $id)
                    ->setParameter('day', $day)
                    ->getQuery()
                    ->getResult()
        
    ;
    }

    public function findByDate($date, $seances)
    { 
        $week=['Dimanche','Lundi','Mardi' ,'Mercredi' , 'Jeudi' ,'Vendredi','Samedi'];
        $Day=date('N' ,strtotime($date->format('Y-m-d')));
        foreach($seances as $s)
        {   
            $d=$s->getDay();
            if($d==0){$day='Lundi';}
            else if($d==1){$day='Mardi';}
            else if($d==2){$day='Mercredi';}
            else if($d==3){$day='Jeudi';}
            else if($d==4){$day='Vendredi';}
            else if($d==5){$day='Samedi';}
            
            if($day==$week[$Day])
            {
                $seance=$s;
            }
        }
        return $seance;
    }

    public function findAllByDay($d, $section)
    { 
        if($d==0){$day='Lundi';}
        else if($d==1){$day='Mardi';}
        else if($d==2){$day='Mercredi';}
        else if($d==3){$day='Jeudi';}
        else if($d==4){$day='Vendredi';}
        else if($d==5){$day='Samedi';}

        $sections=$section->getSchoolYear()->getSections();
        $tab=[];
        foreach($sections as $s)
        {
            $seances=$s->getSeances();
            foreach($seances as $sea)
            {
                $D=$sea->getDay();
                if($D==0){$Day='Lundi';}
                else if($D==1){$Day='Mardi';}
                else if($D==2){$Day='Mercredi';}
                else if($D==3){$Day='Jeudi';}
                else if($D==4){$Day='Vendredi';}
                else if($D==5){$Day='Samedi';}
                if($Day==$day)
                {
                    $tab[]=$sea;
                }
            }
        }
        
        return $tab;
    }
    

    public function findTimeTable($seances)
    {
        $week=['Lundi','Mardi' ,'Mercredi' , 'Jeudi' ,'Vendredi','Samedi'];
        $timetable=[];
        for($i=0; $i<6; $i++)
        {   $day=[];
            $k=0;
            foreach ($seances as $s)
            {   
                $D=$s->getDay();
                if($D==0){$Day='Lundi';}
                else if($D==1){$Day='Mardi';}
                else if($D==2){$Day='Mercredi';}
                else if($D==3){$Day='Jeudi';}
                else if($D==4){$Day='Vendredi';}
                else if($D==5){$Day='Samedi';}

                if( $Day == $week[$i] )
                {
                   
                    $day[$k]=$s;
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

    public function findByTeaching($teacher,$section)
    { 
        $tab=[];
        $seances=$section->getSeances();
        foreach($seances as $s)
        {
            if($s->getCourse()->getLibelle()==$teacher->getSpecialty())
            {
                $tab[]=$s;
            }
        }
        
        
        return $tab;
    }

    
    
    
   

    // /**
    //  * @return Seance[] Returns an array of Seance objects
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
    public function findOneBySomeField($value): ?Seance
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
