<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 */
class CarRepository extends ServiceEntityRepository
{
    private $em;
    public function __construct(ManagerRegistry $registry , EntityManagerInterface $em)
    {
        parent::__construct($registry, Car::class);
        $this->em = $em;
    }

//    /**
//     * @return Car[] Returns an array of Car objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Car
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function getCarsAvailable(): array
    {
        $now = new \DateTime();

        $query = $this->em->createQuery(
            'SELECT c 
            FROM App\Entity\Car c 
            WHERE c.id NOT IN (
                SELECT IDENTITY(r.idCar) 
                FROM App\Entity\Reservation r 
                WHERE :now BETWEEN r.dateStart AND r.dateEnd
            )'
        )->setParameter('now', $now);
        return $query->getResult();
    }
    
    public function getOneCar($id): ?Car
    {
        $Car = $this->em->getRepository(Car::class)->find($id);
        return $Car;
    }
    
    public function checkCarsAvailable($idCar, $dateStart, $dateEnd)
    {
        $query = $this->em->createQuery(
            'SELECT COUNT(r.id) 
            FROM App\Entity\Reservation r
            WHERE r.idCar = :idCar
            AND (
                (:dateStart BETWEEN r.dateStart AND r.dateEnd) OR 
                (:dateEnd BETWEEN r.dateStart AND r.dateEnd) OR 
                (r.dateStart BETWEEN :dateStart AND :dateEnd)
            )'
        )
        ->setParameter('idCar', $idCar)
        ->setParameter('dateStart', $dateStart)
        ->setParameter('dateEnd', $dateEnd);

        $result = $query->getSingleScalarResult();

        // If the result is 0, it means no conflicting reservations exist, hence the car is available
        return $result == 0;
    }
    public function checkCarsAvailableForUpdate($idCar, $dateStart, $dateEnd , $idRes)
    {
        $query = $this->em->createQuery(
            'SELECT COUNT(r.id) 
            FROM App\Entity\Reservation r
            WHERE r.idCar = :idCar
            AND (
                (:dateStart BETWEEN r.dateStart AND r.dateEnd) OR 
                (:dateEnd BETWEEN r.dateStart AND r.dateEnd) OR 
                (r.dateStart BETWEEN :dateStart AND :dateEnd)
            ) 
            AND r.id != :id
            '
        )
        ->setParameter('idCar', $idCar)
        ->setParameter('dateStart', $dateStart)
        ->setParameter('dateEnd', $dateEnd)
        ->setParameter('id', $idRes);

        $result = $query->getSingleScalarResult();

        return $result == 0;
    }

}
