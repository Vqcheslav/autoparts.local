<?php

namespace App\Controller\Api;

use App\Model\ResponseDto;
use App\Service\AutopartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AutopartController extends AbstractController
{
    public function __construct(
        private readonly AutopartService $autopartService,
        private readonly NormalizerInterface $normalizer
    ) {
    }

    #[Route('/api/autoparts', name: 'api.autoparts', methods: ['GET', 'HEAD'])]
    public function index(): JsonResponse
    {
        $autoparts = $this->autopartService->getLastAutoparts();
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('show')
            ->toArray();
        $normalized = $this->normalizer->normalize($autoparts, null, $context);
        $status = 200;
        $data = new ResponseDto(true, $status, 'Autoparts', $normalized);

        return $this->json($data, $status);
    }
}
