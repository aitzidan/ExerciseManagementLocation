<?php

namespace App\Services;

use App\Repository\CarRepository;

class CarService
{
    private $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    public function getCarsAvailable(): array
    {
        return $this->carRepository->getCarsAvailable();
    }

    public function getOneCar($id)
    {
        return $this->carRepository->getOneCar($id);
    }
}
