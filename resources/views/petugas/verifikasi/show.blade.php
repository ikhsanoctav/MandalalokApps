@extends('layouts.petugas')

@section('title', 'Detail Verifikasi Lapangan')
@section('breadcrumb', 'Detail Verifikasi')

@section('content')
    <div class="space-y-6 max-w-5xl mx-auto">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Detail Verifikasi Lapangan</h2>
                <a href="{{ route('operator.verifikasi.index') }}" class="text-sm text-slate-700 hover:text-slate-700 flex items-center gap-1 mt-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar
                </a>
            </div>
            <a href="{{ route('operator.verifikasi.create', $umkm->id_umkm) }}" class="inline-flex items-center justify-center px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors shadow-sm shadow-blue-600/20 gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Catat Kunjungan Baru
            </a>
        </div>

        @if (session('success') || session('toast'))
            <div class="bg-green-50 text-green-700 p-4 rounded-xl border border-green-100 shadow-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') ?? session('toast')['message'] }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Info UMKM --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-100 bg-slate-50">
                        <h3 class="font-bold text-slate-800">Informasi UMKM</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nama Usaha</p>
                            <p class="font-bold text-slate-800">{{ $umkm->nama_usaha }}</p>
                            <p class="text-sm text-slate-700">{{ $umkm->kategori?->nama_kategori ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Pemilik</p>
                            <p class="font-medium text-slate-700">{{ $umkm->pemilik?->nama_lengkap ?? '-' }}</p>
                            <p class="text-sm text-slate-700">RT {{ $umkm->pemilik?->rt ?? '-' }} / RW {{ $umkm->pemilik?->rw ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Kontak</p>
                            <p class="text-sm text-slate-700">{{ $umkm->telp_usaha ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Alamat</p>
                            <p class="text-sm text-slate-700">{{ $umkm->alamat_usaha ?? '-' }}</p>
                        </div>
                        @if($umkm->latitude && $umkm->longitude)
                            <a href="https://maps.google.com/?q={{ $umkm->latitude }},{{ $umkm->longitude }}" target="_blank" class="block w-full text-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-md transition-colors">
                                📍 Buka di Google Maps
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Riwayat Kunjungan --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden h-full">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800">Riwayat Kunjungan Lapangan</h3>
                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                            {{ $umkm->verifikasiLapangan->count() }} Kunjungan
                        </span>
                    </div>
                    
                    <div class="p-6">
                        @if($umkm->verifikasiLapangan->isEmpty())
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h4 class="text-lg font-bold text-slate-700 mb-1">Belum Ada Riwayat</h4>
                                <p class="text-slate-700 text-sm mb-6">UMKM ini belum pernah dikunjungi untuk verifikasi lapangan.</p>
                                <a href="{{ route('operator.verifikasi.create', $umkm->id_umkm) }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors shadow-sm shadow-blue-600/20">
                                    Mulai Kunjungan Pertama
                                </a>
                            </div>
                        @else
                            <div class="relative border-l-2 border-slate-100 ml-3 space-y-8 pb-4">
                                @foreach($umkm->verifikasiLapangan as $verifikasi)
                                    <div class="relative pl-6">
                                        {{-- Timeline Dot --}}
                                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full border-4 border-white {{ $verifikasi->status_kunjungan === 'butuh_tindak_lanjut' ? 'bg-amber-500' : 'bg-emerald-500' }}"></div>
                                        
                                        <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                                            <div class="flex flex-wrap items-start justify-between gap-4 mb-3">
                                                <div>
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="text-sm font-bold text-slate-800">{{ $verifikasi->tanggal_kunjungan->format('l, d M Y') }}</span>
                                                        <span class="text-xs text-slate-700">•</span>
                                                        <span class="text-xs text-slate-700">Oleh: {{ $verifikasi->petugas?->name ?? 'Unknown' }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-2 mt-2">
                                                        {!! $verifikasi->status_badge !!}
                                                        {!! $verifikasi->kondisi_badge !!}
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            @if($verifikasi->catatan_kunjungan)
                                                <div class="mt-4 bg-white p-4 rounded-2xl border border-slate-100">
                                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Catatan Kunjungan</p>
                                                    <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $verifikasi->catatan_kunjungan }}</p>
                                                </div>
                                            @endif

                                            @if(!empty($verifikasi->foto_kunjungan) && is_array($verifikasi->foto_kunjungan))
                                                <div class="mt-4">
                                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Foto Bukti</p>
                                                    <div class="flex flex-wrap gap-3">
                                                        @foreach($verifikasi->foto_kunjungan as $foto)
                                                            <a href="{{ Storage::url($foto) }}" target="_blank" class="block w-24 h-24 rounded-2xl overflow-hidden border border-slate-200 hover:ring-2 hover:ring-blue-500 transition-all">
                                                                <img src="{{ Storage::url($foto) }}" class="w-full h-full object-cover" alt="Foto Kunjungan">
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($verifikasi->latitude_kunjungan && $verifikasi->longitude_kunjungan)
                                                <div class="mt-4 flex items-center gap-2 text-xs text-slate-700 bg-slate-100/50 p-2 rounded-md inline-flex">
                                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    Tercatat pada kordinat: {{ $verifikasi->latitude_kunjungan }}, {{ $verifikasi->longitude_kunjungan }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
