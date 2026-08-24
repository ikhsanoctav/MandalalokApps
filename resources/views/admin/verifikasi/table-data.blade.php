        <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

            <div class="block md:hidden divide-y divide-slate-100">
                @forelse($umkms as $umkm)
                    <div class="p-4 space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-slate-800">{{ $umkm->nama_usaha }}</h4>
                                <p class="text-xs text-slate-400">{{ $umkm->no_pendaftaran ?? '-' }}</p>
                            </div>
                            <div>
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                    Menunggu Verifikasi
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <p class="text-slate-500">Pemilik</p>
                                <p class="font-medium text-slate-700">{{ $umkm->pemilik?->nama_lengkap ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-slate-500">Kelurahan</p>
                                <p class="font-medium text-slate-700">{{ $umkm->pemilik?->kelurahan ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-slate-500">Tanggal Daftar</p>
                                <p class="font-medium text-slate-700">
                                    {{ $umkm->created_at ? $umkm->created_at->format('d/m/Y') : '-' }}</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-2 border-t">
                            <a href="{{ route('admin.verifikasi.show', $umkm->id_umkm) }}"
                                class="px-4 py-2 text-sm text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 transition-all font-medium flex items-center gap-2">
                                Tinjau & Verifikasi
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>Verifikasi otomatis aktif. Tidak ada antrean verifikasi UMKM.</p>
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

                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tgl Daftar</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Aksi</th>
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
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                        Menunggu Verif
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500">
                                    {{ $umkm->created_at ? $umkm->created_at->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.verifikasi.show', $umkm->id_umkm) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-md transition-all">
                                        Tinjau
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Verifikasi otomatis aktif. Tidak ada antrean verifikasi UMKM.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($umkms->hasPages())
                <div class="px-4 py-3 border-t border-slate-200">
                    {{ $umkms->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
