<?php

namespace Meraki\Packages\Auth\Tests\Unit\Drivers;

use Meraki\Packages\Auth\Drivers\ApiDriver;
use Meraki\Packages\Auth\Http\Responses\ApiAuthResult;
use Meraki\Packages\Auth\Tests\TestCase;

class ApiDriverTest extends TestCase
{
    private ApiDriver $driver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->driver = new ApiDriver();
        $this->app['config']->set('meraki-auth.platforms.api.enabled', true);
    }

    public function test_login_returns_token_with_correct_credentials(): void
    {
        \Meraki\Packages\Auth\Models\User::factory()->create([
            'email'    => 'api@example.com',
            'password' => bcrypt('password123'),
        ]);

        $result = $this->driver->login([
            'email'    => 'api@example.com',
            'password' => 'password123',
        ]);

        $this->assertInstanceOf(ApiAuthResult::class, $result);
        $this->assertTrue($result->success());
        $this->assertArrayHasKey('token', $result->data());
        $this->assertArrayHasKey('token_type', $result->data());
        $this->assertEquals('Bearer', $result->data()['token_type']);
    }

    public function test_login_returns_failure_with_wrong_credentials(): void
    {
        \Meraki\Packages\Auth\Models\User::factory()->create([
            'email'    => 'api@example.com',
            'password' => bcrypt('password123'),
        ]);

        $result = $this->driver->login([
            'email'    => 'api@example.com',
            'password' => 'wrong',
        ]);

        $this->assertFalse($result->success());
        $this->assertEquals(422, $result->toResponse()->getStatusCode());
    }

    public function test_register_creates_token(): void
    {
        $result = $this->driver->register([
            'name'     => 'API User',
            'email'    => 'apiuser@example.com',
            'password' => 'password123',
        ]);

        $this->assertTrue($result->success());
        $this->assertArrayHasKey('token', $result->data());
        $this->assertEquals(201, $result->toResponse()->getStatusCode());
    }

    public function test_login_token_stored_in_database(): void
    {
        \Meraki\Packages\Auth\Models\User::factory()->create([
            'email'    => 'dbtest@example.com',
            'password' => bcrypt('password123'),
        ]);

        $result = $this->driver->login([
            'email'    => 'dbtest@example.com',
            'password' => 'password123',
        ]);

        $this->assertTrue($result->success());
        $this->assertDatabaseCount('personal_access_tokens', 1);
    }
}
