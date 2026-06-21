<?php

namespace Meraki\Packages\Auth\Tests\Unit;

use Illuminate\Support\Facades\Schema;
use Meraki\Packages\Auth\AuthServiceProvider;
use Orchestra\Testbench\TestCase;

class UninstallCommandTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [AuthServiceProvider::class];
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
    }

    private function createMerakiMetaTable(): void
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('meraki_meta', function ($table) {
            $table->id();
            $table->string('key')->index();
            $table->string('value')->nullable();
            $table->timestamps();
        });
    }

    private function markAsInstalled(): void
    {
        $this->app['db']->table('meraki_meta')->insert([
            'key'        => 'meraki-auth.installed',
            'value'      => now()->toISOString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createAuthTables(): void
    {
        $schema = $this->app['db']->connection()->getSchemaBuilder();

        $schema->create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamps();
        });

        $schema->create('password_reset_tokens', function ($table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        $schema->create('sessions', function ($table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function test_command_warns_when_not_installed_and_no_meraki_meta_table(): void
    {
        $this->artisan('meraki-auth:uninstall')
            ->expectsOutputToContain('not installed')
            ->assertSuccessful();
    }

    public function test_command_warns_when_not_installed(): void
    {
        $this->createMerakiMetaTable();

        $this->artisan('meraki-auth:uninstall')
            ->expectsOutputToContain('not installed')
            ->assertSuccessful();
    }

    public function test_drop_tables_drops_auth_tables_and_clears_meta(): void
    {
        $this->createMerakiMetaTable();
        $this->createAuthTables();
        $this->markAsInstalled();

        $this->artisan('meraki-auth:uninstall', ['--drop-tables' => true])
            ->assertSuccessful();

        $this->assertFalse(Schema::hasTable('users'));
        $this->assertFalse(Schema::hasTable('password_reset_tokens'));
        $this->assertFalse(Schema::hasTable('sessions'));
        $this->assertFalse(
            $this->app['db']->table('meraki_meta')->where('key', 'meraki-auth.installed')->exists()
        );
    }

    public function test_meta_is_cleared_after_uninstall(): void
    {
        $this->createMerakiMetaTable();
        $this->createAuthTables();
        $this->markAsInstalled();

        $this->artisan('meraki-auth:uninstall', ['--drop-tables' => true])
            ->assertSuccessful();

        $this->assertFalse(
            $this->app['db']->table('meraki_meta')->where('key', 'meraki-auth.installed')->exists()
        );
    }
}
