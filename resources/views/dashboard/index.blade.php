<x-layouts.app>
    <x-slot name="title">Dashboard</x-slot>

    @php
        $hour = (int) date('G');
        $greeting = $hour < 12 ? 'Selamat Pagi' : ($hour < 17 ? 'Selamat Siang' : 'Selamat Malam');
        $user = auth()->user();
    @endphp

    {{-- Hero Greeting Banner --}}
    <div class="relative mb-8 rounded-3xl overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-primary-900 p-8 sm:p-10 shadow-lg">
        {{-- Decorative background elements --}}
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-primary-500/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 rounded-full bg-blue-500/10 blur-2xl"></div>
        
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <p class="text-primary-200 font-medium tracking-wide text-sm uppercase mb-1">{{ format_date_id(now()) }}</p>
                <h1 class="text-3xl sm:text-4xl font-bold text-white tracking-tight mb-2">
                    {{ $greeting }}, <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-200 to-white">{{ explode(' ', $user->name)[0] }}</span> 👋
                </h1>
                <p class="text-slate-300 text-sm sm:text-base max-w-xl">
                    Pantau okupansi kamar, manajemen reservasi tamu, dan kinerja hotel Anda secara menyeluruh.
                </p>
            </div>
            
            <div class="hidden md:flex shrink-0 p-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 items-center justify-center">
                <x-icon name="building" class="w-12 h-12 text-primary-200" />
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <x-composites.stat-card label="Total Kamar" :value="$totalRooms" icon="bed-double" color="primary" />
        <x-composites.stat-card label="Tersedia" :value="$availableRooms" icon="check-circle" color="success" />
        <x-composites.stat-card label="Terisi" :value="$occupiedRooms" icon="x-circle" color="destructive" />
        <x-composites.stat-card label="Total Tamu" :value="$totalGuests" icon="users" color="info" />
    </div>

    {{-- Quick Actions --}}
    <div class="mb-6">
        <h2 class="text-h3 text-slate-900 mb-3">Aksi Cepat</h2>
        <div class="grid grid-cols-4 gap-2 sm:gap-3">
            <x-composites.quick-action label="Reservasi" icon="plus" href="/reservations/create" color="primary" />
            <x-composites.quick-action label="Check-In" icon="log-in" href="/check-in" color="success" />
            <x-composites.quick-action label="Check-Out" icon="log-out" href="/check-out" color="warning" />
            <x-composites.quick-action label="Tamu Baru" icon="user-plus" href="/guests" color="info" />
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <div class="card p-4">
            <h3 class="text-h3 text-slate-900 mb-3">Tren Pendapatan Bulanan</h3>
            <div id="revenueChart" class="w-full"></div>
        </div>

        <div class="card p-4">
            <h3 class="text-h3 text-slate-900 mb-3">Statistik Okupansi Kamar</h3>
            <div id="occupancyChart" class="flex items-center justify-center gap-8 h-64"></div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="card p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-h3 text-slate-900">Aktivitas Terbaru</h3>
            <a href="/activity-logs" class="text-caption text-primary hover:underline cursor-pointer">Lihat semua</a>
        </div>

        @if(count($recentActivities) > 0)
            <div class="divide-y divide-slate-100">
                @foreach($recentActivities as $activity)
                    <div class="flex items-start gap-3 py-3 first:pt-0 last:pb-0">
                        <div class="w-8 h-8 rounded-full bg-primary-50 flex items-center justify-center shrink-0 mt-0.5">
                            <x-icon name="calendar-check" class="h-4 w-4 text-primary" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-body text-slate-900">{{ $activity['action'] }}</p>
                            <p class="text-caption text-muted-foreground">{{ $activity['user']['name'] ?? 'System' }} · {{ $activity['time_ago'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-body text-muted-foreground text-center py-8">Belum ada aktivitas hari ini.</p>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data for Revenue Chart
            const rawRevenueData = @json($revenueData);
            const revenueData = [...rawRevenueData].reverse();
            
            const revenueOptions = {
                chart: { type: 'area', toolbar: { show: false }, fontFamily: 'inherit', height: 250, sparkline: { enabled: false } },
                colors: ['#0f172a'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] }
                },
                series: [{ name: 'Pendapatan', data: revenueData.map(d => d.revenue) }],
                xaxis: {
                    categories: revenueData.map(d => d.month),
                    axisBorder: { show: false }, axisTicks: { show: false },
                    labels: { style: { colors: '#64748b' } }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) { return 'Rp ' + (val / 1000000).toFixed(1) + 'M'; },
                        style: { colors: '#64748b' }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9', strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                },
                tooltip: {
                    y: { formatter: function(val) { return "Rp " + val.toLocaleString('id-ID'); } }
                }
            };
            
            if(revenueData.length > 0) {
                new ApexCharts(document.querySelector("#revenueChart"), revenueOptions).render();
            } else {
                document.querySelector("#revenueChart").innerHTML = '<div class="h-[250px] w-full flex items-center justify-center text-slate-500">Tidak ada data</div>';
            }

            // Data for Occupancy Chart
            const available = {{ $availableRooms }};
            const occupied = {{ $occupiedRooms }};
            const total = available + occupied;
            const percent = total > 0 ? Math.round((occupied / total) * 100) : 0;
            
            const occupancyOptions = {
                chart: { type: 'radialBar', height: 250, fontFamily: 'inherit' },
                series: [percent],
                colors: ['#1e3a8a'],
                plotOptions: {
                    radialBar: {
                        hollow: { size: '65%' },
                        track: { background: '#f1f5f9', strokeWidth: '100%' },
                        dataLabels: {
                            name: { offsetY: 20, color: '#64748b', fontSize: '12px', fontWeight: 500, show: true, formatter: function() { return "Terisi"; } },
                            value: { offsetY: -10, color: '#0f172a', fontSize: '28px', fontWeight: 700, show: true, formatter: function(val) { return val + "%"; } }
                        }
                    }
                },
                stroke: { lineCap: 'round' }
            };
            
            new ApexCharts(document.querySelector("#occupancyChart"), occupancyOptions).render();
        });
    </script>
    @endpush
</x-layouts.app>
