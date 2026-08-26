
@extends('layouts.admin')
@section('title', 'Daftar UMKM')
@section('breadcrumb', 'Master Data UMKM / Daftar UMKM')

@section('content')
    <div class="space-y-4 md:space-y-6">

        <!-- Cards Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
            <!-- Total UMKM -->
            <div class="bg-blue-600 rounded-2xl p-5 md:p-6 text-white relative overflow-hidden flex flex-col justify-between shadow-sm">
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-blue-100 text-xs font-semibold tracking-wider uppercase mb-1">Total UMKM</p>
                            <h3 class="text-3xl font-bold">{{ $totalUmkm }}</h3>
                        </div>
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"></path></svg>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-white/20 text-white">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            {{ number_format($percentageIncrease, 0) }}%
                        </span>
                        <span class="text-xs text-blue-200">dari bulan lalu</span>
                    </div>
                    
                    <div class="space-y-1">
                        <div class="flex justify-between text-[10px] md:text-xs text-blue-100">
                            <span>Terverifikasi: {{ $terverifikasiCount }}</span>
                            <span>Menunggu: {{ $menungguCount }}</span>
                        </div>
                        <div class="w-full bg-blue-800/50 rounded-full h-1.5">
                            <div class="bg-white h-1.5 rounded-full" style="width: {{ $totalUmkm > 0 ? ($terverifikasiCount / $totalUmkm) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Perlu Verifikasi -->
            <div class="bg-amber-500 rounded-2xl p-5 md:p-6 text-white relative overflow-hidden flex flex-col justify-between shadow-sm">
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-amber-100 text-xs font-semibold tracking-wider uppercase mb-1">Perlu Verifikasi</p>
                            <h3 class="text-3xl font-bold">{{ $menungguCount }}</h3>
                        </div>
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-auto pt-4 md:pt-6">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold border border-white/30 text-white">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Butuh Aksi
                        </span>
                        <span class="text-xs text-amber-100">Pengajuan baru</span>
                    </div>
                </div>
            </div>

            <!-- UMKM Aktif -->
            <div class="bg-emerald-500 rounded-2xl p-5 md:p-6 text-white relative overflow-hidden flex flex-col justify-between shadow-sm">
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-emerald-100 text-xs font-semibold tracking-wider uppercase mb-1">UMKM Aktif</p>
                            <h3 class="text-3xl font-bold">{{ $aktifCount }}</h3>
                        </div>
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-auto pt-4 md:pt-6">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold border border-white/30 text-white">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Terverifikasi
                        </span>
                        <span class="text-xs text-emerald-100">Siap beroperasi</span>
                    </div>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.umkm.index') }}" id="filterForm">
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-slate-200 p-4 md:p-6">
                <div class="flex justify-between items-center mb-3 md:mb-4">
                    <h3 class="font-semibold text-slate-700 flex items-center gap-2 text-sm md:text-base">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        Filter Data
                    </h3>
                    <a href="{{ route('admin.umkm.index') }}" class="text-xs text-blue-600 hover:text-blue-800">Reset
                        Filter</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-7 gap-3 md:gap-4">
                    <input type="text" name="search" placeholder="Cari nama usaha..." value="{{ request('search') }}"
                        oninput="clearTimeout(window.searchDebounceTimer); window.searchDebounceTimer = setTimeout(() => window.triggerFilter && window.triggerFilter(), 250)"
                        class="w-full px-3 py-2 md:px-4 md:py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">

                    <select name="kelurahan" onchange="window.triggerFilter && window.triggerFilter()"
                        class="w-full px-3 py-2 md:px-4 md:py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">Semua Kelurahan</option>
                        @foreach ($kelurahans as $kelurahanItem)
                            <option value="{{ $kelurahanItem }}"
                                {{ request('kelurahan') == $kelurahanItem ? 'selected' : '' }}>
                                {{ $kelurahanItem }}
                            </option>
                        @endforeach
                    </select>

                    <select name="kategori" onchange="window.triggerFilter && window.triggerFilter()"
                        class="w-full px-3 py-2 md:px-4 md:py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $kategoriItem)
                            <option value="{{ $kategoriItem->id }}"
                                {{ request('kategori') == $kategoriItem->id ? 'selected' : '' }}>
                                {{ $kategoriItem->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    <select name="sektor" onchange="window.triggerFilter && window.triggerFilter()"
                        class="w-full px-3 py-2 md:px-4 md:py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">Semua Sektor</option>
                        @foreach ($sektors as $sektorItem)
                            <option value="{{ $sektorItem->id }}"
                                {{ request('sektor') == $sektorItem->id ? 'selected' : '' }}>
                                {{ $sektorItem->nama_sektor }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status_verifikasi" onchange="window.triggerFilter && window.triggerFilter()"
                        class="w-full px-3 py-2 md:px-4 md:py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">Semua Status</option>
                        <option value="terverifikasi"
                            {{ request('status_verifikasi') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="menunggu_verifikasi" {{ request('status_verifikasi') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu
                        </option>
                        <option value="ditolak" {{ request('status_verifikasi') == 'ditolak' ? 'selected' : '' }}>Ditolak
                        </option>
                    </select>

                    <select name="sort" onchange="window.triggerFilter && window.triggerFilter()"
                        class="w-full px-3 py-2 md:px-4 md:py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                        <option value="a-z" {{ request('sort') == 'a-z' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="z-a" {{ request('sort') == 'z-a' ? 'selected' : '' }}>Nama Z-A</option>
                    </select>

                    <div class="flex gap-2">
                        <select name="per_page" onchange="window.triggerFilter && window.triggerFilter()"
                            class="flex-1 px-3 py-2 md:px-4 md:py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-all">
                            Cari
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div class="flex justify-end gap-3">
            <button type="button" onclick="document.getElementById('exportModal').classList.remove('hidden')"
                class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-all flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Data
            </button>
            <button onclick="openScanModal()"
                class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-md transition-colors shadow-sm gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 3.5a.5.5 0 11-1 0 .5.5 0 011 0zm-5-7.5a.5.5 0 11-1 0 .5.5 0 011 0zm-8 8a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                </svg>
                Scan QR UMKM
            </button>
            <a href="{{ route('admin.umkm.create') }}"
                class="px-4 py-2 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-all flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah UMKM
            </a>
        </div>

        <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-slate-200 overflow-hidden" id="umkmTableContainer" style="transition: opacity 0.25s ease-in-out, filter 0.25s ease-in-out;">

            <div class="block md:hidden divide-y divide-slate-100">
                @forelse($umkms as $umkm)
                    <div class="p-4 space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-slate-800">{{ $umkm->nama_usaha }}</h4>
                                <p class="text-xs text-slate-400">{{ $umkm->no_pendaftaran ?? '-' }}</p>
                            </div>
                            <div>
                                @if ($umkm->status_verifikasi == 'terverifikasi')
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        Terverifikasi
                                    </span>
                                @elseif($umkm->status_verifikasi == 'menunggu_verifikasi')
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        Menunggu
                                    </span>
                                @elseif($umkm->status_verifikasi == 'ditolak')
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        Ditolak
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                        Draft
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <p class="text-slate-500">Pemilik</p>
                                <p class="font-medium text-slate-700">{{ $umkm->pemilik?->nama_lengkap ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-slate-500">Kelurahan</p>
                                <p class="font-medium text-slate-700">{{ $umkm->pemilik?->kelurahan ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-slate-500">Tanggal Daftar</p>
                                <p class="font-medium text-slate-700">
                                    {{ $umkm->created_at ? $umkm->created_at->format('d/m/Y') : '-' }}</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-2 border-t">
                            <button onclick="detailUmkm('{{ $umkm->id_umkm }}')" title="Lihat Detail"
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-2xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                            <a href="{{ route('admin.umkm.edit', $umkm->id_umkm) }}" title="Edit Data" data-no-ajax="true"
                                class="p-2 text-amber-600 hover:bg-amber-50 rounded-2xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                        <p>Tidak ada data UMKM</p>
                    </div>
                @endforelse
            </div>

            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama Usaha</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Pemilik</th>

                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tgl Daftar</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($umkms as $index => $umkm)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-slate-500">{{ $umkms->firstItem() + $index }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-slate-800">{{ $umkm->nama_usaha }}</p>
                                    <p class="text-xs text-slate-400">{{ $umkm->no_pendaftaran ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-sm text-slate-600">{{ $umkm->pemilik?->nama_lengkap ?? '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ $umkm->pemilik?->kelurahan ?? '-' }}</p>
                                </td>

                                <td class="px-4 py-3">
                                    @if ($umkm->status_verifikasi == 'terverifikasi')
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            Terverifikasi
                                        </span>
                                    @elseif($umkm->status_verifikasi == 'menunggu_verifikasi')
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                            Menunggu
                                        </span>
                                    @elseif($umkm->status_verifikasi == 'ditolak')
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                            Ditolak
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                            <span class="w-1.5 h-1.5 bg-slate-500 rounded-full"></span>
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500">
                                    {{ $umkm->created_at ? $umkm->created_at->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button onclick="detailUmkm('{{ $umkm->id_umkm }}')" title="Lihat Detail"
                                            class="text-blue-600 hover:bg-blue-50 p-1.5 rounded-2xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>
                                        <a href="{{ route('admin.umkm.edit', $umkm->id_umkm) }}" title="Edit Data" data-no-ajax="true"
                                            class="p-2 text-amber-600 hover:bg-amber-50 rounded-2xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        @if ($umkm->status_verifikasi != 'terverifikasi')
                                            <button onclick="verifyAdminUmkm('{{ $umkm->id_umkm }}', '{{ e($umkm->nama_usaha) }}')"
                                                class="text-emerald-600 hover:text-emerald-800 p-1.5 hover:bg-emerald-50 rounded-2xl transition-all"
                                                title="Verifikasi / Setujui">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        @endif
                                        @if ($umkm->status_verifikasi != 'ditolak')
                                            <button onclick="rejectAdminUmkm('{{ $umkm->id_umkm }}', '{{ e($umkm->nama_usaha) }}')"
                                                class="text-red-600 hover:text-red-800 p-1.5 hover:bg-red-50 rounded-2xl transition-all"
                                                title="Tolak / Penolakan">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                        </path>
                                    </svg>
                                    Tidak ada data UMKM
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($umkms->hasPages())
                <div class="px-4 py-3 border-t border-slate-200">
                    {{ $umkms->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        #reader {
            border: none !important;
        }

        #reader video {
            border-radius: 12px;
        }

        #reader__scan_region {
            border-radius: 12px;
            overflow: hidden;
        }

        #reader__dashboard_section_swaplink {
            display: none !important;
        }
    </style>

    {{-- Modal QR Scanner --}}
    <div id="scanModal" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-sm flex items-center justify-center">
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">📷 Scan QR Code UMKM</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Arahkan kamera ke QR Code UMKM</p>
                </div>
                <button onclick="closeScanModal()" class="text-slate-400 hover:text-slate-600 bg-slate-50 hover:bg-slate-100 rounded-full p-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Scanner Area --}}
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
                <p class="text-xs text-slate-400 text-center mt-3">
                    QR Code akan terdeteksi secara otomatis. Pastikan cahaya cukup.
                </p>

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

    {{-- Modal Detail UMKM Container --}}
    <x-modal name="detailModal" maxWidth="2xl">
        <div class="relative bg-white rounded-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center z-10">
                <h3 class="text-xl font-bold text-slate-800">
                    <i class="mdi mdi-store text-blue-600 mr-2"></i>
                    Detail UMKM
                </h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="mdi mdi-close text-2xl"></i>
                </button>
            </div>
            <div id="modalContent" class="p-6">
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-2 text-slate-700">Memuat data...</p>
                </div>
            </div>
            <div id="modalFooter" class="sticky bottom-0 bg-white border-t px-6 py-4 flex justify-end gap-3 z-10">
                <button onclick="closeModal()"
                    class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </x-modal>

    <div id="exportModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('exportModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <form action="{{ route('admin.laporan.export') }}" method="POST">
                    @csrf
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="kelurahan" value="{{ request('kelurahan') }}">
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    <input type="hidden" name="sektor" value="{{ request('sektor') }}">
                    <input type="hidden" name="status_verifikasi" value="{{ request('status_verifikasi') }}">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Export Data UMKM</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">Pilih format file dan kolom yang ingin disertakan dalam laporan (data akan difilter sesuai tampilan saat ini).</p>
                                    
                                    <div class="mt-4 mb-5 border-t border-slate-100 pt-4" x-data="exportColumnSelector()">
                                        <p class="text-sm font-semibold text-slate-700 mb-2">Pilih Kolom Data:</p>
                                        
                                        <!-- Active columns as tags -->
                                        <div class="flex flex-wrap gap-2 mb-3">
                                            <template x-for="(col, index) in activeColumns" :key="col.value">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                    <span x-text="col.label"></span>
                                                    <button type="button" @click="removeColumn(index)" class="hover:bg-indigo-200 hover:text-indigo-900 rounded-full p-0.5 transition-colors focus:outline-none">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                    <input type="hidden" name="columns[]" :value="col.value">
                                                </span>
                                            </template>
                                        </div>

                                        <!-- Dropdown to add more -->
                                        <div class="relative" x-show="availableColumns.length > 0">
                                            <select @change="addColumn($event.target.value); $event.target.value=''" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50 py-2 px-3 cursor-pointer">
                                                <option value="">+ Tambah Kolom Lainnya...</option>
                                                <template x-for="col in availableColumns" :key="col.value">
                                                    <option :value="col.value" x-text="col.label"></option>
                                                </template>
                                            </select>
                                        </div>
                                        
                                        <div x-show="activeColumns.length === 0" class="text-xs text-red-500 mt-2 font-medium">
                                            * Pilih setidaknya satu kolom untuk diexport.
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <button type="submit" name="format" value="excel" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform hover:-translate-y-0.5">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            Export ke Excel (.xlsx)
                                        </button>
                                        <button type="submit" name="format" value="pdf" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-all transform hover:-translate-y-0.5">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            Export ke PDF (.pdf)
                                        </button>
                                    </div>
                                    
                                    <script>
                                        function exportColumnSelector() {
                                            const allCols = [
                                                { value: 'no_pendaftaran', label: 'No. Pendaftaran' },
                                                { value: 'nama_usaha', label: 'Nama Usaha' },
                                                { value: 'pemilik', label: 'Nama Pemilik' },
                                                { value: 'kategori', label: 'Kategori' },
                                                { value: 'sektor', label: 'Sektor' },
                                                { value: 'status_usaha', label: 'Status Usaha' },
                                                { value: 'tahun_berdiri', label: 'Thn Berdiri' },
                                                { value: 'no_izin', label: 'No. Izin' },
                                                { value: 'jenis_izin', label: 'Jenis Izin' },
                                                { value: 'npwp', label: 'NPWP Usaha' },
                                                { value: 'telepon', label: 'Telepon Usaha' },
                                                { value: 'email', label: 'Email Usaha' },
                                                { value: 'jumlah_tk', label: 'Jumlah TK' },
                                                { value: 'alamat', label: 'Alamat Usaha' },
                                                { value: 'petugas', label: 'Petugas Pendata' },
                                                { value: 'tgl_pendataan', label: 'Tgl Pendataan' },
                                                { value: 'status_verifikasi', label: 'Status Verifikasi' },
                                                { value: 'alasan_penolakan', label: 'Alasan Penolakan' }
                                            ];
                                            const defaultVals = ['no_pendaftaran', 'nama_usaha', 'pemilik', 'kategori', 'sektor', 'tahun_berdiri', 'no_izin', 'jumlah_tk', 'status_verifikasi'];
                                            
                                            return {
                                                activeColumns: allCols.filter(c => defaultVals.includes(c.value)),
                                                get availableColumns() {
                                                    const activeVals = this.activeColumns.map(c => c.value);
                                                    return allCols.filter(c => !activeVals.includes(c.value));
                                                },
                                                addColumn(val) {
                                                    if (!val) return;
                                                    const col = allCols.find(c => c.value === val);
                                                    if (col) this.activeColumns.push(col);
                                                },
                                                removeColumn(index) {
                                                    this.activeColumns.splice(index, 1);
                                                }
                                            }
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" onclick="document.getElementById('exportModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function verifyAdminUmkm(id, nama) {
            window.dispatchEvent(new CustomEvent('open-confirm-modal', {
                detail: {
                    title: 'Verifikasi / Setujui UMKM',
                    message: `Apakah Anda yakin ingin menyetujui dan memverifikasi data UMKM "${nama || ''}"?`,
                    confirmText: 'Ya, Verifikasi',
                    action: () => {
                        const token = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
                        return fetch(`/admin/verifikasi/${id}/verify`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                if (window.showToast) window.showToast(data.message || 'UMKM berhasil diverifikasi', 'success');
                                if (window.triggerFilter) window.triggerFilter();
                                else location.reload();
                            } else {
                                if (window.showToast) window.showToast(data.message || 'Gagal memverifikasi UMKM', 'error');
                                else alert(data.message);
                            }
                        });
                    }
                }
            }));
        }

        function rejectAdminUmkm(id, nama) {
            const alasan = prompt(`Masukkan alasan penolakan/pembatalan verifikasi untuk UMKM "${nama || ''}":`);
            if (alasan === null) return;
            if (!alasan.trim()) {
                alert('Alasan penolakan harus diisi.');
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            fetch(`/admin/verifikasi/${id}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ reason: alasan })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    if (window.showToast) window.showToast(data.message || 'UMKM berhasil ditolak', 'warning');
                    if (window.triggerFilter) window.triggerFilter();
                    else location.reload();
                } else {
                    if (window.showToast) window.showToast(data.message || 'Gagal menolak UMKM', 'error');
                    else alert(data.message);
                }
            });
        }

        function exportData() {
            alert('Fitur export sedang dalam pengembangan');
        }

        // Smooth AJAX search and responsive filter behaviors
        (function() {
            let isFetching = false;

            window.performAjaxFetch = function(url) {
                const tableContainer = document.getElementById('umkmTableContainer');
                const filterForm = document.getElementById('filterForm');
                if (!tableContainer || isFetching) return;
                isFetching = true;

                tableContainer.style.opacity = '0.4';
                tableContainer.style.filter = 'blur(1px)';
                tableContainer.style.pointerEvents = 'none';

                const timestamp = new Date().getTime();
                const separator = url.includes('?') ? '&' : '?';
                const fetchUrl = url + separator + '_t=' + timestamp;

                const resetState = () => {
                    tableContainer.style.opacity = '1';
                    tableContainer.style.filter = 'none';
                    tableContainer.style.pointerEvents = 'auto';
                    isFetching = false;

                    if (filterForm) {
                        const searchInput = filterForm.querySelector('input[name="search"]');
                        if (searchInput && document.activeElement === searchInput) {
                            try {
                                const valLen = searchInput.value.length;
                                searchInput.setSelectionRange(valLen, valLen);
                            } catch(e) {}
                        }
                    }
                };

                fetch(fetchUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json, text/html',
                        'Cache-Control': 'no-cache'
                    }
                })
                .then(response => {
                    const contentType = response.headers.get('content-type') || '';
                    if (contentType.includes('application/json')) {
                        return response.json().then(data => ({ type: 'json', data }));
                    } else {
                        return response.text().then(html => ({ type: 'html', html }));
                    }
                })
                .then(result => {
                    try {
                        let newHtml = '';
                        if (result.type === 'json' && result.data && result.data.html) {
                            newHtml = result.data.html;
                        } else if (result.type === 'html' && result.html) {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(result.html, 'text/html');
                            const containerInDoc = doc.getElementById('umkmTableContainer');
                            newHtml = containerInDoc ? containerInDoc.innerHTML : result.html;
                        }

                        if (newHtml) {
                            tableContainer.innerHTML = newHtml;
                            if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                                window.Alpine.initTree(tableContainer);
                            }
                        }

                        const exportForm = document.querySelector('#exportModal form');
                        if (exportForm) {
                            const currentUrlParams = new URLSearchParams(new URL(url, window.location.origin).search);
                            ['search', 'kelurahan', 'kategori', 'sektor', 'status_verifikasi'].forEach(field => {
                                const input = exportForm.querySelector(`input[name="${field}"]`);
                                if (input) input.value = currentUrlParams.get(field) || '';
                            });
                        }

                        window.history.pushState({}, '', url);
                    } catch (e) {
                        console.error('Error rendering HTML:', e);
                    }
                    resetState();
                })
                .catch(err => {
                    console.error('AJAX Filter error:', err);
                    resetState();
                });
            }
        })();

        // Barcode Scanner Logic
        let html5QrCode = null;
        let scanHandled = false;

        function openScanModal() {
            document.getElementById('scanModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            scanHandled = false;

            setTimeout(() => {
                html5QrCode = new Html5Qrcode("reader");

                // Responsive qrbox size
                const config = {
                    fps: 10,
                    qrbox: function(viewfinderWidth, viewfinderHeight) {
                        let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                        let qrboxSize = Math.floor(minEdgeSize * 0.75); // 75% of screen
                        return { width: qrboxSize, height: qrboxSize };
                    }
                };

                const onScanSuccess = (decodedText) => {
                    if (scanHandled) return;
                    scanHandled = true;

                    document.getElementById('scan-status').classList.remove('hidden');
                    document.getElementById('scan-status-text').textContent = 'QR Code terbaca! Mengalihkan...';

                    html5QrCode.stop().then(() => {
                        window.location.href = "{{ route('admin.umkm.scan-result') }}?q=" + encodeURIComponent(decodedText);
                    }).catch(() => {
                        window.location.href = "{{ route('admin.umkm.scan-result') }}?q=" + encodeURIComponent(decodedText);
                    });
                };

                // Try starting with environment camera
                html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    onScanSuccess,
                    () => { /* ignore scan failure */ }
                ).catch(err => {
                    console.warn("Kamera environment gagal, mencoba kamera lain...", err);
                    // Fallback: try to get any camera
                    Html5Qrcode.getCameras().then(devices => {
                        if (devices && devices.length) {
                            // Use the last camera (usually the back camera)
                            let cameraId = devices[devices.length - 1].id;
                            html5QrCode.start(cameraId, config, onScanSuccess, () => {})
                            .catch(err2 => {
                                document.getElementById('scan-status').classList.remove('hidden');
                                document.getElementById('scan-status-text').textContent = 'Kamera tidak dapat diakses.';
                            });
                        } else {
                            document.getElementById('scan-status').classList.remove('hidden');
                            document.getElementById('scan-status-text').textContent = 'Tidak ada kamera terdeteksi.';
                        }
                    }).catch(err3 => {
                        document.getElementById('scan-status').classList.remove('hidden');
                        if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
                            document.getElementById('scan-status-text').textContent = 'Akses kamera butuh HTTPS!';
                        } else {
                            document.getElementById('scan-status-text').textContent = 'Kamera diblokir / tidak diizinkan.';
                        }
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

            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("reader");
            }

            html5QrCode.scanFile(file, true)
                .then(decodedText => {
                    document.getElementById('scan-status-text').textContent = 'QR Code terbaca! Mengalihkan...';
                    window.location.href = "{{ route('admin.umkm.scan-result') }}?q=" + encodeURIComponent(decodedText);
                })
                .catch(err => {
                    document.getElementById('scan-status-text').textContent = 'QR Code tidak ditemukan pada gambar.';
                    e.target.value = ''; // Reset input
                });
        });

        function detailUmkm(id) {
            const modalContent = document.getElementById('modalContent');
            const modalFooter = document.getElementById('modalFooter');

            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'detailModal' }));
            modalContent.innerHTML =
                `<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div><p class="mt-2 text-slate-700">Memuat data...</p></div>`;
            modalFooter.innerHTML = `<button onclick="closeModal()" class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">Tutup</button>`;

            fetch(`/admin/umkm/${id}/modal`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modalContent.innerHTML = data.html;
                    } else {
                        modalContent.innerHTML =
                            `<div class="text-center py-8 text-red-500"><i class="mdi mdi-alert-circle text-4xl mb-2"></i><p>${data.message || 'Gagal memuat data.'}</p></div>`;
                    }
                })
                .catch(error => {
                    modalContent.innerHTML =
                        `<div class="text-center py-8 text-red-500"><i class="mdi mdi-alert-circle text-4xl mb-2"></i><p>Terjadi kesalahan koneksi.</p></div>`;
                });
        }

        function closeModal() {
            window.dispatchEvent(new CustomEvent('close-modal', { detail: 'detailModal' }));
        }
    </script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
@endsection
