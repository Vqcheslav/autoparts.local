<?php

namespace App\Service;

use App\Entity\Autopart;
use App\Entity\Favorite;
use App\Entity\User;
use App\Model\FavoriteDTO;
use App\Repository\AutopartRepository;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\NonUniqueResultException;

class AutopartService
{
    public function __construct(
        private readonly AutopartRepository $autopartRepository,
        private readonly FavoriteRepository $favoriteRepository
    ) {
    }

    public function getLastAutoparts(int $amount = 50): array
    {
        return $this->autopartRepository->getLast($amount);
    }

    public function findAll(): array
    {
        return $this->autopartRepository->findAll();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getAutopartById(string $autopartId): ?Autopart
    {
        return $this->autopartRepository->getAutopartById($autopartId);
    }

    public function createFavorite(FavoriteDTO $favoriteDTO): Favorite
    {
        $favorite = new Favorite();
        $favorite->setUser(
            $this->favoriteRepository->find($favoriteDTO->userId)
        );
        $favorite->setAutopart(
            $this->autopartRepository->find($favoriteDTO->autopartId)
        );
        $this->favoriteRepository->save($favorite, true);

        return $favorite;
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