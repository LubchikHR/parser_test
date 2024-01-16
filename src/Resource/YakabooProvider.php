<?php

declare(strict_types=1);

namespace App\Resource;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Exception;

class YakabooProvider implements ResourceProviderInterface
{
    private const CATEGORY_GOODS_PAGE = 'https://www.yakaboo.ua/ua/knigi/hudozhestvennaja-literatura/klassicheskaja-proza.html?p=%s';
    private const COUNT_PAGES = 3;

    private HttpClientInterface $httpClient;

    public function __construct()
    {
        $this->httpClient = HttpClient::create();
    }

    /**
     * @return iterable
     * @throws \Exception
     */
    public function load(): iterable
    {
        try {
            for ($page = 1; $page <= self::COUNT_PAGES; $page++) {
                $url = sprintf(self::CATEGORY_GOODS_PAGE, $page);
                $response = $this->httpClient->request('GET', $url);

                yield $response->getContent();
            }
        } catch (ExceptionInterface $e) {
            throw new Exception(sprintf('Error loading HTML: "%s".', $e->getMessage()));
        }
    }
}
