<?php

declare(strict_types=1);

namespace App\Service\DTO;

class GoodsListDTO
{
    /**
     * @var GoodsDTO[]
     */
    private array $goods = [];

    public function add(GoodsDTO $dto)
    {
        $this->goods[] = $dto;
    }

    /**
     * @return GoodsDTO[]
     */
    public function goods(): array
    {
        return $this->goods;
    }
}
