<?php

namespace App\Controller\Api;

use App\Model\ResponseDto;
use App\Model\UserDto;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService
    ) {
    }

    #[Route('/api/user/register', name: 'api.user.register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload(acceptFormat: 'form')] UserDto $userDto,
        NormalizerInterface $normalizer
    ): Response {
        $user = $this->userService->create($userDto);
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('show')
            ->toArray();
        $normalized = $normalizer->normalize($user, null, $context);
        $status = 201;
        $data = new ResponseDto(
            true,
            $status,
            'Вы успешно зарегистрировались. Пожалуйста, войдите через форму входа',
            $normalized
        );

        return $this->json($data, $status);
    }
}
