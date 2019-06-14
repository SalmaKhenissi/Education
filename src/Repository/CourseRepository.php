<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function findBySection($section)
    {
        $specialty=$section->getSpecialty()->getId();
        $level=$section->getLevel()->getId();

        $query=$this->createQueryBuilder('c')    
                        ->Join('c.level', 'L')
                        ->Join('c.specialty', 'S')           
                        ->where('L.id like :idL')
                        ->andWhere('S.id like :idS')
                        ->setParameter('idL', $level)
                        ->setParameter('idS', $specialty)
                        ->getQuery();

        return $query->getResult() ;
    }

    public function findByOP($libelle , $specialty , $level)
    {

        if ($libelle && $specialty && $level)
        {
            $courses=$this->createQueryBuilder('c')
                        ->Join('c.level', 'L')
                        ->Join('c.specialty', 'Sep')
                        ->where('L.number like :l')
                        ->andWhere('c.libelle like :li')
                        ->andWhere('Sep.shortcut like :sep')
                        ->setParameter('l', $level)
                        ->setParameter('li', $libelle)
                        ->setParameter('sep', $specialty)
                        ->getQuery()
                        ->getResult();
        }
        else if ($libelle  && $level)
        {
            $courses=$this->createQueryBuilder('c')
                        ->Join('c.level', 'L')
                        ->where('L.number like :l')
                        ->andWhere('c.libelle like :li')
                        ->setParameter('l', $level)
                        ->setParameter('li', $libelle)
                        ->getQuery()
                        ->getResult();
        }
        else if ($libelle && $specialty )
        {
            $courses=$this->createQueryBuilder('c')
                        ->Join('c.level', 'L')
                        ->Join('c.specialty', 'Sep')
                        ->andWhere('c.libelle like :li')
                        ->andWhere('Sep.shortcut like :sep')
                        ->setParameter('li', $libelle)
                        ->setParameter('sep', $specialty)
                        ->getQuery()
                        ->getResult();
        }
        else if ( $specialty && $level)
        {
            $courses=$this->createQueryBuilder('c')
                        ->Join('c.level', 'L')
                        ->Join('c.specialty', 'Sep')
                        ->where('L.number like :l')
                        ->andWhere('Sep.shortcut like :sep')
                        ->setParameter('l', $level)
                        ->setParameter('sep', $specialty)
                        ->getQuery()
                        ->getResult();
        }
        else if ( $level)
        {
            $courses=$this->createQueryBuilder('c')
                        ->Join('c.level', 'L')
                        ->where('L.number like :l')
                        ->setParameter('l', $level)
                        ->getQuery()
                        ->getResult();
        }
        else if ( $specialty)
        {
            $courses=$this->createQueryBuilder('c')
                        ->Join('c.specialty', 'Sep')
                        ->andWhere('Sep.shortcut like :sep')
                        ->setParameter('sep', $specialty)
                        ->getQuery()
                        ->getResult();
        }
        else if ($libelle )
        {
            $courses=$this->createQueryBuilder('c')
                        ->where('c.libelle like :li')
                        ->setParameter('li', $libelle)
                        ->getQuery()
                        ->getResult();
        }
        
        else{
        $courses=$this->createQueryBuilder('c')
                        ->getQuery()
                        ->getResult();
        }
        
        $tab=[];
        foreach($courses as $c)
        {
            $tab[$c->getId()]=$c->getLibelle();
        }
        asort($tab);
        $sorted=[];
        foreach($tab as $k => $v )
        {
            $sorted[]=$this->findById($k)[0];
        }
        
            return $sorted ;
    }

    // /**
    //  * @return Course[] Returns an array of Course objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Course
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
