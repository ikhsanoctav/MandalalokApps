@extends('layouts.admin')
@section('title', 'Verifikasi Akun (KTP)')
@section('breadcrumb', 'Verifikasi / Akun Pemilik')

@section('content')
    <div class="space-y-4 md:space-y-6">

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

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
                    <input type="text" name="search" placeholder="Cari nama lengkap..." value="{{ request('search') }}"
                        class="w-full px-3 py-2 md:px-4 md:py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">

                    <div class="flex gap-2 lg:col-span-2">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-all">
                            Cari
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
