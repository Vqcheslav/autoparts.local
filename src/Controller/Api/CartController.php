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

    #[Route('/api/autoparts/carts', name: 'api.carts.create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json')] CartDTO $cartDto,
        NormalizerInterface $normalizer
    ): JsonResponse
    {
        $cart = $this->autopartService->createCart($cartDto);

        if (is_null($cart)) {
            $status = 409;
            $data = new ResponseDTO(false, $status, 'Уже в корзине', []);

            return $this->json($data, $status);
        }

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('show')
            ->toArray();
        $normalized = $normalizer->normalize($cart, null, $context);
        $status = 201;
        $data = new ResponseDTO(true, $status, 'Добавлено в корзину', $normalized);

        return $this->json($data, $status);
    }

    #[Route('/api/autoparts/carts', name: 'api.carts.delete', methods: ['DELETE'])]
    public function delete(#[MapRequestPayload(acceptFormat: 'json')] CartDTO $cartDto): JsonResponse
    {
        $cartId = $this->autopartService->deleteCart($cartDto);

        if (is_null($cartId)) {
            $status = 404;
            $data = new ResponseDTO(true, $status, 'Не найдено', []);

            return $this->json($data, $status);
        }

        return $this->json([], 204);
    }
}
