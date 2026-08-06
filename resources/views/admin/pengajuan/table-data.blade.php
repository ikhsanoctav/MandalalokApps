<div class="overflow-x-auto">
    <table class="w-full">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">No</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">UMKM Pemohon</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Jenis Pengajuan</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nominal</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal Daftar</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Catatan</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($pengajuans as $index => $pengajuan)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $pengajuans->firstItem() + $index }}</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800">{{ $pengajuan->umkm->nama_usaha ?? '-' }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $pengajuan->umkm->pemilik?->nama_lengkap ?? '-' }}
                        </p>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $jenisBadge = [
                                'pembiayaan' => 'bg-purple-100 text-purple-700 border-purple-200',
                                'bantuan' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'perizinan' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'lainnya' => 'bg-slate-100 text-slate-700 border-slate-200',
                            ];
                            $jenisClass =
                                $jenisBadge[$pengajuan->jenis_pengajuan] ??
                                'bg-slate-100 text-slate-700 border-slate-200';
                        @endphp
                        <span
                            class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $jenisClass }}">
                            {{ ucfirst($pengajuan->jenis_pengajuan) }}
                        </span>
                        @if ($pengajuan->jenis_pengajuan === 'bantuan' && $pengajuan->nama_program)
                            <p class="text-[10px] text-slate-500 font-medium mt-1 leading-snug">{{ $pengajuan->nama_program }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-semibold text-slate-800">
                        @if ($pengajuan->nominal)
                            Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}
                        @else
                            <span class="text-slate-400 font-normal">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">
                        {{ $pengajuan->tanggal_pengajuan ? $pengajuan->tanggal_pengajuan->format('d M Y') : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        {!! $pengajuan->status_badge !!}
                    </td>
                    <td class="px-6 py-4 text-xs text-slate-500 max-w-[200px] truncate"
                        title="{{ $pengajuan->catatan }}">
                        {{ $pengajuan->catatan ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            
                            <button onclick="showDetail('{{ $pengajuan->id_pengajuan }}')"
                                class="text-blue-600 hover:text-blue-800 p-1.5 hover:bg-blue-50 rounded-2xl transition-all"
                                title="Detail Pengajuan">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>

                            @if ($pengajuan->status == 'menunggu' || $pengajuan->status == 'proses')
                                
                                @if ($pengajuan->status == 'menunggu')
                                    <button
                                        onclick="updateStatus('{{ $pengajuan->id_pengajuan }}', 'proses', this.dataset.nama)"
                                        data-nama="{{ e($pengajuan->umkm->nama_usaha ?? 'UMKM') }}"
                                        class="text-blue-600 hover:text-blue-800 p-1.5 hover:bg-blue-50 rounded-2xl transition-all"
                                        title="Proses Pengajuan">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89l-2.286 2.287M21 4v6h-6">
                                            </path>
                                        </svg>
                                    </button>
                                @endif

                                <button
                                    onclick="updateStatus('{{ $pengajuan->id_pengajuan }}', 'disetujui', this.dataset.nama)"
                                    data-nama="{{ e($pengajuan->umkm->nama_usaha ?? 'UMKM') }}"
                                    class="text-emerald-600 hover:text-emerald-800 p-1.5 hover:bg-emerald-50 rounded-2xl transition-all"
                                    title="Setujui Pengajuan">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>

                                <button
                                    onclick="updateStatus('{{ $pengajuan->id_pengajuan }}', 'ditolak', this.dataset.nama)"
                                    data-nama="{{ e($pengajuan->umkm->nama_usaha ?? 'UMKM') }}"
                                    class="text-rose-600 hover:text-rose-800 p-1.5 hover:bg-rose-50 rounded-2xl transition-all"
                                    title="Tolak Pengajuan">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="font-medium text-slate-500 text-sm">Tidak ada data pengajuan bantuan</p>
                        <p class="text-xs mt-1 text-slate-400">Silakan sesuaikan filter pencarian Anda</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($pengajuans->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $pengajuans->appends(request()->query())->links() }}
        </div>
    @endif
</div>
