<?php

namespace Meraki\Packages\Auth\Tests\Feature\Auth;

use Meraki\Packages\Auth\Tests\TestCase;

class PasswordResetFlowTest extends TestCase
{
    public function test_forgot_password_page_renders(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_page_renders(): void
    {
        $response = $this->get('/reset-password/test-token');

        $response->assertStatus(200);
    }
}
