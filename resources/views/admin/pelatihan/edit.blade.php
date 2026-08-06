@extends('layouts.admin')
@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-12">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-600 shadow-inner">
                <i class="mdi mdi-school text-3xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Edit Pelatihan: {{ $pelatihan->judul }}</h2>
                <p class="text-slate-500 mt-1 font-medium">Ubah data dan pengaturan jadwal pelatihan UMKM.</p>
            </div>
        </div>
        <a href="{{ route('admin.pelatihan.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-slate-200 hover:border-indigo-300 hover:text-indigo-600 text-slate-700 font-bold rounded-lg transition-all shadow-sm">
            <i class="mdi mdi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white/80 overflow-hidden relative p-8">
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none transform translate-x-1/2 -translate-y-1/2"></div>
        <form action="{{ route('admin.pelatihan.update', $pelatihan->id) }}" method="POST" enctype="multipart/form-data" class="relative z-10">
            @csrf
            @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Banner Pelatihan (Opsional)</label>
                @if($pelatihan->banner)
                    <div class="mb-3">
                        <img src="{{ asset($pelatihan->banner) }}" alt="Banner Pelatihan" class="w-full max-w-md h-auto rounded-xl border border-slate-200">
                    </div>
                @endif
                <input type="file" name="banner" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-[10px] text-slate-500 mt-1">Biarkan kosong jika tidak ingin mengubah banner. Maks 2MB, rasio ideal 16:9 (Landscape).</p>
                @error('banner') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Pelatihan <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul', $pelatihan->judul) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                @error('judul') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Pelatihan</label>
                <textarea name="deskripsi" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">{{ old('deskripsi', $pelatihan->deskripsi) }}</textarea>
                @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Waktu Mulai <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="tanggal_mulai" value="{{ old('tanggal_mulai', $pelatihan->tanggal_mulai->format('Y-m-d\TH:i')) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                @error('tanggal_mulai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Waktu Selesai <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="tanggal_selesai" value="{{ old('tanggal_selesai', $pelatihan->tanggal_selesai->format('Y-m-d\TH:i')) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                @error('tanggal_selesai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Lokasi <span class="text-red-500">*</span></label>
                <input type="text" name="lokasi" value="{{ old('lokasi', $pelatihan->lokasi) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                @error('lokasi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kuota Peserta <span class="text-red-500">*</span></label>
                <input type="number" name="kuota" value="{{ old('kuota', $pelatihan->kuota) }}" required min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                @error('kuota') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            @php
                $syaratLama = old('syarat_dokumen', $pelatihan->syarat_dokumen ?? []);
                if(!is_array($syaratLama) || count($syaratLama) === 0) {
                    $syaratLama = [''];
                }
            @endphp
            <div class="md:col-span-2" x-data="{ syarat: {{ json_encode($syaratLama) }} }">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Syarat Dokumen (Opsional)</label>
                <p class="text-xs text-slate-500 mb-2">Persyaratan dokumen yang wajib diunggah pelaku UMKM saat mendaftar.</p>
                
                <template x-for="(s, index) in syarat" :key="index">
                    <div class="flex gap-2 mb-2">
                        <input type="text" x-model="syarat[index]" name="syarat_dokumen[]" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Misal: Fotokopi KTP">
                        <button type="button" @click="syarat.splice(index, 1)" x-show="syarat.length > 1" class="px-4 py-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-colors">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </template>
                
                <button type="button" @click="syarat.push('')" class="mt-2 text-sm text-blue-600 font-semibold hover:text-blue-700 inline-flex items-center gap-1">
                    <i class="fa-solid fa-plus-circle"></i> Tambah Syarat Lainnya
                </button>
                
                @error('syarat_dokumen') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Status Publikasi <span class="text-red-500">*</span></label>
                <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
                    <option value="draft" {{ old('status', $pelatihan->status) == 'draft' ? 'selected' : '' }}>Draft (Belum Tampil di Pelaku)</option>
                    <option value="published" {{ old('status', $pelatihan->status) == 'published' ? 'selected' : '' }}>Published (Tampil & Bisa Daftar)</option>
                    <option value="completed" {{ old('status', $pelatihan->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-between border-t pt-6 gap-3">
            <button type="button" x-data @click="$dispatch('open-confirm-modal', {
                title: 'Hapus Pelatihan',
                message: 'Apakah Anda yakin ingin menghapus pelatihan ini?',
                confirmText: 'Ya, Hapus',
                action: () => {
                    document.getElementById('delete-form').submit();
                    return new Promise(() => {});
                }
            })" class="px-5 py-2.5 text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-all">
                Hapus Pelatihan
            </button>
            <div class="flex gap-2">
                <a href="{{ route('admin.pelatihan.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">Batal</a>
                <button type="submit" class="btn-primary px-6 py-2.5 text-sm font-bold text-white rounded-xl transition-all shadow-md">Simpan Perubahan</button>
            </div>
        </div>
    </form>

    <form id="delete-form" action="{{ route('admin.pelatihan.destroy', $pelatihan->id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

@endsection
