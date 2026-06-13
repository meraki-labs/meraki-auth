<?php

namespace Meraki\Packages\Auth\Drivers;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Meraki\Packages\Auth\Contracts\AuthResultContract;
use Meraki\Packages\Auth\Contracts\PlatformDriverContract;
use Meraki\Packages\Auth\Http\Responses\ApiAuthResult;

class ApiDriver implements PlatformDriverContract
{
    public function login(array $credentials, array $options = []): AuthResultContract
    {
        if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            return new ApiAuthResult(false, [
                'message' => 'The provided credentials are incorrect.',
            ], 422);
        }

        $user  = Auth::user();
        $token = $this->createToken($user);

        Auth::logout();

        return new ApiAuthResult(true, [
            'token'      => $token['plaintext'],
            'token_type' => 'Bearer',
            'expires_at' => $token['expires_at'],
            'user'       => $user,
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

        $token = $this->createToken($user);

        return new ApiAuthResult(true, [
            'token'      => $token['plaintext'],
            'token_type' => 'Bearer',
            'expires_at' => $token['expires_at'],
            'user'       => $user,
        ], 201);
    }

    public function logout(): void
    {
        $user = Auth::user();

        if ($user && method_exists($user->currentAccessToken(), 'delete')) {
            $user->currentAccessToken()->delete();
        }
    }

    public function user(): ?Authenticatable
    {
        return Auth::guard('sanctum')->user();
    }

    public function check(): bool
    {
        return Auth::guard('sanctum')->check();
    }

    protected function createToken(Authenticatable $user): array
    {
        $tokenName  = config('meraki-auth.platforms.api.token.name', 'meraki-auth-token');
        $abilities  = config('meraki-auth.platforms.api.token.abilities', ['*']);
        $expiryMins = config('meraki-auth.platforms.api.token.expiry');

        $expiresAt = $expiryMins ? Carbon::now()->addMinutes((int) $expiryMins) : null;

        $newToken = $user->createToken($tokenName, $abilities, $expiresAt);

        return [
            'plaintext'  => $newToken->plainTextToken,
            'expires_at' => $expiresAt?->toIso8601String(),
        ];
    }
}
