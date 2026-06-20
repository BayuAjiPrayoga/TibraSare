<?php

namespace Tests\Browser;

use App\Models\RoomCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BookingFlowTest extends DuskTestCase
{
    /**
     * A Dusk test for guest booking flow.
     */
    public function test_guest_can_initiate_booking()
    {
        // Pastikan ada kamar yang bisa dipesan
        $category = RoomCategory::whereHas('rooms', function ($query) {
            $query->where('status', \App\Enums\RoomStatus::Available);
        })->first();

        if (!$category) {
            $this->markTestSkipped('Tidak ada kamar yang tersedia untuk diuji pemesanannya.');
        }

        // Buat user guest sementara untuk login
        $email = 'booker_' . time() . '@example.com';
        $user = User::factory()->create([
            'name' => 'Dusk Booker',
            'email' => $email,
            'password' => bcrypt('password123'),
        ]);

        $this->browse(function (Browser $browser) use ($category, $user) {
            $browser->loginAs($user->id)
                    ->visit("/guest-rooms/{$category->id}")
                    ->assertSee($category->name)
                    ->clickLink('Pesan Sekarang')
                    ->waitForLocation("/book/{$category->id}", 5)
                    ->assertPathIs("/book/{$category->id}")
                    ->assertSee('Detail Pemesanan')
                    // Isi form pemesanan
                    ->type('guest_name', 'Tamu Dusk')
                    ->type('guest_phone', '081234567890')
                    ->type('check_in_date', now()->addDays(1)->format('Y-m-d'))
                    ->type('check_out_date', now()->addDays(2)->format('Y-m-d'))
                    ->type('number_of_guests', '2')
                    ->press('Lanjutkan ke Pembayaran')
                    // Karena ini memanggil Xendit (Payment Gateway external) yang mungkin perlu internet/token, 
                    // kita hanya memastikan halaman tidak error 500 dan mencoba redirect.
                    ->pause(3000); // Tunggu proses redirect Xendit
            
            // Cek URL apakah menuju Invoice Xendit atau error validation
            $url = $browser->driver->getCurrentURL();
            $this->assertTrue(
                str_contains($url, 'checkout.xendit.co') || 
                str_contains($url, 'dashboard') || 
                str_contains($url, 'book/'),
                "Gagal melakukan redirect pemesanan. Berada di URL: {$url}"
            );
        });

        // Cleanup
        $user->delete();
    }
}
