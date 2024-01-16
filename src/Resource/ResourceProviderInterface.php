<?php

declare(strict_types=1);

namespace App\Resource;

interface ResourceProviderInterface
{
    public function load(): iterable;
}
