<?php

namespace App\Service;

use App\Repository\AutopartRepository;

class AutopartService
{
    public function __construct(
        private AutopartRepository $autopartRepository
    ) {
    }

    public function getLastAutoparts(int $amount = 50)
    {

    }

    public function findAll()
    {
        return $this->autopartRepository->findAll();
    }
}