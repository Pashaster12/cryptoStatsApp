<?php

namespace App\Services\LaravelCryptoStats\Connectors;

interface ConnectorInterface
{
    public function validateAddress($currency, $address);
}
