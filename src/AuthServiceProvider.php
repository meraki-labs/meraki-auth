<?php

namespace Meraki\Packages\Auth;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Meraki\Core\Modules\PermissionRegistry;
use Meraki\Packages\Auth\Contracts\AuthManager;
use Meraki\Packages\Auth\Http\Middleware\EnsureAuthDriverActive;
use Meraki\Packages\Auth\Services\AuthDriverManager;
use Meraki\Packages\Auth\Services\AuthManager as AuthManagerImpl;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/meraki-auth.php', 'meraki-auth');

        $this->app->singleton(AuthManager::class, AuthManagerImpl::class);

        $this->app->singleton(AuthDriverManager::class, function ($app) {
            return new AuthDriverManager(
                $app->make(PermissionRegistry::class),
            );
        });
    }

    public function boot(): void
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('meraki.auth.active', EnsureAuthDriverActive::class);

        $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'meraki-auth');

        if (config('meraki-auth.platforms.api.enabled', false)) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api-auth.php');
        }

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

        if (!class_exists('CreatePersonalAccessTokensTable')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/create_personal_access_tokens_table.php'
                    => database_path('migrations/' . date('Y_m_d_His', time() + 1) . '_create_personal_access_tokens_table.php'),
            ], ['meraki-migrations', 'meraki-auth-migrations']);
        }

        if ($this->app->make(AuthDriverManager::class)->isActive()) {
            $this->registerPermissions();
        }
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
