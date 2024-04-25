<?php

namespace App\Service;

use App\Repository\CarRepository;

readonly class CarService extends AbstractService
{
    public function __construct(
        private CarRepository $carRepository,
    ) {
    }

    public function findAll(): array
    {
        return $this->carRepository->findAll();
    }
}