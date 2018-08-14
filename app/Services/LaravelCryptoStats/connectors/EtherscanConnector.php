<?php

namespace App\Services\LaravelCryptoStats\Connectors;

class EtherscanConnector implements ConnectorInterface
{
    function validateWallet($currency, $wallet)
    {
        return 1;
    }
}
