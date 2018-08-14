<?php

namespace App\Services\LaravelCryptoStats\Connectors;

interface ConnectorInterface
{
    public function validateWallet($currency, $wallet);
}
