<?php

namespace App\Repository;

use App\Entity\Discipline;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Discipline|null find($id, $lockMode = null, $lockVersion = null)
 * @method Discipline|null findOneBy(array $criteria, array $orderBy = null)
 * @method Discipline[]    findAll()
 * @method Discipline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DisciplineRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Discipline::class);
    }

    public function  findbySeances ($seances)
    {
        $disciplines=[];
        $list=[];
        foreach($seances as $s)
        {  
             $disc=$s->getDisciplines();
             foreach($disc as $d)
             {
                 $list[]=$d;
             }
        }

        $dates=[];
        foreach($list as $l)
        { 
                $dates[]=$l->getDate()->format('Y-m-d');
        }
        $unique=array_unique($dates);

        foreach($unique as $date)
        {   $groupe=[];
            foreach($list as $l)
            {   if($l->getDate()->format('Y-m-d')==$date)
                {
                        $groupe[]=$l;
                }
            }
            $k=date('n',strtotime($date)).date('d',strtotime($date));
            $disciplines[$k]=$groupe;
        }
            
            
        
        krsort($disciplines);
        return $disciplines;
    }

    public function  findbyDate ($register , $date)
    {   $page=null;
        foreach($register as $s)
            {   if($s[0]->getDate()->format('md')==$date)
                {
                        $page=$s;
                }
            }
            return $page;
    }

    // /**
    //  * @return Discipline[] Returns an array of Discipline objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Discipline
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
