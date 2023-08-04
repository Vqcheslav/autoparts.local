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
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Exception;

class AutopartService
{
    public function __construct(
        private readonly AutopartRepository $autopartRepository,
        private readonly FavoriteRepository $favoriteRepository,
        private readonly CartRepository $cartRepository,
        private readonly EntityManagerInterface $em
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

    /**
     * @throws NonUniqueResultException
     */
    public function toggleFavorite(FavoriteDTO $favoriteDTO): bool
    {
        $favorite = $this->favoriteRepository->getByUserIdAndAutopartId($favoriteDTO->userId, $favoriteDTO->autopartId);

        if ($favorite === null) {
            $this->createFavorite($favoriteDTO);

            return true;
        }

        $this->removeFavorite($favorite);

        return false;
    }

    public function createFavorite(FavoriteDTO $favoriteDTO): ?Favorite
    {
        try {
            $favorite = new Favorite();
            $favorite->setUser(
                $this->getEntityManager()->getReference(User::class, $favoriteDTO->userId)
            );
            $favorite->setAutopart(
                $this->getEntityManager()->getReference(Autopart::class, $favoriteDTO->autopartId)
            );
            $this->favoriteRepository->save($favorite, true);
        } catch (Exception) {
            return null;
        }

        return $favorite;
    }

    public function removeFavorite(Favorite $favorite): void
    {
//        $this->favoriteRepository->removeByUserIdAndAutopartId(
//            $favoriteDTO->userId,
//            $favoriteDTO->autopartId
//        );

        $this->favoriteRepository->remove($favorite, true);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function toggleCart(CartDTO $cartDTO): bool
    {
        $cart = $this->cartRepository->getByUserIdAndAutopartId($cartDTO->userId, $cartDTO->autopartId);

        if ($cart === null) {
            $this->createCart($cartDTO);

            return true;
        }

        $this->removeCart($cart);

        return false;
    }

    public function createCart(CartDTO $cartDTO): ?Cart
    {
        try {
            $cart = new Cart();
            $cart->setUser(
                $this->getEntityManager()->getReference(User::class, $cartDTO->userId)
            );
            $cart->setAutopart(
                $this->getEntityManager()->getReference(Autopart::class, $cartDTO->autopartId)
            );
            $this->cartRepository->save($cart, true);
        } catch (Exception) {
            return null;
        }

        return $cart;
    }

    public function removeCart(Cart $cart): void
    {
//        $this->cartRepository->removeByUserIdAndAutopartId(
//            $cartDTO->userId,
//            $cartDTO->autopartId
//        );

        $this->cartRepository->remove($cart, true);
    }

    public function getFavoritesByUser(User $user): array
    {
        return $this->autopartRepository->getFavoritesByUser($user);
    }

    public function getInCartByUser(User $user): array
    {
        return $this->autopartRepository->getInCartByUser($user);
    }

    private function getEntityManager(): EntityManagerInterface
    {
        return $this->em;
    }
}