@extends('layouts.admin')
@section('title', 'Verifikasi Akun (KTP)')
@section('breadcrumb', 'Verifikasi / Akun Pemilik')

@section('content')
    <div class="space-y-4 md:space-y-6">

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Card -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-md border-0 p-5 flex items-center justify-between text-white">
                <div>
                    <p class="text-sm font-medium text-blue-100 mb-1">Total Akun</p>
                    <h3 class="text-3xl font-bold">{{ $countTotal }}</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="mdi mdi-account-group text-2xl text-white"></i>
                </div>
            </div>

            <!-- Pending Card -->
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-md border-0 p-5 flex items-center justify-between text-white">
                <div>
                    <p class="text-sm font-medium text-amber-100 mb-1">Menunggu Verifikasi</p>
                    <h3 class="text-3xl font-bold">{{ $countPending }}</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="mdi mdi-clock-outline text-2xl text-white"></i>
                </div>
            </div>

            <!-- Verified Card -->
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-md border-0 p-5 flex items-center justify-between text-white">
                <div>
                    <p class="text-sm font-medium text-emerald-100 mb-1">Terverifikasi</p>
                    <h3 class="text-3xl font-bold">{{ $countTerverifikasi }}</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="mdi mdi-check-decagram text-2xl text-white"></i>
                </div>
            </div>

            <!-- Rejected Card -->
            <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl shadow-md border-0 p-5 flex items-center justify-between text-white">
                <div>
                    <p class="text-sm font-medium text-rose-100 mb-1">Ditolak</p>
                    <h3 class="text-3xl font-bold">{{ $countDitolak }}</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="mdi mdi-close-circle-outline text-2xl text-white"></i>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route(auth()->user()->hasRole('super_admin') ? 'superadmin.verifikasi_akun.index' : 'admin.verifikasi_akun.index') }}" id="filterForm">
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-slate-200 p-4 md:p-6">
                <div class="flex justify-between items-center mb-3 md:mb-4">
                    <h3 class="font-semibold text-slate-700 flex items-center gap-2 text-sm md:text-base">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        Filter Data Verifikasi Akun
                    </h3>
                    <a href="{{ route(auth()->user()->hasRole('super_admin') ? 'superadmin.verifikasi_akun.index' : 'admin.verifikasi_akun.index') }}" class="text-xs text-blue-600 hover:text-blue-800">Reset
                        Filter</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 items-end">
                    <div class="w-full">
                        <label class="block text-xs text-slate-500 font-semibold mb-1">Status Verifikasi</label>
                        <select name="status" class="w-full px-3 py-2 md:px-4 md:py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="semua" {{ $status === 'semua' ? 'selected' : '' }}>Semua Status</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="terverifikasi" {{ $status === 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div class="w-full">
                        <label class="block text-xs text-slate-500 font-semibold mb-1">Pencarian</label>
                        <input type="text" name="search" placeholder="Cari nama atau NIK..." value="{{ request('search') }}"
                            class="w-full px-3 py-2 md:px-4 md:py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </div>

                    <div class="w-full">
                        <label class="block text-xs text-slate-500 font-semibold mb-1">Tampilkan</label>
                        <select name="per_page" class="w-full px-3 py-2 md:px-4 md:py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            @foreach([10, 25, 50] as $pp)
                                <option value="{{ $pp }}" {{ request('per_page', 10) == $pp ? 'selected' : '' }}>{{ $pp }} Baris</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="w-full px-4 py-2 md:py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-all">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div id="table-container">
                @include('admin.verifikasi_akun.table-data')
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filterForm');
            if (filterForm) {
                let searchTimeout;
                const searchInput = filterForm.querySelector('input[name="search"]');
                const statusSelect = filterForm.querySelector('select[name="status"]');
                
                if (statusSelect) {
                    statusSelect.addEventListener('change', function() {
                        filterForm.submit();
                    });
                }
                
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            filterForm.submit();
                        }, 600);
                    });
                }
            }
        });
    </script>
@endsection
