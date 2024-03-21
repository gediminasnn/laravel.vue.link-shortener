<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\IUrlRepository;
use App\Models\Url;

class UrlRepository implements IUrlRepository
{
    public function findByIdentifierAndFolder(string $identifier, ?string $folder = null): ?Url
    {
        $url = Url::where('identifier', $identifier)->where('folder', $folder)->first();
        return $url;
    }

    public function findByLongUrlAndFolder(string $longUrl, ?string $folder = null): ?Url
    {
        $url = Url::where('long_url', $longUrl)->where('folder', $folder)->first();
        return $url;
    }

    public function save(Url $url): void
    {
        $url->save();
    }
}
