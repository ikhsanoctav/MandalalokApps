@extends('layouts.admin')
@section('title', 'Verifikasi Data UMKM')
@section('breadcrumb', 'Verifikasi Lapangan / Data UMKM')

@section('content')
    <div class="space-y-4 md:space-y-6">

        <form method="GET" action="{{ route('admin.verifikasi.index') }}" id="filterForm">
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-slate-200 p-4 md:p-6">
                <div class="flex justify-between items-center mb-3 md:mb-4">
                    <h3 class="font-semibold text-slate-700 flex items-center gap-2 text-sm md:text-base">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        Filter Data Verifikasi
                    </h3>
                    <a href="{{ route('admin.verifikasi.index') }}" class="text-xs text-blue-600 hover:text-blue-800">Reset
                        Filter</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                    <input type="text" name="search" placeholder="Cari nama usaha..." value="{{ request('search') }}"
                        class="w-full px-3 py-2 md:px-4 md:py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">

                    <select name="kelurahan"
                        class="w-full px-3 py-2 md:px-4 md:py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">Semua Kelurahan</option>
                        @foreach ($kelurahans as $kelurahanItem)
                            <option value="{{ $kelurahanItem }}"
                                {{ request('kelurahan') == $kelurahanItem ? 'selected' : '' }}>
                                {{ $kelurahanItem }}
                            </option>
                        @endforeach
                    </select>

                    <select name="kategori"
                        class="w-full px-3 py-2 md:px-4 md:py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}"
                                {{ request('kategori') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex gap-2">
                        <select name="per_page"
                            class="flex-1 px-3 py-2 md:px-4 md:py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
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

        <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

            <div class="block md:hidden divide-y divide-slate-100">
                @forelse($umkms as $umkm)
                    <div class="p-4 space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-slate-800">{{ $umkm->nama_usaha }}</h4>
                                <p class="text-xs text-slate-400">{{ $umkm->no_pendaftaran ?? '-' }}</p>
                            </div>
                            <div>
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                    Menunggu Verifikasi
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <p class="text-slate-500">Pemilik</p>
                                <p class="font-medium text-slate-700">{{ $umkm->pemilik->nama_lengkap ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-slate-500">Kelurahan</p>
                                <p class="font-medium text-slate-700">{{ $umkm->pemilik->kelurahan ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-slate-500">Tanggal Daftar</p>
                                <p class="font-medium text-slate-700">
                                    {{ $umkm->created_at ? $umkm->created_at->format('d/m/Y') : '-' }}</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-2 border-t">
                            <a href="{{ route('admin.verifikasi.show', $umkm->id_umkm) }}"
                                class="px-4 py-2 text-sm text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 transition-all font-medium flex items-center gap-2">
                                Tinjau & Verifikasi
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
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
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>Verifikasi otomatis aktif. Tidak ada antrean verifikasi UMKM.</p>
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
                                    <p class="text-sm text-slate-600">{{ $umkm->pemilik->nama_lengkap ?? '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ $umkm->pemilik->kelurahan ?? '-' }}</p>
                                </td>

                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                        Menunggu Verif
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500">
                                    {{ $umkm->created_at ? $umkm->created_at->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.verifikasi.show', $umkm->id_umkm) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-md transition-all">
                                        Tinjau
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Verifikasi otomatis aktif. Tidak ada antrean verifikasi UMKM.
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

    <script>
        // Auto submit and responsive filter behaviors
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filterForm');
            if (filterForm) {
                filterForm.querySelectorAll('select').forEach(select => {
                    select.addEventListener('change', () => filterForm.submit());
                });

                let searchTimeout;
                const searchInput = filterForm.querySelector('input[name="search"]');
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            filterForm.submit();
                        }, 600);
                    });

                    if (searchInput.value.length > 0) {
                        searchInput.focus();
                        const len = searchInput.value.length;
                        searchInput.setSelectionRange(len, len);
                    }
                }
            }
        });
    </script>
@endsection
