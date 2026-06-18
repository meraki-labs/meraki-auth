<?php

namespace Meraki\Packages\Auth\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

interface PlatformDriverContract
{
    public function login(array $credentials, array $options = []): AuthResultContract;

    public function register(array $data): AuthResultContract;

    public function logout(): void;

    public function user(): ?Authenticatable;

    public function check(): bool;
}
