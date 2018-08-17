<?php

namespace App\Services\LaravelCryptoStats;

use RuntimeException;

class LaravelCryptoStats
{
    /**
     * Service config instance
     * 
     * @var array 
     */
    private $config;
    
    /**
     * LaravelCryptoStats buider
     */
    public function __construct()
    {
        $this->config = config('laravel_crypto_stats');
    }
    
    /**
     * Return the list of the cryptocurrencies defined n the config
     * 
     * @return array
     */
    public function getCurrencies()
    {
        return $this->config['currencies'];
    }
    
    /**
     * Dynamically call the method of the API connector instance
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $factory = new LaravelCryptoStatsFactory();
        $instance = $factory->getInstance($parameters[0]);
        
        if (! $instance) {
            throw new RuntimeException('Instance of the LaravelCryptoStats API connector was not created!');
        }
        
        return $instance->$method(...$parameters);
    }
}
