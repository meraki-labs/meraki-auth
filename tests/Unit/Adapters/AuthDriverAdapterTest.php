<?php

namespace Meraki\Packages\Auth\Tests\Unit\Adapters;

use Illuminate\Support\Facades\Auth;
use Meraki\Packages\Auth\Adapters\AuthDriverAdapter;
use Orchestra\Testbench\TestCase;

class AuthDriverAdapterTest extends TestCase
{
    public function test_check_delegates_to_auth_facade(): void
    {
        Auth::shouldReceive('check')->once()->andReturn(true);

        $adapter = new AuthDriverAdapter();
        $this->assertTrue($adapter->check());
    }

    public function test_id_delegates_to_auth_facade(): void
    {
        Auth::shouldReceive('id')->once()->andReturn(42);

        $adapter = new AuthDriverAdapter();
        $this->assertSame(42, $adapter->id());
    }

    public function test_user_delegates_to_auth_facade(): void
    {
        $user = new \stdClass();
        Auth::shouldReceive('user')->once()->andReturn($user);

        $adapter = new AuthDriverAdapter();
        $this->assertSame($user, $adapter->user());
    }

    public function test_user_returns_null_when_no_user(): void
    {
        Auth::shouldReceive('user')->once()->andReturn(null);

        $adapter = new AuthDriverAdapter();
        $this->assertNull($adapter->user());
    }
}
