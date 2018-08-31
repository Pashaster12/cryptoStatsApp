<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;

class PageAccessTest extends TestCase
{
    public function testLoginPageAccess()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
    
    public function testRegisterPageAccess()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }
    
    public function testPasswordResetPageAccess()
    {
        $response = $this->get('/password/reset');
        $response->assertStatus(200);
    }
    
    public function testMainPageAccess()
    {
        $this->loginWithFakeUser();
        
        $response = $this->get('/');
        $response->assertStatus(200);
    }
    
    private function loginWithFakeUser()
    {
        $user = factory(User::class)->make();

        $this->be($user);
    }
}
