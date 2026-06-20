<x-layouts.app>
    <x-slot name="title">Activity Logs</x-slot>

    <x-composites.page-header
        title="Riwayat Sistem"
        description="Catatan aktivitas seluruh pengguna untuk keperluan audit (Read-Only)."
    />

    {{-- Search --}}
    <div class="mb-6" x-data="{ search: '' }">
        <x-ui.input
            placeholder="Cari aktivitas atau nama user..."
            icon="search"
            x-model="search"
        />

        @if(count($logs) > 0)
            <div class="card p-4 sm:p-6 mt-4">
                <div class="relative border-l-2 border-slate-100 ml-4 sm:ml-6 space-y-8 py-2">
                    @foreach($logs as $log)
                        @php
                            $actionLower = strtolower($log->action);
                            $type = 'other';
                            if (str_contains($actionLower, 'login')) $type = 'login';
                            elseif (str_contains($actionLower, 'logout')) $type = 'logout';
                            elseif (str_contains($actionLower, 'create') || str_contains($actionLower, 'buat')) $type = 'create';
                            elseif (str_contains($actionLower, 'delete') || str_contains($actionLower, 'hapus')) $type = 'delete';
                            elseif (str_contains($actionLower, 'check-in')) $type = 'check_in';
                            elseif (str_contains($actionLower, 'check-out')) $type = 'check_out';

                            $actionStyles = match($type) {
                                'login'     => ['icon' => 'log-in',         'bg' => 'bg-info-light',        'text' => 'text-sky-600'],
                                'logout'    => ['icon' => 'log-out',        'bg' => 'bg-slate-100',         'text' => 'text-slate-600'],
                                'create'    => ['icon' => 'edit',           'bg' => 'bg-success-light',     'text' => 'text-green-600'],
                                'delete'    => ['icon' => 'trash',          'bg' => 'bg-destructive-light', 'text' => 'text-red-600'],
                                'check_in'  => ['icon' => 'calendar-check', 'bg' => 'bg-primary-100',      'text' => 'text-primary-600'],
                                'check_out' => ['icon' => 'calendar-check', 'bg' => 'bg-warning-light',    'text' => 'text-amber-600'],
                                default     => ['icon' => 'circle',         'bg' => 'bg-slate-100',         'text' => 'text-slate-600'],
                            };
                        @endphp
                        <div
                            class="relative pl-6 sm:pl-8"
                            x-show="!search || '{{ strtolower($log->action . ' ' . ($log->user->name ?? 'System')) }}'.includes(search.toLowerCase())"
                        >
                            {{-- Timeline Marker --}}
                            <div class="absolute -left-4 sm:-left-[18px] top-1 flex h-8 w-8 items-center justify-center rounded-full border-4 border-white shadow-sm {{ $actionStyles['bg'] }} {{ $actionStyles['text'] }}">
                                <x-icon :name="$actionStyles['icon']" class="h-3.5 w-3.5" />
                            </div>

                            {{-- Content --}}
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 bg-white border border-slate-100 rounded-lg p-3 shadow-sm hover:shadow-md transition-shadow">
                                <div class="w-full">
                                    <div class="flex items-center justify-between">
                                        <p class="text-body font-semibold text-slate-900">{{ $log->action }}</p>
                                        <div class="text-xs text-slate-400 bg-slate-50 px-2 py-1 rounded w-fit h-fit whitespace-nowrap">
                                            {{ $log->created_at->format('d M Y H:i:s') }}
                                        </div>
                                    </div>
                                    
                                    @if($log->description)
                                        <p class="text-sm text-slate-600 mt-1">{{ $log->description }}</p>
                                    @endif

                                    @if(!empty($log->properties))
                                        <div class="mt-2 w-full overflow-x-auto bg-slate-50 border border-slate-200 rounded p-2 text-xs font-mono text-slate-700" x-data="{ expanded: false }">
                                            <button @click="expanded = !expanded" class="text-primary hover:underline font-sans font-medium mb-1 flex items-center gap-1">
                                                <span x-text="expanded ? 'Sembunyikan Perubahan (Data Audit)' : 'Lihat Perubahan (Data Audit)'"></span>
                                            </button>
                                            <pre x-show="expanded" x-transition class="whitespace-pre-wrap break-all">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </div>
                                    @endif

                                    <p class="text-xs text-slate-500 flex items-center gap-2 mt-3">
                                        <x-ui.avatar :name="$log->user->name ?? 'System'" size="xs" />
                                        <span class="font-medium text-slate-700">{{ $log->user->name ?? 'System' }}</span>
                                        <span class="text-slate-300">•</span>
                                        <span>{{ $log->ip_address ?? '-' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-6">
                    {{ $logs->links() }}
                </div>
            </div>
        @else
            <x-composites.empty-state
                icon="history"
                title="Tidak ada riwayat"
                description="Sistem belum mencatat aktivitas apapun."
            />
        @endif
    </div>
</x-layouts.app>
