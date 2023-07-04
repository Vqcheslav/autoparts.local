<?php

namespace App\Controller\Api;

use App\Model\UserDTO;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService
    ) {
    }

    #[Route('/api/register', name: 'api.register', methods: ['POST'])]
    public function register(#[MapRequestPayload(acceptFormat: 'form')] UserDTO $userDTO): Response
    {
        $user = $this->userService->create($userDTO);

        return $this->json(['ok' => true, 'data' => $user]);
    }
}
