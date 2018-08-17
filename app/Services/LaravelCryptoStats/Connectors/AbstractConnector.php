<?php

namespace App\Services\LaravelCryptoStats\Connectors;

use GuzzleHttp\Client;

abstract class AbstractConnector
{
    /**
     * Service config instance
     * 
     * @var array 
     */
    protected $config;
    protected $supported_currencies;
    protected $api_url_prefix;
    protected $api_description;
    
    /**
     * LaravelCryptoStats buider
     */
    public function __construct()
    {
        $this->config = config('laravel_crypto_stats');
    }
    
    public abstract function validateAddress(string $currency, string $address): bool;
    public abstract function getBalance(string $currency, string $address): float;
    
    protected function sendApiRequest(string $url, string $currency, string $address)
    {
        if($currency && $address)
        {
            if(in_array($currency, $this->supported_currencies))
            {
                $request_url = $this->api_url_prefix . $url . '/' . $currency . '/' . $address;

                $client = new Client();
                $response = $client->request('GET', $request_url);

                if($response)
                {
                    $response_body = json_decode($response->getBody(), true);
                    return $response_body;
                }
            }

            throw new RuntimeException('"' . $currency . '"' . ' is not a supported cryptocurrency!');
        }
        
        throw new RuntimeException('Currency and wallet address can not be empty!');
    }
    
    protected function roundBalance($balance): float
    {
        if(isset($balance)) return round($balance, 8);
            
        throw new RuntimeException('Balance can not be empty!');
    }
}