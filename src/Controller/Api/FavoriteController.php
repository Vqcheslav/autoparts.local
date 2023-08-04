<?php

namespace App\Controller\Api;

use App\Model\FavoriteDTO;
use App\Model\ResponseDTO;
use App\Service\AutopartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FavoriteController extends AbstractController
{
    public function __construct(
        private readonly AutopartService $autopartService
    ) {
    }

    #[Route('/api/autoparts/favorites', name: 'api.favorites.toggle', methods: ['POST'])]
    public function toggle(
        #[MapRequestPayload(acceptFormat: 'json')] FavoriteDTO $favoriteDTO
    ): JsonResponse
    {
        $flag = $this->autopartService->toggleFavorite($favoriteDTO);
        $detail = 'Товар удален из избранного';

        if ($flag) {
            $detail = 'Добавлено в избранное';
        }

        $status = 200;
        $data = new ResponseDTO(true, $status, $detail, ['added' => $flag]);

        return $this->json($data, $status);
    }
}
