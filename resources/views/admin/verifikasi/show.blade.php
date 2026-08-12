@extends('layouts.admin')
@section('title', 'Detail UMKM - ' . ($umkm->nama_usaha ?? 'UMKM'))
@section('breadcrumb', 'Verifikasi Lapangan / Data UMKM / Detail')

@section('content')
    <div class="space-y-4 md:space-y-6">

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.verifikasi.index') }}"
                class="px-4 py-2 text-sm text-slate-600 bg-slate-100 rounded-md hover:bg-slate-200 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali
            </a>

            <a href="{{ route('admin.umkm.edit', $umkm->id_umkm) }}"
                class="px-4 py-2 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                </svg>
                Edit
            </a>
            @if ($umkm->status_verifikasi == 'terkirim')
                <button onclick="openVerifyModal()"
                    class="px-4 py-2 text-sm text-white bg-emerald-600 rounded-md hover:bg-emerald-700 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Verifikasi
                </button>
                <button onclick="openRejectModal()"
                    class="px-4 py-2 text-sm text-white bg-red-600 rounded-md hover:bg-red-700 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                    Tolak
                </button>
            @endif
            <button onclick="window.print()"
                class="px-4 py-2 text-sm text-slate-600 bg-slate-100 rounded-md hover:bg-slate-200 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Print
            </button>
        </div>

        @if (session('toast'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const toast = @json(session('toast'));
                    if (window.showToast) {
                        window.showToast(toast.type, toast.title, toast.message);
                    }
                });
            </script>
        @endif

        <div
            class="rounded-2xl p-4 
        @if ($umkm->status_verifikasi == 'terverifikasi') bg-emerald-50 border border-emerald-200
        @elseif($umkm->status_verifikasi == 'terkirim') bg-amber-50 border border-amber-200
        @elseif($umkm->status_verifikasi == 'ditolak') bg-red-50 border border-red-200
        @else bg-slate-50 border border-slate-200 @endif">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-full 
                @if ($umkm->status_verifikasi == 'terverifikasi') bg-emerald-200
                @elseif($umkm->status_verifikasi == 'terkirim') bg-amber-200
                @elseif($umkm->status_verifikasi == 'ditolak') bg-red-200
                @else bg-slate-200 @endif flex items-center justify-center">
                    @if ($umkm->status_verifikasi == 'terverifikasi')
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @elseif($umkm->status_verifikasi == 'terkirim')
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @elseif($umkm->status_verifikasi == 'ditolak')
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                    @endif
                </div>
                <div>
                    @if ($umkm->status_verifikasi == 'terverifikasi')
                        <p class="font-semibold text-emerald-800">UMKM Terverifikasi</p>
                        <p class="text-sm text-emerald-700">UMKM ini telah diverifikasi pada
                            {{ ($umkm->tanggal_verifikasi ?? $umkm->updated_at ?? $umkm->created_at) ? \Carbon\Carbon::parse($umkm->tanggal_verifikasi ?? $umkm->updated_at ?? $umkm->created_at)->format('d/m/Y H:i') : '-' }}
                        </p>
                    @elseif($umkm->status_verifikasi == 'terkirim')
                        <p class="font-semibold text-amber-800">Menunggu Verifikasi</p>
                        <p class="text-sm text-amber-700">UMKM ini perlu segera diverifikasi</p>
                    @elseif($umkm->status_verifikasi == 'ditolak')
                        <p class="font-semibold text-red-800">Ditolak</p>
                        <p class="text-sm text-red-700">{{ $umkm->catatan_penolakan ?? 'Tidak ada catatan' }}</p>
                    @else
                        <p class="font-semibold text-slate-800">Draft</p>
                        <p class="text-sm text-slate-600">UMKM masih dalam bentuk draft</p>
                    @endif
                </div>
            </div>
        </div>

        @if($umkm->latestVerifikasiLapangan)
            <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-5 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-indigo-900 mb-1">Rekomendasi Verifikasi Lapangan</h3>
                        <p class="text-sm text-indigo-700 mb-3">Operator lapangan telah melakukan kunjungan ke lokasi UMKM ini pada <strong>{{ \Carbon\Carbon::parse($umkm->latestVerifikasiLapangan->tanggal_kunjungan)->format('d F Y') }}</strong>.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white/60 rounded-xl p-3 border border-indigo-100">
                                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-1">Kondisi Lapangan</p>
                                @php
                                    $kondisiBadge = [
                                        'sesuai' => 'bg-emerald-100 text-emerald-700',
                                        'tidak_sesuai' => 'bg-red-100 text-red-700',
                                        'tutup_sementara' => 'bg-amber-100 text-amber-700',
                                        'tidak_ditemukan' => 'bg-slate-200 text-slate-700'
                                    ][$umkm->latestVerifikasiLapangan->kondisi_usaha] ?? 'bg-slate-100 text-slate-600';
                                    
                                    $kondisiText = [
                                        'sesuai' => 'Sesuai Data',
                                        'tidak_sesuai' => 'Tidak Sesuai Data',
                                        'tutup_sementara' => 'Tutup Sementara',
                                        'tidak_ditemukan' => 'Tidak Ditemukan'
                                    ][$umkm->latestVerifikasiLapangan->kondisi_usaha] ?? $umkm->latestVerifikasiLapangan->kondisi_usaha;
                                @endphp
                                <span class="inline-flex px-2 py-1 rounded-md text-xs font-bold {{ $kondisiBadge }}">{{ $kondisiText }}</span>
                            </div>
                            <div class="bg-white/60 rounded-xl p-3 border border-indigo-100">
                                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-1">Petugas Pemeriksa</p>
                                <p class="text-sm font-semibold text-indigo-900">{{ $umkm->latestVerifikasiLapangan->petugas?->name ?? 'Petugas' }}</p>
                            </div>
                        </div>
                        
                        @if($umkm->latestVerifikasiLapangan->catatan_kunjungan)
                            <div class="mt-4 bg-white/60 rounded-xl p-3 border border-indigo-100">
                                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-1">Catatan Petugas</p>
                                <p class="text-sm text-indigo-900">{{ $umkm->latestVerifikasiLapangan->catatan_kunjungan }}</p>
                            </div>
                        @endif

                        @php
                            $fotoKunjungan = $umkm->latestVerifikasiLapangan->foto_kunjungan;
                            if (is_string($fotoKunjungan)) {
                                $fotoKunjungan = json_decode($fotoKunjungan, true);
                            }
                        @endphp

                        @if(!empty($fotoKunjungan) && is_array($fotoKunjungan))
                            <div class="mt-4 bg-white/60 rounded-xl p-3 border border-indigo-100">
                                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-2">Foto Survei Lapangan</p>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($fotoKunjungan as $foto)
                                        <div class="relative group">
                                            <img src="{{ Storage::url($foto) }}" alt="Foto Kunjungan" 
                                                class="w-24 h-24 object-cover rounded-2xl border border-indigo-200 shadow-sm cursor-pointer hover:scale-105 transition-transform duration-300"
                                                onclick="openImageModal('{{ Storage::url($foto) }}')">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div
            class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-200/60 overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300">
            <div
                class="px-6 py-5 border-b border-slate-100/60 bg-gradient-to-r from-slate-50/80 to-white/80 backdrop-blur-md">
                <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    Foto UMKM
                </h3>
            </div>
            <div class="p-5">
                @if ($umkm->foto_utama || !empty($umkm->foto_gallery))
                    <div class="space-y-6">
                        @if ($umkm->foto_utama)
                            <div>
                                <label class="text-xs text-slate-500 uppercase tracking-wider block mb-2">Foto Utama</label>
                                <div class="relative inline-block">
                                    <img src="{{ Storage::url($umkm->foto_utama) }}"
                                        alt="Foto Utama {{ $umkm->nama_usaha }}"
                                        class="w-48 h-48 object-cover rounded-xl border-2 border-pink-200 shadow-md hover:scale-105 transition-transform duration-300 cursor-pointer"
                                        onclick="openImageModal('{{ Storage::url($umkm->foto_utama) }}')">
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8 bg-slate-50 rounded-xl">
                                <svg class="w-16 h-16 mx-auto text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <p class="text-slate-400 mt-2">Belum ada foto utama</p>
                            </div>
                        @endif

                        @if ($umkm->foto_gallery)
                            @php $galleries = is_string($umkm->foto_gallery) ? json_decode($umkm->foto_gallery, true) : (array) $umkm->foto_gallery; @endphp
                            @if (is_array($galleries) && count($galleries) > 0)
                                <div>
                                    <label class="text-xs text-slate-500 uppercase tracking-wider block mb-2">Gallery
                                        Foto</label>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach ($galleries as $gallery)
                                            <div class="relative group">
                                                <img src="{{ Storage::url($gallery) }}"
                                                    alt="Gallery {{ $umkm->nama_usaha }}"
                                                    class="w-32 h-32 object-cover rounded-xl border border-pink-200 shadow-sm hover:scale-105 transition-transform duration-300 cursor-pointer"
                                                    onclick="openImageModal('{{ Storage::url($gallery) }}')">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                @else
                    <div class="text-center py-12 bg-slate-50 rounded-xl">
                        <svg class="w-20 h-20 mx-auto text-slate-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <p class="text-slate-400 mt-2">Belum ada foto UMKM</p>
                        <p class="text-xs text-slate-400 mt-1">Silakan edit UMKM untuk menambahkan foto</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="space-y-6">
                <div
                    class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-200/60 overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300">
                    <div
                        class="px-6 py-5 border-b border-slate-100/60 bg-gradient-to-r from-slate-50/80 to-white/80 backdrop-blur-md">
                        <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Data Pemilik
                        </h3>
                    </div>
                    <div class="p-5 space-y-3">
                        <div
                            class="flex justify-between items-center p-3 rounded-xl hover:bg-slate-50/80 transition-colors border-b border-slate-100/50 last:border-0">
                            <span class="text-sm text-slate-500">Nama Lengkap</span>
                            <span
                                class="text-sm font-medium text-slate-800">{{ $umkm->pemilik?->nama_lengkap ?? '-' }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center p-3 rounded-xl hover:bg-slate-50/80 transition-colors border-b border-slate-100/50 last:border-0">
                            <span class="text-sm text-slate-500">NIK</span>
                            <span class="text-sm font-mono text-slate-800">{{ $umkm->pemilik?->nik ?? '-' }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center p-3 rounded-xl hover:bg-slate-50/80 transition-colors border-b border-slate-100/50 last:border-0">
                            <span class="text-sm text-slate-500">No. Telepon</span>
                            <span
                                class="text-sm text-slate-800">{{ $umkm->pemilik?->no_telepon ?? ($umkm->pemilik?->no_hp ?? '-') }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center p-3 rounded-xl hover:bg-slate-50/80 transition-colors border-b border-slate-100/50 last:border-0">
                            <span class="text-sm text-slate-500">Email</span>
                            <span class="text-sm text-slate-800">{{ $umkm->pemilik?->email ?? '-' }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center p-3 rounded-xl hover:bg-slate-50/80 transition-colors border-b border-slate-100/50 last:border-0">
                            <span class="text-sm text-slate-500">Kelurahan</span>
                            <span class="text-sm text-slate-800">{{ $umkm->pemilik?->kelurahan ?? '-' }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center p-3 rounded-xl hover:bg-slate-50/80 transition-colors border-b border-slate-100/50 last:border-0">
                            <span class="text-sm text-slate-500">RW / RT</span>
                            <span class="text-sm text-slate-800">{{ $umkm->pemilik?->rw ?? '-' }} /
                                {{ $umkm->pemilik?->rt ?? '-' }}</span>
                        </div>
                        <div class="py-2">
                            <span class="text-sm text-slate-500 block mb-1">Alamat</span>
                            <p class="text-sm text-slate-800">{{ $umkm->pemilik?->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                
                <div
                    class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-200/60 overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300">
                    <div
                        class="px-6 py-5 border-b border-slate-100/60 bg-gradient-to-r from-slate-50/80 to-white/80 backdrop-blur-md">
                        <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            Informasi UMKM
                        </h3>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300">
                                <p class="text-xs text-slate-500">Nama Usaha</p>
                                <p class="text-base font-semibold text-slate-800 mt-1">{{ $umkm->nama_usaha ?? '-' }}</p>
                            </div>
                            <div
                                class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300">
                                <p class="text-xs text-slate-500">No. Pendaftaran</p>
                                <p class="text-sm font-mono text-slate-800 mt-1">{{ $umkm->no_pendaftaran ?? '-' }}</p>
                            </div>
                            <div
                                class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300">
                                <p class="text-xs text-slate-500">Skala Usaha</p>
                                <p class="mt-1">
                                    <span
                                        class="inline-flex px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-700">
                                    </span>
                                </p>
                            </div>
                            <div
                                class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300">
                                <p class="text-xs text-slate-500">Sektor Usaha</p>
                                <p class="text-sm text-slate-800 mt-1">{{ $umkm->sektor?->nama_sektor ?? '-' }}</p>
                            </div>
                            <div
                                class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300">
                                <p class="text-xs text-slate-500">Bentuk Jualan</p>
                                <p class="text-sm text-slate-800 mt-1">{{ $umkm->bentuk_jualan ?? '-' }}</p>
                            </div>
                            <div
                                class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300">
                                <p class="text-xs text-slate-500">Status Usaha</p>
                                <p class="mt-1">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $umkm->status_usaha == 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full {{ $umkm->status_usaha == 'aktif' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                        {{ ucfirst($umkm->status_usaha ?? '-') }}
                                    </span>
                                </p>
                            </div>
                            <div
                                class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300">
                                <p class="text-xs text-slate-500">Tahun Berdiri</p>
                                <p class="text-sm text-slate-800 mt-1">{{ $umkm->tahun_berdiri ?? '-' }}</p>
                            </div>
                            <div
                                class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300">
                                <p class="text-xs text-slate-500">Email Usaha</p>
                                <p class="text-sm text-slate-800 mt-1">{{ $umkm->email_usaha ?? '-' }}</p>
                            </div>
                            <div
                                class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300">
                                <p class="text-xs text-slate-500">Telepon Usaha</p>
                                <p class="text-sm text-slate-800 mt-1">{{ $umkm->telp_usaha ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-200/60 overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300">
                    <div
                        class="px-6 py-5 border-b border-slate-100/60 bg-gradient-to-r from-slate-50/80 to-white/80 backdrop-blur-md">
                        <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Lokasi & Deskripsi
                        </h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div
                            class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300">
                            <p class="text-xs text-slate-500 mb-1">Alamat Usaha</p>
                            <p class="text-sm text-slate-800">{{ $umkm->alamat_usaha ?? '-' }}</p>
                        </div>
                        <div
                            class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300">
                            <p class="text-xs text-slate-500 mb-1">Deskripsi Usaha</p>
                            <p class="text-sm text-slate-800">{{ $umkm->deskripsi ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-200/60 overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300">
                    <div
                        class="px-6 py-5 border-b border-slate-100/60 bg-gradient-to-r from-slate-50/80 to-white/80 backdrop-blur-md">
                        <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            Tenaga Kerja
                        </h3>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-slate-50 rounded-xl">
                                <p class="text-2xl font-bold text-blue-600">{{ $umkm->jumlah_tenaga_kerja ?? 0 }}</p>
                                <p class="text-xs text-slate-500 mt-1">Total</p>
                            </div>
                            <div class="text-center p-4 bg-slate-50 rounded-xl">
                                <p class="text-2xl font-bold text-blue-600">{{ $umkm->tenaga_kerja_laki ?? 0 }}</p>
                                <p class="text-xs text-slate-500 mt-1">Laki-laki</p>
                            </div>
                            <div class="text-center p-4 bg-slate-50 rounded-xl">
                                <p class="text-2xl font-bold text-blue-600">{{ $umkm->tenaga_kerja_perempuan ?? 0 }}</p>
                                <p class="text-xs text-slate-500 mt-1">Perempuan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                        <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Riwayat Bantuan / Pengajuan
                        </h3>
                    </div>
                    <div class="p-5">
                        @if (!empty($umkm->riwayat_bantuan))
                            <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $umkm->riwayat_bantuan }}</p>
                        @else
                            <p class="text-sm text-slate-500 italic">Belum ada riwayat bantuan yang diajukan/diterima.</p>
                        @endif
                    </div>
                </div>

                <div
                    class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-200/60 overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300">
                    <div
                        class="px-6 py-5 border-b border-slate-100/60 bg-gradient-to-r from-slate-50/80 to-white/80 backdrop-blur-md">
                        <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 12h14M12 5l7 7-7 7"></path>
                            </svg>
                            Media Sosial
                        </h3>
                    </div>
                    <div class="p-5">
                        @php
                            $mediaSosial = [];
                            if (!empty($umkm->media_sosial)) {
                                if (is_string($umkm->media_sosial)) {
                                    $mediaSosial = json_decode($umkm->media_sosial, true) ?: [];
                                } elseif (is_array($umkm->media_sosial)) {
                                    $mediaSosial = $umkm->media_sosial;
                                }
                            }
                        @endphp
                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-slate-500">Facebook</p>
                                    <p class="text-xs text-slate-700 truncate">{{ $mediaSosial['facebook'] ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                                <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-slate-500">Instagram</p>
                                    <p class="text-xs text-slate-700 truncate">{{ $mediaSosial['instagram'] ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-200/60 overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300">
                    <div
                        class="px-6 py-5 border-b border-slate-100/60 bg-gradient-to-r from-slate-50/80 to-white/80 backdrop-blur-md">
                        <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Perizinan
                        </h3>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300">
                                <p class="text-xs text-slate-500">NIB / Izin Usaha</p>
                                <p class="text-sm text-slate-800 mt-1">{{ $umkm->no_izin_usaha ?? '-' }}</p>
                            </div>
                            <div
                                class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300">
                                <p class="text-xs text-slate-500">Jenis Izin</p>
                                <p class="text-sm text-slate-800 mt-1">{{ $umkm->jenis_izin ?? '-' }}</p>
                            </div>
                            <div
                                class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300">
                                <p class="text-xs text-slate-500">NPWP</p>
                                <p class="text-sm text-slate-800 mt-1">{{ $umkm->npwp_usaha ?? '-' }}</p>
                            </div>
                            <div
                                class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300">
                                <p class="text-xs text-slate-500">Website</p>
                                <p class="text-sm text-slate-800 mt-1">{{ $umkm->website ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-200/60 overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300">
                    <div
                        class="px-6 py-5 border-b border-slate-100/60 bg-gradient-to-r from-slate-50/80 to-white/80 backdrop-blur-md">
                        <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informasi Tambahan
                        </h3>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div
                                class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300 text-center">
                                <p class="text-xs text-slate-500">Tanggal Pendataan</p>
                                <p class="text-sm font-medium text-slate-800 mt-1">
                                    {{ $umkm->tanggal_pendataan ? \Carbon\Carbon::parse($umkm->tanggal_pendataan)->format('d/m/Y') : '-' }}
                                </p>
                            </div>
                            <div
                                class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300 text-center">
                                <p class="text-xs text-slate-500">Petugas Pendata</p>
                                <p class="text-sm font-medium text-slate-800 mt-1">
                                    {{ $umkm->petugas ? $umkm->petugas?->name : $umkm->id_petugas ?? '-' }}</p>
                            </div>
                            <div
                                class="bg-white/60 backdrop-blur-md rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:bg-white/80 transition-all duration-300 text-center">
                                <p class="text-xs text-slate-500">Tanggal Daftar</p>
                                <p class="text-sm font-medium text-slate-800 mt-1">
                                    {{ $umkm->created_at ? $umkm->created_at->format('d/m/Y H:i') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-200/60 overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300">
            <div
                class="px-6 py-5 border-b border-slate-100/60 bg-gradient-to-r from-slate-50/80 to-white/80 backdrop-blur-md">
                <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Riwayat Pengajuan Bantuan & Pembiayaan UMKM
                </h3>
            </div>
            <div class="p-5">
                @if ($umkm->pengajuan->isNotEmpty())
                    <div class="overflow-x-auto border border-slate-200/80 rounded-2xl">
                        <table class="w-full text-left border-collapse min-w-[700px]">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                        Tanggal Pengajuan</th>
                                    <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                        Jenis Pengajuan</th>
                                    <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                        Nominal Bantuan</th>
                                    <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                        Status Verifikasi</th>
                                    <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                        Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($umkm->pengajuan as $historyItem)
                                    @php
                                        $historyJenisBadges = [
                                            'pembiayaan' => 'bg-purple-100 text-purple-700 border-purple-200',
                                            'bantuan' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'perizinan' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'lainnya' => 'bg-slate-100 text-slate-700 border-slate-200',
                                        ];
                                        $historyJenisClass =
                                            $historyJenisBadges[$historyItem->jenis_pengajuan] ??
                                            'bg-slate-100 text-slate-700';

                                        $historyStatusBadges = [
                                            'menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'proses' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'ditolak' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        ];
                                        $historyStatusClass =
                                            $historyStatusBadges[$historyItem->status] ?? 'bg-slate-50 text-slate-700';
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-5 py-4 text-sm font-semibold text-slate-700">
                                            {{ $historyItem->tanggal_pengajuan ? $historyItem->tanggal_pengajuan->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-5 py-4">
                                            <span
                                                class="inline-flex px-2.5 py-0.5 rounded-xl text-[10px] font-bold border {{ $historyJenisClass }}">
                                                {{ ucfirst($historyItem->jenis_pengajuan) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-sm font-bold text-slate-800">
                                            @if ($historyItem->nominal)
                                                Rp {{ number_format($historyItem->nominal, 0, ',', '.') }}
                                            @else
                                                <span class="text-slate-400 font-normal">-</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4">
                                            <span
                                                class="inline-flex px-2.5 py-0.5 rounded-xl text-[10px] font-bold border {{ $historyStatusClass }}">
                                                {{ ucfirst($historyItem->status == 'proses' ? 'Diproses' : $historyItem->status) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-sm text-slate-600 max-w-[280px] truncate"
                                            title="{{ $historyItem->catatan }}">
                                            {{ $historyItem->catatan ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div
                        class="text-center py-8 bg-slate-50 rounded-2xl border border-dashed border-slate-300/80 flex flex-col items-center justify-center space-y-2">
                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <div class="space-y-0.5">
                            <p class="text-sm font-bold text-slate-650">Belum Ada Riwayat Pengajuan</p>
                            <p class="text-xs text-slate-400">Pelaku UMKM ini belum pernah mengajukan bantuan, pembiayaan,
                                atau perizinan.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if ($umkm->status_verifikasi == 'ditolak' && $umkm->catatan_penolakan)
            <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-2xl border border-red-200 p-5">
                <div class="flex gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-red-800">Catatan Penolakan</h4>
                        <p class="text-sm text-red-700 mt-1">{{ $umkm->catatan_penolakan }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div id="imageModal" class="fixed inset-0 z-50 hidden bg-slate-900/80 backdrop-blur-md transition-opacity"
        onclick="closeImageModal()">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="relative transform transition-all scale-95 opacity-0 duration-300" id="imageModalContent">
                <button onclick="closeImageModal()"
                    class="absolute -top-12 right-0 text-white/70 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full p-2 backdrop-blur-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                <img id="modalImage" src="" alt="Preview"
                    class="max-w-full max-h-[85vh] rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/10">
            </div>
        </div>
    </div>

    <div id="verifyModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeVerifyModal()">
            </div>
            <div class="relative bg-white/95 backdrop-blur-xl rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)] border border-white/50 max-w-md w-full transform transition-all scale-95 opacity-0 duration-300"
                id="verifyModalContent">
                <div class="p-8 text-center">
                    <div
                        class="w-20 h-20 mx-auto mb-6 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-50 flex items-center justify-center border border-emerald-100 shadow-inner">
                        <svg class="w-10 h-10 text-emerald-500 drop-shadow-sm" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Verifikasi UMKM</h3>
                    <p class="text-slate-500 mb-8 leading-relaxed">Apakah Anda yakin ingin memverifikasi UMKM <strong
                            class="text-slate-800">{{ $umkm->nama_usaha }}</strong>?</p>
                    <div class="flex gap-3">
                        <button onclick="closeVerifyModal()"
                            class="flex-1 px-4 py-3 bg-slate-100/80 hover:bg-slate-200 rounded-lg text-sm font-semibold text-slate-700 transition-all">
                            Batal
                        </button>
                        <button onclick="confirmVerify()"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-lg text-sm font-semibold transition-all shadow-[0_8px_20px_rgba(16,185,129,0.25)] hover:shadow-[0_8px_20px_rgba(16,185,129,0.4)] hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Ya, Verifikasi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeRejectModal()">
            </div>
            <div class="relative bg-white/95 backdrop-blur-xl rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)] border border-white/50 max-w-md w-full transform transition-all scale-95 opacity-0 duration-300"
                id="rejectModalContent">
                <div class="p-8">
                    <div class="text-center mb-6">
                        <div
                            class="w-20 h-20 mx-auto mb-6 rounded-full bg-gradient-to-br from-red-100 to-red-50 flex items-center justify-center border border-red-100 shadow-inner">
                            <svg class="w-10 h-10 text-red-500 drop-shadow-sm" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 mb-2">Tolak UMKM</h3>
                        <p class="text-slate-500 mb-2 leading-relaxed">Apakah Anda yakin ingin menolak UMKM <strong
                                class="text-slate-800">{{ $umkm->nama_usaha }}</strong>?</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Alasan Penolakan <span
                                class="text-red-500">*</span></label>
                        <textarea id="rejectReason" rows="3"
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all resize-none"
                            placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="closeRejectModal()"
                            class="flex-1 px-4 py-3 bg-slate-100/80 hover:bg-slate-200 rounded-lg text-sm font-semibold text-slate-700 transition-all">
                            Batal
                        </button>
                        <button onclick="confirmReject()"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg text-sm font-semibold transition-all shadow-[0_8px_20px_rgba(239,68,68,0.25)] hover:shadow-[0_8px_20px_rgba(239,68,68,0.4)] hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Ya, Tolak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ============ MODAL VERIFIKASI ============
        function openVerifyModal() {
            const modal = document.getElementById('verifyModal');
            const modalContent = document.getElementById('verifyModalContent');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Animation
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeVerifyModal() {
            const modal = document.getElementById('verifyModal');
            const modalContent = document.getElementById('verifyModalContent');

            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        function confirmVerify() {
            closeVerifyModal();

            if (window.showToast) {
                window.showToast('info', 'Memproses...', 'Sedang memverifikasi UMKM');
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.verifikasi.verify', $umkm->id_umkm) }}';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            document.body.appendChild(form);
            setTimeout(() => {
                form.submit();
            }, 1500);
        }

        // ============ MODAL PENOLAKAN ============
        function openRejectModal() {
            const modal = document.getElementById('rejectModal');
            const modalContent = document.getElementById('rejectModalContent');
            modal.classList.remove('hidden');
            document.getElementById('rejectReason').value = '';
            document.body.style.overflow = 'hidden';

            // Animation
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeRejectModal() {
            const modal = document.getElementById('rejectModal');
            const modalContent = document.getElementById('rejectModalContent');

            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        function confirmReject() {
            const reason = document.getElementById('rejectReason').value.trim();

            if (!reason) {
                if (window.showToast) {
                    window.showToast('warning', 'Perhatian!', 'Alasan penolakan harus diisi');
                } else {
                    alert('Alasan penolakan harus diisi');
                }
                return;
            }

            closeRejectModal();

            if (window.showToast) {
                window.showToast('info', 'Memproses...', 'Sedang memproses penolakan UMKM');
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.verifikasi.reject', $umkm->id_umkm) }}';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.name = 'reason';
            reasonInput.value = reason;
            form.appendChild(reasonInput);

            document.body.appendChild(form);
            setTimeout(() => {
                form.submit();
            }, 1500);
        }

        // ============ FUNGSI PREVIEW GAMBAR ============
        function openImageModal(imageUrl) {
            const modal = document.getElementById('imageModal');
            const modalContent = document.getElementById('imageModalContent');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageUrl;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Trigger animation
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            const modalContent = document.getElementById('imageModalContent');

            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
                closeVerifyModal();
                closeRejectModal();
            }
        });
    </script>
@endsection
