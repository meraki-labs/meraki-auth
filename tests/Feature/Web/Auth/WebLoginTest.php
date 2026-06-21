<?php

namespace Meraki\Packages\Auth\Tests\Feature\Web\Auth;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Meraki\Packages\Auth\Models\User;
use Meraki\Packages\Auth\Tests\TestCase;

class WebLoginTest extends TestCase
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
        $router->get('/', fn() => 'ok')->name('home');
    }

    public function test_login_page_returns_200(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_login_with_valid_credentials_redirects(): void
    {
        User::factory()->create([
            'email'    => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->post('/login', [
            'email'    => 'user@example.com',
            'password' => 'password123',
        ])->assertRedirect();
    }

    public function test_login_with_wrong_password_returns_email_error(): void
    {
        User::factory()->create([
            'email'    => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->post('/login', [
            'email'    => 'user@example.com',
            'password' => 'wrong',
        ])->assertSessionHasErrors('email');
    }

    public function test_login_missing_email_returns_validation_error(): void
    {
        $this->post('/login', [
            'password' => 'password123',
        ])->assertSessionHasErrors('email');
    }

    public function test_authenticated_user_is_redirected_from_login(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/login')->assertRedirect();
    }
}
