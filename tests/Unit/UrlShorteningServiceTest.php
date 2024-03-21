<?php

namespace Tests\Unit;

use App\Exceptions\ThreatsFoundException;
use App\Interfaces\IUniqueUrlIdentifierGenerator;
use App\Interfaces\IUrlRepository;
use App\Interfaces\IUrlShorteningService;
use App\Interfaces\IUrlsSafeBrowsingCheckerInterface;
use App\Models\Url;
use App\Services\UrlShorteningService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class UrlShorteningServiceTest extends TestCase
{
    /**
     * @var MockObject|IUrlRepository
     */
    private $mockUrlRepository;

    /**
     * @var MockObject|IUrlsSafeBrowsingCheckerInterface
     */
    private $mockSafeBrowsingChecker;

    /**
     * @var MockObject|IUniqueUrlIdentifierGenerator
     */
    private $mockUniqueIdentifierGenerator;

    private IUrlShorteningService $urlShorteningService;

    public function setUp(): void
    {
        $this->mockUrlRepository = $this->createMock(IUrlRepository::class);
        $this->mockSafeBrowsingChecker = $this->createMock(IUrlsSafeBrowsingCheckerInterface::class);
        $this->mockUniqueIdentifierGenerator = $this->createMock(IUniqueUrlIdentifierGenerator::class);

        $this->urlShorteningService = new UrlShorteningService(
            $this->mockUrlRepository,
            $this->mockSafeBrowsingChecker,
            $this->mockUniqueIdentifierGenerator
        );
    }

    public function testShortenMethodReturnsExpetedIdentifierAndFolder()
    {
        $safeUrl = 'https://www.example.com';
        $expectedIdentifier = 'X0X0X0';
        $expectedFolder = null;

        $this->mockSafeBrowsingChecker->expects($this->once())
            ->method('check')
            ->with([$safeUrl])
            ->willReturn([]); // No threats found

        $this->mockUrlRepository->expects($this->once())
            ->method('findByLongUrlAndFolder')
            ->with($safeUrl, $expectedFolder)
            ->willReturn(null); // No existing URL

        $this->mockUniqueIdentifierGenerator->expects($this->once())
            ->method('generate')
            ->willReturn($expectedIdentifier); // Create identifier

        $this->mockUrlRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Url::class)); // Expecting a Url object to be saved

        [$identifier, $folder] = $this->urlShorteningService->shorten($safeUrl);

        $this->assertEquals($expectedIdentifier, $identifier);
        $this->assertEquals($expectedFolder, $folder);
    }

    public function testShortenMethodReturnsExistingPath()
    {
        $safeUrl = 'https://www.example.com';
        $existingIdentifier = 'X0X0X0';

        $existingUrl = new Url([
            'long_url' => $safeUrl,
            'identifier' => $existingIdentifier,
            'folder' => null
        ]);

        $this->mockSafeBrowsingChecker->expects($this->once())
            ->method('check')
            ->with([$safeUrl])
            ->willReturn([]); // No threats found

        $this->mockUrlRepository->expects($this->once())
            ->method('findByLongUrlAndFolder')
            ->with($safeUrl)
            ->willReturn($existingUrl); // Existing URL found

        [$identifier,] = $this->urlShorteningService->shorten($safeUrl);

        $this->assertEquals($existingIdentifier, $identifier);
    }

    public function testShortenMethodThrowsThreatsFoundException()
    {
        $maliciousUrl = 'https://malicious.com';

        $this->mockSafeBrowsingChecker->expects($this->once())
            ->method('check')
            ->with([$maliciousUrl])
            ->willReturn([$this->any()]); // Threats found

        $this->expectException(ThreatsFoundException::class);
        $this->expectExceptionMessage('Google Safe Browsing has identified threats in this URL.');

        $this->urlShorteningService->shorten($maliciousUrl);
    }

    public function testShortenMethodWithEmptyUrlParameterThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Long URL cannot be empty.');

        $this->urlShorteningService->shorten('');
    }
}
