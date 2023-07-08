<?php

namespace App\Controller;

use App\Entity\Autopart;
use App\Service\AutopartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AutopartController extends AbstractController
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

    #[Route('/autopart/{autopartId}', name: 'autopart')]
    public function show(string $autopartId): Response
    {
        $autopart = $this->autopartService->getAutopartById($autopartId);

        if (is_null($autopart)) {
            throw $this->createNotFoundException();
        }

        return $this->render('autopart.html.twig', ['autopart' => $autopart]);
    }
}
