<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    public function __construct(
        private UserService $userService
    ) {
    }

    #[Route('/login', name: 'login', methods: ['GET', 'HEAD', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError() ? "Неверный email или пароль" : null;
        $email = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', [
            'error' => $error,
            'email' => $email,
        ]);
    }

    #[Route('/register', name: 'register', methods: ['GET', 'HEAD', 'POST'])]
    public function register(): Response
    {
        return $this->render('register.html.twig');
    }

    #[Route('/logout', name: 'logout', methods: ['GET', 'HEAD', 'POST'])]
    public function logout(Security $security): Response
    {
        $security->logout();

        return $this->redirectToRoute('homepage');
    }
}