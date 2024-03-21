<?php

declare(strict_types=1);

namespace App\Interfaces;

interface IUniqueUrlIdentifierGenerator
{
    public function generate(?string $folder = null): string;
}
