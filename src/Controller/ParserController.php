<?php

namespace App\Controller;

use App\Model\ParserDto;
use App\Service\AutopartService;
use App\Service\CarService;
use App\Service\ParserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class ParserController extends AbstractController
{
    public function __construct(
        private readonly ParserService $parserService,
        private readonly AutopartService $autopartService,
        private readonly CarService $carService,
    ) {
    }

    #[Route('/parser', name: 'app_parser_index')]
    public function index(): Response
    {
        $url = explode('?', ParserService::URL_FORMAT)[0];
        $autoparts = $this->autopartService->getAutopartsWhereImagePathLike(ParserService::MAIN_SITE);
        $cars = $this->carService->findAll();
        $launches = $this->autopartService->getLaunchesWhereImagePathLike(ParserService::MAIN_SITE);

        return $this->render('parser.html.twig', [
            'url'       => $url,
            'autoparts' => $autoparts,
            'cars'      => $cars,
            'launches'  => $launches,
        ]);
    }

    #[Route('/parser/parse', name: 'app_parser_parse', methods: ['POST'])]
    public function parse(
        #[MapRequestPayload(acceptFormat: 'json')] ParserDto $parserDto
    ): JsonResponse
    {
        $resultDto = $this->parserService->parse($parserDto);

        if ($resultDto->isOk()) {
            $resultDto = $this->autopartService->processDataFromParser($resultDto->getData());
        }

        if ($resultDto->isOk()) {
            $resultDto->setDetail('Parsed successfully. Please update the page');
        }

        return $this->json($resultDto);
    }

    #[Route('/parser/test', name: 'app_parser_test', methods: ['POST'])]
    public function test(): JsonResponse
    {
        $resultDto = $this->parserService->parse(new ParserDto(
            url: ParserService::DEFAULT_URL,
            page: ParserService::DEFAULT_PAGE,
            carId: ParserService::DEFAULT_CAR_ID
        ));

        if ($resultDto->hasErrors() || count($resultDto->getData()) !== ParserService::ROWS_PER_PAGE_BY_DEFAULT) {
            return $this->json($resultDto->setOk(false)->setDetail('Tests failed'));
        }

        return $this->json($resultDto->setDetail('All tests passed successfully'));
    }
}
