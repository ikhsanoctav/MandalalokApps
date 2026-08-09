@extends('layouts.pelaku')
@section('title', 'Dashboard Pelaku UMKM')

@section('content')
<div class="space-y-8 pb-10">

    <!-- Premium Hero Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-white/40 backdrop-blur-xl border border-white/60 shadow-xl shadow-indigo-200/50 p-6 md:p-8">
        <!-- Decorative elements inside the hero -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-48 h-48 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-48 h-48 bg-gradient-to-br from-blue-400 to-cyan-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s;"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-slate-800">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/60 border border-white/80 text-xs font-black tracking-widest uppercase mb-4 text-indigo-600 shadow-sm backdrop-blur-md">
                    <i class="mdi mdi-storefront-outline text-indigo-500 text-sm"></i> Portal Pelaku UMKM
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-3 text-slate-800 drop-shadow-sm">
                    Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">{{ $user->name }}!</span>
                </h1>
                <p class="text-slate-600 text-base max-w-2xl font-medium leading-relaxed">
                    Pantau kinerja dan kelola semua izin serta pendaftaran UMKM Anda dalam satu dasbor pintar dan responsif.
                </p>
            </div>

            <!-- Modern Date/Time Widget -->
            <div class="flex-shrink-0 backdrop-blur-xl bg-white/70 rounded-2xl p-5 border border-white/80 shadow-lg shadow-indigo-100/50 min-w-[200px] transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                        <i class="mdi mdi-calendar-clock text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Hari Ini</p>
                        <p class="text-base font-black text-slate-800">{{ now()->translatedFormat('d M Y') }}</p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-200/50 flex justify-between items-center">
                    <span class="text-xs font-semibold text-slate-500">Waktu Lokal</span>
                    <span class="text-lg font-bold text-indigo-600 font-mono" id="realtimeClock">{{ now()->format('H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Profil Pemilik (Wallet Card Style) -->
    <div class="bg-white/60 backdrop-blur-xl rounded-2xl shadow-lg shadow-slate-200/50 border border-white/80 p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Profil Identitas</h3>
                <p class="text-slate-500 text-sm font-medium mt-1">Data pemilik yang terhubung dengan NIK</p>
            </div>
            <div class="flex items-center gap-3">
                @if (!$pemilik)
                    <div class="px-3 py-1.5 bg-rose-100 text-rose-700 text-xs font-bold rounded-md border border-rose-200 flex items-center gap-1.5 shadow-sm">
                        <i class="mdi mdi-alert-circle"></i> Data Belum Lengkap
                    </div>
                    <a href="{{ route('pelaku.profil.edit') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <i class="mdi mdi-account-edit"></i> Lengkapi Profil
                    </a>
                @else
                    <div class="px-3 py-1.5 bg-emerald-100/80 text-emerald-700 text-xs font-bold rounded-md border border-emerald-200 flex items-center gap-1.5 shadow-sm backdrop-blur-md">
                        <i class="mdi mdi-check-decagram text-base"></i> Akun Terverifikasi
                    </div>
                    <a href="{{ route('pelaku.profil.edit') }}" class="px-4 py-2 bg-white text-slate-700 rounded-lg text-xs font-bold border border-slate-200 hover:bg-slate-50 hover:shadow-md transition-all">
                        <i class="mdi mdi-pencil"></i> Edit
                    </a>
                @endif
            </div>
        </div>

        @if ($pemilik)
            <!-- ID Card UI -->
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-6 shadow-xl text-white transform transition-transform hover:-translate-y-1 hover:shadow-indigo-500/20 duration-500 border border-slate-700" x-data="{ showNik: false }">
                
                <!-- Watermark Logo -->
                <div class="absolute right-0 bottom-0 opacity-5 transform translate-x-1/4 translate-y-1/4 pointer-events-none">
                    <i class="mdi mdi-fingerprint" style="font-size: 240px;"></i>
                </div>

                <!-- Glassy Header -->
                <div class="flex justify-between items-start mb-6 relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20">
                            <i class="mdi mdi-badge-account-outline text-2xl text-indigo-300"></i>
                        </div>
                        <div>
                            <p class="text-indigo-300 text-[10px] font-bold tracking-widest uppercase">ID Pemilik UMKM</p>
                            <p class="text-lg font-bold font-mono tracking-widest mt-0.5">{{ $pemilik->nik_masked }}</p>
                        </div>
                    </div>
                    <button @click="showNik = !showNik" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md rounded-md text-[10px] font-bold tracking-wider transition-colors flex items-center gap-1.5">
                        <i class="mdi" :class="showNik ? 'mdi-eye-off' : 'mdi-eye'"></i>
                        <span x-text="showNik ? 'Sembunyikan' : 'Tampilkan'"></span>
                    </button>
                </div>

                <!-- Identity Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 relative z-10">
                    <div class="space-y-1">
                        <p class="text-slate-400 text-[10px] font-semibold uppercase tracking-wider">Nama Lengkap</p>
                        <p class="text-base font-bold">{{ $pemilik->nama_lengkap }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-slate-400 text-[10px] font-semibold uppercase tracking-wider">Nomor Induk Kependudukan (NIK)</p>
                        <p class="text-base font-bold font-mono text-indigo-300" x-text="showNik ? '{{ $pemilik->nik }}' : '{{ $pemilik->nik_masked }}'"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-slate-400 text-[10px] font-semibold uppercase tracking-wider">Tempat, Tanggal Lahir</p>
                        <p class="text-base font-bold">{{ $pemilik->tempat_lahir ?? '-' }}, {{ $pemilik->tanggal_lahir ? \Carbon\Carbon::parse($pemilik->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-slate-400 text-[10px] font-semibold uppercase tracking-wider">Nomor HP</p>
                        <p class="text-base font-bold">{{ $pemilik->no_hp ?? '-' }}</p>
                    </div>
                    <div class="space-y-1 md:col-span-2 border-t border-white/10 pt-3 mt-1">
                        <p class="text-slate-400 text-[10px] font-semibold uppercase tracking-wider">Alamat Sesuai KTP</p>
                        <p class="text-sm font-bold leading-relaxed">{{ $pemilik->alamat ?? '-' }} {{ $pemilik->rt ? 'RT ' . $pemilik->rt : '' }}{{ $pemilik->rw ? '/RW ' . $pemilik->rw : '' }}, Kel. {{ $pemilik->kelurahan ?? '-' }}</p>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State for Profile -->
            <div class="bg-indigo-50/50 rounded-2xl border border-indigo-100 border-dashed p-8 text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="mdi mdi-account-question-outline text-3xl text-indigo-500"></i>
                </div>
                <h4 class="text-lg font-bold text-slate-800 mb-2">Profil Belum Lengkap</h4>
                <p class="text-sm text-slate-500 max-w-sm mx-auto mb-6">Anda harus melengkapi data profil dan NIK terlebih dahulu sebelum bisa menggunakan fitur UMKM.</p>
                <a href="{{ route('pelaku.profil.edit') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-lg text-xs font-bold shadow-md shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                    Mulai Lengkapi Profil <i class="mdi mdi-arrow-right"></i>
                </a>
            </div>
        @endif
    </div>

    @if ($pemilik)
    <!-- Data UMKM Grid -->
    <div class="bg-white/60 backdrop-blur-xl rounded-2xl shadow-lg shadow-slate-200/50 border border-white/80 p-6 md:p-8 mt-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Katalog Usaha UMKM</h3>
                <p class="text-slate-500 font-medium mt-1">Daftar usaha yang terhubung dengan akun Anda</p>
            </div>
            
            @if ($pemilik)
                <a href="{{ route('pelaku.umkm.create') }}" class="group relative inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-md transition-all shadow-md shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-0.5 overflow-hidden">
                    <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
                    <i class="mdi mdi-plus-circle-outline text-base"></i> Daftar Usaha Baru
                </a>
            @endif
        </div>

        @if ($umkms->isEmpty())
            <div class="bg-slate-50 rounded-2xl border border-slate-200 border-dashed p-8 text-center mt-4">
                <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-3 border border-slate-100">
                    <i class="mdi mdi-store-off-outline text-3xl text-slate-400"></i>
                </div>
                <h4 class="text-lg font-bold text-slate-700 mb-1">Belum Ada Usaha Terdaftar</h4>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mb-0">Klik tombol "Daftar Usaha Baru" di atas untuk mendaftarkan UMKM Anda.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($umkms as $umkm)
                    <div x-data="{ showModal: false }" class="h-full">
                        <div class="group relative bg-white rounded-2xl overflow-hidden border border-slate-200 hover:border-indigo-300 transition-all duration-300 hover:shadow-xl hover:shadow-indigo-100 flex flex-col h-full transform hover:-translate-y-1">
                        
                        <!-- Image Container with Gradient Overlay -->
                        <div class="h-40 relative overflow-hidden bg-white flex items-center justify-center">
                            @if ($umkm->foto_utama)
                                <img src="{{ Storage::url($umkm->foto_utama) }}" alt="{{ $umkm->nama_usaha }}"
                                    class="w-full h-full object-contain p-2 transition-transform duration-700 group-hover:scale-105">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-gradient-to-br from-slate-50 to-slate-100">
                                    <i class="mdi mdi-image-off-outline text-4xl mb-1 opacity-30"></i>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/10 to-transparent pointer-events-none"></div>
                            
                            <!-- Badges -->
                            <div class="absolute top-3 right-3 flex flex-col gap-1.5 items-end">
                                {!! $umkm->status_verifikasi_badge !!}
                                {!! $umkm->status_usaha_badge !!}
                            </div>

                            <!-- Title over image -->
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <h3 class="font-extrabold text-lg text-white line-clamp-1 drop-shadow-md">{{ $umkm->nama_usaha }}</h3>
                                <p class="text-xs font-mono text-indigo-200 mt-1 drop-shadow-md flex items-center gap-1"><i class="mdi mdi-identifier"></i> {{ $umkm->no_pendaftaran }}</p>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex-1 flex flex-col bg-white">
                            <div class="mt-1 space-y-2 flex-1">
                                <div class="flex items-center text-xs font-medium text-slate-600 bg-slate-50 px-3 py-1.5 rounded-md border border-slate-100">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-2">
                                        <i class="mdi mdi-shape-outline text-sm"></i>
                                    </div>
                                    <span class="truncate">{{ $umkm->kategori?->nama_kategori ?? 'Umum' }}</span>
                                </div>
                                <div class="flex items-center text-xs font-medium text-slate-600 bg-slate-50 px-3 py-1.5 rounded-md border border-slate-100">
                                    <div class="w-6 h-6 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mr-2">
                                        <i class="mdi mdi-domain text-sm"></i>
                                    </div>
                                    <span class="truncate">{{ $umkm->sektor?->nama_sektor ?? '-' }}</span>
                                </div>
                            </div>

                            @if ($umkm->status_verifikasi == 'ditolak')
                                <div class="mt-3 p-3 bg-rose-50 rounded-lg border border-rose-100 flex items-start gap-2">
                                    <i class="mdi mdi-alert text-rose-500 text-lg mt-0.5"></i>
                                    <div>
                                        <p class="text-[10px] text-rose-800 font-bold uppercase tracking-wider mb-0.5">Catatan Penolakan</p>
                                        <p class="text-xs text-rose-600 font-medium">{{ $umkm->catatan_penolakan ?? 'Silakan perbaiki data Anda.' }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col gap-2">
                                <button @click.prevent="showModal = true"
                                    class="flex items-center justify-center w-full py-2 bg-slate-50 hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 rounded-lg text-xs font-bold transition-all border border-slate-200 hover:border-indigo-200 group/btn">
                                    <i class="mdi mdi-information-outline text-base mr-1.5 group-hover/btn:scale-110 transition-transform"></i>
                                    Detail Informasi
                                </button>

                                    <a href="{{ route('pelaku.umkm.edit', $umkm->id_umkm) }}"
                                        class="flex items-center justify-center w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all shadow-md hover:shadow-lg">
                                        <i class="mdi mdi-file-edit-outline text-base mr-1.5"></i>
                                        Ubah Data Usaha
                                    </a>
                            </div>
                        </div>
                    </div>

                        <!-- Full Detail Modal (Using standard existing layout but updated with modern UI) -->
                        <template x-teleport="body">
                        <div x-cloak x-show="showModal" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                
                                <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showModal = false" aria-hidden="true"></div>

                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                <div x-show="showModal" x-transition.scale.origin.bottom
                                    class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-slate-200">

                                    <div class="bg-white/80 backdrop-blur-md border-b border-slate-100 px-6 py-4 flex items-center justify-between sticky top-0 z-20">
                                        <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                                            <i class="mdi mdi-storefront text-indigo-600"></i>
                                            Detail: {{ $umkm->nama_usaha }}
                                        </h3>
                                        <button @click="showModal = false" class="text-slate-400 hover:text-rose-500 bg-slate-50 hover:bg-rose-50 rounded-full p-2 transition-all">
                                            <i class="mdi mdi-close text-xl"></i>
                                        </button>
                                    </div>

                                    <div class="p-0 overflow-y-auto max-h-[75vh]">
                                        <!-- Header Image -->
                                        <div class="h-48 sm:h-56 bg-white relative flex items-center justify-center">
                                            @if ($umkm->foto_utama)
                                                <img src="{{ Storage::url($umkm->foto_utama) }}" alt="{{ $umkm->nama_usaha }}" class="w-full h-full object-contain p-4">
                                            @else
                                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-50">
                                                    <i class="mdi mdi-image-off-outline text-6xl opacity-30 mb-2"></i>
                                                    <span class="font-medium">Tidak ada foto</span>
                                                </div>
                                            @endif
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent pointer-events-none"></div>
                                            <div class="absolute bottom-6 left-6 right-6">
                                                <div class="flex flex-wrap items-center gap-3 mb-3">
                                                    {!! $umkm->status_verifikasi_badge !!}
                                                    {!! $umkm->status_usaha_badge !!}
                                                    <span class="px-3 py-1 bg-white/20 backdrop-blur-md text-white text-xs font-bold rounded-full border border-white/30">{{ $umkm->kategori?->nama_kategori ?? 'Umum' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Content details existing loop -->
                                        <div class="p-6 md:p-8 space-y-8 bg-slate-50/50">
                                            <!-- Sama seperti sebelumnya, hanya dirapikan -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                                                <div class="space-y-5 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                                                    <h4 class="font-black text-slate-800 uppercase tracking-widest text-xs flex items-center gap-2 mb-4">
                                                        <i class="mdi mdi-information text-indigo-500 text-lg"></i> Info Umum
                                                    </h4>
                                                    <div>
                                                        <p class="font-bold text-slate-400 text-xs uppercase mb-1">Alamat Usaha</p>
                                                        <p class="text-slate-800 font-medium">{{ $umkm->alamat_usaha }}</p>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <p class="font-bold text-slate-400 text-xs uppercase mb-1">Tahun Berdiri</p>
                                                            <p class="text-slate-800 font-medium">{{ $umkm->tahun_berdiri ?? '-' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="font-bold text-slate-400 text-xs uppercase mb-1">Tenaga Kerja</p>
                                                            <p class="text-slate-800 font-medium">{{ $umkm->jumlah_tenaga_kerja ?? 0 }} Orang</p>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-slate-400 text-xs uppercase mb-1">Perkiraan Omset Bulanan</p>
                                                        <p class="text-slate-800 font-medium">{{ $umkm->perkiraan_omset ? 'Rp ' . number_format($umkm->perkiraan_omset, 0, ',', '.') : '-' }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-slate-400 text-xs uppercase mb-1">Deskripsi</p>
                                                        <p class="text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100">{{ $umkm->deskripsi ?? 'Belum ada deskripsi.' }}</p>
                                                    </div>
                                                </div>

                                                <div class="space-y-5 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                                                    <h4 class="font-black text-slate-800 uppercase tracking-widest text-xs flex items-center gap-2 mb-4">
                                                        <i class="mdi mdi-file-document-outline text-indigo-500 text-lg"></i> Kontak & Legal
                                                    </h4>
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <p class="font-bold text-slate-400 text-xs uppercase mb-1">No. Telepon</p>
                                                            <p class="text-slate-800 font-medium">{{ $umkm->telp_usaha ?? '-' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="font-bold text-slate-400 text-xs uppercase mb-1">Email</p>
                                                            <p class="text-slate-800 font-medium break-all">{{ $umkm->email_usaha ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-slate-400 text-xs uppercase mb-1">NIB / SIUP</p>
                                                        <p class="text-slate-800 font-medium">{{ $umkm->no_izin_usaha ?? 'Belum memiliki izin' }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-slate-400 text-xs uppercase mb-1">NPWP Usaha</p>
                                                        <p class="text-slate-800 font-medium">{{ $umkm->npwp_usaha ?? '-' }}</p>
                                                    </div>
                                                    
                                                    @if(!empty($umkm->no_pendaftaran))
                                                    <div class="pt-4 border-t border-slate-100" x-data="{ showQr: false }">
                                                        <p class="font-bold text-slate-400 text-xs uppercase mb-3">QR Code Identitas</p>
                                                        <div @click="showQr = true" class="bg-white p-3 rounded-xl border-2 border-indigo-100 inline-block cursor-pointer hover:border-indigo-500 hover:shadow-lg transition-all group/qr">
                                                            <div class="group-hover/qr:scale-105 transition-transform duration-300">
                                                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->generate($umkm->no_pendaftaran) !!}
                                                            </div>
                                                        </div>

                                                         <!-- Modal QR Detail Teleported to Body -->
                                                         <template x-teleport="body">
                                                             <div x-show="showQr" x-cloak x-transition.opacity class="fixed inset-0 z-[99999] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
                                                                 <div class="relative bg-white p-8 rounded-3xl shadow-2xl text-center max-w-sm w-full transform transition-all" @click.outside="showQr = false">
                                                                     <button @click="showQr = false" class="absolute -top-10 right-0 text-white/80 hover:text-white transition-colors focus:outline-none">
                                                                         <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                         </svg>
                                                                     </button>
                                                                     <h3 class="text-xl font-bold text-slate-800 mb-6">QR Code Usaha</h3>
                                                                     <div class="inline-block p-4 border-4 border-slate-100 rounded-2xl bg-white shadow-inner">
                                                                         {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(240)->generate($umkm->no_pendaftaran) !!}
                                                                     </div>
                                                                     <p class="mt-6 font-mono text-slate-600 font-bold text-lg tracking-widest">{{ $umkm->no_pendaftaran }}</p>
                                                                     <p class="mt-1 text-sm text-slate-400 font-medium">{{ $umkm->nama_usaha }}</p>
                                                                     <div class="mt-8 space-y-2.5">
                                                                         <button onclick="printQrDirect('{{ route('pelaku.umkm.print-qr', $umkm->id_umkm) }}', this)"
                                                                             class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#1e40af] hover:bg-[#1e3a8a] text-white font-semibold rounded-xl transition-all shadow-md hover:shadow-lg text-sm">
                                                                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                                             </svg>
                                                                             Cetak QR Code
                                                                         </button>
                                                                         <a href="{{ route('pelaku.umkm.print-dokumen', $umkm->id_umkm) }}" target="_blank"
                                                                             class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 transition-all text-sm border border-slate-200">
                                                                             <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                             </svg>
                                                                             Cetak Dokumen Resmi
                                                                         </a>
                                                                     </div>
                                                                 </div>
                                                             </div>
                                                         </template>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>

                                            @php $gallery = $umkm->foto_gallery ? json_decode($umkm->foto_gallery, true) : []; @endphp
                                            @if (count($gallery) > 0)
                                                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                                                    <h4 class="font-black text-slate-800 uppercase tracking-widest text-xs flex items-center gap-2 mb-4">
                                                        <i class="mdi mdi-image-multiple text-indigo-500 text-lg"></i> Galeri Foto Usaha
                                                    </h4>
                                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                                        @foreach ($gallery as $foto)
                                                            <div class="aspect-square rounded-xl overflow-hidden border border-slate-200 group/img relative cursor-pointer">
                                                                <img src="{{ Storage::url($foto) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover/img:scale-110">
                                                                <div class="absolute inset-0 bg-black/0 group-hover/img:bg-black/20 transition-colors"></div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        </template>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @endif
</div>

<script>
    // Realtime clock update
    setInterval(() => {
        const now = new Date();
        const clock = document.getElementById('realtimeClock');
        if(clock) {
            clock.innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');
        }
    }, 60000);

    function printQrDirect(url, btn) {
        let printFrame = document.getElementById('qrPrintFrame');
        if (!printFrame) {
            printFrame = document.createElement('iframe');
            printFrame.id = 'qrPrintFrame';
            printFrame.style.position = 'absolute';
            printFrame.style.top = '-9999px';
            printFrame.style.left = '-9999px';
            document.body.appendChild(printFrame);
        }
        
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mempersiapkan...';
        btn.disabled = true;

        printFrame.onload = function() {
            printFrame.contentWindow.print();
            btn.innerHTML = originalText;
            btn.disabled = false;
        };
        
        printFrame.src = url;
    }
</script>

<style>
@keyframes shimmer {
    100% { transform: translateX(100%); }
}
</style>
@endsection
