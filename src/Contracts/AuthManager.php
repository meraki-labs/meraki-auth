<?php

namespace Meraki\Packages\Auth\Contracts;

interface AuthManager
{
    public function register(array $credentials): object;

    public function login(array $credentials, bool $remember = false): bool;

    public function logout(): void;

    public function user(): ?object;

    public function check(): bool;

    public function changePassword(object $user, string $currentPassword, string $newPassword): bool;

    public function requestPasswordReset(string $email): string;

    public function resetPassword(string $email, string $token, string $password): string;
}
