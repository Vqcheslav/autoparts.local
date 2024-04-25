<?php

namespace App\Service;

use App\Model\Autopart\CreateAutopartDto;
use App\Model\ParserDto;
use App\Model\ResultDto;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

readonly class ParserService extends AbstractService
{
    public const URL_FORMAT = '%s?PAGEN_1=%d';

    public const MAIN_SITE = 'https://bamper.by';

    public const CURRENCY = 'BYN';

    public const REQUEST_METHOD = 'GET';

    public const ROWS_PER_PAGE_BY_DEFAULT = 20;

    public const DEFAULT_URL = 'https://bamper.by/zchbu/marka_opel/model_astra/god_2001-2001/toplivo_dizel/enginevalue_1.7/';

    public const DEFAULT_WAREHOUSE_ID = '0936f1e3-90f6-4a12-99e9-85ff0d97a181';

    public const DEFAULT_MANUFACTURER_ID = '4f64b287-ff0d-4ae3-9c47-214bf863f249';

    public const DEFAULT_CAR_ID = 'aa420eb6-b3a3-4685-b241-ead427000dd3';

    public const DEFAULT_PAGE = 1;

    public function __construct(
        private HttpClientInterface $client,
        private LoggerInterface $logger,
    ) {
    }

    public function parse(ParserDto $parserDto): ResultDto
    {
        $html = $this->getContentByPage($parserDto->url, $parserDto->page);
        $data = $this->getDataFromHtml($html, $parserDto->carId);
        $ok = ! empty($data);

        return $this->makeResultDto(ok: $ok, data: $data);
    }

    private function getContentByPage(string $url, int $page = 1): string
    {
        $content = '';
        $url = sprintf(self::URL_FORMAT, $url, $page);

        try {
            $response = $this->client->request(self::REQUEST_METHOD, $url);
            $content = $response->getContent();
        } catch (Throwable $e) {
            $this->logger->critical('Cannot get content from page: ', [
                'url'       => $url,
                'throwable' => $e,
            ]);
        }

        return $content;
    }

    private function getDataFromHtml(string $html, string $carId): ?array
    {
        $result = [];

        $doc = new Crawler($html);
        $arrayIterator = $doc->filter('div.item-list')->getIterator();

        foreach ($arrayIterator as $item) {
            $item = new Crawler($item);
            $uri = $item->filter('h5.add-title a')->attr('href');
            $imagePath = $item->filter('img.thumbnail:first-child')->attr('src');
            $description = $item->filter('span.info-row div:nth-child(2)')->text();

            $result[] = new CreateAutopartDto(
                carId: $carId,
                warehouseId: self::DEFAULT_WAREHOUSE_ID,
                manufacturerId: self::DEFAULT_MANUFACTURER_ID,
                title: $item->filter('h5.add-title a b')->text(),
                description: $description . '. Подробнее по ссылке ' . self::MAIN_SITE . $uri,
                imagePath: self::MAIN_SITE . $imagePath,
                price: ((int) $item->filter('h2.item-price span')->text()) / 100,
                currency: self::CURRENCY,
            );
        }

        return $result;
    }
}
