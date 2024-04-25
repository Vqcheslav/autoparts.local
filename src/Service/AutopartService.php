<?php

namespace App\Service;

use App\Entity\Autopart;
use App\Entity\Car;
use App\Entity\Cart;
use App\Entity\Favorite;
use App\Entity\Manufacturer;
use App\Entity\User;
use App\Entity\Warehouse;
use App\Model\Autopart\CreateAutopartDto;
use App\Model\CartDto;
use App\Model\FavoriteDto;
use App\Model\ResultDto;
use App\Repository\AutopartRepository;
use App\Repository\CartRepository;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Exception;

readonly class AutopartService extends AbstractService
{
    public function __construct(
        private AutopartRepository $autopartRepository,
        private FavoriteRepository $favoriteRepository,
        private CartRepository $cartRepository,
        private EntityManagerInterface $entityManager,
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

    public function getAutopartById(string $autopartId): ?Autopart
    {
        return $this->autopartRepository->getAutopartById($autopartId);
    }

    public function createAutopart(CreateAutopartDto $dto, bool $flush = true): ResultDto
    {
        try {
            $autopart = new Autopart();
            $autopart->setCar(
                $this->entityManager->getReference(Car::class, $dto->carId)
            );
            $autopart->setWarehouse(
                $this->entityManager->getReference(Warehouse::class, $dto->warehouseId)
            );
            $autopart->setManufacturer(
                $this->entityManager->getReference(Manufacturer::class, $dto->manufacturerId)
            );
            $autopart->setTitle($dto->title);
            $autopart->setDescription($dto->description);
            $autopart->setImagePath($dto->imagePath);
            $autopart->setPrice($dto->price);
            $autopart->setCurrency($dto->currency);
            list($hour, $minute) = explode(':', (new \DateTimeImmutable())->format('H:i'));
            $autopart->setCreatedAt((new \DateTimeImmutable())->setTime($hour, $minute));
            $this->autopartRepository->save($autopart, $flush);
        } catch (ORMException $e) {
            return $this->makeResultDto(ok: false, data: $e, detail: $e->getMessage());
        }

        return $this->makeResultDto(ok: true, data: $autopart, detail: 'Saved');
    }

    /**
     * @throws NonUniqueResultException
     */
    public function toggleFavorite(FavoriteDto $favoriteDto): bool
    {
        $favorite = $this->favoriteRepository
            ->getByUserIdAndAutopartId($favoriteDto->userId, $favoriteDto->autopartId);

        if ($favorite === null) {
            $this->createFavorite($favoriteDto);

            return true;
        }

        $this->removeFavorite($favorite);

        return false;
    }

    public function createFavorite(FavoriteDto $favoriteDto): ?Favorite
    {
        try {
            $favorite = new Favorite();
            $favorite->setUser(
                $this->entityManager->getReference(User::class, $favoriteDto->userId)
            );
            $favorite->setAutopart(
                $this->entityManager->getReference(Autopart::class, $favoriteDto->autopartId)
            );
            $this->favoriteRepository->save($favorite, true);
        } catch (Exception) {
            return null;
        }

        return $favorite;
    }

    public function removeFavorite(Favorite $favorite): void
    {
        $this->favoriteRepository->remove($favorite, true);
    }

    public function toggleCart(CartDto $cartDto): bool
    {
        $cart = $this->cartRepository->getByUserIdAndAutopartId($cartDto->userId, $cartDto->autopartId);

        if ($cart === null) {
            $this->createCart($cartDto);

            return true;
        }

        $this->removeCart($cart);

        return false;
    }

    public function createCart(CartDto $cartDto): ?Cart
    {
        try {
            $cart = new Cart();
            $cart->setUser(
                $this->entityManager->getReference(User::class, $cartDto->userId)
            );
            $cart->setAutopart(
                $this->entityManager->getReference(Autopart::class, $cartDto->autopartId)
            );
            $this->cartRepository->save($cart, true);
        } catch (Exception) {
            return null;
        }

        return $cart;
    }

    public function removeCart(Cart $cart): void
    {
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

    public function processDataFromParser(array $data): ResultDto
    {
        foreach ($data as $createAutopartDto) {
            $resultDto = $this->createAutopart($createAutopartDto);

            if ($resultDto->hasErrors()) {
                return $resultDto;
            }
        }

        return $this->makeResultDto(ok: true, data: $data, detail: 'Success');
    }

    public function getAutopartsWhereImagePathLike(string $imagePath)
    {
        return $this->autopartRepository->getAutopartsWhereImagePathLike($imagePath);
    }

    public function getLaunchesWhereImagePathLike(string $imagePath)
    {
        return $this->autopartRepository->getLaunchesWhereImagePathLike($imagePath);
    }
}