<?php

namespace App\Services\LaravelCryptoStats\Connectors;

use App\Services\LaravelCryptoStats\Validators\EthereumValidator;
use GuzzleHttp\Client;
use RuntimeException;

class EtherscanConnector extends AbstractConnector
{
    private $supported_currencies = ['ETH'];
    private $api_url_prefix = 'https://api.etherscan.io/api?';
    private $api_description = 'https://etherscan.io/apis';
    
    public function validateAddress($currency, $address): bool
    {
        if($currency && $address)
        {
            if(in_array($currency, $this->supported_currencies)) return EthereumValidator::isAddress($address);
            
            throw new RuntimeException('"' . $currency . '"' . ' is not a supported cryptocurrency!');
        }
        
        throw new RuntimeException('Currency and wallet address can not be empty!');
    }
    
    public function getBalance($currency, $address)
    {
        $url = $this->api_url_prefix . 'module=account&action=balance&address=' . $address . '&tag=latest&apikey=' . $this->config['etherscan_api_key'];
        $params = [
            'currency' => $currency,
            'address' => $address
        ];
        
        $response = $this->sendApiRequest($url, $params);
        
        if($response)
        {
            $balance = $this->convertFromWei($response);
            return $this->roundBalance($balance);
        }
        
        throw new RuntimeException('Output data is not correct. Check the API description - ' . $this->api_description . '!');
    }
    
    private function getApiResponse(string $url, array $params)
    {
        $response = $this->sendApiRequest($url, $params);
        if ($response && $response['status'] && $response['result'])
        {
            return $response['result'];
        }
    }
    
    private function convertFromWei($balance): float
    {
        if(isset($balance)) return $balance/pow(10, 18);
            
        throw new RuntimeException('Balance can not be empty!');
    }
}
