<?php

declare(strict_types=1);

namespace App\Interfaces;

interface IUrlShorteningService
{
    public function shorten(string $longUrl, ?string $folder = null);
}
