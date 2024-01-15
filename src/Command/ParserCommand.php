<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\HtmlParserService;
use App\Service\SaveCSVService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ParserCommand extends Command
{
    private const CATEGORY_GOODS_PAGE = 'https://www.yakaboo.ua/ua/knigi/hudozhestvennaja-literatura/klassicheskaja-proza.html?p=%s';
    private const FILE_PATH = __DIR__ . '/../../parsed_goods.csv';
    private const COUNT_PAGES = 3;

    private HtmlParserService $parserService;
    private SaveCSVService $csvService;
    private HttpClientInterface $httpClient;

    public function __construct(HtmlParserService $parserService, SaveCSVService $csvService, string $name = null)
    {
        parent::__construct($name);

        $this->parserService = $parserService;
        $this->csvService = $csvService;
        $this->httpClient = HttpClient::create();
    }

    protected function configure()
    {
        $this
            ->setName('app:parse-web-pages')
            ->setDescription('Lets parse a few pages..');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            foreach ($this->loadHtml() as $html) {
                $goodsDTO = $this->parserService->parse($html);

                if (!empty($goodsDTO->goods())) {
                    $this->csvService->save($goodsDTO, self::FILE_PATH);
                }
            }
        } catch (\Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    private function loadHtml(): iterable
    {
        try {
            for ($page = 1; $page <= self::COUNT_PAGES; $page++) {
                $url = sprintf(self::CATEGORY_GOODS_PAGE, $page);
                $response = $this->httpClient->request('GET', $url);

                yield $response->getContent();
            }
        } catch (ExceptionInterface $e) {
            throw new \Exception('Error loading HTML: ' . $e->getMessage());
        }
    }
}
