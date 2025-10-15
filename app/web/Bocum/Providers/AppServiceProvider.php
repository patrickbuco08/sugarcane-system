<?php

namespace Bocum\Providers;

use Illuminate\Support\ServiceProvider;
use Bocum\Services\ConfigurationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register ConfigurationService as singleton for efficient caching
        $this->app->singleton(ConfigurationService::class, function ($app) {
            return new ConfigurationService();
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
