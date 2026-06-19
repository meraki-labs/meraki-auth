<?php

namespace Meraki\Packages\Auth\Tests\Feature\Web\Auth;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Notification;
use Meraki\Packages\Auth\Models\User;
use Meraki\Packages\Auth\Tests\TestCase;

class WebRegisterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);
        $app['config']->set('app.key', 'base64:' . base64_encode(str_repeat('a', 32)));
    }

    protected function defineRoutes($router): void
    {
        $router->get('/dashboard', fn() => 'ok')->name('dashboard');
    }

    public function test_register_page_returns_200(): void
    {
        $this->get('/register')->assertOk();
    }

    public function test_valid_registration_creates_user_and_redirects(): void
    {
        Notification::fake();

        $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_duplicate_email_fails_with_validation_error(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'existing@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');
    }

    public function test_password_confirmation_mismatch_fails_validation(): void
    {
        $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'different123',
        ])->assertSessionHasErrors('password');
    }
}

class WebRegistrationDisabledTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);
        $app['config']->set('meraki-auth.features.registration', false);
    }

    public function test_register_route_returns_404_when_registration_disabled(): void
    {
        $this->get('/register')->assertNotFound();
    }
}
