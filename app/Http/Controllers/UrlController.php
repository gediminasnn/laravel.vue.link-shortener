<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\GoogleApiResponseErrorException;
use App\Exceptions\ThreatsFoundException;
use App\Http\Requests\ShortenUrlRequest;
use App\Interfaces\IUrlRepository;
use App\Interfaces\IUrlShorteningService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class UrlController extends Controller
{
    private IUrlRepository $urlRepository;
    private IUrlShorteningService $urlShorteningService;

    public function __construct(
        IUrlRepository $urlRepository,
        IUrlShorteningService $urlShorteningService,
    ) {
        $this->urlRepository = $urlRepository;
        $this->urlShorteningService = $urlShorteningService;
    }

    public function redirectByIdentifier(string $identifier): RedirectResponse
    {
        $url = $this->urlRepository->findByIdentifierAndFolder($identifier);
        if (!$url) {
            abort(404);
        }

        return redirect()->to($url->long_url);
    }

    public function redirectByIdentifierAndFolder(string $folder, string $identifier): RedirectResponse
    {
        $url = $this->urlRepository->findByIdentifierAndFolder($identifier, $folder);
        if (!$url) {
            return abort(404);
        }

        return redirect()->to($url->long_url);
    }

    public function shortenUrl(ShortenUrlRequest $request): JsonResponse
    {
        try {
            [$identifier] = $this->urlShorteningService->shorten($request->get('long_url'));

            return response()->json(['url' => route('redirect', ['identifier' => $identifier])]);
        } catch (GoogleApiResponseErrorException|ThreatsFoundException $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function shortenFolderedUrl(ShortenUrlRequest $request): JsonResponse
    {
        try {
            [$identifier, $folder] = $this->urlShorteningService->shorten($request->get('long_url'), $request->get('folder'));

            return response()->json(['url' => route('redirect-foldered', ['folder' => $folder, 'identifier' => $identifier])]);
        } catch (GoogleApiResponseErrorException|ThreatsFoundException $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
