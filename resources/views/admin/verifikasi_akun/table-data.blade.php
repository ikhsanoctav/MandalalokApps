<div class="block md:hidden divide-y divide-slate-100">
    @forelse($pemiliks as $pemilik)
        <div class="p-4 space-y-3">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-semibold text-slate-800 {{ !$pemilik->foto_ktp ? 'text-rose-600' : '' }}">
                        {{ $pemilik->nama_lengkap }}
                        @if(!$pemilik->foto_ktp)
                            <span class="inline-flex items-center ml-2 px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700">Data KTP Kosong</span>
                        @endif
                    </h4>
                    <p class="text-xs text-slate-400">Kelurahan: {{ $pemilik->kelurahan ?? '-' }}</p>
                </div>
                <div>
                    @if($pemilik->status_verifikasi_ktp === 'terverifikasi')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Terverifikasi</span>
                    @elseif($pemilik->status_verifikasi_ktp === 'pending')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Pending</span>
                    @elseif($pemilik->status_verifikasi_ktp === 'ditolak')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">Ditolak</span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">{{ ucfirst($pemilik->status_verifikasi_ktp) }}</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 text-xs">
                <div>
                    <p class="text-slate-500">Tanggal Daftar</p>
                    <p class="font-medium text-slate-700">
                        {{ $pemilik->created_at ? $pemilik->created_at->format('d/m/Y') : '-' }}</p>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t">
                <a href="{{ route(auth()->user()->hasRole('super_admin') ? 'superadmin.verifikasi_akun.show' : 'admin.verifikasi_akun.show', $pemilik->id_pemilik) }}"
                    class="px-4 py-2 text-sm text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 transition-all font-medium flex items-center gap-2">
                    Tinjau
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </a>
            </div>
        </div>
    @empty
        <div class="p-8 text-center text-slate-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p>Tidak ada data verifikasi akun untuk ditampilkan.</p>
        </div>
    @endforelse
</div>

<div class="hidden md:block overflow-x-auto">
    <table class="w-full">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">No</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama Lengkap</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kelurahan</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tgl Daftar</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($pemiliks as $index => $pemilik)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3 text-sm text-slate-500">{{ $pemiliks->firstItem() + $index }}</td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-slate-800 {{ !$pemilik->foto_ktp ? 'text-rose-600' : '' }}">
                            {{ $pemilik->nama_lengkap }}
                            @if(!$pemilik->foto_ktp)
                                <span class="inline-flex items-center ml-2 px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700">KTP Kosong</span>
                            @endif
                        </p>
                    </td>
                    <td class="px-4 py-3">
                        <p class="text-sm text-slate-600">{{ $pemilik->kelurahan ?? '-' }}</p>
                    </td>

                    <td class="px-4 py-3">
                        @if($pemilik->status_verifikasi_ktp === 'terverifikasi')
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                Terverifikasi
                            </span>
                        @elseif($pemilik->status_verifikasi_ktp === 'pending')
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                Pending
                            </span>
                        @elseif($pemilik->status_verifikasi_ktp === 'ditolak')
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">
                                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                <span class="w-1.5 h-1.5 bg-slate-500 rounded-full"></span>
                                {{ ucfirst($pemilik->status_verifikasi_ktp) }}
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-500">
                        {{ $pemilik->created_at ? $pemilik->created_at->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route(auth()->user()->hasRole('super_admin') ? 'superadmin.verifikasi_akun.show' : 'admin.verifikasi_akun.show', $pemilik->id_pemilik) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-md transition-all">
                            Tinjau
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tidak ada data verifikasi akun untuk ditampilkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($pemiliks->hasPages())
    <div class="px-4 py-3 border-t border-slate-200">
        {{ $pemiliks->appends(request()->query())->links() }}
    </div>
@endif
