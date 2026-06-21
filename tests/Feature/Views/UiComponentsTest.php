<?php

namespace Meraki\Packages\Auth\Tests\Feature\Views;

use Meraki\Packages\Auth\Tests\TestCase;

class UiComponentsTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);
        $app['config']->set('app.key', 'base64:' . base64_encode(str_repeat('a', 32)));
    }

    public function test_input_renders_with_name_attribute(): void
    {
        $this->blade('<x-meraki-auth::input name="email" />')
            ->assertSee('name="email"', false);
    }

    public function test_input_with_error_has_red_border_class(): void
    {
        $this->blade('<x-meraki-auth::input name="email" error="Required" />')
            ->assertSee('border-red-500', false);
    }

    public function test_button_primary_variant_has_primary_css_var_class(): void
    {
        $this->blade('<x-meraki-auth::button variant="primary">Click</x-meraki-auth::button>')
            ->assertSee('bg-[var(--ma-primary)]', false);
    }

    public function test_button_secondary_variant_has_border_css_var_class(): void
    {
        $this->blade('<x-meraki-auth::button variant="secondary">Click</x-meraki-auth::button>')
            ->assertSee('border-[var(--ma-border)]', false);
    }

    public function test_button_loading_renders_disabled_attribute(): void
    {
        $this->blade('<x-meraki-auth::button :loading="true">Click</x-meraki-auth::button>')
            ->assertSee('disabled', false);
    }

    public function test_alert_renders_slot_content(): void
    {
        $this->blade('<x-meraki-auth::alert type="success">All good</x-meraki-auth::alert>')
            ->assertSee('All good');
    }

    public function test_form_field_renders_label_and_error(): void
    {
        $view = $this->blade(
            '<x-meraki-auth::form-field label="Email" for="email" error="Required">
                <input id="email" />
            </x-meraki-auth::form-field>'
        );

        $view->assertSee('Email');
        $view->assertSee('Required');
    }

    public function test_card_renders_title_in_heading(): void
    {
        $this->blade('<x-meraki-auth::card title="My Card">Content</x-meraki-auth::card>')
            ->assertSee('My Card');
    }
}
