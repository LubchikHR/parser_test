<?php

declare(strict_types=1);

namespace App\Service\Parser;

use App\Service\DTO\GoodsDTO;
use App\Service\DTO\GoodsListDTO;
use Symfony\Component\DomCrawler\Crawler;

class YakabooHtmlParserService implements ParserInterface
{
    private string $baseUrl;
    private Crawler $crawler;

    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    public function parse(string $data): GoodsListDTO
    {
        $listDTO = new GoodsListDTO();
        $this->crawler->addHtmlContent($data);
        $nodes = $this->crawler->filterXPath('//div[contains(@class, "category-card ")]');

        $this->baseUrl = $this->parseBaseUrl();

        for ($i = 0; $i < $nodes->count(); $i++) {
            $listDTO->add(
                $this->parseProcess(
                    $nodes->eq($i)
                )
            );
        }

        $this->crawler->clear();

        return $listDTO;
    }

    private function parseProcess(Crawler $node): GoodsDTO
    {
        return new GoodsDTO(
            $this->getGoodsTitle($node),
            $this->getGoodsPrice($node),
            $this->getGoodsThumbnailUrl($node),
            $this->getGoodsUrl($node),
        );
    }

    private function getGoodsTitle(Crawler $node): string
    {
        return $node->filterXPath('//a[contains(@class, "ui-card-title ")]')->attr('title') ?? '';
    }

    private function getGoodsPrice(Crawler $node): string
    {
        return $node
                ->filterXPath('//div[contains(@class, "ui-price-display__main ")]/span | //div[contains(@class, "ui-price-display__main")]/span')
                ->text() ?? '';
    }

    private function getGoodsThumbnailUrl(Crawler $node): string
    {
        return $this->baseUrl . $node
                ->filterXPath('//div[contains(@class, "product-image ")]/img')
                ->attr('src') ?? '';
    }

    private function getGoodsUrl(Crawler $node): string
    {
        return $this->baseUrl . $node
                ->filterXPath('//a[contains(@class, "ui-card-title ")]')
                ->attr('href') ?? '';
    }

    private function parseBaseUrl(): string
    {
       $url = $this->crawler->filterXPath('//meta[@property="og:url"]')->attr('content') ?? '';
       ['scheme' => $scheme, 'host' => $host] = parse_url($url);

       return sprintf('%s://%s', $scheme, $host);
    }
}
