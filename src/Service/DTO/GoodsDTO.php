<?php

declare(strict_types=1);

namespace App\Service\DTO;

class GoodsDTO
{
    private string $name;
    private string $price;
    private string $thumbnailUrl;
    private string $pageUrl;

    public function __construct(string $name, string $price, string $thumbnailUrl, string $pageUrl)
    {
        $this->name = $name;
        $this->price = $price;
        $this->thumbnailUrl = $thumbnailUrl;
        $this->pageUrl = $pageUrl;
    }

    public function toArray(): array
    {
        return [$this->name, $this->price, $this->thumbnailUrl, $this->pageUrl];
    }
}
