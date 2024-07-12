<?php

namespace App\Services;

use App\Repository\ReservationRepository;

class ReservationService
{
    private $reservationRepository;

    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function addReservation($dateStart, $dateEnd, $User, $Cars)
    {
        return $this->reservationRepository->addReservation($dateStart, $dateEnd, $User, $Cars);
    }

    public function getReservation($id)
    {
        return $this->reservationRepository->getReservation($id);
    }

    public function updateReservation($reservation, $dateStart, $dateEnd)
    {
        return $this->reservationRepository->updateReservation($reservation, $dateStart, $dateEnd);
    }

    public function cancelReservation($reservation)
    {
        return $this->reservationRepository->cancelledReservation($reservation);
    }

    public function getReservationsByUser($id)
    {
        return $this->reservationRepository->getReservationByUser($id);
    }
}
