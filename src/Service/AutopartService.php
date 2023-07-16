<?php

namespace App\Service;

use App\Entity\Autopart;
use App\Entity\Cart;
use App\Entity\Favorite;
use App\Entity\User;
use App\Model\CartDTO;
use App\Model\FavoriteDTO;
use App\Repository\AutopartRepository;
use App\Repository\CartRepository;
use App\Repository\FavoriteRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\NonUniqueResultException;

class AutopartService
{
    public function __construct(
        private readonly AutopartRepository $autopartRepository,
        private readonly FavoriteRepository $favoriteRepository,
        private readonly CartRepository $cartRepository,
        private readonly UserRepository $userRepository
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

    public function createFavorite(FavoriteDTO $favoriteDTO): ?Favorite
    {
        try {
            $favorite = new Favorite();
            $favorite->setUser(
                $this->userRepository->find($favoriteDTO->userId)
            );
            $favorite->setAutopart(
                $this->autopartRepository->find($favoriteDTO->autopartId)
            );
            $this->favoriteRepository->save($favorite, true);
        } catch (UniqueConstraintViolationException) {
            return null;
        }

        return $favorite;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function deleteFavorite(FavoriteDTO $favoriteDTO): ?string
    {
        $favorite = $this->favoriteRepository->getByUserIdAndAutopartId(
            $favoriteDTO->userId,
            $favoriteDTO->autopartId
        );

        if (is_null($favorite)) {
            return null;
        }

        $favoriteId = $favorite->getFavoriteId();
        $this->favoriteRepository->remove($favorite, true);

        return $favoriteId;
    }

    public function createCart(CartDTO $cartDTO): ?Cart
    {
        try {
            $cart = new Cart();
            $cart->setUser(
                $this->userRepository->find($cartDTO->userId)
            );
            $cart->setAutopart(
                $this->autopartRepository->find($cartDTO->autopartId)
            );
            $this->cartRepository->save($cart, true);
        } catch (UniqueConstraintViolationException) {
            return null;
        }

        return $cart;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function deleteCart(CartDTO $cartDTO): ?string
    {
        $cart = $this->cartRepository->getByUserIdAndAutopartId(
            $cartDTO->userId,
            $cartDTO->autopartId
        );

        if (is_null($cart)) {
            return null;
        }

        $cartId = $cart->getCartId();
        $this->cartRepository->remove($cart, true);

        return $cartId;
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