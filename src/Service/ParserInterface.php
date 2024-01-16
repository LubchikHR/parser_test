<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\DTO\GoodsListDTO;

interface ParserInterface
{
    public function parse(string $data): GoodsListDTO;
}
