<?php

namespace Tests\Browser;

use App\Models\RoomCategory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class GuestNavigationTest extends DuskTestCase
{
    /**
     * A Dusk test for guest navigation flow.
     */
    public function test_guest_can_navigate_landing_and_rooms()
    {
        $this->browse(function (Browser $browser) {
            // Cek apakah ada data kamar
            $category = RoomCategory::first();

            $browser->visit('/')
                    ->assertSee('A Symphony of Elegance & Nature') // Asumsi hero text
                    ->pause(1000)
                    ->clickLink('Eksplorasi Kamar') // Tombol di landing page
                    ->waitForLocation('/guest-rooms', 5)
                    ->assertPathIs('/guest-rooms')
                    ->assertSee('Eksplorasi Kamar');

            if ($category) {
                $browser->assertSee($category->name)
                        ->clickLink('Lihat Detail')
                        ->waitForLocation("/guest-rooms/{$category->id}", 5)
                        ->assertSee($category->name)
                        ->assertSee('Fasilitas Utama');
                
                // Cek rekomendasi kamar lain jika lebih dari 1 kategori
                if (RoomCategory::count() > 1) {
                    $browser->assertSee('Rekomendasi Kamar Lain');
                }

                // Cek tombol kembali ke daftar kamar (hanya untuk mobile topbar atau desktop topbar)
                $browser->clickLink('Daftar Kamar')
                        ->waitForLocation('/guest-rooms', 5)
                        ->assertPathIs('/guest-rooms');
            }
        });
    }
}
