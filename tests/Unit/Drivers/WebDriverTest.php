<?php

namespace Meraki\Packages\Auth\Tests\Unit\Drivers;

use Illuminate\Support\Facades\Auth;
use Meraki\Packages\Auth\Drivers\WebDriver;
use Meraki\Packages\Auth\Http\Responses\WebAuthResult;
use Meraki\Packages\Auth\Tests\TestCase;

class WebDriverTest extends TestCase
{
    private WebDriver $driver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->driver = new WebDriver();
    }

    public function test_login_returns_success_result_with_correct_credentials(): void
    {
        $user = \Meraki\Packages\Auth\Models\User::factory()->create([
            'email'    => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $result = $this->driver->login([
            'email'    => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertInstanceOf(WebAuthResult::class, $result);
        $this->assertTrue($result->success());
    }

    public function test_login_returns_failure_result_with_wrong_password(): void
    {
        \Meraki\Packages\Auth\Models\User::factory()->create([
            'email'    => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $result = $this->driver->login([
            'email'    => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertInstanceOf(WebAuthResult::class, $result);
        $this->assertFalse($result->success());
    }

    public function test_register_creates_user_and_logs_in(): void
    {
        $result = $this->driver->register([
            'name'     => 'John Doe',
            'email'    => 'john@example.com',
            'password' => 'password123',
        ]);

        $this->assertTrue($result->success());
        $this->assertTrue(Auth::check());
        $this->assertEquals('john@example.com', Auth::user()->email);
    }

    public function test_check_returns_false_when_not_authenticated(): void
    {
        $this->assertFalse($this->driver->check());
    }

    public function test_user_returns_null_when_not_authenticated(): void
    {
        $this->assertNull($this->driver->user());
    }

    public function test_logout_clears_session(): void
    {
        $user = \Meraki\Packages\Auth\Models\User::factory()->create();
        Auth::login($user);

        $this->assertTrue(Auth::check());
        $this->driver->logout();
        $this->assertFalse(Auth::check());
    }
}
