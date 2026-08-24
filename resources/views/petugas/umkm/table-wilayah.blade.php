                        <div class="block md:hidden divide-y divide-slate-100">
                            @foreach ($umkmWilayah as $item)
                                <article class="p-4 space-y-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="font-mono text-xs font-semibold text-slate-700">{{ $item->no_pendaftaran ?? '-' }}</p>
                                            <h3 class="text-base font-bold text-slate-800 leading-snug">{{ $item->nama_usaha }}</h3>
                                            <p class="text-xs text-slate-700 mt-1">{{ $item->alamat_usaha }}</p>
                                        </div>
                                        <div class="shrink-0">
                                            @include('petugas.umkm._status-badge', ['status' => $item->status_verifikasi])
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                        <div>
                                            <p class="text-xs font-semibold text-slate-400 uppercase">Pemilik</p>
                                            <p class="font-medium text-slate-700">{{ $item->pemilik?->nama_lengkap ?? '-' }}</p>
                                            <p class="text-xs text-slate-700">RT {{ $item->pemilik?->rt ?? '-' }} / RW {{ $item->pemilik?->rw ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-slate-400 uppercase">Kategori</p>
                                            <p class="font-medium text-slate-700">{{ $item->kategori?->nama_kategori ?? '-' }}</p>
                                            <p class="text-xs text-slate-700">{{ $item->sektor?->nama_sektor ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-2 pt-1">
                                        <a href="{{ route('operator.umkm.show', $item->id_umkm) }}" class="inline-flex items-center justify-center px-3 py-2.5 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold">
                                            Detail
                                        </a>
                                        @if($item->id_petugas === Auth::id() && $item->status_verifikasi !== 'terverifikasi')
                                            <a href="{{ route('operator.umkm.edit', $item->id_umkm) }}" class="inline-flex items-center justify-center px-3 py-2.5 bg-amber-50 text-amber-700 rounded-lg text-sm font-semibold">
                                                Edit
                                            </a>
                                            <a href="{{ route('operator.umkm.upload-foto', $item->id_umkm) }}" class="inline-flex items-center justify-center px-3 py-2.5 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-semibold">
                                                Foto
                                            </a>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-[#0f2e5c] text-white text-xs uppercase tracking-wider font-semibold">
                                        <th class="px-6 py-3">No. Pendaftaran</th>
                                        <th class="px-6 py-3">Nama Usaha</th>
                                        <th class="px-6 py-3">Pemilik</th>
                                        <th class="px-6 py-3">Kategori</th>
                                        <th class="px-6 py-3">Status</th>
                                        <th class="px-6 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($umkmWilayah as $item)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <span class="font-mono text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-1 rounded-md">{{ $item->no_pendaftaran ?? '-' }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="font-bold text-slate-800">{{ $item->nama_usaha }}</p>
                                                <p class="text-xs text-slate-700 truncate max-w-[200px]">{{ $item->alamat_usaha }}</p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="font-medium text-slate-700">{{ $item->pemilik?->nama_lengkap ?? '-' }}</p>
                                                <p class="text-xs text-slate-700">RT {{ $item->pemilik?->rt ?? '-' }} / RW {{ $item->pemilik?->rw ?? '-' }}</p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 mb-1">{{ $item->kategori?->nama_kategori ?? '-' }}</span>
                                                <br>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-purple-50 text-purple-700">{{ $item->sektor?->nama_sektor ?? '-' }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                @include('petugas.umkm._status-badge', ['status' => $item->status_verifikasi])
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <a href="{{ route('operator.umkm.show', $item->id_umkm) }}" class="inline-flex p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-2xl transition-colors tooltip" data-tip="Lihat Detail">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    </a>
                                                    @if($item->id_petugas === Auth::id() && $item->status_verifikasi !== 'terverifikasi')
                                                        <a href="{{ route('operator.umkm.edit', $item->id_umkm) }}" class="inline-flex p-2 bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white rounded-2xl transition-colors tooltip" data-tip="Edit Data">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        </a>
                                                        <a href="{{ route('operator.umkm.upload-foto', $item->id_umkm) }}" class="inline-flex p-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-2xl transition-colors tooltip" data-tip="Upload Foto">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($umkmWilayah->hasPages())
                            <div class="p-4 border-t border-slate-200">
                                {{ $umkmWilayah->appends(request()->except('wilayah_page'))->links() }}
                            </div>
                        @endif
