<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\AutopartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public function __construct(
        private AutopartService $autopartService
    ) {
    }

    #[Route('/cart', name: 'cart')]
    public function index(): Response
    {
        if (! $this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('login');
        }

        /* @var User $user*/
        $user = $this->getUser();
        $autoparts = $this->autopartService->getInCartByUser($user);

        return $this->render('cart.html.twig', ['autoparts' => $autoparts]);
    }
}
