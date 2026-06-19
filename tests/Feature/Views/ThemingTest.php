<?php

namespace Meraki\Packages\Auth\Tests\Feature\Views;

use Meraki\Packages\Auth\Tests\TestCase;

class ThemingTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);
        $app['config']->set('app.key', 'base64:' . base64_encode(str_repeat('a', 32)));
    }

    public function test_login_page_injects_primary_css_variable(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('--ma-primary: #6c63ff', false);
    }

    public function test_config_override_is_reflected_in_css_variables(): void
    {
        $this->app['config']->set('meraki-auth.ui.theme.primary', '#ff0000');

        $this->get('/login')
            ->assertOk()
            ->assertSee('--ma-primary: #ff0000', false);
    }

    public function test_all_css_tokens_appear_in_layout(): void
    {
        $response = $this->get('/login')->assertOk();

        foreach (['--ma-bg', '--ma-text', '--ma-primary', '--ma-border', '--ma-radius', '--ma-font'] as $token) {
            $response->assertSee($token, false);
        }
    }

    public function test_fallback_defaults_are_used_when_config_key_missing(): void
    {
        $this->app['config']->set('meraki-auth.ui.theme', []);

        $this->get('/login')
            ->assertOk()
            ->assertSee('--ma-primary: #6c63ff', false);
    }
}
