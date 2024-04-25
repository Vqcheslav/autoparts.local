<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\AutopartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AutopartController extends AbstractController
{
    public function __construct(
        private readonly AutopartService $autopartService
    ) {
    }

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        $autoparts = $this->autopartService->getLastAutoparts();

        return $this->render('homepage.html.twig', ['autoparts' => $autoparts]);
    }

    #[Route('/autopart/{autopartId}', name: 'autopart')]
    public function show(string $autopartId): Response
    {
        $autopart = $this->autopartService->getAutopartById($autopartId);

        if (is_null($autopart)) {
            throw $this->createNotFoundException();
        }

        return $this->render('autopart.html.twig', ['autopart' => $autopart]);
    }

    #[Route('/user/favorite', name: 'favorite')]
    public function showFavorite(): Response
    {
        /* @var User $user*/
        $user = $this->getUser();
        $autoparts = $this->autopartService->getFavoritesByUser($user);

        return $this->render('favorite.html.twig', ['autoparts' => $autoparts]);
    }

    #[Route('/user/cart', name: 'cart')]
    public function showCart(): Response
    {
        /* @var User $user*/
        $user = $this->getUser();
        $autoparts = $this->autopartService->getInCartByUser($user);

        return $this->render('cart.html.twig', ['autoparts' => $autoparts]);
    }
}
