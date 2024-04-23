<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\GoogleApiResponseErrorException;
use App\Exceptions\UnexpectedGoogleApiResponseException;
use Illuminate\Support\Facades\Http;
use App\Interfaces\IUrlsSafeBrowsingCheckerInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

class GoogleSafeBrowsingUrlChecker implements IUrlsSafeBrowsingCheckerInterface
{
    public const THREAT_TYPE_MALWARE = 'MALWARE';
    public const THREAT_TYPE_SOCIAL_ENGINEERING = 'SOCIAL_ENGINEERING';
    public const THREAT_TYPE_UNSPECIFIED = 'UNSPECIFIED';
    public const THREAT_TYPE_UNWANTED_SOFTWARE = 'UNWANTED_SOFTWARE';
    public const THREAT_TYPE_POTENTIALLY_HARMFUL_APPLICATION = 'POTENTIALLY_HARMFUL_APPLICATION';
    public const SAFE_BROWSING_API_URL = 'https://safebrowsing.googleapis.com/v4/threatMatches:find';
    public const PLATFORM_TYPE_WINDOWS = 'WINDOWS';
    public const THREAT_ENTRY_TYPE_URL = 'URL';

    public const HTTP_CODE_BAD_REQUEST = 400;

    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string[] $urls
     * @throws GoogleApiResponseErrorException if the api server provides error response
     */
    public function check(array $urls): array
    {
        $threatInfo = $this->prepareThreatInfo($urls);

        $response = $this->sendApiRequest($threatInfo);

        $data = $this->parseResponseBody($response);

        if ($this->hasError($data)) {
            throw new GoogleApiResponseErrorException($data['error']['message'], self::HTTP_CODE_BAD_REQUEST);
        }

        if ($this->hasThreats($data)) {
            return $this->extractThreatTypes($data);
        }

        if (!empty($data)) {
            throw new UnexpectedGoogleApiResponseException('Unexpected response from Google Safe Browsing API');
        }

        return [];
    }

    private function prepareThreatInfo(array $urls): array
    {
        $threatInfo = [
            'threatInfo' => [
                'threatTypes' => [
                    self::THREAT_TYPE_MALWARE,
                    self::THREAT_TYPE_SOCIAL_ENGINEERING,
                ],
                'platformTypes' => [self::PLATFORM_TYPE_WINDOWS],
                'threatEntryTypes' => [self::THREAT_ENTRY_TYPE_URL],
                'threatEntries' => []
            ]
        ];

        foreach ($urls as $url) {
            $threatInfo['threatInfo']['threatEntries'][] = ['url' => $url];
        }

        return $threatInfo;
    }

    private function sendApiRequest(array $threatInfo): Response
    {
        $url = $this->prepareUrl();
        $request = $this->prepareRequest();

        return $request->post($url, $threatInfo);
    }

    private function prepareUrl(): string
    {
        return self::SAFE_BROWSING_API_URL . '?key=' . $this->apiKey;
    }

    private function prepareRequest(): PendingRequest
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json',
        ]);
    }

    private function parseResponseBody(Response $response): array
    {
        $body = $response->body();
        if (empty($body)) {
            return [];
        }

        return json_decode($body, true);
    }

    private function hasError(array $data): bool
    {
        return isset($data['error']) && !empty($data['error']);
    }

    private function hasThreats(array $data): bool
    {
        return isset($data['matches']) && !empty($data['matches']);
    }

    private function extractThreatTypes(array $data = []): array
    {
        return $data['matches'];
    }
}
