<?php

namespace App\Tests\Service;

use App\Model\ParserDto;
use App\Service\AutopartService;
use App\Service\ParserService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ParserServiceTest extends KernelTestCase
{
    public function testParsing(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $parserService = $container->get(ParserService::class);
        $resultDto = $parserService->parse(
            new ParserDto(
                url: ParserService::DEFAULT_URL,
                page: ParserService::DEFAULT_PAGE,
                carId: ParserService::DEFAULT_CAR_ID,
                warehouseId: ParserService::DEFAULT_WAREHOUSE_ID,
                manufacturerId: ParserService::DEFAULT_MANUFACTURER_ID,
            ),
        );

        $this->assertCount(ParserService::ROWS_PER_PAGE_BY_DEFAULT, $resultDto->getData());
    }

    public function testCountOfParsing(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $autopartService = $container->get(AutopartService::class);
        $result = $autopartService->getLaunchesWhereImagePathLike(ParserService::MAIN_SITE);

        foreach ($result as $launch) {
            $this->assertEquals(ParserService::ROWS_PER_PAGE_BY_DEFAULT, $launch['rows']);
        }
    }
}