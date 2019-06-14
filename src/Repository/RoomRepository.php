<?php

namespace App\Repository;

use App\Entity\Room;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function findByAvailability($exam , $rooms ,$examsPerDate)
    { 
        
        $start=$exam->getStartAt();
        $finish=$exam->getFinishAt();
        $roomExams=[];
        foreach($examsPerDate as $e) 
        {   if($e->getStartAt()>=$start && $e->getStartAt()<$finish )
            {
                $roomExams[]=$e->getRoom();
            }
        }
        $tab=array_diff($rooms,$roomExams);
        if($exam->getRoom()!=null)
        {
            $tab[]=$exam->getRoom();
        }

        return ( $tab);

    }

    public function findRoomByAvailability($seance , $rooms ,$seancesPerDay)
    {

        $start=$seance->getStartAt();
        $finish=$seance->getFinishAt();
        $roomSeances=[];
        foreach($seancesPerDay as $s) 
        {   if($s->getStartAt()>=$start && $s->getStartAt()<$finish )
            {
                $roomSeances[]=$s->getRoom();
            }
        }
        $tab=array_diff($rooms,$roomSeances);
        if($seance->getRoom()!=null)
        {
            $tab[]=$seance->getRoom();
        }

        return ( $tab);
    }

    public function findByDay($seances ,$exam)
    {
        $week=['Dimanche','Lundi','Mardi' ,'Mercredi' , 'Jeudi' ,'Vendredi','Samedi'];
        foreach($seances as $s)
            {   $Day=$s->getDay();

                if($Day==0){$day='Lundi';}
                else if($Day==1){$day='Mardi';}
                else if($Day==2){$day='Mercredi';}
                else if($Day==3){$day='Jeudi';}
                else if($Day==4){$day='Vendredi';}
                else if($Day==5){$day='Samedi';}

                $d=date('N',strtotime($exam->getPassAt()->format('Y-m-d')));
                if($day==$week[$d])
                {
                    $room=$s->getRoom();
                }

            }
            return $room;
    }

    // /**
    //  * @return Room[] Returns an array of Room objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Room
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
