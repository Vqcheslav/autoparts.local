<?php

namespace App\Service;

use App\Entity\User;
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

    public function getFavoritesByUser(User $user): array
    {
        return $this->autopartRepository->getFavoritesByUser($user);
    }

    public function getInCartByUser(User $user): array
    {
        return $this->autopartRepository->getInCartByUser($user);
    }
}