<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomCategory;
use App\Models\Room;
use App\Models\RoomImage;
use App\Models\Facility;

class SundaTibraSareSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Facilities
        $facilities = [
            ['name' => 'WiFi Gratis', 'description' => 'Akses internet cepat di seluruh area.'],
            ['name' => 'AC', 'description' => 'Pendingin ruangan.'],
            ['name' => 'TV Kabel', 'description' => 'Beragam channel hiburan.'],
            ['name' => 'Kamar Mandi Alam', 'description' => 'Kamar mandi semi terbuka dengan air hangat.'],
            ['name' => 'Sarapan Tradisional', 'description' => 'Sarapan menu khas Sunda setiap pagi.'],
            ['name' => 'Private Pool', 'description' => 'Kolam renang pribadi.'],
            ['name' => 'Balkon Pemandangan', 'description' => 'Balkon luas menghadap kebun teh/pegunungan.'],
        ];

        $facilityIds = [];
        foreach ($facilities as $facility) {
            $created = Facility::firstOrCreate(['name' => $facility['name']], $facility);
            $facilityIds[$facility['name']] = $created->id;
        }

        // 2. Create Room Categories
        $categories = [
            [
                'name' => 'Saung Alit',
                'description' => 'Kamar standar berkonsep gubuk bambu khas Sunda yang sejuk dan nyaman. Cocok untuk pasangan atau backpacker yang mencari ketenangan alam.',
                'base_price' => 450000,
                'image_path' => 'room_categories/saung_alit.png',
                'facilities' => ['WiFi Gratis', 'Kamar Mandi Alam', 'Sarapan Tradisional'],
            ],
            [
                'name' => 'Bumi Pasundan',
                'description' => 'Kamar deluxe yang luas dengan arsitektur rumah tradisional Sunda. Lantai kayu, aksen batik, dan jendela besar yang menghadap langsung ke rimbunnya hutan.',
                'base_price' => 850000,
                'image_path' => 'room_categories/bumi_pasundan.png',
                'facilities' => ['WiFi Gratis', 'AC', 'TV Kabel', 'Kamar Mandi Alam', 'Sarapan Tradisional', 'Balkon Pemandangan'],
            ],
            [
                'name' => 'Puri Parahyangan',
                'description' => 'Villa premium super mewah dengan kolam renang pribadi (private plunge pool). Menggabungkan kemewahan modern dengan ukiran tradisional Sunda. Cocok untuk bulan madu.',
                'base_price' => 1850000,
                'image_path' => 'room_categories/puri_parahyangan.png',
                'facilities' => ['WiFi Gratis', 'AC', 'TV Kabel', 'Kamar Mandi Alam', 'Sarapan Tradisional', 'Private Pool', 'Balkon Pemandangan'],
            ]
        ];

        foreach ($categories as $index => $catData) {
            $facs = $catData['facilities'];
            unset($catData['facilities']);

            $category = RoomCategory::create($catData);

            // Create Rooms for this category
            // Saung Alit: 8 rooms, Bumi Pasundan: 5 rooms, Puri Parahyangan: 2 rooms
            $roomCount = $index === 0 ? 8 : ($index === 1 ? 5 : 2);
            $prefix = $index === 0 ? 'SA-' : ($index === 1 ? 'BP-' : 'PP-');

            for ($i = 1; $i <= $roomCount; $i++) {
                $room = Room::create([
                    'room_category_id' => $category->id,
                    'room_number' => $prefix . (100 + $i),
                    'price' => $category->base_price,
                    'status' => \App\Enums\RoomStatus::Available,
                    'description' => 'Kamar ' . $category->name . ' nomor ' . (100 + $i),
                ]);

                // Sync Facilities
                $syncIds = [];
                foreach ($facs as $fname) {
                    $syncIds[] = $facilityIds[$fname];
                }
                $room->facilities()->sync($syncIds);

                // Create Image for the room (using the same category image for demo)
                RoomImage::create([
                    'room_id' => $room->id,
                    'image_path' => $category->image_path,
                ]);
            }
        }
    }
}
