<?php

namespace Meraki\Packages\Auth\Tests\Unit;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Meraki\Packages\Auth\Services\AuthManager;
use Meraki\Packages\Auth\Tests\TestCase;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class AuthManagerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_changePassword_returns_false_when_current_password_wrong(): void
    {
        $user = Mockery::mock();
        $user->password = Hash::make('correct_password');

        $manager = new AuthManager();
        $result = $manager->changePassword($user, 'wrong_password', 'new_password');

        $this->assertFalse($result);
    }

    public function test_changePassword_hashes_and_saves_new_password(): void
    {
        $user = Mockery::mock();
        $user->password = Hash::make('old_password');
        $user->shouldReceive('forceFill')
            ->once()
            ->withArgs(fn ($data) => isset($data['password']) && Hash::check('new_password', $data['password']))
            ->andReturnSelf();
        $user->shouldReceive('save')->once()->andReturn(true);

        $manager = new AuthManager();
        $result = $manager->changePassword($user, 'old_password', 'new_password');

        $this->assertTrue($result);
    }

    public function test_requestPasswordReset_delegates_to_password_broker(): void
    {
        Password::shouldReceive('sendResetLink')
            ->once()
            ->with(['email' => 'user@example.com'])
            ->andReturn(Password::RESET_LINK_SENT);

        $manager = new AuthManager();
        $result = $manager->requestPasswordReset('user@example.com');

        $this->assertSame(Password::RESET_LINK_SENT, $result);
    }

    public function test_resetPassword_hashes_password_and_fires_event(): void
    {
        Event::fake();

        Password::shouldReceive('reset')
            ->once()
            ->andReturnUsing(function ($credentials, $callback) {
                $mockUser = Mockery::mock();
                $mockUser->shouldReceive('forceFill')->once()->andReturnSelf();
                $mockUser->shouldReceive('save')->once();
                $callback($mockUser);
                return Password::PASSWORD_RESET;
            });

        $manager = new AuthManager();
        $result = $manager->resetPassword('user@example.com', 'token', 'newpassword');

        $this->assertSame(Password::PASSWORD_RESET, $result);
        Event::assertDispatched(PasswordReset::class);
    }
}
