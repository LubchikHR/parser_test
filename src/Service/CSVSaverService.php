<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\DTO\GoodsListDTO;

class CSVSaverService implements SaverInterface
{
    private const FILE_PATH = __DIR__ . '/../../parsed_goods.csv';

    public function save(GoodsListDTO $dto): void
    {
        $file = fopen(self::FILE_PATH, 'a');

        if ($file === false) {
            throw new \Exception('Error opening the file.');
        }

        foreach ($dto->goods() as $good) {
            fputcsv($file, $good->toArray());
        }

        fclose($file);
    }
}
