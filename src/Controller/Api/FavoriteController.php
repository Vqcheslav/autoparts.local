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

    #[Route('/api/autoparts/favorites', name: 'api.favorites.create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json')] FavoriteDTO $favoriteDTO,
        NormalizerInterface $normalizer
    ): JsonResponse
    {
        $favorite = $this->autopartService->createFavorite($favoriteDTO);

        if (is_null($favorite)) {
            $status = 409;
            $data = new ResponseDTO(false, $status, 'Уже в избранном', []);

            return $this->json($data, $status);
        }

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('show')
            ->toArray();
        $normalized = $normalizer->normalize($favorite, null, $context);
        $status = 201;
        $data = new ResponseDTO(true, $status, 'Добавлено в избранное', $normalized);

        return $this->json($data, $status);
    }

    #[Route('/api/autoparts/favorites', name: 'api.favorites.delete', methods: ['DELETE'])]
    public function delete(#[MapRequestPayload(acceptFormat: 'json')] FavoriteDTO $favoriteDTO): JsonResponse
    {
        $favoriteId = $this->autopartService->deleteFavorite($favoriteDTO);

        if (is_null($favoriteId)) {
            $status = 404;
            $data = new ResponseDTO(true, $status, 'Не найдено', []);

            return $this->json($data, $status);
        }

        return $this->json([], 204);
    }
}
