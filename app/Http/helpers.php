<?php

function random_ethereum_address()
{
    $pool = '0123456789abcdefABCDEF';
    $random_string = substr(str_shuffle(str_repeat($pool, 40)), 0, 40);
    
    $address = '0x' . $random_string;
    
    return $address;
}

function random_float()
{
    return round(mt_rand() / mt_getrandmax(), 8);
}

/**
 * Round the balance value with the rounding_degree config value
 * 
 * @param float $number
 * @return float
 */
function format_number(float $number): float
{
    return number_format($number, config('constants.rounding_degree'), '.', '');
}
