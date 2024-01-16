<?php

declare(strict_types=1);

namespace App\Command;

use App\Resource\ResourceProviderInterface;
use App\Service\Saver\SaverInterface;
use App\Service\Parser\ParserInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParserCommand extends Command
{
    private ParserInterface $parserService;
    private SaverInterface $saverService;
    private ResourceProviderInterface $provider;

    public function __construct(
        ParserInterface $parserService,
        SaverInterface $saverService,
        ResourceProviderInterface $provider,
        string $name = null
    ) {
        parent::__construct($name);

        $this->parserService = $parserService;
        $this->saverService = $saverService;
        $this->provider = $provider;
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
            foreach ($this->provider->load() as $data) {
                $goodsDTO = $this->parserService->parse($data);

                if (!empty($goodsDTO->goods())) {
                    $this->saverService->save($goodsDTO);
                }
            }
        } catch (\Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
