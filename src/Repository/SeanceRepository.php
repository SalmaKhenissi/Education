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


    public function findByDay($day, $id)
    { 

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
        $day=date('N' ,strtotime($date->format('Y-m-d')));
        foreach($seances as $s)
        {
            if($s->getDay()==$week[$day])
            {
                $seance=$s;
            }
        }
        return $seance;
    }

    public function findAllByDay($day, $section)
    { 
        $sections=$section->getSchoolYear()->getSections();
        $tab=[];
        foreach($sections as $s)
        {
            $seances=$s->getSeances();
            foreach($seances as $sea)
            {
                if($sea->getDay()==$day)
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
                if( $s->getDay() == $week[$i] )
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
