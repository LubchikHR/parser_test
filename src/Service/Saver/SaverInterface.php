<?php

declare(strict_types=1);

namespace App\Service\Saver;

use App\Service\DTO\GoodsListDTO;

interface SaverInterface
{
    public function save(GoodsListDTO $dto): void;
}
