<?php

namespace App\Services\LaravelCryptoStats;

use App\Services\LaravelCryptoStats\Connectors\{EtherscanConnector, ChainsoConnector};

class LaravelCryptoStats
{
    private $instance;
    
    private function getInstance($currency) {
        if($currency)
        {
            if($currency == 'ETH') $this->instance = new EtherscanConnector();
            elseif($currency == 'BTC' || $currency == 'LTH') $this->instance = new ChainsoConnector();
        }
        
        return $this->instance;
    }
    
    public function getCurrencies()
    {
        return config('laravel_crypto_stats.currencies');
    }
    
    public function __call($name, $arguments)
    {
        $instance = $this->getInstance($arguments[0]);
        return $instance->$name(...$arguments);
    }
    
    /*public function validateWallet($currency, $wallet)
    {
        $instance = $this->getInstance($currency);
        $instance->validateWallet($currency, $wallet);
    }*/
}
