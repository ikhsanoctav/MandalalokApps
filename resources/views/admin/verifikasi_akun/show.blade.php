@extends('layouts.admin')
@section('title', 'Detail Verifikasi Akun')
@section('breadcrumb', 'Verifikasi / Akun Pemilik / Detail')

@section('content')
    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-800">Detail Verifikasi Akun KTP</h2>
            <a href="{{ route(auth()->user()->hasRole('super_admin') ? 'superadmin.verifikasi_akun.index' : 'admin.verifikasi_akun.index') }}"
                class="px-4 py-2 bg-white text-slate-600 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors font-medium text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Detail Profil Kiri -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Data Pemilik -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Informasi Pemilik (Sesuai KTP)
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">NIK</p>
                            <p class="text-slate-800 font-medium font-mono">{{ $pemilik->nik ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</p>
                            <p class="text-slate-800 font-medium">{{ $pemilik->nama_lengkap }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tempat, Tanggal Lahir</p>
                            <p class="text-slate-800 font-medium">
                                {{ $pemilik->tempat_lahir ?? '-' }},
                                {{ $pemilik->tanggal_lahir ? \Carbon\Carbon::parse($pemilik->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Jenis Kelamin</p>
                            <p class="text-slate-800 font-medium">
                                @if ($pemilik->jenis_kelamin == 'L') Laki-Laki
                                @elseif($pemilik->jenis_kelamin == 'P') Perempuan
                                @else - @endif
                            </p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Alamat Domisili</p>
                            <p class="text-slate-800 font-medium leading-relaxed">
                                @php
                                    $rawAlamat = trim($pemilik->alamat ?? '');
                                    $hasRt = (bool)preg_match('/rt[\.\s]*\d+/i', $rawAlamat);
                                    $hasRw = (bool)preg_match('/rw[\.\s]*\d+/i', $rawAlamat);
                                    $hasKel = !empty($pemilik->kelurahan) && stripos($rawAlamat, $pemilik->kelurahan) !== false;
                                    
                                    $suffixParts = [];
                                    if (!$hasRt && !empty($pemilik->rt)) {
                                        $suffixParts[] = 'RT ' . sprintf('%02d', (int)preg_replace('/\D/', '', $pemilik->rt));
                                    }
                                    if (!$hasRw && !empty($pemilik->rw)) {
                                        $suffixParts[] = 'RW ' . sprintf('%02d', (int)preg_replace('/\D/', '', $pemilik->rw));
                                    }
                                    $rtRwStr = implode(' / ', $suffixParts);
                                    $kelStr = (!$hasKel && !empty($pemilik->kelurahan)) ? 'Kel. ' . $pemilik->kelurahan : '';
                                    $extraStr = implode(', ', array_filter([$rtRwStr, $kelStr]));
                                @endphp
                                {{ $rawAlamat ?: '-' }}
                                @if($extraStr)
                                    <br><span class="text-slate-500 text-xs">{{ $extraStr }}</span>
                                @endif
                                <br>
                                <span class="text-xs text-slate-500">Kecamatan: {{ $pemilik->kecamatan ?? 'Mandalajati' }}, {{ $pemilik->kota_kab ?? 'Kota Bandung' }}</span>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">No. HP / WhatsApp</p>
                            <p class="text-slate-800 font-medium">{{ $pemilik->no_hp ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Email (Terdaftar)</p>
                            <p class="text-slate-800 font-medium">{{ $user->email ?? $pemilik->email ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kanan: Aksi & KTP -->
            <div class="space-y-6">

                <!-- Pratinjau KTP -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Foto KTP
                        </h3>
                    </div>
                    <div class="p-6">
                        @if ($pemilik->foto_ktp)
                            <div class="aspect-video bg-slate-100 rounded-xl overflow-hidden mb-4 relative group cursor-pointer border border-slate-200 shadow-inner"
                                onclick="window.open('{{ route(auth()->user()->hasRole('super_admin') ? 'superadmin.verifikasi_akun.ktp' : 'admin.verifikasi_akun.ktp', $pemilik->id_pemilik) }}', '_blank')">
                                <img src="{{ route(auth()->user()->hasRole('super_admin') ? 'superadmin.verifikasi_akun.ktp' : 'admin.verifikasi_akun.ktp', $pemilik->id_pemilik) }}" 
                                    alt="Foto KTP {{ $pemilik->nama_lengkap }}" 
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                                    <span class="text-white font-medium flex items-center gap-2 bg-black/50 px-4 py-2 rounded-full">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                        Lihat Layar Penuh
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="aspect-video bg-slate-50 border border-slate-200 border-dashed rounded-xl flex flex-col items-center justify-center text-slate-400 p-6 text-center">
                                <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm font-medium">Foto KTP Tidak Tersedia</p>
                                <p class="text-xs mt-1">Pemilik belum/gagal mengunggah foto KTP.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Panel Aksi -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Aksi Verifikasi
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if ($pemilik->status_verifikasi_ktp == 'terverifikasi')
                            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex gap-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <p class="text-emerald-800 font-bold mb-1">Sudah Diverifikasi</p>
                                    <p class="text-emerald-600 text-sm leading-relaxed">Akun ini telah berhasil diverifikasi dan bisa mendaftarkan UMKM.</p>
                                </div>
                            </div>
                        @elseif ($pemilik->status_verifikasi_ktp == 'ditolak')
                            <div class="p-4 bg-red-50 border border-red-200 rounded-xl flex gap-3 mb-4">
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </div>
                                <div>
                                    <p class="text-red-800 font-bold mb-1">Akun Ditolak</p>
                                    <p class="text-red-600 text-sm leading-relaxed">Akun ini ditolak dengan alasan: <strong>{{ $pemilik->catatan }}</strong></p>
                                </div>
                            </div>
                        @else
                            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl mb-4">
                                <p class="text-amber-800 font-medium text-sm flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Verifikasi otomatis aktif untuk akun ini.
                                </p>
                            </div>

                            <form id="verify-form" action="{{ route(auth()->user()->hasRole('super_admin') ? 'superadmin.verifikasi_akun.verify' : 'admin.verifikasi_akun.verify', $pemilik->id_pemilik) }}" method="POST" class="mb-3">
                                @csrf
                                <button type="button" x-data @click="$dispatch('open-confirm-modal', {
                                    title: 'Verifikasi Akun',
                                    message: 'Apakah Anda yakin ingin menyetujui akun ini?',
                                    confirmText: 'Ya, Setujui',
                                    action: () => {
                                        document.getElementById('verify-form').submit();
                                        return new Promise(() => {}); // Prevent immediate modal close before redirect
                                    }
                                })" class="w-full flex items-center justify-center gap-2 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Setujui (Verify)
                                </button>
                            </form>

                            <button type="button" onclick="document.getElementById('rejectForm').classList.toggle('hidden')" class="w-full flex items-center justify-center gap-2 py-3 bg-white border border-red-200 text-red-600 hover:bg-red-50 rounded-xl font-bold transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Tolak Verifikasi
                            </button>

                            <form id="rejectForm" action="{{ route(auth()->user()->hasRole('super_admin') ? 'superadmin.verifikasi_akun.reject' : 'admin.verifikasi_akun.reject', $pemilik->id_pemilik) }}" method="POST" class="hidden mt-4 bg-slate-50 p-4 rounded-xl border border-slate-200 animate-fade-in">
                                @csrf
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Alasan Penolakan</label>
                                <textarea name="reason" rows="3" required class="w-full rounded-2xl border-slate-300 focus:border-red-500 focus:ring-red-500 text-sm mb-3" placeholder="Masukkan alasan penolakan (misal: Foto KTP buram, NIK tidak sesuai)..."></textarea>
                                <button type="submit" class="w-full py-2 bg-red-600 hover:bg-red-700 text-white rounded-2xl font-semibold text-sm transition-colors">Kirim Penolakan</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
