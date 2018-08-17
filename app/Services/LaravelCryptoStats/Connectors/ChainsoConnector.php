<?php

namespace App\Services\LaravelCryptoStats\Connectors;

use RuntimeException;

class ChainsoConnector extends AbstractConnector
{
    private $supported_currencies = ['BTC', 'LTC'];
    private $api_url_prefix = 'https://chain.so//api/v2/';
    private $api_description = 'https://chain.so/api';
    
    public function validateAddress(string $currency, string $address): bool
    {
        $url = $this->api_url_prefix . 'is_address_valid/' . $currency . '/' . $address;
        $params = [
            'currency' => $currency,
            'address' => $address
        ];
        
        $response = $this->getApiResponse($url, $params);
        
        if($response && isset($response['is_valid'])) return $response['is_valid'];
        
        throw new RuntimeException('Output data is not correct. Check the API description - ' . $this->api_description . '!');
    }
    
    public function getBalance(string $currency, string $address): float
    {
        $url = $this->api_url_prefix . 'get_address_balance/' . $currency . '/' . $address;
        $params = [
            'currency' => $currency,
            'address' => $address
        ];
        
        $response = $this->getApiResponse($url, $params);
        
        if($response && isset($response['confirmed_balance'])) return $this->roundBalance($response['confirmed_balance']);
        
        throw new RuntimeException('Output data is not correct. Check the API description - ' . $this->api_description . '!');
    }
    
    private function getApiResponse(string $url, array $params)
    {
        $response = $this->sendApiRequest($url, $params);
        if($response && $response['data'] && $response['status'] && $response['status'] == 'success')
        {
            return $response['data'];
        }
    }
}
