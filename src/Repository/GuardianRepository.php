<?php

namespace App\Repository;

use App\Entity\Guardian;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Guardian|null find($id, $lockMode = null, $lockVersion = null)
 * @method Guardian|null findOneBy(array $criteria, array $orderBy = null)
 * @method Guardian[]    findAll()
 * @method Guardian[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuardianRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Guardian::class);
    }

    public function findByOption($firstName , $lastName )
    {
        $em = $this->getEntityManager();
        
        if ($firstName &&  $lastName )
        {
              $dql = "SELECT g FROM App\Entity\Guardian g where g.firstName like :firstName and g.lastName like :lastName ";
              $query = $em->createQuery($dql);
              $query->setParameter('firstName', $firstName)
                    ->setParameter('lastName', $lastName);
        }
        else if ($firstName)
        {
            $dql = "SELECT g FROM App\Entity\Guardian g where g.firstName like :firstName  ";
              $query = $em->createQuery($dql);
              $query->setParameter('firstName', $firstName);

        }
        else if ($lastName)
        {
            $dql = "SELECT g FROM App\Entity\Guardian g where  g.lastName like :lastName ";
              $query = $em->createQuery($dql);
              $query->setParameter('lastName', $lastName);

        }
         else 
        {
            $dql = "SELECT g FROM App\Entity\Guardian g  ";
            $query = $em->createQuery($dql);
        }

        $tab=[];
        foreach($query->getResult() as $g)
        {
            $tab[$g->getId()]=$g->getLastName();
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
    //  * @return Guardian[] Returns an array of Guardian objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Guardian
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
