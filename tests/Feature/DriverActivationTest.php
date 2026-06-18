<?php

namespace Meraki\Packages\Auth\Tests\Feature;

use Meraki\Core\Modules\PermissionRegistry;
use Meraki\Packages\Auth\AuthServiceProvider;
use Meraki\Packages\Auth\Facades\MerakiAuthDriver;
use Meraki\Packages\Auth\Services\AuthDriverManager;
use Orchestra\Testbench\TestCase;

class DriverActivationTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [AuthServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'MerakiAuthDriver' => \Meraki\Packages\Auth\Facades\MerakiAuthDriver::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
        $app->singleton(PermissionRegistry::class, PermissionRegistry::class);
    }

    public function test_login_route_returns_200_when_driver_is_active(): void
    {
        config(['meraki-auth.driver.active' => true]);

        $this->app->singleton(AuthDriverManager::class, function () {
            $manager = new AuthDriverManager(app(PermissionRegistry::class));
            return $manager;
        });

        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_login_route_returns_503_when_driver_is_inactive(): void
    {
        $manager = $this->app->make(AuthDriverManager::class);
        $manager->deactivate();

        $response = $this->get('/login');

        $response->assertStatus(503);
    }

    public function test_activate_after_deactivate_returns_200(): void
    {
        $manager = $this->app->make(AuthDriverManager::class);
        $manager->deactivate();

        $this->get('/login')->assertStatus(503);

        $manager->activate();

        $this->get('/login')->assertStatus(200);
    }
}
