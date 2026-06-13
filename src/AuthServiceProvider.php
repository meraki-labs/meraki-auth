<?php

namespace Meraki\Packages\Auth;

use Illuminate\Support\ServiceProvider;
use Meraki\Core\Modules\PermissionRegistry;
use Meraki\Packages\Auth\Contracts\AuthManager;
use Meraki\Packages\Auth\Services\AuthManager as AuthManagerImpl;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/meraki-auth.php', 'meraki-auth');

        $this->app->singleton(AuthManager::class, AuthManagerImpl::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'meraki-auth');

        $this->publishes([
            __DIR__ . '/../config/meraki-auth.php' => config_path('meraki-auth.php'),
        ], ['meraki-config', 'meraki-auth-config']);

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/meraki-auth'),
        ], ['meraki-views', 'meraki-auth-views']);

        if (!class_exists('CreateAuthTables')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/create_auth_tables.php'
                    => database_path('migrations/' . date('Y_m_d_His') . '_create_auth_tables.php'),
            ], ['meraki-migrations', 'meraki-auth-migrations']);
        }

        $this->registerPermissions();
    }

    protected function registerPermissions(): void
    {
        if (!$this->app->bound(PermissionRegistry::class)) {
            return;
        }

        $permissions = config('meraki-auth.permissions', []);

        if (!empty($permissions)) {
            $this->app->make(PermissionRegistry::class)->register($permissions);
        }
    }
}
