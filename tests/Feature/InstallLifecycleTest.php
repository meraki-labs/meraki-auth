<?php

namespace Meraki\Packages\Auth\Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\SanctumServiceProvider;
use Meraki\Packages\Auth\AuthServiceProvider;
use Orchestra\Testbench\TestCase;

class InstallLifecycleTest extends TestCase
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
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('meraki-auth.driver.active', false);
        $app['config']->set('meraki-auth.user_model', \Meraki\Packages\Auth\Models\User::class);
        $app['config']->set('auth.providers.users.model', \Meraki\Packages\Auth\Models\User::class);
    }

    protected function defineDatabaseMigrations(): void
    {
        // Load only meraki_meta migration; auth table migrations are created by install command.
        $this->loadMigrationsFrom(
            __DIR__ . '/../../vendor/merakilab/meraki-core/database/migrations'
        );
    }

    protected function setUp(): void
    {
        $this->cleanPublishedMigrations();
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->cleanPublishedMigrations();
    }

    private function cleanPublishedMigrations(): void
    {
        $dir = base_path('database/migrations');
        foreach (['*_create_auth_tables.php', '*_create_personal_access_tokens_table.php'] as $pattern) {
            foreach (glob($dir . '/' . $pattern) ?: [] as $file) {
                @unlink($file);
            }
        }
    }

    public function test_install_creates_auth_tables_and_writes_meta(): void
    {
        $this->artisan('meraki-auth:install')->assertSuccessful();

        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasTable('password_reset_tokens'));
        $this->assertTrue(Schema::hasTable('sessions'));

        $this->assertTrue(
            $this->app['db']->table('meraki_meta')->where('key', 'meraki-auth.installed')->exists()
        );
    }

    public function test_second_install_without_force_is_idempotent(): void
    {
        $this->artisan('meraki-auth:install')->assertSuccessful();
        $this->artisan('meraki-auth:install')->assertSuccessful();

        $this->assertSame(
            1,
            $this->app['db']->table('meraki_meta')->where('key', 'meraki-auth.installed')->count()
        );
    }

    public function test_uninstall_drops_tables_and_removes_meta(): void
    {
        $this->artisan('meraki-auth:install')->assertSuccessful();

        $this->artisan('meraki-auth:uninstall', ['--drop-tables' => true])->assertSuccessful();

        $this->assertFalse(Schema::hasTable('users'));
        $this->assertFalse(Schema::hasTable('password_reset_tokens'));
        $this->assertFalse(Schema::hasTable('sessions'));

        $this->assertFalse(
            $this->app['db']->table('meraki_meta')->where('key', 'meraki-auth.installed')->exists()
        );
    }

    public function test_full_install_uninstall_reinstall_cycle(): void
    {
        // Install
        $this->artisan('meraki-auth:install')->assertSuccessful();
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(
            $this->app['db']->table('meraki_meta')->where('key', 'meraki-auth.installed')->exists()
        );

        // Uninstall via rollback
        $this->artisan('meraki-auth:uninstall')->assertSuccessful();
        $this->assertFalse(
            $this->app['db']->table('meraki_meta')->where('key', 'meraki-auth.installed')->exists()
        );

        // Reinstall
        $this->artisan('meraki-auth:install')->assertSuccessful();
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(
            $this->app['db']->table('meraki_meta')->where('key', 'meraki-auth.installed')->exists()
        );
    }
}
