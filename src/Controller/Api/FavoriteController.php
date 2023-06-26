<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Service\AutopartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    public function __construct(
        private readonly AutopartService $autopartService
    ) {
    }

    #[Route('/api/autoparts/favorites/{user}/{isInCart}', name: 'api.autoparts.favorites', methods: ['GET', 'HEAD'])]
    public function index(User $user, bool $isInCart = false): JsonResponse
    {
        $autoparts = $this->autopartService->getFavoritesByUser($user);

        return $this->json(['data' => $autoparts], 200);
    }
}
