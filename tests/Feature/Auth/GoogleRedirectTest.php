<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoogleRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_redirect_returns_to_login_when_google_config_is_missing(): void
    {
        config([
            'services.google.client_id' => '',
            'services.google.client_secret' => '',
            'services.google.redirect' => '',
        ]);

        $this->get('/auth/google')
            ->assertRedirect(route('login', absolute: false))
            ->assertSessionHas('status');
    }

    public function test_legacy_google_redirect_route_is_available(): void
    {
        $this->assertTrue(route('auth.google', absolute: false) === '/auth/google');
    }
}
