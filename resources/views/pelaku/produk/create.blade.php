@extends('layouts.pelaku')

@section('title', 'Tambah Produk UMKM')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3 text-sm text-slate-500 mb-2">
        <a href="{{ route('pelaku.produk.index') }}" class="hover:text-blue-600 transition-colors">Katalog Produk</a>
        <i class="mdi mdi-chevron-right"></i>
        <span class="text-slate-800 font-medium">Tambah Produk</span>
    </div>
    <h2 class="text-2xl font-bold text-slate-800">Tambah Produk Baru</h2>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <form action="{{ route('pelaku.produk.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Pilihan UMKM -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Usaha/UMKM <span class="text-red-500">*</span></label>
                <select name="id_umkm" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block p-3" required>
                    <option value="" disabled selected>-- Pilih UMKM --</option>
                    @foreach($umkms as $umkm)
                        <option value="{{ $umkm->id_umkm }}" {{ old('id_umkm') == $umkm->id_umkm ? 'selected' : '' }}>
                            {{ $umkm->nama_usaha }} ({{ $umkm->kategori?->nama_kategori ?? 'Umum' }})
                        </option>
                    @endforeach
                </select>
                @error('id_umkm') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Nama Produk -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block p-3" placeholder="Contoh: Keripik Pisang Rasa Coklat" required>
                @error('nama_produk') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Harga Produk -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Produk (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block p-3" placeholder="Contoh: 25000">
                <p class="mt-1 text-xs text-slate-500">Kosongkan jika tidak ingin mencantumkan harga</p>
                @error('harga') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Deskripsi Produk -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Produk</label>
                <textarea name="deskripsi" rows="4" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block p-3" placeholder="Ceritakan detail produk Anda (Bahan, Ukuran, Varian, dll)">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Foto Produk (Multiple) -->
            <div class="md:col-span-2 border border-slate-200 rounded-xl p-5 bg-slate-50/50">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Produk / Etalase (Maks 7 Foto)</label>
                
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl bg-white hover:bg-slate-50 transition-colors relative" id="drop-zone">
                    <div class="space-y-2 text-center w-full">
                        <i class="mdi mdi-image-multiple-outline text-4xl text-slate-400"></i>
                        <div class="flex flex-col sm:flex-row items-center justify-center text-sm text-slate-600 gap-1">
                            <label for="foto_produk" class="relative cursor-pointer bg-white rounded-xl font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                <span>Pilih file gambar</span>
                                <input id="foto_produk" name="foto_produk[]" type="file" class="sr-only" multiple accept="image/*" onchange="previewImages(this)">
                            </label>
                            <p>atau tarik dan lepas di sini</p>
                        </div>
                        <p class="text-xs text-slate-500">PNG, JPG, JPEG maks 2MB per foto</p>
                    </div>
                </div>
                @error('foto_produk') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                @error('foto_produk.*') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror

                <!-- Preview Container -->
                <div id="image-preview-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-4 hidden">
                    <!-- Images will be previewed here -->
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
            <a href="{{ route('pelaku.produk.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-md hover:bg-slate-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
                Simpan Produk
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewImages(input) {
        const container = document.getElementById('image-preview-container');
        container.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            if(input.files.length > 7) {
                alert('Maksimal 7 foto yang diizinkan!');
                input.value = '';
                container.classList.add('hidden');
                return;
            }
            
            container.classList.remove('hidden');
            
            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-square rounded-2xl overflow-hidden border border-slate-200 group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-white text-xs font-medium">Foto ${index + 1}</span>
                        </div>
                    `;
                    container.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        } else {
            container.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection
