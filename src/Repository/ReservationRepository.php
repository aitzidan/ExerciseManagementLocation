<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    private $em; 
    public function __construct(ManagerRegistry $registry , EntityManagerInterface $em)
    {
        parent::__construct($registry, Reservation::class);
        $this->em =$em; 
    }

    //    /**
    //     * @return Reservation[] Returns an array of Reservation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reservation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function addReservation($dateStart, $dateEnd, $User, $Cars): ?Reservation
    {
        $reservation = new Reservation();
        $reservation->setDateStart($dateStart);
        $reservation->setDateEnd($dateEnd);
        $reservation->setDateCreation(new \DateTime("now"));
        $reservation->setIdUser($User);
        $reservation->setStatus("Réservé");
        $reservation->setIdCar($Cars);
        $this->em->persist($reservation);
        $this->em->flush();
        return $reservation;
    }

    public function getReservation($id):?Reservation
    {
        return $this->find($id);
    }
    public function updateReservation($reservation , $dateStart , $dateEnd): ?Reservation
    {
        $reservation->setDateStart($dateStart);
        $reservation->setDateEnd($dateEnd);
        $this->em->flush();
        return $reservation;
    }
    public function cancelledReservation($reservation): ?Reservation
    {
        $reservation->setStatus("Annulé");
        $this->em->flush();
        return $reservation;
    }

    
    public function getReservationByUser($id)
    {
        return $this->findBy(["idUser"=>$id]);
    }
}
