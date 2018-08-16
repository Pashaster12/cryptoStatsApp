<?php

namespace App\Services\LaravelCryptoStats\Connectors;

interface ConnectorInterface
{
    public function validateAddress($currency, $address);
    public function getBalance($currency, $address);
    public function roundBalance($balance);
}
