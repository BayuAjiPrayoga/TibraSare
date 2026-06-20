<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware(['web', 'auth', 'role:admin'])
            ->get('/testing/admin-only', fn () => response()->json(['ok' => true]));
    }

    public function test_admin_can_access_admin_only_route(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin)
            ->get('/testing/admin-only')
            ->assertOk()
            ->assertJson(['ok' => true]);
    }

    public function test_receptionist_cannot_access_admin_only_route(): void
    {
        $receptionist = User::factory()->create(['role' => UserRole::Receptionist]);

        $this->actingAs($receptionist)
            ->get('/testing/admin-only')
            ->assertForbidden();
    }
}
