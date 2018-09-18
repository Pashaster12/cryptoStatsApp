<?php

namespace Tests\Unit;

use Tests\TestCase;

use CryptoStat;

class CustomHelpersTest extends TestCase
{
    /**
     * Test custom helper functions from the App\Http\helpers.php
     */
    
    /**
     * Test the format_number() helper
     */
    public function testFormatNumber()
    {
        $target_value = 1.67583978;
        $initial_value = 1.675839781223232323223232323;
        
        $this->assertEquals($target_value, format_number($initial_value));
    }
    
    /**
     * Test the random_float() helper
     */
    public function testRandomFloat()
    {
        $initial_value = random_float();
        
        $this->assertInternalType('float', $initial_value);
    }
    
    /**
     * Test the random_ethereum_address() helper
     */
    public function testRandomEthereumAddress()
    {
        $initial_value = random_ethereum_address();
        $this->assertInternalType('string', $initial_value);
        
        CryptoStat::setCurrency('ETH');
        $is_valid = CryptoStat::validateAddress($initial_value);        
        
        $this->assertTrue($is_valid);
    }
}
