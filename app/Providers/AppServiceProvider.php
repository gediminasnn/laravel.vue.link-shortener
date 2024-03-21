<?php

declare(strict_types=1);

namespace App\Providers;

use App\Interfaces\IRandomStringGenerator;
use App\Interfaces\IUrlRepository;
use App\Interfaces\IUrlShorteningService;
use App\Repositories\UrlRepository;
use App\Helpers\RandomAlphanumericStringGenerator;
use App\Interfaces\IUniqueUrlIdentifierGenerator;
use App\Interfaces\IUrlsSafeBrowsingCheckerInterface;
use App\Services\GoogleSafeBrowsingUrlChecker;
use App\Services\UniqueUrlIdentifierGenerator;
use App\Services\UrlShorteningService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IUrlShorteningService::class, UrlShorteningService::class);
        $this->app->bind(IRandomStringGenerator::class, RandomAlphanumericStringGenerator::class);
        $this->app->bind(IUrlRepository::class, UrlRepository::class);
        $this->app->bind(IUrlsSafeBrowsingCheckerInterface::class, function () {
            return new GoogleSafeBrowsingUrlChecker(env('GOOGLE_SAFE_BROWSING_API_KEY'));
        });
        $this->app->bind(IUniqueUrlIdentifierGenerator::class, UniqueUrlIdentifierGenerator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
