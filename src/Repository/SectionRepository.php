<?php

namespace App\Repository;

use App\Entity\Section;
use App\Entity\Level;
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

    
    
   /* public function findbyTeacher(Teacher $teacher)
    {
        return $this->createQueryBuilder('s')
        ->Join('s.teachers', 't')
        ->where('t.id = :teacher_id')
        ->setParameter('teacher_id', $teacher->getId())
        ->getQuery()
        ->getResult()
        
    ;
    }*/

    public function generateName($section )    
    {
            $level =$section->getLevel()->getLibelle();
            $number =$section->getNumber();
            return($level." année ".$number);
    }


    public function findByOption($level,$number)
    {
        $em = $this->getEntityManager();
        
        
       /* if ($level && $specialty && $number )
        {
            $query=$this->createQueryBuilder('s')
                        ->Join('s.level', 'L')
                        ->Join('s.specialty', 'Sep')
                        ->where('L.number like :l')
                        ->andWhere('s.number like :n')
                        ->andWhere('Sep.libelle like :sep')
                        ->setParameter('l', $level)
                        ->setParameter('n', $number)
                        ->setParameter('sep', $specialty)
                        ->getQuery();
        }
        else*/ if ($level &&  $number )
        {
            $query=$this->createQueryBuilder('s')
                        ->Join('s.level', 'L')
                        ->where('L.number like :l')
                        ->andWhere('s.number like :n')
                        ->setParameter('l', $level)
                        ->setParameter('n', $number)
                        ->getQuery();
        }
       /* else if ($level &&  $specialty )
        {
            $query=$this->createQueryBuilder('s')
                        ->Join('s.level', 'L')
                        ->Join('s.specialty', 'Sep')
                        ->where('L.number like :l')
                        ->andWhere('Sep.libelle like :sep')
                        ->setParameter('l', $level)
                        ->setParameter('sep', $specialty)
                        ->getQuery();
        }
        else if ($specialty &&  $number )
        {
            $query=$this->createQueryBuilder('s')
                        ->Join('s.specialty', 'Sep')
                        ->where('Sep.libelle like :l')
                        ->andWhere('s.number like :n')
                        ->setParameter('l', $specialty)
                        ->setParameter('n', $number)
                        ->getQuery();
        }*/
        else if ($level)
        {
              $query=$this->createQueryBuilder('s')
                        ->Join('s.level', 'L')
                        ->where('L.number like :l')
                        ->setParameter('l', $level)
                        ->getQuery();
        }
       /* else if ($specialty)
        {
              $query=$this->createQueryBuilder('s')
                        ->Join('s.specialty', 'Sep')
                        ->where('Sep.libelle like :l')
                        ->setParameter('l', $specialty)
                        ->getQuery();
        }*/
        else if ($number)
        {
              $query=$this->createQueryBuilder('s')
                        ->where('s.number like :n')
                        ->setParameter('n', $number)
                        ->getQuery();
        }
        else 
        {
            $query=$this->createQueryBuilder('s')
                        ->getQuery();
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
