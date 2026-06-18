<?php

namespace Meraki\Packages\Auth\Tests\Unit;

use Meraki\Core\Modules\PermissionRegistry;
use Meraki\Packages\Auth\Services\AuthDriverManager;
use Orchestra\Testbench\TestCase;

class AuthDriverManagerTest extends TestCase
{
    private function makeManager(bool $active = true): AuthDriverManager
    {
        config(['meraki-auth.driver.active' => $active]);
        config(['meraki-auth.permissions' => []]);

        return new AuthDriverManager(new PermissionRegistry());
    }

    public function test_is_active_returns_true_when_config_active_is_true(): void
    {
        $manager = $this->makeManager(true);

        $this->assertTrue($manager->isActive());
    }

    public function test_is_active_returns_false_when_config_active_is_false(): void
    {
        $manager = $this->makeManager(false);

        $this->assertFalse($manager->isActive());
    }

    public function test_deactivate_sets_is_active_to_false(): void
    {
        $manager = $this->makeManager(true);

        $manager->deactivate();

        $this->assertFalse($manager->isActive());
    }

    public function test_activate_sets_is_active_to_true(): void
    {
        $manager = $this->makeManager(false);

        $manager->activate();

        $this->assertTrue($manager->isActive());
    }

    public function test_driver_name_returns_meraki_auth(): void
    {
        $manager = $this->makeManager();

        $this->assertSame('meraki-auth', $manager->driverName());
    }

    public function test_activate_registers_permissions(): void
    {
        config(['meraki-auth.permissions' => [
            ['module' => 'auth', 'name' => 'auth.login', 'label' => 'Login'],
        ]]);
        config(['meraki-auth.driver.active' => false]);

        $registry = new PermissionRegistry();
        $manager = new AuthDriverManager($registry);

        $manager->activate();

        $this->assertNotEmpty($registry->all());
        $this->assertSame('auth.login', $registry->all()[0]['name']);
    }
}
