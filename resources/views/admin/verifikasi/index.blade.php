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

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 md:gap-4">
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

                    <select name="sort"
                        class="w-full px-3 py-2 md:px-4 md:py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                        <option value="a-z" {{ request('sort') == 'a-z' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="z-a" {{ request('sort') == 'z-a' ? 'selected' : '' }}>Nama Z-A</option>
                    </select>

                    <div class="flex gap-2">
                        <select name="per_page"
                            class="flex-1 px-3 py-2 md:px-4 md:py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <button type="button" onclick="window.triggerFilter()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-all">
                            Cari
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div id="table-container">
            @include('admin.verifikasi.table-data')
        </div>
    </div>

    @push('scripts')
    <script>
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        window.triggerFilter = function() {
            const form = document.getElementById('filterForm');
            const url = new URL(form.action);
            const formData = new FormData(form);
            
            for (const [key, value] of formData.entries()) {
                if (value) {
                    url.searchParams.append(key, value);
                }
            }
            
            const container = document.getElementById('table-container');
            if (container) {
                container.style.opacity = '0.5';
                container.style.pointerEvents = 'none';
            }
            
            url.searchParams.append('ajax', '1');
            
            fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && container) {
                    container.innerHTML = data.html;
                    url.searchParams.delete('ajax');
                    window.history.pushState({}, '', url.toString());
                    attachPaginationListeners();
                }
            })
            .catch(error => console.error('Error fetching data:', error))
            .finally(() => {
                if (container) {
                    container.style.opacity = '1';
                    container.style.pointerEvents = 'auto';
                }
            });
        };

        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', debounce(() => window.triggerFilter(), 500));
        }

        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', () => window.triggerFilter());
            });
        }

        function attachPaginationListeners() {
            document.querySelectorAll('.pagination a').forEach(link => {
                const newLink = link.cloneNode(true);
                link.parentNode.replaceChild(newLink, link);
                
                newLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    const form = document.getElementById('filterForm');
                    const formData = new FormData(form);
                    
                    for (const [key, value] of formData.entries()) {
                        if (value && !url.searchParams.has(key)) {
                            url.searchParams.append(key, value);
                        }
                    }
                    
                    const container = document.getElementById('table-container');
                    if (container) {
                        container.style.opacity = '0.5';
                        container.style.pointerEvents = 'none';
                    }
                    
                    url.searchParams.append('ajax', '1');
                    
                    fetch(url.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && container) {
                            container.innerHTML = data.html;
                            url.searchParams.delete('ajax');
                            window.history.pushState({}, '', url.toString());
                            attachPaginationListeners();
                            window.scrollTo({top: 0, behavior: 'smooth'});
                        }
                    })
                    .catch(error => console.error('Error fetching data:', error))
                    .finally(() => {
                        if (container) {
                            container.style.opacity = '1';
                            container.style.pointerEvents = 'auto';
                        }
                    });
                });
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            attachPaginationListeners();
        });
    </script>
    @endpush
@endsection
