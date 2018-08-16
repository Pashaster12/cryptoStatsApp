<?php

namespace App\Services\LaravelCryptoStats\Connectors;

use GuzzleHttp\Client;
use RuntimeException;

class ChainsoConnector implements ConnectorInterface
{
    public function validateAddress($currency, $address)
    {
        if($currency && $address)
        {
            if(in_array($currency, ['BTC', 'LTC']))
            {
                $url = 'https://chain.so/api/v2/is_address_valid/' . $currency . '/' . $address;

                $client = new Client();
                $response = $client->request('GET', $url);

                if($response)
                {
                    $response_body = json_decode($response->getBody(), true);
                    if($response_body && $response_body['status'])
                    {
                        if($response_body['data'] && $response_body['data']['is_valid'] && $response_body['status'] == 'success')
                        {
                            return $response_body['data']['is_valid'];
                        }
                    }
                }
            }
            
            throw new RuntimeException('"' . $currency . '"' . ' is not a supported cryptocurrency!');
        }
        
        throw new RuntimeException('Currency and wallet address can not be empty!');
    }
    
    public function getBalance($currency, $address): float
    {
        if($currency && $address)
        {
            if(in_array($currency, ['BTC', 'LTC']))
            {
                $url = 'https://chain.so//api/v2/get_address_balance/' . $currency . '/' . $address;

                $client = new Client();
                $response = $client->request('GET', $url);

                if($response)
                {
                    $response_body = json_decode($response->getBody(), true);
                    if($response_body && $response_body['status'])
                    {
                        if($response_body['data'] && isset($response_body['data']['confirmed_balance']) && $response_body['status'] == 'success')
                        {
                            return $this->roundBalance($response_body['data']['confirmed_balance']);
                        }
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
}
