<?php

namespace Tests\Unit;

use App\Interfaces\IRandomStringGenerator;
use App\Interfaces\IUrlRepository;
use App\Models\Url;
use App\Services\UniqueUrlIdentifierGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class UniqueUrlIdentifierGeneratorTest extends TestCase
{
    /** @var MockObject|IRandomStringGenerator */
    private $mockRandomStringGenerator;
    /** @var MockObject|IUrlRepository */
    private $mockUrlRepository;
    private UniqueUrlIdentifierGenerator $uniqueUrlIdentifierGenerator;

    public function setUp(): void
    {
        $this->mockRandomStringGenerator = $this->createMock(IRandomStringGenerator::class);
        $this->mockUrlRepository = $this->createMock(IUrlRepository::class);

        $this->uniqueUrlIdentifierGenerator = new UniqueUrlIdentifierGenerator(
            $this->mockRandomStringGenerator,
            $this->mockUrlRepository
        );
    }

    public function testGenerateMethodReturnsUniqueIdentifier()
    {
        $expectedUniqueIdentifier = 'X0X0X0';

        $this->mockRandomStringGenerator->expects($this->once())
            ->method('generate')
            ->willReturn($expectedUniqueIdentifier); // Create identifier

        $this->mockUrlRepository->expects($this->once())
            ->method('findByIdentifierAndFolder')
            ->with($expectedUniqueIdentifier, null)
            ->willReturn(null); // No existing URL with given identifier

        $identifier = $this->uniqueUrlIdentifierGenerator->generate();

        $this->assertEquals($expectedUniqueIdentifier, $identifier);
    }

    public function testGenerateMethodReturnsUniqueIdentifierWithFolder()
    {
        $expectedUniqueIdentifier = "X0X0X0";
        $expectedFolder = 'test_folder';

        $this->mockRandomStringGenerator->expects($this->once())
            ->method('generate')
            ->willReturn($expectedUniqueIdentifier); // Create identifier

        $this->mockUrlRepository->expects($this->once())
            ->method('findByIdentifierAndFolder')
            ->with($expectedUniqueIdentifier, $expectedFolder)
            ->willReturn(null); // No existing URL with given identifier and foler

        $identifier = $this->uniqueUrlIdentifierGenerator->generate($expectedFolder);

        $this->assertEquals($expectedUniqueIdentifier, $identifier);
    }

    public function testGenerateMethodRegeneratesIdentifierOnDuplicate()
    {
        $expectedDuplicateIdentifier = 'N0N0N0';
        $expectedUniqueIdentifier = 'X0X0X0';

        // Create in consecutive order duplicate and then unique identifier
        $this->mockRandomStringGenerator->expects($this->exactly(2))
            ->method('generate')
            ->willReturnOnConsecutiveCalls($expectedDuplicateIdentifier, $expectedUniqueIdentifier);

        // Simulate finding a duplicate and then not finding one
        $this->mockUrlRepository->expects($this->exactly(2))
            ->method('findByIdentifierAndFolder')
            ->with(...self::withConsecutive(
                [$expectedDuplicateIdentifier, null],
                [$expectedUniqueIdentifier, null]
            ))
            ->willReturnOnConsecutiveCalls($this->createMock(Url::class), null);

        $identifier = $this->uniqueUrlIdentifierGenerator->generate();

        $this->assertEquals($expectedUniqueIdentifier, $identifier);
    }
}
