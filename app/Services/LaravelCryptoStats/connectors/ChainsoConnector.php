<?php

namespace App\Services\LaravelCryptoStats\Connectors;

class ChainsoConnector implements ConnectorInterface
{
    function validateWallet($currency, $wallet)
    {
        return 2;
    }
}
