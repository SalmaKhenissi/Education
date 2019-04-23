<?php

namespace App\Repository;

use App\Entity\Seance;
use App\Entity\Section;
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

    public function findTimeTable($seances)
    {
        $week=['Lundi','Mardi' ,'Mercredi' , 'Jeudi' ,'Vendredi','Samedi'];
        $timetable=[];
        $i=0;
        foreach ($seances as $s)
        {
            if($s->getDay()==$week[$i])
            {
              
            }
        }
        return $timetable ;
        
    ;
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
