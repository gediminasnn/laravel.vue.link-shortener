<?php

namespace Tests\Unit\App\Helpers;

use App\Helpers\RandomAlphanumericStringGenerator;
use Tests\TestCase;

class RandomAlphanumericStringGeneratorTest extends TestCase
{
    private RandomAlphanumericStringGenerator $randomAlphanumericStringGenerator;

    public function setUp(): void
    {
        $this->randomAlphanumericStringGenerator = new RandomAlphanumericStringGenerator();
    }

    public function testGenerateMethodReturnsString(): void
    {
        $randomString = $this->randomAlphanumericStringGenerator->generate();

        $this->assertIsString($randomString);
    }

    public function testGeneratedStringLengthIsCorrect(): void
    {
        $randomString = $this->randomAlphanumericStringGenerator->generate();

        $this->assertEquals($this->randomAlphanumericStringGenerator::STRING_LENGTH, strlen($randomString));
    }

    public function testGeneratedStringContainsAlphanumericCharacters(): void
    {
        $randomString = $this->randomAlphanumericStringGenerator->generate();

        $allowedCharacters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $allCharactersValid = true;

        foreach (str_split($randomString) as $char) {
            if (!str_contains($allowedCharacters, $char)) {
                $allCharactersValid = false;
                break;
            }
        }

        $this->assertTrue($allCharactersValid, 'Generated string contains characters outside the allowed set.');
    }
}
