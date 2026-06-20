<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminDashboardTest extends DuskTestCase
{
    /**
     * A Dusk test for admin dashboard and room management.
     */
    public function test_admin_can_manage_rooms()
    {
        $admin = User::where('email', 'admin@tibrasare.test')->first();

        if (!$admin) {
            $this->markTestSkipped('Seeder belum dijalankan. Admin user tidak ditemukan.');
        }

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin->id)
                    ->visit('/dashboard')
                    ->assertSee('Selamat Datang')
                    // Buka menu sidebar untuk Kamar & Tipe (Jika di desktop, menu sidebar selalu ada)
                    ->clickLink('Kamar & Tipe')
                    ->waitForLocation('/room-categories', 5)
                    ->assertPathIs('/room-categories')
                    ->assertSee('Tipe Kamar')
                    // Navigasi ke Daftar Kamar fisik
                    ->clickLink('Manajemen Kamar')
                    ->waitForLocation('/rooms', 5)
                    ->assertPathIs('/rooms')
                    ->assertSee('Manajemen Kamar')
                    // Coba navigasi ke Reservasi
                    ->clickLink('Reservasi')
                    ->waitForLocation('/reservations', 5)
                    ->assertPathIs('/reservations')
                    ->assertSee('Reservasi');
        });
    }
}
