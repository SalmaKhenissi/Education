<?php

namespace App\Repository;

use App\Entity\Section;
use App\Entity\Teacher;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Section|null find($id, $lockMode = null, $lockVersion = null)
 * @method Section|null findOneBy(array $criteria, array $orderBy = null)
 * @method Section[]    findAll()
 * @method Section[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SectionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Section::class);
    }

    
    public function findbyTeacher(Teacher $teacher)
    {
        return $this->createQueryBuilder('s')
        ->Join('s.teachers', 't')
        ->where('t.id = :teacher_id')
        ->setParameter('teacher_id', $teacher->getId())
        ->getQuery()
        ->getResult()
        
    ;
    }


    public function findByOption($level,$track,$nbrGroup)
    {
        $em = $this->getEntityManager();
        
        if ($level && $track && $nbrGroup )
        {
              $dql = "SELECT s FROM App\Entity\Section s where s.level like :l and s.track like :t  and s.nbrGroup like :n ";
              $query = $em->createQuery($dql);
              $query->setParameter('l', $level)
                    ->setParameter('t', $track)
                    ->setParameter('n', $nbrGroup);
        }
        else if ($level &&  $nbrGroup )
        {
              $dql = "SELECT s FROM App\Entity\Section s where s.level like :l   and s.nbrGroup like :n ";
              $query = $em->createQuery($dql);
              $query->setParameter('l', $level)
                    ->setParameter('n', $nbrGroup);
        }
        else if ($track &&  $nbrGroup )
        {
              $dql = "SELECT s FROM App\Entity\Section s where s.track like :t   and s.nbrGroup like :n ";
              $query = $em->createQuery($dql);
              $query->setParameter('t', $track)
                    ->setParameter('n', $nbrGroup);
        }
        else if ($level && $track )
        {
              $dql = "SELECT s FROM App\Entity\Section s where s.level like :l and s.track like :t ";
              $query = $em->createQuery($dql);
              $query->setParameter('l', $level)
                    ->setParameter('t', $track);
        }
        else if ($level)
        {
              $dql = "SELECT s FROM App\Entity\Section s where s.level like :l  ";
              $query = $em->createQuery($dql);
              $query->setParameter('l', $level);
        }
        else if ($track)
        {
              $dql = "SELECT s FROM App\Entity\Section s where s.track like :t  ";
              $query = $em->createQuery($dql);
              $query->setParameter('t', $track);
        }
        else if ($nbrGroup)
        {
              $dql = "SELECT s FROM App\Entity\Section s where s.nbrGroup like :n  ";
              $query = $em->createQuery($dql);
              $query->setParameter('n', $nbrGroup);
        }
        else 
        {
            $dql = "SELECT s FROM App\Entity\Section s  ";
            $query = $em->createQuery($dql);
        }
        return $query->getResult() ;
     
    }
    // /**
    //  * @return Section[] Returns an array of Section objects
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
    public function findOneBySomeField($value): ?Section
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
