<x-layouts.app>
    <x-slot name="title">Pengaturan</x-slot>

    <x-composites.page-header
        title="Pengaturan Aplikasi"
        description="Konfigurasi data dasar hotel dan sistem."
    />

    <div class="max-w-4xl">
        <form method="POST" action="{{ route('settings.store') }}">
            @csrf

            <div class="card p-4 sm:p-6 mb-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4 border-b pb-2">Informasi Hotel</h2>
                <div class="space-y-4">
                    <x-ui.input label="Nama Hotel" name="hotel_name" icon="hotel" :value="$settings['hotel_name'] ?? ''" />
                    <x-ui.input label="Alamat Lengkap" name="address" icon="edit" :value="$settings['address'] ?? ''" />
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-ui.input label="Nomor Telepon" name="phone" icon="phone" :value="$settings['phone'] ?? ''" />
                        <x-ui.input label="Email Kontak" name="email" type="email" icon="mail" :value="$settings['email'] ?? ''" />
                    </div>
                </div>
            </div>

            <div class="card p-4 sm:p-6 mb-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4 border-b pb-2">Aturan Operasional</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-ui.input type="time" label="Jam Check-In Standar" name="check_in_time" icon="history" :value="$settings['check_in_time'] ?? '14:00'" />
                    <x-ui.input type="time" label="Jam Check-Out Standar" name="check_out_time" icon="history" :value="$settings['check_out_time'] ?? '12:00'" />
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <x-ui.button variant="outline" type="reset">Reset</x-ui.button>
                <x-ui.button type="submit" icon="check-circle">Simpan Perubahan</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.app>
