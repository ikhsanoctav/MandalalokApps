                    <div class="block md:hidden divide-y divide-slate-100">
                        @forelse($umkmSaya as $umkm)
                            <article class="p-4 space-y-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="font-mono text-xs font-semibold text-slate-700">{{ $umkm->no_pendaftaran ?? '-' }}</p>
                                        <h3 class="text-base font-bold text-slate-800 leading-snug">{{ $umkm->nama_usaha }}</h3>
                                        <p class="text-xs text-slate-700 mt-1">{{ $umkm->kategori?->nama_kategori ?? '-' }}</p>
                                    </div>
                                    <div class="shrink-0">
                                        @include('petugas.umkm._status-badge', ['status' => $umkm->status_verifikasi])
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <p class="text-xs font-semibold text-slate-400 uppercase">Pemilik</p>
                                        <p class="font-medium text-slate-700">{{ $umkm->pemilik?->nama_lengkap ?? '-' }}</p>
                                        <p class="text-xs text-slate-700">{{ $umkm->pemilik?->nik_masked ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-400 uppercase">Tgl Input</p>
                                        <p class="font-medium text-slate-700">{{ $umkm->tanggal_pendataan ? $umkm->tanggal_pendataan->format('d M Y') : '-' }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-2 pt-1">
                                    <a href="{{ route('operator.umkm.show', $umkm->id_umkm) }}" class="inline-flex items-center justify-center px-3 py-2.5 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold">
                                        Detail
                                    </a>
                                    @if($umkm->status_verifikasi !== 'terverifikasi')
                                        <a href="{{ route('operator.umkm.edit', $umkm->id_umkm) }}" class="inline-flex items-center justify-center px-3 py-2.5 bg-amber-50 text-amber-700 rounded-lg text-sm font-semibold">
                                            Edit
                                        </a>
                                        <a href="{{ route('operator.umkm.upload-foto', $umkm->id_umkm) }}" class="inline-flex items-center justify-center px-3 py-2.5 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-semibold">
                                            Foto
                                        </a>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <div class="py-12 px-4 text-center">
                                <p class="text-slate-700 font-medium">Belum ada data UMKM yang diinput.</p>
                                <a href="{{ route('operator.umkm.create') }}" class="text-blue-600 text-sm hover:underline mt-1 inline-block">Mulai Input Data Sekarang</a>
                            </div>
                        @endforelse
                    </div>

                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="py-3 px-6 text-xs font-bold text-slate-700 uppercase tracking-wider">No. Pendaftaran</th>
                                    <th class="py-3 px-6 text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Usaha</th>
                                    <th class="py-3 px-6 text-xs font-bold text-slate-700 uppercase tracking-wider">Pemilik</th>
                                    <th class="py-3 px-6 text-xs font-bold text-slate-700 uppercase tracking-wider">Tgl Input</th>
                                    <th class="py-3 px-6 text-xs font-bold text-slate-700 uppercase tracking-wider">Status</th>
                                    <th class="py-3 px-6 text-xs font-bold text-slate-700 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($umkmSaya as $umkm)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="py-4 px-6">
                                            <span class="font-mono text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-1 rounded-md">{{ $umkm->no_pendaftaran ?? '-' }}</span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <p class="font-bold text-sm text-slate-800">{{ $umkm->nama_usaha }}</p>
                                            <p class="text-xs text-slate-700 mt-0.5">{{ $umkm->kategori?->nama_kategori ?? '-' }}</p>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-sm text-slate-700">{{ $umkm->pemilik?->nama_lengkap ?? '-' }}</div>
                                            <div class="text-xs text-slate-700 mt-0.5">{{ $umkm->pemilik?->nik_masked ?? '-' }}</div>
                                        </td>
                                        <td class="py-4 px-6 text-sm text-slate-600">
                                            {{ $umkm->tanggal_pendataan ? $umkm->tanggal_pendataan->format('d M Y') : '-' }}
                                        </td>
                                        <td class="py-4 px-6">
                                            @include('petugas.umkm._status-badge', ['status' => $umkm->status_verifikasi])
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <a href="{{ route('operator.umkm.show', $umkm->id_umkm) }}" class="inline-flex p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-2xl transition-colors tooltip" data-tip="Lihat Detail">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </a>
                                                @if($umkm->status_verifikasi !== 'terverifikasi')
                                                    <a href="{{ route('operator.umkm.edit', $umkm->id_umkm) }}" class="inline-flex p-2 bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white rounded-2xl transition-colors tooltip" data-tip="Edit Data">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </a>
                                                    <a href="{{ route('operator.umkm.upload-foto', $umkm->id_umkm) }}" class="inline-flex p-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-2xl transition-colors tooltip" data-tip="Upload Foto">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 px-6 text-center">
                                            <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            </div>
                                            <p class="text-slate-700 font-medium">Belum ada data UMKM yang diinput.</p>
                                            <a href="{{ route('operator.umkm.create') }}" class="text-blue-600 text-sm hover:underline mt-1 inline-block">Mulai Input Data Sekarang</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($umkmSaya->hasPages())
                        <div class="p-4 border-t border-slate-200">
                            {{ $umkmSaya->appends(request()->except('saya_page'))->links() }}
                        </div>
                    @endif
