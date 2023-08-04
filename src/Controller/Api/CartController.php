<?php

namespace App\Controller\Api;

use App\Model\CartDTO;
use App\Model\ResponseDTO;
use App\Service\AutopartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CartController extends AbstractController
{
    public function __construct(
        private readonly AutopartService $autopartService
    ) {
    }

    #[Route('/api/autoparts/carts', name: 'api.carts.toggle', methods: ['POST'])]
    public function toggle(
        #[MapRequestPayload(acceptFormat: 'json')] CartDTO $cartDTO
    ): JsonResponse
    {
        $added = $this->autopartService->toggleCart($cartDTO);
        $detail = 'Товар удален из корзины';

        if ($added) {
            $detail = 'Добавлено в корзину';
        }

        $status = 200;
        $data = new ResponseDTO(true, $status, $detail, ['added' => $added]);

        return $this->json($data, $status);
    }
}
