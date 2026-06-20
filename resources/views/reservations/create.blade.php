<x-layouts.app>
    <x-slot name="title">Buat Reservasi</x-slot>

    <x-composites.page-header title="Buat Reservasi Baru" description="Isi data reservasi tamu." :breadcrumbs="[['label' => 'Reservasi', 'href' => route('reservations.index')], ['label' => 'Buat Baru']]" />

    <div x-data="{
        step: 1,
        form: {
            room_id: '', check_in_date: '', check_out_date: '',
            guest_id: '', full_name: '', email: '', phone: '', identity_type: 'KTP', identity_number: '',
            useExisting: false
        },
        selectedRoom: null,
        selectRoom(room) { this.form.room_id = room.id; this.selectedRoom = room; },
    }" class="max-w-3xl">

        {{-- Step Indicator --}}
        <div class="flex items-center gap-2 mb-8">
            <template x-for="s in [1,2,3]" :key="s">
                <div class="flex items-center gap-2">
                    <div :class="step >= s ? 'bg-primary text-white' : 'bg-slate-200 text-slate-500'" class="w-8 h-8 rounded-full flex items-center justify-center text-caption font-semibold transition-colors" x-text="s"></div>
                    <span class="text-caption font-medium hidden sm:inline" :class="step >= s ? 'text-slate-900' : 'text-slate-400'" x-text="s === 1 ? 'Pilih Kamar' : (s === 2 ? 'Data Tamu' : 'Konfirmasi')"></span>
                    <template x-if="s < 3"><div class="w-8 sm:w-16 h-px bg-slate-200"></div></template>
                </div>
            </template>
        </div>

        <form method="POST" action="{{ route('reservations.store') }}">
            @csrf

            {{-- Step 1: Room Selection --}}
            <div x-show="step === 1" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-ui.input type="date" label="Tanggal Check-In" name="check_in_date" x-model="form.check_in_date" :error="$errors->first('check_in_date')" required />
                    <x-ui.input type="date" label="Tanggal Check-Out" name="check_out_date" x-model="form.check_out_date" :error="$errors->first('check_out_date')" required />
                </div>

                <h3 class="text-h3 text-slate-900 mt-6">Pilih Kamar Tersedia</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($availableRooms as $room)
                        <button type="button" @click="selectRoom({{ json_encode($room) }})" :class="form.room_id === {{ $room['id'] }} ? 'ring-2 ring-primary bg-primary-50' : ''" class="card p-3 text-center cursor-pointer hover:shadow-md transition-all">
                            <p class="text-h3 text-slate-900">{{ $room['room_number'] }}</p>
                            <p class="text-caption text-muted-foreground">{{ $room['category']['name'] }}</p>
                            <p class="text-caption font-semibold text-slate-700 mt-1">{{ format_currency($room['price']) }}/malam</p>
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="room_id" x-model="form.room_id" />

                <div class="flex justify-end pt-4">
                    <x-ui.button type="button" @click="if(form.room_id && form.check_in_date && form.check_out_date) step = 2" icon-right="chevron-right">Selanjutnya</x-ui.button>
                </div>
            </div>

            {{-- Step 2: Guest Data --}}
            <div x-show="step === 2" class="space-y-4">
                @if(count($guests) > 0)
                    <label class="flex items-center gap-2 text-body cursor-pointer">
                        <input type="checkbox" x-model="form.useExisting" class="rounded border-slate-300 text-primary focus:ring-primary/20 cursor-pointer" />
                        <span>Pilih tamu yang sudah terdaftar</span>
                    </label>

                    <div x-show="form.useExisting" class="flex flex-col gap-1.5">
                        <label class="text-caption font-medium text-slate-700">Tamu Terdaftar</label>
                        <select name="guest_id" x-model="form.guest_id" class="w-full h-10 rounded-md border border-slate-300 bg-white pl-3 pr-10 text-body text-slate-900 appearance-none cursor-pointer focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                            <option value="">Pilih tamu...</option>
                            @foreach($guests as $g)
                                <option value="{{ $g->id }}">{{ $g->full_name }} ({{ $g->email }})</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div x-show="!form.useExisting" class="space-y-4">
                    <x-ui.input label="Nama Lengkap" name="full_name" x-model="form.full_name" :error="$errors->first('full_name')" />
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-ui.input label="Email" type="email" name="email" x-model="form.email" :error="$errors->first('email')" />
                        <x-ui.input label="Telepon" name="phone" x-model="form.phone" :error="$errors->first('phone')" />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-caption font-medium text-slate-700">Tipe Identitas</label>
                            <select name="identity_type" x-model="form.identity_type" class="w-full h-10 rounded-md border border-slate-300 bg-white pl-3 pr-10 text-body text-slate-900 appearance-none cursor-pointer focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                <option value="KTP">KTP</option>
                                <option value="Passport">Passport</option>
                                <option value="SIM">SIM</option>
                            </select>
                        </div>
                        <x-ui.input label="Nomor Identitas" name="identity_number" x-model="form.identity_number" :error="$errors->first('identity_number')" />
                    </div>
                </div>

                <div class="flex justify-between pt-4">
                    <x-ui.button variant="outline" type="button" @click="step = 1" icon="arrow-left">Kembali</x-ui.button>
                    <x-ui.button type="button" @click="step = 3" icon-right="chevron-right">Selanjutnya</x-ui.button>
                </div>
            </div>

            {{-- Step 3: Confirmation --}}
            <div x-show="step === 3" class="space-y-4">
                <div class="card p-5 space-y-3">
                    <h3 class="text-h3 text-slate-900">Ringkasan Reservasi</h3>
                    <div class="grid grid-cols-2 gap-3 text-body">
                        <div><span class="text-muted-foreground">Kamar:</span> <span class="font-semibold" x-text="selectedRoom ? selectedRoom.room_number : '-'"></span></div>
                        <div><span class="text-muted-foreground">Kategori:</span> <span class="font-semibold" x-text="selectedRoom ? selectedRoom.category.name : '-'"></span></div>
                        <div><span class="text-muted-foreground">Check-In:</span> <span class="font-semibold" x-text="form.check_in_date"></span></div>
                        <div><span class="text-muted-foreground">Check-Out:</span> <span class="font-semibold" x-text="form.check_out_date"></span></div>
                        <div><span class="text-muted-foreground">Tamu:</span> <span class="font-semibold" x-text="form.useExisting ? 'Tamu Terdaftar' : form.full_name"></span></div>
                    </div>
                </div>

                <div class="flex justify-between pt-4">
                    <x-ui.button variant="outline" type="button" @click="step = 2" icon="arrow-left">Kembali</x-ui.button>
                    <x-ui.button type="submit" icon="check-circle">Buat Reservasi</x-ui.button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
