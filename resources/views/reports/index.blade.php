<x-layouts.app>
    <x-slot name="title">Laporan</x-slot>

    <x-composites.page-header title="Laporan" description="Ringkasan data operasional hotel.">
        <x-slot name="action">
            <div class="flex gap-2">
                <x-ui.button variant="outline" icon="file-text" onclick="exportReportPDF(reportData)">Export PDF</x-ui.button>
                <x-ui.button icon="download" onclick="exportReportExcel(reportData)">Export Excel</x-ui.button>
            </div>
        </x-slot>
    </x-composites.page-header>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <x-composites.stat-card label="Total Pendapatan Tahun Ini" :value="format_currency($stats['monthly_revenue'])" icon="bar-chart-3" color="success" />
        <x-composites.stat-card label="Total Reservasi Tahun Ini" :value="$stats['total_reservations']" icon="calendar-check" color="primary" />
    </div>

    {{-- Report Table --}}
    @if(count($reportData) > 0)
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-body">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-caption font-semibold text-slate-600">Kode</th>
                            <th class="text-left px-4 py-3 text-caption font-semibold text-slate-600">Tamu</th>
                            <th class="text-left px-4 py-3 text-caption font-semibold text-slate-600">Kamar</th>
                            <th class="text-left px-4 py-3 text-caption font-semibold text-slate-600">Check-In</th>
                            <th class="text-left px-4 py-3 text-caption font-semibold text-slate-600">Check-Out</th>
                            <th class="text-right px-4 py-3 text-caption font-semibold text-slate-600">Total</th>
                            <th class="text-center px-4 py-3 text-caption font-semibold text-slate-600">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($reportData as $row)
                            @php $statusCfg = config("navigation.reservation_status.{$row['status']}", ['label' => $row['status'], 'color' => 'muted']); @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-caption font-semibold text-primary tabular-nums">#{{ $row['booking_code'] }}</td>
                                <td class="px-4 py-3">{{ $row['guest_name'] }}</td>
                                <td class="px-4 py-3">{{ $row['room_number'] }}</td>
                                <td class="px-4 py-3 tabular-nums">{{ format_date_short($row['check_in']) }}</td>
                                <td class="px-4 py-3 tabular-nums">{{ format_date_short($row['check_out']) }}</td>
                                <td class="px-4 py-3 text-right font-semibold tabular-nums">{{ format_currency($row['total_price']) }}</td>
                                <td class="px-4 py-3 text-center">
                                    <x-ui.badge :variant="$statusCfg['color']" size="sm" :dot="true">{{ $statusCfg['label'] }}</x-ui.badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($reportData->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $reportData->links() }}
                </div>
            @endif
        </div>
    @else
        <x-composites.empty-state icon="bar-chart-3" title="Belum ada data" description="Data laporan akan muncul setelah ada transaksi." />
    @endif

    @push('scripts')
    @vite('resources/js/reports.js')
    <script>
        const reportData = @json($reportData->items());
    </script>
    @endpush
</x-layouts.app>
