<?php

namespace App\Services\LaravelCryptoStats;

use RuntimeException;
use App\Services\LaravelCryptoStats\Connectors\{EtherscanConnector, ChainsoConnector};

class LaravelCryptoStats
{
    private function getInstance($currency)
    {
        if($currency)
        {
            if($currency == 'ETH') return new EtherscanConnector();
            elseif($currency == 'BTC' || $currency == 'LTH') return new ChainsoConnector();
            
            throw new RuntimeException('"' . $currency . '" cryptocurrency is not supported now! List of the currently available values: "BTC", "LTH", "ETH"');
        }
        
        throw new RuntimeException('Currency can not be null!');
    }
    
    public function __call($name, $arguments)
    {
        $instance = $this->getInstance($arguments[0]);
        
        if (! $instance) {
            throw new RuntimeException('Instance of the LaravelCryptoStats API connector was not created!');
        }
        
        return $instance->$name(...$arguments);
    }
    
    public function getCurrencies()
    {
        return config('laravel_crypto_stats.currencies');
    }
}
