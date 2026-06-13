<?php

namespace Meraki\Packages\Auth\Drivers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Meraki\Packages\Auth\Contracts\AuthResultContract;
use Meraki\Packages\Auth\Contracts\PlatformDriverContract;
use Meraki\Packages\Auth\Http\Responses\ApiAuthResult;

class SpaDriver implements PlatformDriverContract
{
    public function login(array $credentials, array $options = []): AuthResultContract
    {
        $success = Auth::attempt([
            'email'    => $credentials['email'],
            'password' => $credentials['password'],
        ]);

        if (!$success) {
            return new ApiAuthResult(false, [
                'message' => 'The provided credentials are incorrect.',
            ], 422);
        }

        return new ApiAuthResult(true, [
            'user' => Auth::user(),
        ]);
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

        return new ApiAuthResult(true, ['user' => $user], 201);
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
