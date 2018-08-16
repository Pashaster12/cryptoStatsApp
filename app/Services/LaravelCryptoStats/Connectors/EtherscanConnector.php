<?php

namespace App\Services\LaravelCryptoStats\Connectors;

use App\Services\LaravelCryptoStats\Validators\EthereumValidator;
use GuzzleHttp\Client;
use RuntimeException;

class EtherscanConnector implements ConnectorInterface
{
    private $api_key;
    
    public function __construct()
    {
        $this->api_key = config('laravel_crypto_stats.etherscan_api_key');
    }
    
    public function validateAddress($currency, $address): bool
    {
        if($currency && $address)
        {
            if($currency == 'ETH') return EthereumValidator::isAddress($address);
            
            throw new RuntimeException('"' . $currency . '"' . ' is not a supported cryptocurrency!');
        }
        
        throw new RuntimeException('Currency and wallet address can not be empty!');
    }
    
    public function getBalance($currency, $address)
    {
        if($currency && $address)
        {
            if($currency == 'ETH')
            {
                $url = 'https://api.etherscan.io/api?module=account&action=balance&address=' . $address . '&tag=latest&apikey=' . $this->api_key;

                $client = new Client();
                $response = $client->request('GET', $url);

                if($response)
                {
                    $response_body = json_decode($response->getBody(), true);
                    if($response_body && $response_body['status'])
                    {
                        $balance = $this->convertFromWei($response_body['result']);
                        return $this->roundBalance($balance);
                    }
                }
            }
            
            throw new RuntimeException('"' . $currency . '"' . ' is not a supported cryptocurrency!');
        }
        
        throw new RuntimeException('Currency and wallet address can not be empty!');        
    }
    
    public function roundBalance($balance): float
    {
        if(isset($balance)) return round($balance, 8);
            
        throw new RuntimeException('Balance can not be empty!');
    }
    
    private function convertFromWei($balance): float
    {
        if(isset($balance)) return $balance/pow(10, 18);
            
        throw new RuntimeException('Balance can not be empty!');
    }
}
