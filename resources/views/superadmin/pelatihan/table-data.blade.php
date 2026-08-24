<div class="bg-white/90 backdrop-blur-sm shadow-sm rounded-3xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="p-4 font-semibold">Judul Pelatihan</th>
                    <th class="p-4 font-semibold">Jadwal</th>
                    <th class="p-4 font-semibold">Lokasi</th>
                    <th class="p-4 font-semibold text-center">Kuota & Pendaftar</th>
                    <th class="p-4 font-semibold text-center">Status</th>
                    <th class="p-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($pelatihans as $p)
                <tr>
                    <td class="p-4 align-top">
                        <div class="font-bold text-slate-800">{{ $p->judul }}</div>
                        @if(!empty($p->syarat_dokumen) && is_array($p->syarat_dokumen) && count($p->syarat_dokumen) > 0)
                        <span class="inline-flex items-center gap-1 mt-1 text-[11px] font-medium px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 border border-amber-200">
                            <i class="fa-solid fa-file-invoice"></i> Wajib Dokumen
                        </span>
                        @endif
                    </td>
                    <td class="p-4 align-top">
                        <div class="font-medium text-slate-700">{{ $p->tanggal_mulai->format('d M Y') }}</div>
                        <div class="text-slate-700 text-xs">{{ $p->tanggal_mulai->format('H:i') }} - {{ $p->tanggal_selesai->format('H:i') }}</div>
                    </td>
                    <td class="p-4 align-top text-slate-600">
                        {{ $p->lokasi }}
                    </td>
                    <td class="p-4 align-top text-center">
                        <div class="inline-flex flex-col items-center">
                            <span class="font-bold text-slate-800">{{ $p->peserta()->count() }} / {{ $p->kuota }}</span>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider">Peserta</span>
                        </div>
                    </td>
                    <td class="p-4 align-top text-center">
                        @if($p->status == 'published')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Aktif</span>
                        @elseif($p->status == 'draft')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">Draft</span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Selesai</span>
                        @endif
                    </td>
                    <td class="p-4 align-top text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('superadmin.pelatihan.show', $p->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors">
                                <i class="fa-solid fa-users"></i> Lihat Peserta
                            </a>
                            <a href="{{ route('superadmin.pelatihan.edit', $p->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-amber-700 bg-amber-100 rounded-lg hover:bg-amber-200 transition-colors">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-700">
                        <div class="mb-2"><i class="fa-regular fa-calendar-xmark text-3xl opacity-50"></i></div>
                        Belum ada data pelatihan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
