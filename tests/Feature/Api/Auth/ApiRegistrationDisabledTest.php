<?php

namespace Meraki\Packages\Auth\Tests\Feature\Api\Auth;

use Meraki\Packages\Auth\Tests\TestCase;

class ApiRegistrationDisabledTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);
        $app['config']->set('meraki-auth.platforms.api.enabled', true);
        $app['config']->set('meraki-auth.features.registration', false);
    }

    public function test_register_endpoint_not_available_when_feature_disabled(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(404);
    }
}
