@extends('layouts.petugas')

@section('title', 'Data UMKM')
@section('breadcrumb', 'Data UMKM')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Data UMKM</h2>
                <p class="text-sm text-slate-500 mt-0.5">Kelurahan <span class="font-semibold text-slate-700">{{ $kelurahan ?: 'Belum Diatur' }}</span></p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="openScanModal()"
                    class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-md transition-colors shadow-sm shadow-emerald-600/20 whitespace-nowrap gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 3.5a.5.5 0 11-1 0 .5.5 0 011 0zm-5-7.5a.5.5 0 11-1 0 .5.5 0 011 0zm-8 8a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                    </svg>
                    Scan QR
                </button>
                <a href="{{ route('operator.umkm.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors shadow-sm shadow-blue-600/20 whitespace-nowrap gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah UMKM
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-xl border border-green-100 shadow-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 text-red-700 p-4 rounded-xl border border-red-100 shadow-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Statistik Cards (Wilayah) --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Wilayah</p>
                <p class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-emerald-100 p-4 shadow-sm">
                <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-wider">Terverifikasi</p>
                <p class="text-2xl font-black text-emerald-700 mt-1">{{ $stats['terverifikasi'] }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-red-100 p-4 shadow-sm">
                <p class="text-[10px] font-bold text-red-500 uppercase tracking-wider">Ditolak</p>
                <p class="text-2xl font-black text-red-700 mt-1">{{ $stats['ditolak'] }}</p>
            </div>
        </div>

        {{-- Tab Switcher --}}
        <div x-data="{ activeTab: '{{ $tab === 'peta' ? 'peta' : 'saya' }}', searchSaya: '', searchWilayah: '' }" class="space-y-4">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                {{-- Tab Header --}}
                <div class="flex border-b border-slate-200">
                    <button @click="activeTab = 'saya'"
                        :class="activeTab === 'saya' ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'"
                        class="flex-1 px-6 py-3.5 text-sm font-semibold transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Input Saya
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-[10px] font-bold"
                            :class="activeTab === 'saya' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600'">{{ $umkmSaya->count() }}</span>
                    </button>
                    <button @click="activeTab = 'wilayah'"
                        :class="activeTab === 'wilayah' ? 'border-b-2 border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'"
                        class="flex-1 px-6 py-3.5 text-sm font-semibold transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Semua Wilayah
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-[10px] font-bold"
                            :class="activeTab === 'wilayah' ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-600'">{{ $umkmWilayah->count() }}</span>
                    </button>
                    <button @click="activeTab = 'peta'"
                        :class="activeTab === 'peta' ? 'border-b-2 border-teal-600 text-teal-600 bg-teal-50/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'"
                        class="flex-1 px-6 py-3.5 text-sm font-semibold transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Peta Wilayah
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-[10px] font-bold"
                            :class="activeTab === 'peta' ? 'bg-teal-600 text-white' : 'bg-slate-200 text-slate-600'">{{ $mapData->count() }}</span>
                    </button>
                </div>

                {{-- Tab: Input Saya --}}
                <div x-show="activeTab === 'saya'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center gap-4">
                        <div class="relative w-full max-w-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" x-model="searchSaya" placeholder="Cari UMKM saya (nama, no. pendaftaran)..." 
                                class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                        </div>
                    </div>
                    <div class="block md:hidden divide-y divide-slate-100">
                        @forelse($umkmSaya as $umkm)
                            @php
                                $searchStringSaya = strtolower($umkm->nama_usaha . ' ' . $umkm->no_pendaftaran . ' ' . ($umkm->pemilik->nama_lengkap ?? ''));
                            @endphp
                            <article class="p-4 space-y-3" x-show="searchSaya === '' || '{{ $searchStringSaya }}'.includes(searchSaya.toLowerCase())">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="font-mono text-xs font-semibold text-slate-500">{{ $umkm->no_pendaftaran ?? '-' }}</p>
                                        <h3 class="text-base font-bold text-slate-800 leading-snug">{{ $umkm->nama_usaha }}</h3>
                                        <p class="text-xs text-slate-500 mt-1">{{ $umkm->kategori->nama_kategori ?? '-' }}</p>
                                    </div>
                                    <div class="shrink-0">
                                        @include('petugas.umkm._status-badge', ['status' => $umkm->status_verifikasi])
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <p class="text-xs font-semibold text-slate-400 uppercase">Pemilik</p>
                                        <p class="font-medium text-slate-700">{{ $umkm->pemilik->nama_lengkap ?? '-' }}</p>
                                        <p class="text-xs text-slate-500">{{ $umkm->pemilik->nik_masked ?? '-' }}</p>
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
                                <p class="text-slate-500 font-medium">Belum ada data UMKM yang diinput.</p>
                                <a href="{{ route('operator.umkm.create') }}" class="text-blue-600 text-sm hover:underline mt-1 inline-block">Mulai Input Data Sekarang</a>
                            </div>
                        @endforelse
                    </div>

                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">No. Pendaftaran</th>
                                    <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Usaha</th>
                                    <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Pemilik</th>
                                    <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Tgl Input</th>
                                    <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($umkmSaya as $umkm)
                                    @php
                                        $searchStringSaya = strtolower($umkm->nama_usaha . ' ' . $umkm->no_pendaftaran . ' ' . ($umkm->pemilik->nama_lengkap ?? ''));
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition-colors" x-show="searchSaya === '' || '{{ $searchStringSaya }}'.includes(searchSaya.toLowerCase())">
                                        <td class="py-4 px-6">
                                            <span class="font-mono text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-1 rounded-md">{{ $umkm->no_pendaftaran ?? '-' }}</span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <p class="font-bold text-sm text-slate-800">{{ $umkm->nama_usaha }}</p>
                                            <p class="text-xs text-slate-500 mt-0.5">{{ $umkm->kategori->nama_kategori ?? '-' }}</p>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-sm text-slate-700">{{ $umkm->pemilik->nama_lengkap ?? '-' }}</div>
                                            <div class="text-xs text-slate-500 mt-0.5">{{ $umkm->pemilik->nik_masked ?? '-' }}</div>
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
                                            <p class="text-slate-500 font-medium">Belum ada data UMKM yang diinput.</p>
                                            <a href="{{ route('operator.umkm.create') }}" class="text-blue-600 text-sm hover:underline mt-1 inline-block">Mulai Input Data Sekarang</a>
                                        </td>
                                    </tr>
                                @endforelse
                                
                                <tr x-cloak x-show="searchSaya !== '' && !Array.from($el.closest('tbody').querySelectorAll('tr:not([x-cloak])')).some(tr => tr.style.display !== 'none')">
                                    <td colspan="6" class="py-8 px-6 text-center text-slate-500">
                                        Tidak ada UMKM yang cocok dengan pencarian "<span x-text="searchSaya" class="font-bold"></span>".
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab: Semua Wilayah --}}
                <div x-show="activeTab === 'wilayah'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @if(empty($kelurahan))
                        <div class="p-8 text-center">
                            <p class="text-slate-500">Akun Anda belum memiliki pengaturan Kelurahan penugasan. Silakan hubungi Admin.</p>
                        </div>
                    @elseif($umkmWilayah->isEmpty())
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-1">Belum ada data</h3>
                            <p class="text-slate-500 text-sm">Belum ada UMKM yang terdaftar di Kelurahan {{ $kelurahan }}.</p>
                        </div>
                    @else
                        {{-- Distribusi Kategori --}}
                        @if($stats['per_kategori']->isNotEmpty())
                        <div class="px-6 pt-4 pb-2">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Distribusi Kategori</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($stats['per_kategori'] as $kategori => $count)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $kategori }} <span class="bg-indigo-600 text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px]">{{ $count }}</span>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <div class="px-6 py-3">
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" x-model="searchWilayah" placeholder="Cari UMKM di wilayah (nama, no pendaftaran, pemilik)..." 
                                    class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all bg-slate-50">
                            </div>
                        </div>

                        <div class="block md:hidden divide-y divide-slate-100">
                            @foreach ($umkmWilayah as $item)
                                @php
                                    $searchStringWil = strtolower($item->nama_usaha . ' ' . $item->no_pendaftaran . ' ' . ($item->pemilik->nama_lengkap ?? '') . ' ' . ($item->kategori->nama_kategori ?? ''));
                                @endphp
                                <article class="p-4 space-y-3" x-show="searchWilayah === '' || '{{ $searchStringWil }}'.includes(searchWilayah.toLowerCase())">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="font-mono text-xs font-semibold text-slate-500">{{ $item->no_pendaftaran ?? '-' }}</p>
                                            <h3 class="text-base font-bold text-slate-800 leading-snug">{{ $item->nama_usaha }}</h3>
                                            <p class="text-xs text-slate-500 mt-1">{{ $item->alamat_usaha }}</p>
                                        </div>
                                        <div class="shrink-0">
                                            @include('petugas.umkm._status-badge', ['status' => $item->status_verifikasi])
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                        <div>
                                            <p class="text-xs font-semibold text-slate-400 uppercase">Pemilik</p>
                                            <p class="font-medium text-slate-700">{{ $item->pemilik->nama_lengkap ?? '-' }}</p>
                                            <p class="text-xs text-slate-500">RT {{ $item->pemilik->rt ?? '-' }} / RW {{ $item->pemilik->rw ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-slate-400 uppercase">Kategori</p>
                                            <p class="font-medium text-slate-700">{{ $item->kategori->nama_kategori ?? '-' }}</p>
                                            <p class="text-xs text-slate-500">{{ $item->sektor->nama_sektor ?? '-' }}</p>
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
                                    <tr class="bg-slate-50 border-y border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
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
                                        @php
                                            $searchStringWil = strtolower($item->nama_usaha . ' ' . $item->no_pendaftaran . ' ' . ($item->pemilik->nama_lengkap ?? '') . ' ' . ($item->kategori->nama_kategori ?? ''));
                                        @endphp
                                        <tr class="hover:bg-slate-50 transition-colors" x-show="searchWilayah === '' || '{{ $searchStringWil }}'.includes(searchWilayah.toLowerCase())">
                                            <td class="px-6 py-4">
                                                <span class="font-mono text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-1 rounded-md">{{ $item->no_pendaftaran ?? '-' }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="font-bold text-slate-800">{{ $item->nama_usaha }}</p>
                                                <p class="text-xs text-slate-500 truncate max-w-[200px]">{{ $item->alamat_usaha }}</p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="font-medium text-slate-700">{{ $item->pemilik->nama_lengkap ?? '-' }}</p>
                                                <p class="text-xs text-slate-500">RT {{ $item->pemilik->rt ?? '-' }} / RW {{ $item->pemilik->rw ?? '-' }}</p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 mb-1">{{ $item->kategori->nama_kategori ?? '-' }}</span>
                                                <br>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-purple-50 text-purple-700">{{ $item->sektor->nama_sektor ?? '-' }}</span>
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
                                    <tr x-cloak x-show="searchWilayah !== '' && !Array.from($el.closest('tbody').querySelectorAll('tr:not([x-cloak])')).some(tr => tr.style.display !== 'none')">
                                        <td colspan="6" class="py-8 px-6 text-center text-slate-500">
                                            Tidak ada UMKM yang cocok dengan pencarian "<span x-text="searchWilayah" class="font-bold"></span>".
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- Tab: Peta Wilayah --}}
                <div x-show="activeTab === 'peta'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-init="$watch('activeTab', val => { if(val === 'peta') { setTimeout(() => { if(window.umkmMap) window.umkmMap.invalidateSize(); }, 100); } })">
                    @if($mapData->isEmpty())
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 bg-teal-50 text-teal-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-1">Belum ada data koordinat</h3>
                            <p class="text-slate-500 text-sm">Belum ada UMKM di wilayah ini yang memiliki data GPS/koordinat.</p>
                        </div>
                    @else
                        <div class="p-4">
                            <div id="umkmMap" class="w-full rounded-xl overflow-hidden border border-slate-200 shadow-sm" style="height: 450px;"></div>
                            <p class="text-xs text-slate-400 text-center mt-2">📍 {{ $mapData->count() }} UMKM dengan data koordinat ditampilkan di peta</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Status Badge Partial --}}
    {{-- Note: Create _status-badge.blade.php partial --}}

    <style>
        .tooltip { position: relative; }
        .tooltip:before {
            content: attr(data-tip); position: absolute; bottom: 100%; left: 50%;
            transform: translateX(-50%) translateY(-4px); background: #1e293b; color: white;
            padding: 4px 8px; border-radius: 6px; font-size: 10px; white-space: nowrap;
            opacity: 0; visibility: hidden; transition: all 0.2s;
        }
        .tooltip:hover:before { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(-8px); }
        #reader { border: none !important; }
        #reader video { border-radius: 12px; }
        #reader__scan_region { border-radius: 12px; overflow: hidden; }
        #reader__dashboard_section_swaplink { display: none !important; }
    </style>

    {{-- Modal QR Scanner --}}
    <div id="scanModal" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-sm flex items-center justify-center">
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">📷 Scan QR Code UMKM</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Arahkan kamera ke QR Code UMKM</p>
                </div>
                <button onclick="closeScanModal()" class="text-slate-400 hover:text-slate-600 bg-slate-50 hover:bg-slate-100 rounded-full p-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <div id="reader" class="w-full rounded-xl overflow-hidden bg-slate-900"></div>
                <div id="scan-status" class="mt-4 text-center text-sm text-slate-500 hidden">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 animate-spin text-emerald-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span id="scan-status-text">Memproses QR Code...</span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 text-center mt-3">QR Code akan terdeteksi secara otomatis. Pastikan cahaya cukup.</p>
                <div class="mt-4 border-t border-slate-100 pt-4">
                    <p class="text-xs text-center text-slate-500 mb-2">Kamera tidak muncul (akses via IP)?</p>
                    <label class="block w-full text-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-md cursor-pointer transition-colors">
                        <input type="file" id="qr-input-file" accept="image/*" class="hidden">
                        Upload / Ambil Foto QR Code
                    </label>
                </div>
            </div>
        </div>
    </div>

    {{-- Leaflet CSS & JS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        // ====== Peta Leaflet ======
        document.addEventListener('DOMContentLoaded', function () {
            const mapData = @json($mapData);

            if (mapData.length > 0 && document.getElementById('umkmMap')) {
                const map = L.map('umkmMap').setView([mapData[0].lat, mapData[0].lng], 15);
                window.umkmMap = map;

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors',
                    maxZoom: 19,
                }).addTo(map);

                const statusColors = {
                    'terverifikasi': '#059669',
                    'terkirim': '#d97706',
                    'ditolak': '#dc2626',
                    'draft': '#64748b',
                };

                const bounds = [];

                mapData.forEach(function (u) {
                    const color = statusColors[u.status] || '#64748b';
                    const marker = L.circleMarker([u.lat, u.lng], {
                        radius: 8,
                        fillColor: color,
                        color: '#fff',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.85,
                    }).addTo(map);

                    marker.bindPopup(
                        '<div style="min-width:180px">' +
                        '<p style="font-weight:700;font-size:14px;margin:0 0 4px">' + u.nama + '</p>' +
                        '<p style="font-size:11px;color:#64748b;margin:0 0 2px">👤 ' + u.pemilik + '</p>' +
                        '<p style="font-size:11px;color:#64748b;margin:0 0 2px">📂 ' + u.kategori + '</p>' +
                        '<p style="font-size:11px;color:#64748b;margin:0 0 6px">📍 ' + u.alamat + '</p>' +
                        '<a href="/operator/umkm/' + u.id + '/show" style="font-size:11px;color:#2563eb;text-decoration:underline">Lihat Detail →</a>' +
                        '</div>'
                    );

                    bounds.push([u.lat, u.lng]);
                });

                if (bounds.length > 1) {
                    map.fitBounds(bounds, { padding: [30, 30] });
                }
            }
        });

        // ====== QR Scanner ======
        let html5QrCode = null;
        let scanHandled = false;

        function openScanModal() {
            document.getElementById('scanModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            scanHandled = false;

            setTimeout(() => {
                html5QrCode = new Html5Qrcode("reader");
                const config = {
                    fps: 10,
                    qrbox: function(viewfinderWidth, viewfinderHeight) {
                        let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                        let qrboxSize = Math.floor(minEdgeSize * 0.75);
                        return { width: qrboxSize, height: qrboxSize };
                    }
                };
                const onScanSuccess = (decodedText) => {
                    if (scanHandled) return;
                    scanHandled = true;
                    document.getElementById('scan-status').classList.remove('hidden');
                    document.getElementById('scan-status-text').textContent = 'QR Code terbaca! Mengalihkan...';
                    html5QrCode.stop().then(() => {
                        window.location.href = "{{ route('operator.umkm.scan-result') }}?q=" + encodeURIComponent(decodedText);
                    }).catch(() => {
                        window.location.href = "{{ route('operator.umkm.scan-result') }}?q=" + encodeURIComponent(decodedText);
                    });
                };
                html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess, () => {}).catch(err => {
                    Html5Qrcode.getCameras().then(devices => {
                        if (devices && devices.length) {
                            let cameraId = devices[devices.length - 1].id;
                            html5QrCode.start(cameraId, config, onScanSuccess, () => {}).catch(() => {
                                document.getElementById('scan-status').classList.remove('hidden');
                                document.getElementById('scan-status-text').textContent = 'Kamera tidak dapat diakses.';
                            });
                        }
                    }).catch(() => {
                        document.getElementById('scan-status').classList.remove('hidden');
                        document.getElementById('scan-status-text').textContent = location.protocol !== 'https:' && location.hostname !== 'localhost' ? 'Akses kamera butuh HTTPS!' : 'Kamera diblokir / tidak diizinkan.';
                    });
                });
            }, 200);
        }

        function closeScanModal() {
            if (html5QrCode) {
                html5QrCode.stop().catch(() => {}).finally(() => {
                    html5QrCode.clear();
                    html5QrCode = null;
                    document.getElementById('scan-status').classList.add('hidden');
                    document.getElementById('scanModal').classList.add('hidden');
                    document.body.style.overflow = '';
                });
            } else {
                document.getElementById('scanModal').classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        document.getElementById('qr-input-file').addEventListener('change', e => {
            if (e.target.files.length === 0) return;
            const file = e.target.files[0];
            document.getElementById('scan-status').classList.remove('hidden');
            document.getElementById('scan-status-text').textContent = 'Membaca gambar...';
            if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");
            html5QrCode.scanFile(file, true)
                .then(decodedText => {
                    document.getElementById('scan-status-text').textContent = 'QR Code terbaca! Mengalihkan...';
                    window.location.href = "{{ route('operator.umkm.scan-result') }}?q=" + encodeURIComponent(decodedText);
                })
                .catch(() => {
                    document.getElementById('scan-status-text').textContent = 'QR Code tidak ditemukan pada gambar.';
                    e.target.value = '';
                });
        });
    </script>
@endsection
