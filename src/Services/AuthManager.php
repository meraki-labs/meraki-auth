<?php

namespace Meraki\Packages\Auth\Services;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Meraki\Packages\Auth\Contracts\AuthManager as AuthManagerContract;
use Meraki\Packages\Auth\Contracts\PlatformDriverContract;
use Meraki\Packages\Auth\Drivers\ApiDriver;
use Meraki\Packages\Auth\Drivers\SpaDriver;
use Meraki\Packages\Auth\Drivers\WebDriver;

class AuthManager implements AuthManagerContract
{
    protected array $drivers = [];

    protected array $driverMap = [
        'web' => WebDriver::class,
        'api' => ApiDriver::class,
        'spa' => SpaDriver::class,
    ];

    public function platform(string $name = null): PlatformDriverContract
    {
        $name ??= config('meraki-auth.default_platform', 'web');

        if (!isset($this->drivers[$name])) {
            $this->drivers[$name] = $this->resolveDriver($name);
        }

        return $this->drivers[$name];
    }

    protected function resolveDriver(string $name): PlatformDriverContract
    {
        $driverConfig = config("meraki-auth.platforms.{$name}.driver", $name);
        $class = $this->driverMap[$driverConfig] ?? null;

        if (!$class) {
            throw new \InvalidArgumentException("Unsupported auth platform driver: [{$name}]");
        }

        return app($class);
    }

    // Backward-compatible proxy methods (web platform)

    public function register(array $credentials): object
    {
        $result = $this->platform('web')->register($credentials);
        return $result->data()['user'];
    }

    public function login(array $credentials, bool $remember = false): bool
    {
        return $this->platform('web')->login($credentials, compact('remember'))->success();
    }

    public function logout(): void
    {
        $this->platform('web')->logout();
    }

    public function user(): ?object
    {
        return $this->platform('web')->user();
    }

    public function check(): bool
    {
        return $this->platform('web')->check();
    }

    public function changePassword(object $user, string $currentPassword, string $newPassword): bool
    {
        if (!Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $user->forceFill(['password' => Hash::make($newPassword)])->save();

        return true;
    }

    public function requestPasswordReset(string $email): string
    {
        return Password::sendResetLink(['email' => $email]);
    }

    public function resetPassword(string $email, string $token, string $password): string
    {
        return Password::reset(
            ['email' => $email, 'token' => $token, 'password' => $password],
            function ($user) use ($password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );
    }
}
