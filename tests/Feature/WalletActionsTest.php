<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Wallet;

class WalletActionsTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testAddCorrectWallet()
    {
        $this->withoutMiddleware();
        
        $wallet = factory(Wallet::class)->make();
        
        $response = $this->post('/addWallet', $wallet->toArray());
        
        $response->assertSessionHasNoErrors()->assertStatus(302);
    }
    
    public function testAddWrongWallet()
    {
        $this->withoutMiddleware();
        
        $wallet = new Wallet([
            'user_id' => 1,
            'currency' => 'ETH',
            'address' => '0x512Fd05630e3067CeED74ba80A358B0869FD926b234'
        ]);
        
        $response = $this->post('/addWallet', $wallet->toArray());
        
        $response->assertSessionHasErrors('address')->assertStatus(302);
    }
    
    public function testUpdateBalance()
    {
        $response = $this->artisan('updatebalance');
        
        $response->expectsOutput('ok')
            ->assertExitCode(0);
    }
}
