@extends('layouts.superadmin')
@section('title', 'Daftar UMKM')
@section('breadcrumb', 'Master Data UMKM / Daftar UMKM')

@section('content')
    <div class="space-y-4 md:space-y-6">

        <form method="GET" action="{{ route('superadmin.umkm') }}" id="filterForm">
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
                    <a href="{{ route('superadmin.umkm') }}" class="text-xs text-blue-600 hover:text-blue-800">Reset
                        Filter</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-7 gap-3 md:gap-4">
                    <input type="text" name="search" placeholder="Cari nama usaha..." value="{{ request('search') }}"
                        oninput="clearTimeout(window.searchDebounceTimer); window.searchDebounceTimer = setTimeout(() => window.triggerFilter && window.triggerFilter(), 250)"
                        class="border border-slate-300 rounded-lg px-3 py-2 md:px-4 md:py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">

                    <select name="kelurahan" onchange="window.triggerFilter && window.triggerFilter()"
                        class="border border-slate-300 rounded-lg px-3 py-2 md:px-4 md:py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Semua Kelurahan</option>
                        @foreach ($kelurahans as $kelurahanItem)
                            <option value="{{ $kelurahanItem }}"
                                {{ request('kelurahan') == $kelurahanItem ? 'selected' : '' }}>
                                {{ $kelurahanItem }}
                            </option>
                        @endforeach
                    </select>

                    <select name="kategori" onchange="window.triggerFilter && window.triggerFilter()"
                        class="border border-slate-300 rounded-lg px-3 py-2 md:px-4 md:py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $kategoriItem)
                            <option value="{{ $kategoriItem->id }}"
                                {{ request('kategori') == $kategoriItem->id ? 'selected' : '' }}>
                                {{ $kategoriItem->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    <select name="sektor" onchange="window.triggerFilter && window.triggerFilter()"
                        class="border border-slate-300 rounded-lg px-3 py-2 md:px-4 md:py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Semua Sektor</option>
                        @foreach ($sektors as $sektorItem)
                            <option value="{{ $sektorItem->id }}"
                                {{ request('sektor') == $sektorItem->id ? 'selected' : '' }}>
                                {{ $sektorItem->nama_sektor }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status_verifikasi" onchange="window.triggerFilter && window.triggerFilter()"
                        class="border border-slate-300 rounded-lg px-3 py-2 md:px-4 md:py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Semua Status</option>
                        <option value="terverifikasi"
                            {{ request('status_verifikasi') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="terkirim" {{ request('status_verifikasi') == 'terkirim' ? 'selected' : '' }}>Menunggu
                        </option>
                        <option value="ditolak" {{ request('status_verifikasi') == 'ditolak' ? 'selected' : '' }}>Ditolak
                        </option>
                    </select>

                    <select name="sort" onchange="window.triggerFilter && window.triggerFilter()"
                        class="border border-slate-300 rounded-lg px-3 py-2 md:px-4 md:py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                        <option value="a-z" {{ request('sort') == 'a-z' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="z-a" {{ request('sort') == 'z-a' ? 'selected' : '' }}>Nama Z-A</option>
                    </select>

                    <div class="flex gap-2">
                        <select name="per_page" onchange="window.triggerFilter && window.triggerFilter()"
                            class="flex-1 border border-slate-300 rounded-lg px-3 py-2 md:px-4 md:py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
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

        <div class="flex justify-end gap-2">
            <button type="button" onclick="document.getElementById('exportModal').classList.remove('hidden')"
                class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-all flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Data
            </button>
            <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden')"
                class="px-4 py-2 text-sm text-white bg-emerald-600 rounded-md hover:bg-emerald-700 transition-all flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Import Excel
            </button>
            <a href="{{ route('superadmin.umkm.create') }}"
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
                                @elseif($umkm->status_verifikasi == 'terkirim')
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
                                <p class="text-slate-700">Pemilik</p>
                                <p class="font-medium text-slate-700">{{ $umkm->pemilik?->nama_lengkap ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-slate-700">Kelurahan</p>
                                <p class="font-medium text-slate-700">{{ $umkm->pemilik?->kelurahan ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-slate-700">Tanggal Daftar</p>
                                <p class="font-medium text-slate-700">
                                    {{ $umkm->created_at ? $umkm->created_at->format('d/m/Y') : '-' }}</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-2 border-t">
                            <a href="{{ route('superadmin.umkm.show', $umkm->id_umkm) }}" title="Lihat Detail"
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-2xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </a>
                            <a href="{{ route('superadmin.umkm.edit', $umkm->id_umkm) }}" title="Edit Data"
                                class="p-2 text-amber-600 hover:bg-amber-50 rounded-2xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </a>
                            <button onclick="confirmDelete('{{ $umkm->id_umkm }}', this.dataset.nama)" data-nama="{{ e($umkm->nama_usaha) }}"
                                class="p-2 text-red-600 hover:bg-red-50 rounded-2xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
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
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Nama Usaha</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Pemilik</th>

                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Tgl Daftar</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-slate-700 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($umkms as $index => $umkm)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-slate-700">{{ $umkms->firstItem() + $index }}</td>
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
                                    @elseif($umkm->status_verifikasi == 'terkirim')
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
                                <td class="px-4 py-3 text-sm text-slate-700">
                                    {{ $umkm->created_at ? $umkm->created_at->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('superadmin.umkm.show', $umkm->id_umkm) }}" title="Lihat Detail"
                                            class="text-blue-600 hover:bg-blue-50 p-1.5 rounded-2xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('superadmin.umkm.edit', $umkm->id_umkm) }}" title="Edit Data"
                                            class="text-amber-600 hover:bg-amber-50 p-1.5 rounded-2xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <button
                                            onclick="confirmDelete('{{ $umkm->id_umkm }}', this.dataset.nama)" data-nama="{{ e($umkm->nama_usaha) }}"
                                            class="text-red-600 hover:bg-red-50 p-1.5 rounded-2xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                            </tr>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-400">
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

    <div id="importModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="document.getElementById('importModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('superadmin.umkm.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Import Data UMKM
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-3">
                                        Unggah file Excel (.xlsx, .csv) yang berisi data UMKM. Belum punya formatnya?
                                    </p>
                                    <a href="{{ route('superadmin.umkm.import.template') }}"
                                        class="inline-flex items-center gap-2 text-sm text-emerald-600 hover:text-emerald-700 font-medium mb-4">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Unduh Template Data
                                    </a>

                                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 mb-4 text-xs text-blue-700">
                                        <p class="font-bold mb-1"><i class="fas fa-info-circle"></i> Panduan Kolom:</p>
                                        <ul class="list-disc ml-4 space-y-0.5">
                                            <li><strong>sektor</strong>: Isi sesuai nama sektor yang ada di sistem (misal: Kuliner, Fashion, Jasa, dll).</li>
                                            <li><strong>bentuk_jualan</strong>: Contoh: Online, Offline, Online & Offline.</li>
                                            <li><strong>perkiraan_omset</strong>: Contoh: 5000000, 15000000.</li>
                                            <li><strong>jenis_kelamin</strong>: L / P</li>
                                        </ul>
                                    </div>

                                    <input type="file" name="file_excel" accept=".xlsx, .csv, .xls" required
                                        class="block w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-200 rounded-md">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Upload & Import
                        </button>
                        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                            class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="exportModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('exportModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <form action="{{ route('superadmin.laporan.export') }}" method="POST">
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

        function verifyUmkm(id, nama) {
            window.dispatchEvent(new CustomEvent('open-confirm-modal', {
                detail: {
                    title: 'Verifikasi / Setujui UMKM',
                    message: `Apakah Anda yakin ingin menyetujui dan memverifikasi data UMKM "${nama || ''}"?`,
                    confirmText: 'Ya, Verifikasi',
                    action: () => {
                        const token = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
                        return fetch(`/superadmin/umkm/${id}/verify`, {
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

        function rejectUmkm(id, nama) {
            const alasan = prompt(`Masukkan alasan penolakan/pembatalan verifikasi untuk UMKM "${nama || ''}":`);
            if (alasan === null) return;
            if (!alasan.trim()) {
                alert('Alasan penolakan harus diisi.');
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            fetch(`/superadmin/umkm/${id}/reject`, {
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

        function confirmDelete(id, nama) {
            const event = window.event;
            const target = event ? event.target : null;
            // Temukan baris (tr) atau card grid
            const cardOrRow = target ? target.closest('.bg-white.rounded-3xl, tr') : null;
            
            window.dispatchEvent(new CustomEvent('open-confirm-modal', {
                detail: {
                    title: 'Hapus UMKM',
                    message: `Apakah Anda yakin ingin menghapus UMKM "${nama}"? Semua data produk dan file akan ikut terhapus.`,
                    confirmText: 'Ya, Hapus',
                    action: () => {
                        const token = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

                        return fetch(`/superadmin/umkm/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (window.showToast) {
                                    window.showToast(data.message || `UMKM "${nama}" berhasil dihapus`, 'success');
                                }
                                if (cardOrRow) {
                                    cardOrRow.style.transition = 'all 0.3s ease';
                                    cardOrRow.style.opacity = '0';
                                    cardOrRow.style.transform = 'scale(0.9)';
                                    setTimeout(() => cardOrRow.remove(), 300);
                                } else {
                                    setTimeout(() => location.reload(), 1500);
                                }
                            } else {
                                if (window.showToast) {
                                    window.showToast(data.message || 'Gagal menghapus UMKM', 'error');
                                } else {
                                    alert('Gagal menghapus: ' + data.message);
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            if (window.showToast) {
                                window.showToast('Terjadi kesalahan saat menghapus', 'error');
                            }
                        });
                    }
                }
            }));
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

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json, text/html'
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
                        if (window.Alpine) {
                            window.Alpine.initTree(tableContainer);
                        }
                    }

                    const exportForm = document.querySelector('#exportModal form');
                    if (exportForm) {
                        const currentUrlParams = new URLSearchParams(new URL(url).search);
                        ['search', 'kelurahan', 'kategori', 'sektor', 'status_verifikasi'].forEach(field => {
                            const input = exportForm.querySelector(`input[name="${field}"]`);
                            if (input) input.value = currentUrlParams.get(field) || '';
                        });
                    }

                    window.history.pushState({}, '', url);
                })
                .catch(err => {
                    console.error('AJAX Filter error:', err);
                })
                .finally(() => {
                    tableContainer.style.opacity = '1';
                    tableContainer.style.filter = 'none';
                    tableContainer.style.pointerEvents = 'auto';
                    isFetching = false;

                    if (filterForm) {
                        const searchInput = filterForm.querySelector('input[name="search"]');
                        if (searchInput && document.activeElement === searchInput) {
                            const valLen = searchInput.value.length;
                            searchInput.setSelectionRange(valLen, valLen);
                        }
                    }
                });
            };

            window.triggerFilter = function() {
                const filterForm = document.getElementById('filterForm');
                if (!filterForm) return;

                const formData = new FormData(filterForm);
                const params = new URLSearchParams();
                for (const [key, val] of formData.entries()) {
                    if (val !== null && val !== undefined && val.toString().trim() !== '') {
                        params.append(key, val);
                    }
                }
                const actionUrl = filterForm.action.split('?')[0];
                const fullUrl = actionUrl + (params.toString() ? '?' + params.toString() : '');
                window.performAjaxFetch(fullUrl);
            };

            function setupListeners() {
                const filterForm = document.getElementById('filterForm');
                const tableContainer = document.getElementById('umkmTableContainer');
                if (!filterForm || !tableContainer) return;

                filterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    window.triggerFilter();
                });

                const resetLink = filterForm.querySelector('a[href*="superadmin/umkm"]');
                if (resetLink) {
                    resetLink.addEventListener('click', function(e) {
                        e.preventDefault();
                        filterForm.querySelectorAll('input[type="text"]').forEach(i => i.value = '');
                        filterForm.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
                        window.triggerFilter();
                    });
                }

                tableContainer.addEventListener('click', function(e) {
                    const link = e.target.closest('a');
                    if (link && link.href && tableContainer.contains(link) && !link.hasAttribute('data-no-ajax')) {
                        const isPagination = link.closest('nav') || link.closest('.pagination') || link.href.includes('page=');
                        if (isPagination) {
                            e.preventDefault();
                            window.performAjaxFetch(link.href);
                        }
                    }
                });

                window.addEventListener('popstate', function() {
                    window.performAjaxFetch(window.location.href);
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', setupListeners);
            } else {
                setupListeners();
            }
        })();
    </script>
@endsection
