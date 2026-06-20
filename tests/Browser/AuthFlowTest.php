<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthFlowTest extends DuskTestCase
{
    /**
     * A Dusk test for admin login.
     */
    public function test_admin_can_login()
    {
        // Pastikan ada user admin dari seeder
        $admin = User::where('email', 'admin@tibrasare.test')->first();

        if (!$admin) {
            $this->markTestSkipped('Seeder belum dijalankan. Admin user tidak ditemukan.');
        }

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->visit('/login')
                    ->assertSee('Masuk ke Akun Anda')
                    ->type('email', $admin->email)
                    ->type('password', 'password')
                    ->press('Masuk')
                    ->waitForLocation('/dashboard', 5)
                    ->assertPathIs('/dashboard')
                    ->assertSee('Selamat Datang')
                    // Coba logout
                    ->click('button[aria-label="Notifikasi"]') // Buka menu profil (Dropdown di topbar biasanya pakai avatar, mari kita klik avatar)
                    // Wait, di topbar admin, tombol profil adalah button dropdown.
                    ->script("document.querySelector('button img').closest('button').click()");
            
            $browser->pause(1000) // tunggu animasi dropdown
                    ->clickLink('Keluar')
                    ->waitForLocation('/', 5)
                    ->assertPathIs('/');
        });
    }

    /**
     * A Dusk test for guest registration.
     */
    public function test_guest_can_register()
    {
        $this->browse(function (Browser $browser) {
            // Gunakan email acak agar tidak bentrok
            $email = 'guest_' . time() . '@example.com';
            
            $browser->visit('/register')
                    ->assertSee('Daftar Akun Baru')
                    ->type('name', 'Dusk Guest')
                    ->type('email', $email)
                    ->type('password', 'password123')
                    ->type('password_confirmation', 'password123')
                    ->press('Daftar')
                    ->waitForLocation('/guest-rooms', 5)
                    ->assertPathIs('/guest-rooms');
            
            // Cleanup user setelah selesai
            User::where('email', $email)->delete();
        });
    }
}
