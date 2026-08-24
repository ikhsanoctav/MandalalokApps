@extends('layouts.pelaku')

@section('title', 'Katalog Produk UMKM')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Katalog Produk</h2>
        <p class="text-sm text-slate-500">Kelola daftar produk untuk UMKM Anda.</p>
    </div>
    <a href="{{ route('pelaku.produk.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg font-medium transition-colors shadow-lg shadow-blue-500/20">
        <i class="mdi mdi-plus text-lg"></i>
        <span>Tambah Produk</span>
    </a>
</div>

@php $activeFiltersProduk = array_filter([request('search'), request('umkm_id')]); @endphp
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
    <form id="filter-form" method="GET" action="{{ route('pelaku.produk.index') }}" class="flex flex-wrap gap-3 items-center">
        <div class="relative flex-1 min-w-[200px]">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><i class="mdi mdi-magnify"></i></span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau deskripsi produk..."
                class="pl-9 pr-4 py-2.5 w-full bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
        </div>
        @if($umkms->count() > 1)
        <div class="relative">
            <select name="umkm_id" onchange="window.triggerFilter()" class="appearance-none bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 pl-4 pr-9 py-2.5 focus:outline-none transition-all cursor-pointer {{ request('umkm_id') ? 'border-blue-400 bg-blue-50 text-blue-700' : '' }}">
                <option value="">Semua Usaha</option>
                @foreach($umkms as $u)
                    <option value="{{ $u->id_umkm }}" {{ request('umkm_id') == $u->id_umkm ? 'selected' : '' }}>{{ $u->nama_usaha }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400"><i class="mdi mdi-chevron-down text-sm"></i></div>
        </div>
        @endif
        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-500 font-semibold">Tampilkan:</span>
            <select name="per_page" onchange="window.triggerFilter()" class="appearance-none bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 pl-3 pr-8 py-2.5 focus:outline-none transition-all cursor-pointer">
                @foreach([10, 20, 50] as $pp)
                    <option value="{{ $pp }}" {{ request('per_page', 10) == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                @endforeach
            </select>
        </div>
        <button type="button" onclick="window.triggerFilter()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-all shadow-sm">Cari</button>
        @if(count($activeFiltersProduk) > 0)
            <a href="{{ route('pelaku.produk.index') }}" class="px-4 py-2.5 bg-rose-50 border border-rose-200 text-rose-600 text-sm font-bold rounded-lg hover:bg-rose-100 transition-all">Reset</a>
        @endif
        <div class="ml-auto text-xs text-slate-500">
            Menampilkan <span class="font-black text-slate-700">{{ $produks->firstItem() ?? 0 }}–{{ $produks->lastItem() ?? 0 }}</span> dari <span class="font-black text-blue-600">{{ $produks->total() }}</span> produk
        </div>
    </form>
</div>

<div id="table-container">
    @include('pelaku.produk.table-data')
</div>

@push('scripts')
<script>
    function produkIndex() {
        return {
            deleteData(id, name, event) {
                const card = event.target.closest('.bg-white.rounded-2xl');
                this.$dispatch('open-confirm-modal', {
                    title: 'Hapus Produk',
                    message: `Apakah Anda yakin ingin menghapus produk "${name}"?`,
                    confirmText: 'Ya, Hapus',
                    action: () => {
                        return fetch(`/pelaku/produk/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                window.showToast(data.message, 'success');
                                if (card) {
                                    card.style.transition = 'all 0.3s ease';
                                    card.style.opacity = '0';
                                    card.style.transform = 'scale(0.9)';
                                    setTimeout(() => card.remove(), 300);
                                }
                            } else {
                                window.showToast(data.message || 'Gagal menghapus data.', 'error');
                            }
                        })
                        .catch(() => {
                            window.showToast('Terjadi kesalahan jaringan.', 'error');
                        });
                    }
                });
            }
        }
    }
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
        const form = document.getElementById("filter-form");
        const url = new URL(form.action);
        const formData = new FormData(form);
        
        for (const [key, value] of formData.entries()) {
            if (value) {
                url.searchParams.append(key, value);
            }
        }
        
        const container = document.getElementById("table-container");
        if (container) {
            container.style.opacity = "0.5";
            container.style.pointerEvents = "none";
        }
        
        url.searchParams.append("ajax", "1");
        
        fetch(url.toString(), {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && container) {
                container.innerHTML = data.html;
                url.searchParams.delete("ajax");
                window.history.pushState({}, "", url.toString());
                attachPaginationListeners();
            }
        })
        .catch(error => console.error("Error fetching data:", error))
        .finally(() => {
            if (container) {
                container.style.opacity = "1";
                container.style.pointerEvents = "auto";
            }
        });
    };

    const searchInput = document.querySelector("input[name=\"search\"]");
    if (searchInput) {
        searchInput.addEventListener("input", debounce(() => window.triggerFilter(), 500));
    }

    function attachPaginationListeners() {
        document.querySelectorAll(".pagination a").forEach(link => {
            const newLink = link.cloneNode(true);
            link.parentNode.replaceChild(newLink, link);
            
            newLink.addEventListener("click", function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const form = document.getElementById("filter-form");
                const formData = new FormData(form);
                
                for (const [key, value] of formData.entries()) {
                    if (value && !url.searchParams.has(key)) {
                        url.searchParams.append(key, value);
                    }
                }
                
                const container = document.getElementById("table-container");
                if (container) {
                    container.style.opacity = "0.5";
                    container.style.pointerEvents = "none";
                }
                
                url.searchParams.append("ajax", "1");
                
                fetch(url.toString(), {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && container) {
                        container.innerHTML = data.html;
                        url.searchParams.delete("ajax");
                        window.history.pushState({}, "", url.toString());
                        attachPaginationListeners();
                        window.scrollTo({top: 0, behavior: "smooth"});
                    }
                })
                .catch(error => console.error("Error fetching data:", error))
                .finally(() => {
                    if (container) {
                        container.style.opacity = "1";
                        container.style.pointerEvents = "auto";
                    }
                });
            });
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        attachPaginationListeners();
    });
</script>
@endpush

@endsection
