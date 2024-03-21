<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\IRandomStringGenerator;
use App\Interfaces\IUniqueUrlIdentifierGenerator;
use App\Interfaces\IUrlRepository;

class UniqueUrlIdentifierGenerator implements IUniqueUrlIdentifierGenerator
{
    private IRandomStringGenerator $randomStringGenerator;
    private IUrlRepository $urlRepository;

    public function __construct(
        IRandomStringGenerator $randomStringGenerator,
        IUrlRepository $urlRepository
    ) {
        $this->randomStringGenerator = $randomStringGenerator;
        $this->urlRepository = $urlRepository;
    }

    public function generate(?string $folder = null): string
    {
        do {
            $randomString = $this->randomStringGenerator->generate();
        } while ($this->isDuplicate($randomString, $folder));

        return $randomString;
    }

    private function isDuplicate(string $identifier, ?string $folder): bool
    {
        return $this->urlRepository->findByIdentifierAndFolder($identifier, $folder) !== null;
    }
}
