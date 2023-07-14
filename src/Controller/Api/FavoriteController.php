<?php

namespace App\Controller\Api;

use App\Model\FavoriteDTO;
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

    #[Route('/api/autoparts/favorites', name: 'api.favorites.create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json')] FavoriteDTO $favoriteDTO,
        NormalizerInterface $normalizer
    ): JsonResponse
    {
        $favorite = $this->autopartService->createFavorite($favoriteDTO);
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('show')
            ->toArray();
        $normalized = $normalizer->normalize($favorite, null, $context);
        $status = 201;
        $data = [
            'ok' => true,
            'status' => $status,
            'detail' => 'Добавлено в избранное',
            'data' => $normalized,
        ];

        return $this->json($data, $status);
    }

    #[Route('/api/autoparts/favorites', name: 'api.favorites.delete', methods: ['DELETE'])]
    public function delete(
        #[MapRequestPayload(acceptFormat: 'json')] FavoriteDTO $favoriteDTO,
        NormalizerInterface $normalizer
    ): JsonResponse
    {
        $favoriteId = $this->autopartService->deleteFavorite($favoriteDTO);
        $status = 204;
        $data = [
            'ok' => true,
            'status' => $status,
            'detail' => 'Удалено из избранного',
            'data' => ['favoriteId' => $favoriteId],
        ];

        return $this->json($data, $status);
    }
}
