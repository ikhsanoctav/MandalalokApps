@php
    $umkm = $pengajuan->umkm;
    $pemilik = $umkm->pemilik ?? null;
@endphp

<div class="space-y-6">

    <div
        class="bg-gradient-to-r from-blue-500/10 to-indigo-500/10 rounded-2xl p-4 border border-blue-200/50 flex flex-wrap items-center justify-between gap-4">
        <div class="space-y-1">
            <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">ID Pengajuan</span>
            <p class="text-sm font-mono font-semibold text-slate-800">{{ $pengajuan->id_pengajuan }}</p>
        </div>
        <div class="flex gap-2">
            
            @php
                $jenisBadges = [
                    'pembiayaan' => 'bg-purple-100 text-purple-700 border-purple-200',
                    'bantuan' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'perizinan' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'lainnya' => 'bg-slate-100 text-slate-700 border-slate-200',
                ];
                $jenisClass = $jenisBadges[$pengajuan->jenis_pengajuan] ?? 'bg-slate-100 text-slate-700';
            @endphp
            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold border {{ $jenisClass }}">
                {{ ucfirst($pengajuan->jenis_pengajuan) }}
            </span>

            @php
                $statusBadges = [
                    'menunggu' => 'bg-amber-100 text-amber-700 border-amber-200',
                    'proses' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'disetujui' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'ditolak' => 'bg-red-100 text-red-700 border-red-200',
                ];
                $statusClass = $statusBadges[$pengajuan->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
            @endphp
            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold border {{ $statusClass }}">
                {{ ucfirst($pengajuan->status == 'proses' ? 'Diproses' : $pengajuan->status) }}
            </span>
        </div>
    </div>

    <div>
        <h4
            class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            Detail Permohonan
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-200/50">
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nominal
                    Bantuan/Pembiayaan</label>
                @if ($pengajuan->nominal)
                    <p class="text-xl font-black text-blue-600 mt-0.5">Rp
                        {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                @else
                    <p class="text-sm font-semibold text-slate-500 mt-0.5">Tanpa Pengajuan Nominal</p>
                @endif
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Pengajuan</label>
                <p class="text-sm font-semibold text-slate-700 mt-0.5">
                    {{ $pengajuan->tanggal_pengajuan ? $pengajuan->tanggal_pengajuan->format('d M Y') : '-' }}</p>
            </div>
            @if ($pengajuan->tanggal_disetujui)
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal
                        Disetujui</label>
                    <p class="text-sm font-semibold text-emerald-700 mt-0.5">
                        {{ $pengajuan->tanggal_disetujui->format('d M Y') }}</p>
                </div>
            @endif
            @if ($pengajuan->nama_program)
                <div class="col-span-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Program Bantuan Aktif</label>
                    <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $pengajuan->nama_program }}</p>
                </div>
            @endif
            <div class="col-span-2">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Keterangan / Keperluan
                    Bantuan</label>
                <p
                    class="text-sm text-slate-700 mt-1 bg-white p-3 rounded-md border border-slate-100 shadow-sm whitespace-pre-line">
                    {{ $pengajuan->keterangan ?? '-' }}</p>
            </div>
            @if ($pengajuan->catatan)
                <div class="col-span-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Catatan
                        Verifikator</label>
                    <p
                        class="text-sm text-slate-700 mt-1 bg-white p-3 rounded-md border border-slate-100 shadow-sm whitespace-pre-line">
                        {{ $pengajuan->catatan }}</p>
                </div>
            @endif
        </div>
    </div>

    <div>
        <h4
            class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            Dokumen Lampiran / Berkas Pengajuan
        </h4>
        @if (!empty($pengajuan->berkas) && count($pengajuan->berkas) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($pengajuan->berkas as $item)
                    <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-200 rounded-xl hover:shadow-sm transition-all">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <svg class="w-8 h-8 text-blue-550 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-700 truncate">{{ $item['nama_dokumen'] ?? 'Dokumen' }}</p>
                                <p class="text-[10px] text-slate-400 font-mono truncate">Klik unduh/lihat berkas</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            <a href="{{ Storage::url($item['file_path']) }}" target="_blank" class="p-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white rounded-2xl transition-all border border-blue-200" title="Buka Berkas">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ Storage::url($item['file_path']) }}" download class="p-1.5 bg-slate-100 hover:bg-slate-700 text-slate-700 hover:text-white rounded-2xl transition-all border border-slate-300" title="Unduh Berkas">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-4 bg-slate-50 border border-dashed border-slate-350 rounded-2xl text-center">
                <p class="text-xs text-slate-450 font-semibold">Tidak ada berkas/dokumen lampiran yang diunggah.</p>
            </div>
        @endif
    </div>

    @if ($umkm)
        <div>
            <h4
                class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
                Profil UMKM Pemohon
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-2 flex items-center gap-3">
                    @if ($umkm->foto_utama)
                        <img src="{{ Storage::url($umkm->foto_utama) }}" alt="Logo {{ $umkm->nama_usaha }}"
                            class="w-12 h-12 object-cover rounded-xl border border-slate-200 shadow-sm">
                    @endif
                    <div>
                        <p class="text-base font-bold text-slate-800 nama-usaha-value">{{ $umkm->nama_usaha }}</p>
                        <p class="text-xs text-slate-400 font-mono">No. Pendaftaran: {{ $umkm->no_pendaftaran ?? '-' }}
                        </p>
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sektor Usaha</label>
                    <p class="text-sm text-slate-700 font-semibold mt-0.5">{{ $umkm->sektor?->nama_sektor ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Bentuk Jualan</label>
                    <p class="text-sm text-slate-700 font-semibold mt-0.5">{{ $umkm->bentuk_jualan ?? '-' }}</p>
                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Perkiraan Omset</label>
                                    <p class="text-sm text-slate-700 font-semibold mt-0.5">{{ $umkm->perkiraan_omset ? 'Rp ' . number_format($umkm->perkiraan_omset, 0, ',', '.') : '-' }}</p>
                                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Skala Usaha</label>
                    <p class="text-sm mt-0.5">
                        <span
                            class="inline-flex px-2 py-0.5 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">{{ $umkm->kategori?->nama_kategori ?? '-' }}</span>
                    </p>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">No. Telepon
                        Usaha</label>
                    <p class="text-sm text-slate-700 font-semibold mt-0.5">{{ $umkm->telp_usaha ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Usaha</label>
                    <p class="text-sm mt-0.5">
                        <span
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                            Aktif
                        </span>
                    </p>
                </div>
                <div class="col-span-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Alamat Usaha</label>
                    <p class="text-sm text-slate-700 mt-0.5">{{ $umkm->alamat_usaha ?? '-' }}</p>
                </div>
            </div>
        </div>
    @endif

    @if ($pemilik)
        <div>
            <h4
                class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Profil Pemilik
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Lengkap</label>
                    <p class="text-sm text-slate-700 font-semibold mt-0.5">{{ $pemilik->nama_lengkap ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">NIK</label>
                    <p class="text-sm text-slate-700 font-mono mt-0.5">{{ $pemilik->nik ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">No. Telepon /
                        HP</label>
                    <p class="text-sm text-slate-700 mt-0.5">{{ $pemilik->no_hp ?? ($pemilik->no_telepon ?? '-') }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kelurahan</label>
                    <p class="text-sm text-slate-700 mt-0.5">{{ $pemilik->kelurahan ?? '-' }}</p>
                </div>
                <div class="col-span-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Alamat Lengkap</label>
                    <p class="text-sm text-slate-700 mt-0.5">{{ $pemilik->alamat ?? '-' }}</p>
                </div>
            </div>
        </div>
    @endif

    @if ($umkm && $umkm->pengajuan->isNotEmpty())
        <div>
            <h4
                class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Riwayat Pengajuan Bantuan & Pembiayaan UMKM
            </h4>
            <div class="overflow-hidden border border-slate-200/80 rounded-2xl">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-2.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-4 py-2.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Jenis
                            </th>
                            <th class="px-4 py-2.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                Nominal</th>
                            <th class="px-4 py-2.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Status
                            </th>
                            <th class="px-4 py-2.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($umkm->pengajuan as $historyItem)
                            @php
                                $isCurrent = $historyItem->id_pengajuan === $pengajuan->id_pengajuan;

                                $historyJenisBadges = [
                                    'pembiayaan' => 'bg-purple-100/70 text-purple-700',
                                    'bantuan' => 'bg-blue-100/70 text-blue-700',
                                    'perizinan' => 'bg-emerald-100/70 text-emerald-700',
                                    'lainnya' => 'bg-slate-100/70 text-slate-700',
                                ];
                                $historyJenisClass =
                                    $historyJenisBadges[$historyItem->jenis_pengajuan] ?? 'bg-slate-100 text-slate-700';

                                $historyStatusBadges = [
                                    'menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'proses' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'ditolak' => 'bg-rose-50 text-rose-700 border-rose-200',
                                ];
                                $historyStatusClass =
                                    $historyStatusBadges[$historyItem->status] ?? 'bg-slate-50 text-slate-700';
                            @endphp
                            <tr
                                class="{{ $isCurrent ? 'bg-blue-50/60 border-l-4 border-blue-500' : 'hover:bg-slate-50' }} transition-colors">
                                <td class="px-4 py-3 text-xs font-semibold text-slate-700">
                                    {{ $historyItem->tanggal_pengajuan ? $historyItem->tanggal_pengajuan->format('d M Y') : '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-bold {{ $historyJenisClass }}">
                                        {{ ucfirst($historyItem->jenis_pengajuan) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs font-bold text-slate-800">
                                    @if ($historyItem->nominal)
                                        Rp {{ number_format($historyItem->nominal, 0, ',', '.') }}
                                    @else
                                        <span class="text-slate-400 font-normal">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $historyStatusClass }}">
                                        {{ ucfirst($historyItem->status == 'proses' ? 'Diproses' : $historyItem->status) }}
                                    </span>
                                    @if ($isCurrent)
                                        <span
                                            class="text-[9px] font-extrabold text-blue-600 block mt-0.5 tracking-wider animate-pulse">(Sedang
                                            Ditinjau)</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-600 max-w-[200px] truncate"
                                    title="{{ $historyItem->catatan }}">
                                    {{ $historyItem->catatan ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
