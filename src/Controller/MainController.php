<?php

namespace App\Controller;

use App\Service\AutopartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    public function __construct(
        private AutopartService $autopartService
    ) {
    }

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        $autoparts = $this->autopartService->getLastAutoparts();

        return $this->render('homepage.html.twig', ['autoparts' => $autoparts]);
    }
}
