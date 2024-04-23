<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\ThreatsFoundException;
use App\Interfaces\IUniqueUrlIdentifierGenerator;
use App\Interfaces\IUrlRepository;
use App\Interfaces\IUrlsSafeBrowsingCheckerInterface;
use App\Interfaces\IUrlShorteningService;
use App\Models\Url;
use InvalidArgumentException;

class UrlShorteningService implements IUrlShorteningService
{
    public const HTTP_CODE_BAD_REQUEST = 400;

    private IUrlRepository $urlRepository;
    private IUrlsSafeBrowsingCheckerInterface $urlsSafeBrowsingChecker;
    private IUniqueUrlIdentifierGenerator $uniqueUrlIdentifierGenerator;

    public function __construct(
        IUrlRepository $urlRepository,
        IUrlsSafeBrowsingCheckerInterface $urlsSafeBrowsingChecker,
        IUniqueUrlIdentifierGenerator $uniqueUrlIdentifierGenerator,
    ) {
        $this->urlRepository = $urlRepository;
        $this->urlsSafeBrowsingChecker = $urlsSafeBrowsingChecker;
        $this->uniqueUrlIdentifierGenerator = $uniqueUrlIdentifierGenerator;
    }

    /**
     * @param string $longUrl
     * @param string|null $folder
     * @throws ThreatsFoundException if url has any threats found on google api
     */
    public function shorten(string $longUrl, ?string $folder = null)
    {
        if (empty($longUrl)) {
            throw new InvalidArgumentException('Long URL cannot be empty.');
        }

        $threats = $this->urlsSafeBrowsingChecker->check([$longUrl]);
        if (count($threats) > 0) {
            throw new ThreatsFoundException('Google Safe Browsing has identified threats in this URL.', self::HTTP_CODE_BAD_REQUEST);
        }

        $existingUrl = $this->urlRepository->findByLongUrlAndFolder($longUrl, $folder);
        if (!$existingUrl) {
            $existingUrl = $this->createUrl($longUrl, $folder);
        }

        return [$existingUrl->getIdentifier(), $existingUrl->getFolder()];
    }

    private function createUrl(string $longUrl, ?string $folder = null): Url
    {
        $url = new Url([
            'long_url' => $longUrl,
            'identifier' => $this->uniqueUrlIdentifierGenerator->generate(),
            'folder' => $folder
        ]);
        $this->urlRepository->save($url);

        return $url;
    }
}
