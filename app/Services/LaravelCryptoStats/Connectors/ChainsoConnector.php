<?php

namespace App\Services\LaravelCryptoStats\Connectors;

class ChainsoConnector implements ConnectorInterface
{
    function validateAddress($currency, $address)
    {
        return 2;
    }
}
