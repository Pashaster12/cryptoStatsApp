<?php

namespace App\Services\LaravelCryptoStats;

use RuntimeException;
use App\Services\LaravelCryptoStats\Connectors\{EtherscanConnector, ChainsoConnector};

class LaravelCryptoStatsFactory
{
    public function getInstance($currency)
    {
        if($currency)
        {
            if($currency == 'ETH') return new EtherscanConnector('ETH');
            elseif($currency == 'BTC' || $currency == 'LTC') return new ChainsoConnector();
            
            throw new RuntimeException('"' . $currency . '" cryptocurrency is not supported now! List of the currently available values: "BTC", "LTC", "ETH"');
        }
        
        throw new RuntimeException('Currency can not be null!');
    }
}
