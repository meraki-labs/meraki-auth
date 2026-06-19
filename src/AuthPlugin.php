<?php

namespace Meraki\Packages\Auth;

use Illuminate\Contracts\Foundation\Application;
use Meraki\Core\CoreManager;
use Meraki\Core\Modules\PackageRegistry;
use Meraki\Packages\Auth\Adapters\AuthDriverAdapter;

class AuthPlugin
{
    public function id(): string
    {
        return 'meraki-auth';
    }

    public function name(): string
    {
        return 'Meraki Authentication';
    }

    public function version(): string
    {
        return '1.0.0';
    }

    public function description(): string
    {
        return 'Authentication plugin for the Meraki ecosystem';
    }

    public function register(Application $app): void
    {
        if ($app->bound(CoreManager::class)) {
            $app->make(CoreManager::class)->extend(
                'auth',
                $this->id(),
                fn (Application $app) => $app->make(AuthDriverAdapter::class)
            );
        }

        if ($app->bound(PackageRegistry::class)) {
            $app->make(PackageRegistry::class)->register($this->id(), [
                'provider' => AuthServiceProvider::class,
                'config'   => 'meraki-auth',
                'version'  => $this->version(),
            ]);
        }
    }

    public function boot(Application $app): void {}
}
