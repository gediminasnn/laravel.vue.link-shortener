<?php

declare(strict_types=1);

namespace App\Interfaces;

interface IUrlsSafeBrowsingCheckerInterface
{
    public function check(array $urls): array;
}
