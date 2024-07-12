<?php

namespace App\Services;

use App\Repository\ReservationRepository;


class UserService
{
    public $reservationsRepo;
    public function __construct(ReservationRepository $reservationsRepo){
        $this->reservationsRepo = $reservationsRepo;
    }
    public function getReservationByUser($id)
    {
         $reservations = $this->reservationsRepo->getReservationByUser($id);
        return $reservations;
    }
}