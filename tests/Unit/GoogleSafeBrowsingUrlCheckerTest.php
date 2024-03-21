<?php

namespace Tests\Feature\Services;

use App\Services\GoogleSafeBrowsingUrlChecker;
use App\Exceptions\ApiResponseErrorException;
use App\Exceptions\UnexpectedApiResponseException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GoogleSafeBrowsingUrlCheckerTest extends TestCase
{
    private const MOCK_API_KEY = 'mock_api_key_ALKadPT49w30';
    private const TEST_URLS = ['https://example.com', 'https://another-example.com'];
    private const MALWARE_URLS = ['http://malware.testing.google.test/testing/malware/*'];
    private const HTTP_SUCCESS_CODE = 200;
    private const HTTP_BAD_REQUEST_CODE = 400;

    private GoogleSafeBrowsingUrlChecker $checker;

    public function setUp(): void
    {
        parent::setUp();

        $this->checker = new GoogleSafeBrowsingUrlChecker(self::MOCK_API_KEY);
    }

    public function testCheckApiSuccessNoThreatsFound()
    {
        Http::fake([
            $this->checker::SAFE_BROWSING_API_URL . '?key=' . self::MOCK_API_KEY
            => Http::response([], self::HTTP_SUCCESS_CODE),
        ]);

        $threats = $this->checker->check(self::TEST_URLS);

        $this->assertEmpty($threats);
    }

    public function testCheckApiSuccessThreatsFound()
    {
        $response = [
            'matches' => [
                [
                    'threatType' => "MALWARE",
                    'platformType' => "WINDOWS",
                    'threatEntryType' => 'URL',
                    'threat' => [
                        'url' => "http://malware.testing.google.test/testing/malware/*"
                    ],
                    'cacheDuration' => '300s'
                ]
            ]
        ];

        Http::fake([
            $this->checker::SAFE_BROWSING_API_URL . '?key=' . self::MOCK_API_KEY
            => Http::response($response, self::HTTP_SUCCESS_CODE),
        ]);

        $threats = $this->checker->check(self::MALWARE_URLS);

        $this->assertEquals($threats[0]['threatType'], $this->checker::THREAT_TYPE_MALWARE);
    }

    public function testCheckApiError()
    {
        $errorMessage = 'API key not valid. Please pass a valid API key.';

        $response = [
            'error' =>
            [
                'message' => $errorMessage,
                'code' => self::HTTP_BAD_REQUEST_CODE,
                'status' => "INVALID_ARGUMENT",
                'details' => [
                    '@type' => "type.googleapis.com/google.rpc.ErrorInfo",
                    'reason' => "API_KEY_INVALID",
                    'domain' => "googleapis.com",
                    'metadata' => [
                        "service" => "safebrowsing.googleapis.com"
                    ]
                ]
            ]
        ];

        Http::fake([
            $this->checker::SAFE_BROWSING_API_URL . '?key=' . self::MOCK_API_KEY
            => Http::response($response, self::HTTP_BAD_REQUEST_CODE),
        ]);

        $this->expectException(ApiResponseErrorException::class);
        $this->expectExceptionMessage($errorMessage);
        $this->expectExceptionCode(self::HTTP_BAD_REQUEST_CODE);

        $this->checker->check(self::TEST_URLS);
    }

    public function testCheckApiSuccessUnexpectedResponse()
    {
        Http::fake([
            $this->checker::SAFE_BROWSING_API_URL . '?key=' . self::MOCK_API_KEY
            => Http::response(['invalid' => 'data'], self::HTTP_SUCCESS_CODE),
        ]);

        $this->expectException(UnexpectedApiResponseException::class);
        $this->expectExceptionMessage('Unexpected response from Google Safe Browsing API');

        $this->checker->check(self::TEST_URLS);
    }
}
