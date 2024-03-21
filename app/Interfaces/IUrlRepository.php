<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Url;

interface IUrlRepository
{
    public function findByIdentifierAndFolder(string $identifier, ?string $folder = null): ?Url;

    public function findByLongUrlAndFolder(string $longUrl, ?string $folder = null): ?Url;

    public function save(Url $url): void;
}
