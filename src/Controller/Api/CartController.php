<?php

namespace App\Controller\Api;

use App\Model\CartDto;
use App\Model\ResponseDto;
use App\Service\AutopartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public function __construct(
        private readonly AutopartService $autopartService
    ) {
    }

    #[Route('/api/autoparts/carts', name: 'api.carts.toggle', methods: ['POST'])]
    public function toggle(
        #[MapRequestPayload(acceptFormat: 'json')] CartDto $cartDto
    ): JsonResponse
    {
        $added = $this->autopartService->toggleCart($cartDto);
        $detail = 'Товар удален из корзины';

        if ($added) {
            $detail = 'Добавлено в корзину';
        }

        $status = 200;
        $data = new ResponseDto(true, $status, $detail, ['added' => $added]);

        return $this->json($data, $status);
    }
}
