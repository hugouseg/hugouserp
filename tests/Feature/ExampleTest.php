<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test that unauthenticated users are redirected to login.
     * 
     * NOTE: This test does not use RefreshDatabase because it only tests
     * the authentication redirect behavior and does not persist any data.
     */
    public function test_unauthenticated_users_are_redirected_to_login(): void
    {
        $response = $this->get('/');

        // Assert guest status before redirect
        $this->assertGuest();

        // Expect redirect to login for unauthenticated users
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

        // Follow the redirect and assert the login page is rendered
        $loginResponse = $this->followingRedirects()->get('/');
        
        // Assert the login page content is rendered
        $loginResponse->assertStatus(200);
        $loginResponse->assertSee('login', false);
        
        // Confirm the guard remains unauthenticated after following redirect
        $this->assertGuest();
    }
}
