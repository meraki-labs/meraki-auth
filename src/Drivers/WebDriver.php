<?php

namespace Meraki\Packages\Auth\Drivers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Meraki\Packages\Auth\Contracts\AuthResultContract;
use Meraki\Packages\Auth\Contracts\PlatformDriverContract;
use Meraki\Packages\Auth\Http\Responses\WebAuthResult;

class WebDriver implements PlatformDriverContract
{
    public function login(array $credentials, array $options = []): AuthResultContract
    {
        $remember = $options['remember'] ?? false;

        $success = Auth::attempt([
            'email'    => $credentials['email'],
            'password' => $credentials['password'],
        ], $remember);

        $redirectTo = $success
            ? config('meraki-auth.platforms.web.redirects.login', '/dashboard')
            : route('login');

        return new WebAuthResult($success, $redirectTo);
    }

    public function register(array $data): AuthResultContract
    {
        $model = config('meraki-auth.user_model');

        $user = $model::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ]);

        Auth::login($user);

        $redirectTo = config('meraki-auth.platforms.web.redirects.register', '/dashboard');

        return new WebAuthResult(true, $redirectTo, ['user' => $user]);
    }

    public function logout(): void
    {
        Auth::logout();
    }

    public function user(): ?Authenticatable
    {
        return Auth::user();
    }

    public function check(): bool
    {
        return Auth::check();
    }
}
