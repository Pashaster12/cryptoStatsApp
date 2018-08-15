<?php

namespace App\Services\LaravelCryptoStats;

use RuntimeException;

class LaravelCryptoStats
{
    public function getCurrencies()
    {
        return config('laravel_crypto_stats.currencies');
    }
    
    public function __call($name, $arguments)
    {
        $factory = new LaravelCryptoStatsFactory();
        $instance = $factory->getInstance($arguments[0]);
        
        if (! $instance) {
            throw new RuntimeException('Instance of the LaravelCryptoStats API connector was not created!');
        }
        
        return $instance->$name(...$arguments);
    }
}
