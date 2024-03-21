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

    public function shorten(string $longUrl, ?string $folder = null)
    {
        if (empty($longUrl)) {
            throw new InvalidArgumentException('Long URL cannot be empty.');
        }

        $threats = $this->urlsSafeBrowsingChecker->check([$longUrl]);
        if (count($threats) > 0) {
            throw new ThreatsFoundException('Google Safe Browsing has identified threats in this URL.');
        }

        $existingUrl = $this->urlRepository->findByLongUrlAndFolder($longUrl, $folder);

        if (!$existingUrl) {
            $url = new Url([
                'long_url' => $longUrl,
                'identifier' => $this->uniqueUrlIdentifierGenerator->generate(),
                'folder' => $folder
            ]);
            $this->urlRepository->save($url);
            $existingUrl = $url;
        }

        return [$existingUrl->getIdentifier(), $folder];
    }
}
