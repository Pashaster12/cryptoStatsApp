<?php

namespace App\Services\LaravelCryptoStats\Connectors;

use App\Services\LaravelCryptoStats\Validators\EthereumValidator;

class EtherscanConnector implements ConnectorInterface
{
    function validateAddress($currency, $address)
    {
        $is_valid = false;
        if($currency && $currency == 'ETH') $is_valid = EthereumValidator::isAddress($address);
        
        return $is_valid;
    }
}
