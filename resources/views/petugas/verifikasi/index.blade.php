@extends('layouts.petugas')

@section('title', 'Verifikasi Lapangan')
@section('breadcrumb', 'Verifikasi Lapangan')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Verifikasi Lapangan</h2>
                <p class="text-sm text-slate-700 mt-0.5">Kelurahan <span class="font-semibold text-slate-700">{{ $kelurahan ?: 'Belum Diatur' }}</span></p>
            </div>
            <a href="{{ route('operator.dashboard') }}" class="text-sm text-blue-600 hover:underline">Kembali ke Dashboard</a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-xl border border-green-100 shadow-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Statistik Kunjungan --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total UMKM</p>
                <p class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-red-100 p-4 shadow-sm">
                <p class="text-[10px] font-bold text-red-500 uppercase tracking-wider">Belum Dikunjungi</p>
                <p class="text-2xl font-black text-red-700 mt-1">{{ $stats['belum'] }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-emerald-100 p-4 shadow-sm">
                <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-wider">Sudah Dikunjungi</p>
                <p class="text-2xl font-black text-emerald-700 mt-1">{{ $stats['sudah'] }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-amber-100 p-4 shadow-sm">
                <p class="text-[10px] font-bold text-amber-500 uppercase tracking-wider">Tindak Lanjut</p>
                <p class="text-2xl font-black text-amber-700 mt-1">{{ $stats['tindak_lanjut'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ searchVerif: '' }">
            <div class="p-6 border-b border-slate-200 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Daftar Kunjungan Lapangan</h3>
                    <p class="text-sm text-slate-700">UMKM terverifikasi di wilayah Anda yang perlu dikunjungi.</p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <div class="relative w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" x-model="searchVerif" placeholder="Cari UMKM / Pemilik..." 
                            class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto pb-1 sm:pb-0">
                        <a href="{{ route('operator.verifikasi.index', ['filter' => 'semua']) }}" class="whitespace-nowrap px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ $filter === 'semua' ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Semua (Aktif)</a>
                        <a href="{{ route('operator.verifikasi.index', ['filter' => 'belum']) }}" class="whitespace-nowrap px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ $filter === 'belum' ? 'bg-red-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Belum</a>
                        <a href="{{ route('operator.verifikasi.index', ['filter' => 'sudah']) }}" class="whitespace-nowrap px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ $filter === 'sudah' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Sudah (Bermasalah)</a>
                        <a href="{{ route('operator.verifikasi.index', ['filter' => 'tindak_lanjut']) }}" class="whitespace-nowrap px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ $filter === 'tindak_lanjut' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Tindak Lanjut</a>
                        <a href="{{ route('operator.verifikasi.index', ['filter' => 'riwayat']) }}" class="whitespace-nowrap px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ $filter === 'riwayat' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Riwayat Selesai</a>
                    </div>
                </div>
            </div>

            @if(empty($kelurahan))
                <div class="p-8 text-center">
                    <p class="text-slate-700">Akun Anda belum memiliki pengaturan Kelurahan penugasan. Silakan hubungi Admin.</p>
                </div>
            @elseif($umkms->isEmpty())
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Belum ada data</h3>
                    <p class="text-slate-700 text-sm">Tidak ada data UMKM yang sesuai dengan filter saat ini.</p>
                </div>
            @else
                <div class="block md:hidden divide-y divide-slate-100">
                    @foreach ($umkms as $umkm)
                        @php
                            $latest = $umkm->latestVerifikasiLapangan;
                            $searchString = strtolower($umkm->nama_usaha . ' ' . ($umkm->pemilik?->nama_lengkap ?? '') . ' ' . ($umkm->pemilik?->nik ?? ''));
                        @endphp
                        <article class="p-4 space-y-3" x-show="searchVerif === '' || '{{ $searchString }}'.includes(searchVerif.toLowerCase())">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="text-base font-bold text-slate-800 leading-snug">{{ $umkm->nama_usaha }}</h3>
                                    <p class="text-xs text-slate-700 mt-1">{{ $umkm->alamat_usaha }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase">Pemilik</p>
                                    <p class="font-medium text-slate-700">{{ $umkm->pemilik?->nama_lengkap ?? '-' }}</p>
                                    <p class="text-xs text-slate-700">RT {{ $umkm->pemilik?->rt ?? '-' }} / RW {{ $umkm->pemilik?->rw ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase">Terakhir</p>
                                    @if($latest)
                                        <p class="font-medium text-slate-700">{{ $latest->tanggal_kunjungan->format('d M Y') }}</p>
                                        <p class="text-xs text-slate-700">Oleh: {{ $latest->petugas?->name ?? '-' }}</p>
                                    @else
                                        <p class="font-medium text-slate-400">-</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @if($latest)
                                    {!! $latest->status_badge !!}
                                    @if($latest->kondisi_usaha)
                                        {!! $latest->kondisi_badge !!}
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Belum Dikunjungi
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('operator.verifikasi.show', $umkm->id_umkm) }}" class="inline-flex w-full items-center justify-center px-4 py-3 text-sm font-semibold rounded-md transition-colors {{ !$latest ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/20' : 'bg-slate-100 text-slate-700' }}">
                                {{ !$latest ? 'Mulai Kunjungan' : 'Lihat Detail' }}
                            </a>
                        </article>
                    @endforeach
                </div>

                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#0f2e5c] text-white text-xs uppercase tracking-wider font-semibold">
                                <th class="px-6 py-4">UMKM & Lokasi</th>
                                <th class="px-6 py-4">Pemilik</th>
                                <th class="px-6 py-4">Status Kunjungan</th>
                                <th class="px-6 py-4">Kunjungan Terakhir</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($umkms as $umkm)
                                @php
                                    $latest = $umkm->latestVerifikasiLapangan;
                                    $searchString = strtolower($umkm->nama_usaha . ' ' . ($umkm->pemilik?->nama_lengkap ?? '') . ' ' . ($umkm->pemilik?->nik ?? ''));
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors" x-show="searchVerif === '' || '{{ $searchString }}'.includes(searchVerif.toLowerCase())">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-800">{{ $umkm->nama_usaha }}</p>
                                        <p class="text-xs text-slate-700 truncate max-w-[250px]">{{ $umkm->alamat_usaha }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-slate-700">{{ $umkm->pemilik?->nama_lengkap ?? '-' }}</p>
                                        <p class="text-xs text-slate-700">RT {{ $umkm->pemilik?->rt ?? '-' }} / RW {{ $umkm->pemilik?->rw ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($latest)
                                            {!! $latest->status_badge !!}
                                            @if($latest->kondisi_usaha)
                                                <div class="mt-1">
                                                    {!! $latest->kondisi_badge !!}
                                                </div>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Belum Dikunjungi
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($latest)
                                            <p class="text-sm font-medium text-slate-700">{{ $latest->tanggal_kunjungan->format('d M Y') }}</p>
                                            <p class="text-xs text-slate-700">Oleh: {{ $latest->petugas?->name ?? '-' }}</p>
                                        @else
                                            <span class="text-slate-400 text-sm">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('operator.verifikasi.show', $umkm->id_umkm) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md transition-colors {{ !$latest ? 'bg-blue-600 text-white hover:bg-blue-700 shadow-sm shadow-blue-600/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                                            {{ !$latest ? 'Mulai Kunjungan' : 'Lihat Detail' }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr x-cloak x-show="searchVerif !== '' && !Array.from($el.closest('tbody').querySelectorAll('tr:not([x-cloak])')).some(tr => tr.style.display !== 'none')">
                                <td colspan="5" class="py-8 px-6 text-center text-slate-700">
                                    Tidak ada UMKM yang cocok dengan pencarian "<span x-text="searchVerif" class="font-bold"></span>".
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
