@props(['id' => 'scan-qr-modal'])

<div x-data="{
        isOpen: false,
        error: null,
        cameras: [],
        activeCameraId: null,
        scanner: null,
        
        initScanner() {
            if (typeof Html5Qrcode === 'undefined') {
                this.error = 'Scanner library belum dimuat.';
                return;
            }
            
            this.error = null;
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    this.cameras = devices;
                    const backCamera = devices.find(d => d.label.toLowerCase().includes('back') || d.label.toLowerCase().includes('belakang'));
                    this.activeCameraId = backCamera ? backCamera.id : devices[0].id;
                    this.startScanner();
                } else {
                    this.error = 'Kamera tidak ditemukan di perangkat ini.';
                }
            }).catch(err => {
                this.error = 'Gagal mengakses kamera. Pastikan Anda telah memberikan izin kamera.';
                console.error(err);
            });
        },
        
        startScanner() {
            if (this.scanner) {
                this.scanner.stop().then(() => {
                    this.scanner.clear();
                    this.runScanner();
                }).catch(e => console.error(e));
            } else {
                this.runScanner();
            }
        },
        
        runScanner() {
            this.scanner = new Html5Qrcode('qr-reader-' + this.$id('modal'));
            this.scanner.start(
                this.activeCameraId, 
                { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 },
                (decodedText) => {
                    this.stopScanner().then(() => {
                        this.isOpen = false;
                        $dispatch('qr-scanned', { text: decodedText });
                    });
                },
                (errorMessage) => { /* Ignore */ }
            ).catch((err) => {
                this.error = 'Gagal memulai kamera: ' + err;
            });
        },
        
        stopScanner() {
            if (this.scanner) {
                return this.scanner.stop().then(() => {
                    this.scanner.clear();
                    this.scanner = null;
                });
            }
            return Promise.resolve();
        },
        
        switchCamera() {
            if (this.cameras.length > 1) {
                const currentIndex = this.cameras.findIndex(c => c.id === this.activeCameraId);
                const nextIndex = (currentIndex + 1) % this.cameras.length;
                this.activeCameraId = this.cameras[nextIndex].id;
                this.startScanner();
            }
        }
    }" 
    @open-qr-scanner.window="isOpen = true; $nextTick(() => initScanner())"
    @keydown.escape.window="if(isOpen) { isOpen = false; stopScanner() }"
    x-id="['modal']"
>
    {{-- Modal Backdrop --}}
    <div x-show="isOpen" style="display: none;" class="fixed inset-0 z-modal-backdrop flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm motion-safe:animate-fade-in">
        {{-- Modal Panel --}}
        <div x-show="isOpen" @click.away="isOpen = false; stopScanner()" class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden flex flex-col motion-safe:animate-slide-up">
            
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary-50 rounded-lg text-primary">
                        <x-icon name="camera" class="w-5 h-5" />
                    </div>
                    <h3 class="font-semibold text-slate-900">Scan QR Code</h3>
                </div>
                <button @click="isOpen = false; stopScanner()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer">
                    <x-icon name="x" class="w-5 h-5" />
                </button>
            </div>

            {{-- Scanner Area --}}
            <div class="p-6 bg-slate-50 flex flex-col items-center">
                <template x-if="error">
                    <div class="text-center p-6 bg-red-50 text-red-600 rounded-xl border border-red-100 w-full">
                        <p class="text-sm font-medium" x-text="error"></p>
                    </div>
                </template>
                
                <div x-show="!error" class="relative w-full max-w-[300px] aspect-square rounded-2xl overflow-hidden bg-black shadow-inner border border-slate-200">
                    <div :id="'qr-reader-' + $id('modal')" class="w-full h-full object-cover"></div>
                </div>
                
                <template x-if="!error">
                    <p class="text-sm text-slate-500 mt-6 text-center">
                        Arahkan kamera ke QR Code tamu pada layar atau kertas.
                    </p>
                </template>
            </div>

            {{-- Footer Controls --}}
            <template x-if="cameras.length > 1 && !error">
                <div class="px-6 py-4 border-t border-slate-100 bg-white flex justify-center">
                    <x-ui.button variant="outline" @click="switchCamera()" icon="refresh-ccw">
                        Ganti Kamera
                    </x-ui.button>
                </div>
            </template>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
@endpush
