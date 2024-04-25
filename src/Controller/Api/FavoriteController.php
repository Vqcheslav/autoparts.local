<?php

namespace App\Controller\Api;

use App\Model\FavoriteDto;
use App\Model\ResponseDto;
use App\Service\AutopartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    public function __construct(
        private readonly AutopartService $autopartService
    ) {
    }

    #[Route('/api/autoparts/favorites', name: 'api.favorites.toggle', methods: ['POST'])]
    public function toggle(
        #[MapRequestPayload(acceptFormat: 'json')] FavoriteDto $favoriteDto
    ): JsonResponse
    {
        $flag = $this->autopartService->toggleFavorite($favoriteDto);
        $detail = 'Товар удален из избранного';

        if ($flag) {
            $detail = 'Добавлено в избранное';
        }

        $status = 200;
        $data = new ResponseDto(true, $status, $detail, ['added' => $flag]);

        return $this->json($data, $status);
    }
}
