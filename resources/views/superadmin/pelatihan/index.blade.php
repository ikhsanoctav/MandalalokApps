@extends('layouts.superadmin')
@section('content')

<div x-data="{ showCreateModal: {{ $errors->any() ? 'true' : 'false' }} }">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Pelatihan</h1>
            <p class="text-sm text-slate-700 mt-1">Kelola data pelatihan yang diwajibkan untuk pelaku UMKM.</p>
        </div>
        <button type="button" @click="showCreateModal = true" class="btn-primary inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white rounded-xl transition-all">
            <i class="fa-solid fa-plus"></i>
            <span>Buat Pelatihan Baru</span>
        </button>
    </div>

    @php $activeFiltersPelatihan = array_filter([request('search'), request('status')]); @endphp
    <div class="bg-white/90 backdrop-blur-sm shadow-sm rounded-3xl border p-5 mb-4">
        <form id="filter-form" method="GET" action="{{ route('superadmin.pelatihan.index') }}" class="flex flex-wrap gap-3 items-center">
            <div class="relative flex-1 min-w-[200px]">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><i class="mdi mdi-magnify"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau lokasi pelatihan..."
                    class="pl-9 pr-4 py-2.5 w-full bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <div class="relative">
                <select name="status" onchange="window.triggerFilter()" class="appearance-none bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 pl-4 pr-9 py-2.5 focus:outline-none transition-all cursor-pointer {{ request('status') ? 'border-blue-400 bg-blue-50 text-blue-700' : '' }}">
                    <option value="">Semua Status</option>
                    <option value="published"  {{ request('status') === 'published'  ? 'selected' : '' }}>✅ Published</option>
                    <option value="draft"      {{ request('status') === 'draft'      ? 'selected' : '' }}>📝 Draft</option>
                    <option value="completed"  {{ request('status') === 'completed'  ? 'selected' : '' }}>🏁 Selesai</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400"><i class="mdi mdi-chevron-down text-sm"></i></div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-500 font-semibold">Tampilkan:</span>
                <select name="per_page" onchange="window.triggerFilter()" class="appearance-none bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 pl-3 pr-8 py-2.5 focus:outline-none transition-all cursor-pointer">
                    @foreach([15, 25, 50] as $pp)
                        <option value="{{ $pp }}" {{ request('per_page', 15) == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" onclick="window.triggerFilter()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm">Cari</button>
            @if(count($activeFiltersPelatihan) > 0)
                <a href="{{ route('superadmin.pelatihan.index') }}" class="px-4 py-2.5 bg-rose-50 border border-rose-200 text-rose-600 text-sm font-bold rounded-xl hover:bg-rose-100 transition-all">Reset</a>
            @endif
            <div class="ml-auto text-xs text-slate-500">
                Menampilkan <span class="font-black text-slate-700">{{ $pelatihans->firstItem() ?? 0 }}–{{ $pelatihans->lastItem() ?? 0 }}</span> dari <span class="font-black text-blue-600">{{ $pelatihans->total() }}</span>
            </div>
        </form>
    </div>

<div id="table-container">
    @include('superadmin.pelatihan.table-data')
</div>

<!-- Modal Tambah Pelatihan -->
<div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <div x-show="showCreateModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" 
             @click="showCreateModal = false" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="showCreateModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl w-full border border-slate-100">
            
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-lg leading-6 font-bold text-slate-800" id="modal-title">
                    Tambah Pelatihan Baru
                </h3>
                <button @click="showCreateModal = false" class="text-slate-400 hover:text-slate-700 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('superadmin.pelatihan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="px-6 py-5 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Banner Pelatihan (Opsional)</label>
                            <input type="file" name="banner" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-[10px] text-slate-700 mt-1">Maks 2MB, rasio ideal 16:9 (Landscape).</p>
                            @error('banner') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Pelatihan <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" value="{{ old('judul') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Misal: Pelatihan Pemasaran Digital">
                            @error('judul') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi Pelatihan</label>
                            <textarea name="deskripsi" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Jelaskan tujuan dan materi yang akan dipelajari...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Waktu Mulai <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            @error('tanggal_mulai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Waktu Selesai <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            @error('tanggal_selesai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lokasi <span class="text-red-500">*</span></label>
                            <input type="text" name="lokasi" value="{{ old('lokasi') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Misal: Aula Kecamatan, atau Link Zoom">
                            @error('lokasi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kuota Peserta <span class="text-red-500">*</span></label>
                            <input type="number" name="kuota" value="{{ old('kuota', 50) }}" required min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            @error('kuota') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2" x-data="{ syarat: [''] }">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Syarat Dokumen (Opsional)</label>
                            <p class="text-xs text-slate-700 mb-2">Tambahkan daftar dokumen yang wajib diunggah pelaku UMKM saat mendaftar.</p>
                            
                            <template x-for="(s, index) in syarat" :key="index">
                                <div class="flex gap-2 mb-2">
                                    <input type="text" x-model="syarat[index]" name="syarat_dokumen[]" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Misal: Fotokopi KTP">
                                    <button type="button" @click="syarat.splice(index, 1)" x-show="syarat.length > 1" class="px-4 py-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-colors">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </template>
                            
                            <button type="button" @click="syarat.push('')" class="mt-1 text-xs text-blue-600 font-semibold hover:text-blue-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-plus-circle"></i> Tambah Syarat Lainnya
                            </button>
                            
                            @error('syarat_dokumen') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status Publikasi <span class="text-red-500">*</span></label>
                            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Belum Tampil di Pelaku)</option>
                                <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published (Tampil & Bisa Daftar)</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all">Batal</button>
                    <button type="submit" class="btn-primary px-6 py-2.5 text-sm font-bold text-white rounded-xl transition-all shadow-md">Simpan Pelatihan</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div> <!-- end x-data div -->

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
