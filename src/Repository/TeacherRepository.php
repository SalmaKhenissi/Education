<?php

namespace App\Repository;

use App\Entity\Teacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Teacher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Teacher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Teacher[]    findAll()
 * @method Teacher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeacherRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Teacher::class);
    }

    public function countBySection($section)
    {
        return $this->createQueryBuilder('t')
                    ->Join('t.sections', 's')
                    ->where('s.id = :section')
                    ->setParameter('section', $section)
                    ->getQuery()
                    ->getResult();

        
    }

    public function findByOption($firstName , $lastName )
    {
        $em = $this->getEntityManager();
        
        if ($firstName &&  $lastName )
        {
              $dql = "SELECT t FROM App\Entity\Teacher t where t.firstName like :firstName and t.lastName like :lastName ";
              $query = $em->createQuery($dql);
              $query->setParameter('firstName', $firstName)
                    ->setParameter('lastName', $lastName);
        }
        else if ($firstName )
        {
              $dql = "SELECT t FROM App\Entity\Teacher t where t.firstName like :firstName  ";
              $query = $em->createQuery($dql);
              $query->setParameter('firstName', $firstName);
        }
        else if (  $lastName )
        {
              $dql = "SELECT t FROM App\Entity\Teacher t where  t.lastName like :lastName ";
              $query = $em->createQuery($dql);
              $query->setParameter('lastName', $lastName);
        }
       
         else 
        {
            $dql = "SELECT t FROM App\Entity\Teacher t  ";
            $query = $em->createQuery($dql);
        }
         return $query->getResult() ;
    }

    

    


    // /**
    //  * @return Teacher[] Returns an array of Teacher objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Teacher
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
