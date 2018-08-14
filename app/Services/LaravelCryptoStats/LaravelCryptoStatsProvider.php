<?php

namespace App\Services\LaravelCryptoStats;

use Illuminate\Support\ServiceProvider;

class LaravelCryptoStatsProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel_crypto_stats.php' => config_path('laravel_crypto_stats.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('laravel-crypto-stats', function () {
            return new LaravelCryptoStats();
        });
    }
}
