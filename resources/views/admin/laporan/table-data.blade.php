        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800">Detail Data UMKM</h3>
            </div>
            <div class="block md:hidden divide-y divide-slate-100">
                @forelse($umkms as $umkm)
                    <div class="p-4 space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-slate-800">{{ $umkm->nama_usaha }}</h4>
                                <p class="text-xs text-slate-400">{{ $umkm->no_pendaftaran ?? '-' }}</p>
                            </div>
                            <div>
                                @if ($umkm->status_verifikasi == 'terverifikasi')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Terverifikasi</span>
                                @elseif($umkm->status_verifikasi == 'menunggu_verifikasi')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Menunggu</span>
                                @elseif($umkm->status_verifikasi == 'ditolak')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Ditolak</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">Draft</span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <p class="text-slate-500">Pemilik</p>
                                <p class="font-medium text-slate-700">{{ $umkm->pemilik?->nama_lengkap ?? '-' }}</p>
                                <p class="text-[10px] text-slate-400">{{ $umkm->pemilik?->kelurahan ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-slate-500">Kategori</p>
                                <p class="font-medium text-slate-700">{{ $umkm->kategori?->nama_kategori ?? '-' }}</p>
                                <p class="text-[10px] text-slate-400">{{ $umkm->sektor?->nama_sektor ?? '-' }}</p>
                            </div>
                            <div class="col-span-2 mt-1">
                                <p class="text-slate-500">Tgl Daftar</p>
                                <p class="font-medium text-slate-700">{{ $umkm->tanggal_pendataan ? \Carbon\Carbon::parse($umkm->tanggal_pendataan)->format('d/m/Y') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p>Tidak ada data UMKM yang sesuai dengan filter.</p>
                    </div>
                @endforelse
            </div>
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama Usaha</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Pemilik</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kategori & Sektor</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tgl Daftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($umkms as $index => $umkm)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-slate-500">{{ $umkms->firstItem() + $index }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-slate-800">{{ $umkm->nama_usaha }}</p>
                                    <p class="text-xs text-slate-400">{{ $umkm->no_pendaftaran ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-sm text-slate-600">{{ $umkm->pemilik?->nama_lengkap ?? '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ $umkm->pemilik?->kelurahan ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-sm text-slate-600">{{ $umkm->kategori?->nama_kategori ?? '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ $umkm->sektor?->nama_sektor ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($umkm->status_verifikasi == 'terverifikasi')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            Terverifikasi
                                        </span>
                                    @elseif($umkm->status_verifikasi == 'menunggu_verifikasi')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                            Menunggu
                                        </span>
                                    @elseif($umkm->status_verifikasi == 'ditolak')
                                        <div class="flex flex-col gap-1 items-start">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                                Ditolak
                                            </span>
                                            <span class="text-[10px] text-red-600 font-medium bg-red-50 px-2 py-0.5 rounded-md border border-red-100 max-w-[200px] truncate" title="{{ $umkm->catatan_penolakan }}">
                                                Alasan: {{ $umkm->catatan_penolakan ?? 'Tidak ada' }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                            <span class="w-1.5 h-1.5 bg-slate-500 rounded-full"></span>
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500">
                                    {{ $umkm->tanggal_pendataan ? \Carbon\Carbon::parse($umkm->tanggal_pendataan)->format('d/m/Y') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                                    Tidak ada data UMKM yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($umkms->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                    {{ $umkms->links() }}
                </div>
            @endif
        </div>
