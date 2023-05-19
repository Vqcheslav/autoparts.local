<?php

namespace App\Controller\Api;

use App\Service\AutopartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AutopartController extends AbstractController
{
    public function __construct(
        private readonly AutopartService $autopartService
    ) {
    }

    #[Route('/api/autoparts', name: 'api.autoparts', methods: ['GET', 'HEAD'])]
    public function index(): JsonResponse
    {
        $autoparts = $this->autopartService->findAll();

        return $this->json(['data' => $autoparts], 200);
    }
}
