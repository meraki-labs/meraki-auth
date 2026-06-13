<?php

namespace Meraki\Packages\Auth\Services;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Meraki\Packages\Auth\Contracts\AuthManager as AuthManagerContract;

class AuthManager implements AuthManagerContract
{
    public function register(array $credentials): object
    {
        $model = config('meraki-auth.user_model');

        return $model::create([
            'name'     => $credentials['name'],
            'email'    => $credentials['email'],
            'password' => $credentials['password'],
        ]);
    }

    public function login(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt([
            'email'    => $credentials['email'],
            'password' => $credentials['password'],
        ], $remember);
    }

    public function logout(): void
    {
        Auth::logout();
    }

    public function user(): ?object
    {
        return Auth::user();
    }

    public function check(): bool
    {
        return Auth::check();
    }

    public function changePassword(object $user, string $currentPassword, string $newPassword): bool
    {
        if (!Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $user->update(['password' => $newPassword]);

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
