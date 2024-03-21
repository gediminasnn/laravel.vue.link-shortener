<?php

declare(strict_types=1);

namespace App\Interfaces;

interface IRandomStringGenerator
{
    public function generate(): string;
}
