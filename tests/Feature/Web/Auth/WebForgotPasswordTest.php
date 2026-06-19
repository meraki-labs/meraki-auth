<?php

namespace Meraki\Packages\Auth\Tests\Feature\Web\Auth;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Notification;
use Meraki\Packages\Auth\Models\User;
use Meraki\Packages\Auth\Tests\TestCase;

class WebForgotPasswordTest extends TestCase
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

    public function test_forgot_password_page_returns_200(): void
    {
        $this->get('/forgot-password')->assertOk();
    }

    public function test_send_reset_link_for_existing_email_redirects(): void
    {
        Notification::fake();

        User::factory()->create(['email' => 'user@example.com']);

        $this->post('/forgot-password', [
            'email' => 'user@example.com',
        ])->assertRedirect();
    }

    public function test_send_reset_link_for_nonexistent_email_still_redirects(): void
    {
        $this->post('/forgot-password', [
            'email' => 'nobody@example.com',
        ])->assertRedirect();
    }

    public function test_forgot_password_missing_email_fails_validation(): void
    {
        $this->post('/forgot-password', [])->assertSessionHasErrors('email');
    }
}
