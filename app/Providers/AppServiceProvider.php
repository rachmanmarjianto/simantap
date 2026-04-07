<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\SimantapService::class, function ($app) {
            return new \App\Services\SimantapService();
        });

        $this->app->singleton(\App\Services\FileService::class, function ($app) {
            return new \App\Services\FileService();
        });

        $this->app->singleton(\App\Services\UACCService::class, function ($app) {
            return new \App\Services\UACCService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}
