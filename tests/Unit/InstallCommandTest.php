<?php

namespace Meraki\Packages\Auth\Tests\Unit;

use Meraki\Packages\Auth\AuthServiceProvider;
use Orchestra\Testbench\TestCase;

class InstallCommandTest extends TestCase
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

    public function test_command_fails_when_meraki_meta_table_does_not_exist(): void
    {
        $this->artisan('meraki-auth:install')
            ->expectsOutputToContain('Please run `php artisan meraki:install` first')
            ->assertFailed();
    }

    public function test_command_warns_and_succeeds_when_already_installed(): void
    {
        $this->createMerakiMetaTable();

        $this->app['db']->table('meraki_meta')->insert([
            'key'        => 'meraki-auth.installed',
            'value'      => now()->toISOString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->artisan('meraki-auth:install')
            ->expectsOutputToContain('already installed')
            ->assertSuccessful();

        $this->assertSame(
            1,
            $this->app['db']->table('meraki_meta')->where('key', 'meraki-auth.installed')->count()
        );
    }

    public function test_command_writes_installed_meta_on_success(): void
    {
        $this->createMerakiMetaTable();

        $this->artisan('meraki-auth:install')->assertSuccessful();

        $this->assertTrue(
            $this->app['db']->table('meraki_meta')->where('key', 'meraki-auth.installed')->exists()
        );
    }

    public function test_force_flag_reruns_even_when_already_installed(): void
    {
        $this->createMerakiMetaTable();

        $this->app['db']->table('meraki_meta')->insert([
            'key'        => 'meraki-auth.installed',
            'value'      => '2000-01-01T00:00:00.000000Z',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->artisan('meraki-auth:install', ['--force' => true])
            ->assertSuccessful();

        $value = $this->app['db']->table('meraki_meta')
            ->where('key', 'meraki-auth.installed')
            ->value('value');

        $this->assertNotSame('2000-01-01T00:00:00.000000Z', $value);
    }

    public function test_second_install_without_force_does_not_duplicate_meta(): void
    {
        $this->createMerakiMetaTable();

        $this->artisan('meraki-auth:install')->assertSuccessful();
        $this->artisan('meraki-auth:install')->assertSuccessful();

        $this->assertSame(
            1,
            $this->app['db']->table('meraki_meta')->where('key', 'meraki-auth.installed')->count()
        );
    }
}
