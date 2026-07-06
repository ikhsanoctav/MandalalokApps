@extends('layouts.pelaku')

@section('title', 'Edit Produk UMKM')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3 text-sm text-slate-500 mb-2">
        <a href="{{ route('pelaku.produk.index') }}" class="hover:text-blue-600 transition-colors">Katalog Produk</a>
        <i class="mdi mdi-chevron-right"></i>
        <span class="text-slate-800 font-medium">Edit Produk</span>
    </div>
    <h2 class="text-2xl font-bold text-slate-800">Edit Produk: {{ $produk->nama_produk }}</h2>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <form action="{{ route('pelaku.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Pilihan UMKM -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Usaha/UMKM <span class="text-red-500">*</span></label>
                <select name="id_umkm" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block p-3" required>
                    @foreach($umkms as $umkm)
                        <option value="{{ $umkm->id_umkm }}" {{ old('id_umkm', $produk->id_umkm) == $umkm->id_umkm ? 'selected' : '' }}>
                            {{ $umkm->nama_usaha }}
                        </option>
                    @endforeach
                </select>
                @error('id_umkm') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Nama Produk -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block p-3" required>
                @error('nama_produk') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Harga Produk -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Produk (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga', $produk->harga ? (int) $produk->harga : '') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block p-3">
                @error('harga') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Deskripsi Produk -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Produk</label>
                <textarea name="deskripsi" rows="4" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block p-3">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                @error('deskripsi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Foto Produk -->
            <div class="md:col-span-2 border border-slate-200 rounded-xl p-5 bg-slate-50/50">
                <label class="block text-sm font-semibold text-slate-700 mb-4">Kelola Foto Produk</label>
                
                @if($produk->foto_produk && count($produk->foto_produk) > 0)
                <div class="mb-6">
                    <p class="text-xs text-slate-500 mb-2">Pilih foto yang ingin <span class="text-red-500 font-bold">dihapus</span> (centang kotak di sudut foto):</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                        @foreach($produk->foto_produk as $index => $foto)
                        <div class="relative aspect-square rounded-2xl overflow-hidden border border-slate-200 bg-white group">
                            <img src="{{ Storage::url($foto) }}" class="w-full h-full object-cover">
                            
                            <label class="absolute top-2 right-2 bg-white/90 p-1.5 rounded-xl shadow-sm cursor-pointer hover:bg-red-50 transition-colors z-10 flex items-center justify-center">
                                <input type="checkbox" name="hapus_foto[{{$index}}]" value="1" class="w-4 h-4 text-red-600 border-gray-300 rounded-lg focus:ring-red-500">
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl bg-white hover:bg-slate-50 transition-colors" id="drop-zone">
                    <div class="space-y-2 text-center w-full">
                        <i class="mdi mdi-image-multiple-outline text-4xl text-slate-400"></i>
                        <div class="flex flex-col sm:flex-row items-center justify-center text-sm text-slate-600 gap-1">
                            <label for="foto_produk" class="relative cursor-pointer bg-white rounded-xl font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                <span>Tambah foto baru</span>
                                <input id="foto_produk" name="foto_produk[]" type="file" class="sr-only" multiple accept="image/*" onchange="previewImages(this)">
                            </label>
                        </div>
                        <p class="text-xs text-slate-500">Total maksimal foto (termasuk yang lama) adalah 10 foto</p>
                    </div>
                </div>
                @error('foto_produk') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                @error('foto_produk.*') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror

                <!-- Preview Container for New Images -->
                <div id="image-preview-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 mt-4 hidden">
                    <!-- Images will be previewed here -->
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
            <a href="{{ route('pelaku.produk.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-md hover:bg-slate-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
                Simpan Perubahan
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
            container.classList.remove('hidden');
            
            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-square rounded-2xl overflow-hidden border border-blue-200 ring-2 ring-blue-500 bg-white';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <div class="absolute inset-x-0 bottom-0 bg-blue-600 text-white text-[10px] py-1 text-center font-medium">
                            Baru
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
