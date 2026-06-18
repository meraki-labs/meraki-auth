<?php

namespace Meraki\Packages\Auth\Tests\Feature\Api\Auth;

use Meraki\Packages\Auth\Tests\TestCase;

class ApiRegisterTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);
        $app['config']->set('meraki-auth.platforms.api.enabled', true);
    }

    public function test_register_creates_user_and_returns_token(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['token', 'token_type', 'user']);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

}
