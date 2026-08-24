@extends('layouts.superadmin')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Laporan Masyarakat (AI Sentiment)</h1>
        <p class="text-sm text-slate-700">Daftar pengaduan warga beserta analisis sentimen otomatis dari Ollama AI.</p>
    </div>
</div>
@endsection

@section('content')
@php $activeFiltersPengaduan = array_filter([request('search'), request('status'), request('date_from'), request('date_to')]); @endphp
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-4">
    <form id="filter-form" method="GET" action="{{ route('superadmin.pengaduan.index') }}" class="flex flex-wrap gap-3 items-center">
        <div class="relative flex-1 min-w-[200px]">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><i class="mdi mdi-magnify"></i></span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pelapor, judul, atau isi..."
                class="pl-9 pr-4 py-2.5 w-full bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
        </div>
        <div class="relative">
            <select name="status" onchange="window.triggerFilter()" class="appearance-none bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 pl-4 pr-9 py-2.5 focus:outline-none transition-all cursor-pointer {{ request('status') ? 'border-blue-400 bg-blue-50 text-blue-700' : '' }}">
                <option value="">Semua Status</option>
                <option value="Menunggu" {{ request('status') === 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                <option value="Diproses" {{ request('status') === 'Diproses' ? 'selected' : '' }}>🔄 Diproses</option>
                <option value="Selesai"  {{ request('status') === 'Selesai'  ? 'selected' : '' }}>✅ Selesai</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400"><i class="mdi mdi-chevron-down text-sm"></i></div>
        </div>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none text-slate-400 text-xs font-bold">Dari</div>
            <input type="date" name="date_from" value="{{ request('date_from') }}" onchange="window.triggerFilter()"
                class="pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 focus:outline-none transition-all {{ request('date_from') ? 'border-blue-400 bg-blue-50 text-blue-700' : '' }}">
        </div>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none text-slate-400 text-xs font-bold">S/d</div>
            <input type="date" name="date_to" value="{{ request('date_to') }}" onchange="window.triggerFilter()"
                class="pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 focus:outline-none transition-all {{ request('date_to') ? 'border-blue-400 bg-blue-50 text-blue-700' : '' }}">
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-500 font-semibold">Tampilkan:</span>
            <select name="per_page" onchange="window.triggerFilter()" class="appearance-none bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 pl-3 pr-8 py-2.5 focus:outline-none transition-all cursor-pointer">
                @foreach([15, 25, 50] as $pp)
                    <option value="{{ $pp }}" {{ request('per_page', 15) == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                @endforeach
            </select>
        </div>
        <button type="button" onclick="window.triggerFilter()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-all shadow-sm">Cari</button>
        @if(count($activeFiltersPengaduan) > 0)
            <a href="{{ route('superadmin.pengaduan.index') }}" class="px-4 py-2.5 bg-rose-50 border border-rose-200 text-rose-600 text-sm font-bold rounded-lg hover:bg-rose-100 transition-all">Reset</a>
        @endif
        @if(isset($pengaduans) && method_exists($pengaduans, 'total'))
        <div class="ml-auto text-xs text-slate-500">
            Menampilkan <span class="font-black text-slate-700">{{ $pengaduans->firstItem() ?? 0 }}–{{ $pengaduans->lastItem() ?? 0 }}</span> dari <span class="font-black text-blue-600">{{ $pengaduans->total() }}</span>
        </div>
        @endif
    </form>
</div>

<div id="table-container">
    @include('superadmin.pengaduan.table-data')
</div>
@endsection
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
        const form = document.getElementById('filter-form');
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

    function attachPaginationListeners() {
        document.querySelectorAll('.pagination a').forEach(link => {
            const newLink = link.cloneNode(true);
            link.parentNode.replaceChild(newLink, link);
            
            newLink.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const form = document.getElementById('filter-form');
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
