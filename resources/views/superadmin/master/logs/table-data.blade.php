<div class="overflow-x-auto rounded-xl">
    <table class="w-full">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengguna
                </th>
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Aktivitas
                    & Tindakan</th>
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">IP
                    Address</th>
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu
                    Kejadian</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 bg-white">
            @forelse($logs as $index => $log)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 text-sm text-slate-500 font-medium">
                        {{ $logs->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                {{ substr($log->user->name ?? 'U', 0, 2) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm">{{ $log->user->name ?? 'Pengguna Sistem' }}
                                </p>
                                <p class="text-[10px] text-slate-400 font-medium capitalize">
                                    {{ str_replace('_', ' ', $log->user->roles->first()->name ?? 'Role') }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="max-w-[480px]">
                            <p class="text-sm text-slate-700 leading-relaxed font-medium">
                                {{ $log->deskripsi }}
                            </p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-xs font-mono text-slate-400">
                        {{ $log->ip_address ?? '127.0.0.1' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">
                        <div class="flex flex-col">
                            <span
                                class="font-semibold text-slate-700 text-xs">{{ $log->created_at ? $log->created_at->format('d M Y') : '-' }}</span>
                            <span
                                class="text-[10px] text-slate-400 mt-0.5">{{ $log->created_at ? $log->created_at->format('H:i:s') : '-' }}</span>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-slate-100 rounded-full flex items-center justify-center text-slate-350">
                            <span class="mdi mdi-clipboard-text-outline text-3xl"></span>
                        </div>
                        <p class="font-semibold text-slate-650 text-sm">Tidak ada log aktivitas sistem</p>
                        <p class="text-xs mt-1 text-slate-400">Silakan ubah kata kunci filter pencarian Anda</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($logs->hasPages())
        <div class="px-6 py-4 border-t border-slate-200/60 bg-white">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    @endif
</div>
