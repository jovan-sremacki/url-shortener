<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ShortUrlService;
use App\Services\GoogleSafeBrowsingService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SafeBrowsingServiceInterface::class, GoogleSafeBrowsingService::class);

        $this->app->bind(ShortUrlService::class, function ($app) {
            $safeBrowsingService = $app->make(SafeBrowsingServiceInterface::class);
            return new ShortUrlService($safeBrowsingService);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
