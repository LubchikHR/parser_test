<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\DTO\GoodsListDTO;

class SaveCSVService
{
    public function save(GoodsListDTO $dto, string $filePath): void
    {
        $file = fopen($filePath, 'a');

        if ($file === false) {
            throw new \Exception('Error opening the file.');
        }

        foreach ($dto->goods() as $good) {
            fputcsv($file, $good->toArray());
        }

        fclose($file);
    }
}
