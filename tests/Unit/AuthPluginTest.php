<?php

namespace Meraki\Packages\Auth\Tests\Unit;

use Illuminate\Contracts\Foundation\Application;
use Meraki\Core\CoreManager;
use Meraki\Core\Modules\PackageRegistry;
use Meraki\Packages\Auth\AuthPlugin;
use PHPUnit\Framework\TestCase;

class AuthPluginTest extends TestCase
{
    private AuthPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->plugin = new AuthPlugin();
    }

    public function test_id_returns_meraki_auth(): void
    {
        $this->assertSame('meraki-auth', $this->plugin->id());
    }

    public function test_name_is_not_empty(): void
    {
        $this->assertNotEmpty($this->plugin->name());
    }

    public function test_version_is_not_empty(): void
    {
        $this->assertNotEmpty($this->plugin->version());
    }

    public function test_description_is_not_empty(): void
    {
        $this->assertNotEmpty($this->plugin->description());
    }

    public function test_register_calls_core_manager_extend_when_bound(): void
    {
        $coreManager = $this->createMock(CoreManager::class);
        $coreManager->expects($this->once())
            ->method('extend')
            ->with('auth', 'meraki-auth', $this->isInstanceOf(\Closure::class));

        $app = $this->createMock(Application::class);
        $app->method('bound')
            ->willReturnCallback(fn ($abstract) => $abstract === CoreManager::class);
        $app->method('make')
            ->with(CoreManager::class)
            ->willReturn($coreManager);

        $this->plugin->register($app);
    }

    public function test_register_calls_package_registry_when_bound(): void
    {
        $packageRegistry = $this->createMock(PackageRegistry::class);
        $packageRegistry->expects($this->once())
            ->method('register')
            ->with('meraki-auth', $this->arrayHasKey('config'));

        $app = $this->createMock(Application::class);
        $app->method('bound')
            ->willReturnCallback(fn ($abstract) => $abstract === PackageRegistry::class);
        $app->method('make')
            ->with(PackageRegistry::class)
            ->willReturn($packageRegistry);

        $this->plugin->register($app);
    }

    public function test_register_does_not_throw_when_core_not_bound(): void
    {
        $app = $this->createMock(Application::class);
        $app->method('bound')->willReturn(false);
        $app->expects($this->never())->method('make');

        $this->plugin->register($app);
        $this->assertTrue(true);
    }

    public function test_boot_does_not_throw(): void
    {
        $app = $this->createMock(Application::class);
        $this->plugin->boot($app);
        $this->assertTrue(true);
    }
}
