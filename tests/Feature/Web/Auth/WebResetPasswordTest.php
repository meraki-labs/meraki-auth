<?php

namespace Meraki\Packages\Auth\Tests\Feature\Web\Auth;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Password;
use Meraki\Packages\Auth\Models\User;
use Meraki\Packages\Auth\Tests\TestCase;

class WebResetPasswordTest extends TestCase
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

    public function test_reset_password_page_returns_200(): void
    {
        $user  = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $this->get("/reset-password/{$token}?email={$user->email}")->assertOk();
    }

    public function test_valid_token_resets_password_and_redirects_to_login(): void
    {
        $user  = User::factory()->create(['email' => 'user@example.com']);
        $token = Password::broker()->createToken($user);

        $this->post('/reset-password', [
            'token'                 => $token,
            'email'                 => 'user@example.com',
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ])->assertRedirect(route('login'));
    }

    public function test_invalid_token_returns_error(): void
    {
        User::factory()->create(['email' => 'user@example.com']);

        $this->post('/reset-password', [
            'token'                 => 'invalid-token',
            'email'                 => 'user@example.com',
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ])->assertSessionHasErrors('email');
    }

    public function test_password_confirmation_mismatch_fails_validation(): void
    {
        $user  = User::factory()->create(['email' => 'user@example.com']);
        $token = Password::broker()->createToken($user);

        $this->post('/reset-password', [
            'token'                 => $token,
            'email'                 => 'user@example.com',
            'password'              => 'newpassword123',
            'password_confirmation' => 'different123',
        ])->assertSessionHasErrors('password');
    }
}
