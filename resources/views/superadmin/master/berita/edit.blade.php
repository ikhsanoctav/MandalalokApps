@extends('layouts.superadmin')
@section('title', 'Edit Warta')
@section('breadcrumb', 'Master Data / Manajemen Warta / Edit')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <style>
        .trix-button-group--file-tools {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('superadmin.berita.index') }}" class="text-slate-700 hover:text-slate-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-slate-800">Edit Warta</h2>
        </div>

        <x-card>
            <form action="{{ route('superadmin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Warta <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul', $berita->judul) }}"
                            class="w-full rounded-2xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                            required>
                        @error('judul')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Konten Berita <span
                                class="text-red-500">*</span></label>
                        <input id="x" type="hidden" name="konten"
                            value="{{ old('konten', $berita->konten) }}"><trix-editor input="x"
                            class="trix-content bg-white min-h-[200px]"></trix-editor>
                        @error('konten')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar / Foto Sampul</label>
                        @if ($berita->gambar)
                            <div class="mb-3">
                                <img src="{{ Storage::url($berita->gambar) }}"
                                    class="h-24 rounded-xl object-cover border border-slate-200">
                            </div>
                        @endif
                        <input type="file" name="gambar"
                            class="block w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            accept="image/*">
                        <p class="text-xs text-slate-700 mt-2">Maksimal 2MB. Kosongkan jika tidak ingin diubah.</p>
                        @error('gambar')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori / Label Warta <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="kategori" value="{{ old('kategori', $berita->kategori) }}" placeholder="Contoh: Program Pemerintah, Bimbingan Teknis"
                            class="w-full rounded-2xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                            required>
                        @error('kategori')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Status Publikasi <span
                                class="text-red-500">*</span></label>
                        <select name="status"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                            required>
                            <option value="draft" {{ old('status', $berita->status) == 'draft' ? 'selected' : '' }}>Draft
                            </option>
                            <option value="published" {{ old('status', $berita->status) == 'published' ? 'selected' : '' }}>
                                Published</option>
                        </select>
                        @error('status')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('superadmin.berita.index') }}"
                        class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition">Batal</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Simpan
                        Perubahan</button>
                </div>
            </form>
        </x-card>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
@endpush
