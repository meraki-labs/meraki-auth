<?php

namespace Meraki\Packages\Auth\Services;

use Meraki\Core\Modules\PermissionRegistry;
use Meraki\Packages\Auth\Contracts\PluginDriver;

class AuthDriverManager implements PluginDriver
{
    private bool $active;

    public function __construct(
        private readonly PermissionRegistry $permissions,
    ) {
        $this->active = (bool) config('meraki-auth.driver.active', true);
    }

    public function activate(): void
    {
        $this->active = true;
        $this->registerPermissions();
    }

    public function deactivate(): void
    {
        $this->active = false;
        // PermissionRegistry::unregister() not yet available in meraki-core.
        // Known limitation: permissions remain registered after deactivate (MVP).
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function driverName(): string
    {
        return 'meraki-auth';
    }

    private function registerPermissions(): void
    {
        $perms = config('meraki-auth.permissions', []);
        if (!empty($perms)) {
            $this->permissions->register($perms);
        }
    }
}
