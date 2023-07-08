<?php

namespace App\Controller\Api;

use App\Model\FavoriteDTO;
use App\Service\AutopartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class FavoriteController extends AbstractController
{
    public function __construct(
        private readonly AutopartService $autopartService
    ) {
    }

    #[Route('/api/autoparts/favorites/{user}/{autopart}', name: 'api.autoparts.favorites', methods: ['POST'])]
    public function index(
        #[MapRequestPayload(acceptFormat: 'json')] FavoriteDTO $favoriteDTO,
        ObjectNormalizer $normalizer
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
}
