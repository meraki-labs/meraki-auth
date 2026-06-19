<?php

namespace Meraki\Packages\Auth\Tests\Feature\Web\Auth;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Meraki\Packages\Auth\Models\User;
use Meraki\Packages\Auth\Tests\TestCase;

class WebEmailVerificationTest extends TestCase
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

    public function test_unverified_user_sees_verification_notice(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->actingAs($user)->get('/verify-email')->assertOk();
    }

    public function test_resend_verification_notification_redirects(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email_verified_at' => null]);

        $this->actingAs($user)
            ->post('/email/verification-notification')
            ->assertRedirect();
    }

    public function test_valid_verification_link_marks_user_verified(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)->get($verifyUrl)->assertRedirect();

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_invalid_hash_returns_403(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $badUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong@email.com')]
        );

        $this->actingAs($user)->get($badUrl)->assertForbidden();
    }
}
