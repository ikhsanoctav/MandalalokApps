<div class="block md:hidden divide-y divide-slate-100">
    @forelse($umkms as $index => $umkm)
        @php
            $statusVerifikasiBadge = [
                'terverifikasi' => 'bg-emerald-100 text-emerald-700',
                'terkirim' => 'bg-amber-100 text-amber-700',
                'ditolak' => 'bg-red-100 text-red-700',
            ];
            $statusVerifikasiLabel = [
                'terverifikasi' => 'Terverifikasi',
                'terkirim' => 'Menunggu',
                'ditolak' => 'Ditolak',
            ];
            $statusUsahaBadge = [
                'aktif' => 'bg-emerald-100 text-emerald-700',
                'non_aktif' => 'bg-slate-100 text-slate-600',
                'tutup' => 'bg-red-100 text-red-700',
                'pindah' => 'bg-blue-100 text-blue-700',
            ];
        @endphp
        <article class="p-4 space-y-3">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400">#{{ $umkms->firstItem() + $index }}</p>
                    <h3 class="text-base font-bold text-slate-800 leading-snug">{{ $umkm->nama_usaha }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ $umkm->no_pendaftaran ?? '-' }}</p>
                </div>
                <span class="shrink-0 inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusVerifikasiBadge[$umkm->status_verifikasi] ?? 'bg-slate-100 text-slate-600' }}">
                    {{ $statusVerifikasiLabel[$umkm->status_verifikasi] ?? 'Draft' }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Pemilik</p>
                    <p class="font-medium text-slate-700">{{ $umkm->pemilik->nama_lengkap ?? '-' }}</p>
                    <p class="text-xs text-slate-500">{{ $umkm->pemilik->kelurahan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Tanggal</p>
                    <p class="font-medium text-slate-700">{{ $umkm->created_at ? $umkm->created_at->format('d/m/Y') : '-' }}</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusUsahaBadge[$umkm->status_usaha] ?? 'bg-slate-100 text-slate-600' }}">
                    {{ ucfirst($umkm->status_usaha ?? '-') }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-2 pt-1">
                <button onclick="showDetail('{{ $umkm->id_umkm }}')"
                    class="inline-flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg bg-blue-50 text-blue-700 text-sm font-semibold">
                    Detail
                </button>
                <a href="{{ route('superadmin.umkm.edit', $umkm->id_umkm) }}"
                    class="inline-flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg bg-amber-50 text-amber-700 text-sm font-semibold">
                    Edit
                </a>
                @if ($umkm->status_verifikasi == 'terkirim')
                    <button onclick="verifyUmkm('{{ $umkm->id_umkm }}')"
                        class="inline-flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg bg-emerald-50 text-emerald-700 text-sm font-semibold">
                        Verifikasi
                    </button>
                    <button onclick="rejectUmkm('{{ $umkm->id_umkm }}')"
                        class="inline-flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg bg-red-50 text-red-700 text-sm font-semibold">
                        Tolak
                    </button>
                @endif
                <button onclick="confirmDelete('{{ $umkm->id_umkm }}', this.dataset.nama)"
                    class="col-span-2 inline-flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg bg-slate-100 text-slate-700 text-sm font-semibold"
                    data-nama="{{ e($umkm->nama_usaha) }}">
                    Hapus
                </button>
            </div>
        </article>
    @empty
        <div class="px-4 py-12 text-center text-slate-400">
            <p>Tidak ada data UMKM</p>
            <p class="text-xs mt-1">Silakan tambah UMKM baru</p>
        </div>
    @endforelse
</div>

<div class="hidden md:block overflow-x-auto">
    <table class="w-full">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">No</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama Usaha</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Pemilik</th>

                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status Verifikasi</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status Usaha</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal Daftar</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($umkms as $index => $umkm)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $umkms->firstItem() + $index }}</td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-slate-800">{{ $umkm->nama_usaha }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $umkm->no_pendaftaran ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-slate-600">{{ $umkm->pemilik->nama_lengkap ?? '-' }}</p>
                        <p class="text-xs text-slate-400">{{ $umkm->pemilik->kelurahan ?? '-' }}</p>
                    </td>

                    <td class="px-6 py-4">
                        @if ($umkm->status_verifikasi == 'terverifikasi')
                            <span
                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                Terverifikasi
                            </span>
                        @elseif($umkm->status_verifikasi == 'terkirim')
                            <span
                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                Menunggu
                            </span>
                        @elseif($umkm->status_verifikasi == 'ditolak')
                            <span
                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                Ditolak
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                <span class="w-1.5 h-1.5 bg-slate-500 rounded-full"></span>
                                Draft
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusUsahaBadge = [
                                'aktif' => 'bg-emerald-100 text-emerald-700',
                                'non_aktif' => 'bg-slate-100 text-slate-600',
                                'tutup' => 'bg-red-100 text-red-700',
                                'pindah' => 'bg-blue-100 text-blue-700',
                            ];
                            $statusUsahaClass = $statusUsahaBadge[$umkm->status_usaha] ?? 'bg-slate-100 text-slate-600';
                        @endphp
                        <span
                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $statusUsahaClass }}">
                            <span
                                class="w-1.5 h-1.5 rounded-full {{ $umkm->status_usaha == 'aktif' ? 'bg-emerald-500' : 'bg-slate-500' }}"></span>
                            {{ ucfirst($umkm->status_usaha ?? '-') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">
                        {{ $umkm->created_at ? $umkm->created_at->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-1">
                            
                            <button onclick="showDetail('{{ $umkm->id_umkm }}')"
                                class="text-blue-600 hover:text-blue-800 p-1.5 hover:bg-blue-50 rounded-2xl transition-all"
                                title="Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>

                            <a href="{{ route('superadmin.umkm.edit', $umkm->id_umkm) }}"
                                class="text-amber-600 hover:text-amber-800 p-1.5 hover:bg-amber-50 rounded-2xl transition-all"
                                title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </a>

                            @if ($umkm->status_verifikasi == 'terkirim')
                                <button onclick="verifyUmkm('{{ $umkm->id_umkm }}')"
                                    class="text-emerald-600 hover:text-emerald-800 p-1.5 hover:bg-emerald-50 rounded-2xl transition-all"
                                    title="Verifikasi">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                                <button onclick="rejectUmkm('{{ $umkm->id_umkm }}')"
                                    class="text-red-600 hover:text-red-800 p-1.5 hover:bg-red-50 rounded-2xl transition-all"
                                    title="Tolak">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            @endif

                            <button onclick="confirmDelete('{{ $umkm->id_umkm }}', this.dataset.nama)"
                                class="text-red-600 hover:text-red-800 p-1.5 hover:bg-red-50 rounded-2xl transition-all"
                                data-nama="{{ e($umkm->nama_usaha) }}"
                                title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                        <p>Tidak ada data UMKM</p>
                        <p class="text-xs mt-1">Silakan tambah UMKM baru</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

@if ($umkms->hasPages())
    <div class="px-4 sm:px-6 py-4 border-t border-slate-200">
        {{ $umkms->appends(request()->query())->links() }}
    </div>
@endif
