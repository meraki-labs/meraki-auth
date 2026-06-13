<?php

namespace Meraki\Packages\Auth\Tests\Feature\Api\Auth;

use Meraki\Packages\Auth\Tests\TestCase;

class ApiPlatformDisabledTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);
        $app['config']->set('meraki-auth.platforms.api.enabled', false);
    }

    public function test_api_routes_not_available_when_platform_disabled(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email'    => 'user@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(404);
    }
}
