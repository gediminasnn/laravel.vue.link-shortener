<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Interfaces\IRandomStringGenerator;

class RandomAlphanumericStringGenerator implements IRandomStringGenerator
{
    public const STRING_LENGTH = 6;

    public function generate(): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = '';

        for ($i = 0; $i < self::STRING_LENGTH; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
}
