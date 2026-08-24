@extends('layouts.superadmin')
@section('title', 'Manajemen Warta')
@section('breadcrumb', 'Master Data / Manajemen Warta')

@section('content')
    <div class="space-y-6" x-data="beritaIndex()">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Warta</h2>
            <a href="{{ route('superadmin.berita.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                + Tambah Warta
            </a>
        </div>

        <x-card class="!p-5">
            @php $activeFilters = array_filter([request('search'), request('status')]); @endphp
            <form id="filter-form" method="GET" action="{{ route('superadmin.berita.index') }}" class="flex flex-wrap gap-3 items-center">
                <div class="relative flex-1 min-w-[200px]">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><i class="mdi mdi-magnify"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, kategori, atau penulis..."
                        class="pl-9 pr-4 py-2.5 w-full bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>
                <div class="relative">
                    <select name="status" onchange="window.triggerFilter()" class="appearance-none bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 pl-4 pr-9 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer {{ request('status') ? 'border-blue-400 bg-blue-50 text-blue-700' : '' }}">
                        <option value="">Semua Status</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>✅ Published</option>
                        <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>📝 Draft</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400"><i class="mdi mdi-chevron-down text-sm"></i></div>
                </div>
                <div class="relative flex items-center gap-2">
                    <span class="text-xs text-slate-500 font-semibold">Tampilkan:</span>
                    <select name="per_page" onchange="window.triggerFilter()" class="appearance-none bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 pl-3 pr-8 py-2.5 focus:outline-none transition-all cursor-pointer">
                        @foreach([10, 25, 50] as $pp)
                            <option value="{{ $pp }}" {{ request('per_page', 10) == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" onclick="window.triggerFilter()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-all shadow-sm">Cari</button>
                @if(count($activeFilters) > 0)
                    <a href="{{ route('superadmin.berita.index') }}" class="px-4 py-2.5 bg-rose-50 border border-rose-200 text-rose-600 text-sm font-bold rounded-lg hover:bg-rose-100 transition-all">Reset</a>
                @endif
                <div class="ml-auto text-xs text-slate-500">
                    Menampilkan <span class="font-black text-slate-700">{{ $berita->firstItem() ?? 0 }}–{{ $berita->lastItem() ?? 0 }}</span> dari <span class="font-black text-blue-600">{{ $berita->total() }}</span>
                    @if(count($activeFilters) > 0) &mdash; <span class="text-blue-600 font-bold">{{ count($activeFilters) }} filter aktif</span>@endif
                </div>
            </form>
        </x-card>

        <x-card>
            <div id="table-container">
                @include('superadmin.master.berita.table-data')
            </div>
        </x-card>
    </div>

    @push('scripts')
    <script>
        function beritaIndex() {
            return {
                deleteData(id, title, event) {
                    const row = event.target.closest('tr');
                    this.$dispatch('open-confirm-modal', {
                        title: 'Hapus Warta',
                        message: `Apakah Anda yakin ingin menghapus warta "${title}"?`,
                        confirmText: 'Ya, Hapus',
                        action: () => {
                            return fetch(`/superadmin/berita/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    window.showToast('success', 'Berhasil!', data.message);
                                    if (row) {
                                        row.style.transition = 'all 0.3s ease';
                                        row.style.opacity = '0';
                                        row.style.transform = 'translateX(-20px)';
                                        setTimeout(() => row.remove(), 300);
                                    }
                                } else {
                                    window.showToast('error', 'Gagal!', data.message || 'Gagal menghapus data.');
                                }
                            })
                            .catch(() => {
                                window.showToast('error', 'Gagal!', 'Terjadi kesalahan jaringan.');
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
            if (!form) return;
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

        document.addEventListener("DOMContentLoaded", () => {
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener("input", debounce(() => window.triggerFilter(), 500));
            }
            attachPaginationListeners();
        });

        function attachPaginationListeners() {
            document.querySelectorAll(".pagination a").forEach(link => {
                const newLink = link.cloneNode(true);
                link.parentNode.replaceChild(newLink, link);
                
                newLink.addEventListener("click", function(e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    const form = document.getElementById("filter-form");
                    if (!form) return;
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
    </script>
    @endpush
@endsection
