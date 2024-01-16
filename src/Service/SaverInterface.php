<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\DTO\GoodsListDTO;

interface SaverInterface
{
    public function save(GoodsListDTO $dto): void;
}
