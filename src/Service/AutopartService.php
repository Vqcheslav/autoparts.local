<?php

namespace App\Service;

use App\Repository\AutopartRepository;

class AutopartService
{
    public function __construct(
        private readonly AutopartRepository $autopartRepository
    ) {
    }

    public function getLastAutoparts(int $amount = 50): array
    {
        return $this->autopartRepository->getLast($amount);
    }

    public function findAll()
    {
        return $this->autopartRepository->findAll();
    }
}