<?php

namespace Meraki\Packages\Auth\Tests;

use Laravel\Sanctum\SanctumServiceProvider;
use Meraki\Packages\Auth\AuthServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            SanctumServiceProvider::class,
            AuthServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('meraki-auth.user_model', \Meraki\Packages\Auth\Models\User::class);
        $app['config']->set('meraki-auth.default_platform', 'web');
        $app['config']->set('auth.providers.users.model', \Meraki\Packages\Auth\Models\User::class);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
