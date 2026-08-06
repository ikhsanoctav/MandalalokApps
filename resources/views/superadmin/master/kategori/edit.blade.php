@extends('layouts.superadmin')
@section('title', 'Edit Kategori UMKM')
@section('breadcrumb', 'Master Data / Kategori / Edit')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-slate-800">Edit Kategori</h2>
            <a href="{{ route('superadmin.kategori.index') }}"
                class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg shadow hover:bg-slate-300 transition">
                Kembali
            </a>
        </div>

        <x-card>
            <form action="{{ route('superadmin.kategori.update', $kategori->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kategori <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_kategori"
                            value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required
                            class="w-full border-slate-300 rounded-2xl p-2.5 focus:ring-blue-500 focus:border-blue-500">
                        @error('nama_kategori')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    @if(str_contains(strtolower($kategori->nama_kategori), 'mikro'))
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Batas Omset Bulanan Maksimal (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="max_omset"
                                value="{{ old('max_omset', \App\Models\Setting::get('kategori_mikro_max_omset', 10000000)) }}" required min="0" step="1000"
                                class="w-full border-slate-300 rounded-2xl p-2.5 focus:ring-blue-500 focus:border-blue-500">
                            @error('max_omset')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                            <p class="text-xs text-slate-400 mt-1">Batas omset bulanan maksimal agar usaha dikategorikan sebagai Mikro.</p>
                        </div>
                    @elseif(str_contains(strtolower($kategori->nama_kategori), 'kecil'))
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Batas Omset Bulanan Maksimal (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="max_omset"
                                value="{{ old('max_omset', \App\Models\Setting::get('kategori_kecil_max_omset', 50000000)) }}" required min="0" step="1000"
                                class="w-full border-slate-300 rounded-2xl p-2.5 focus:ring-blue-500 focus:border-blue-500">
                            @error('max_omset')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                            <p class="text-xs text-slate-400 mt-1">Batas omset bulanan maksimal agar usaha dikategorikan sebagai Kecil. Di atas nominal ini akan dikelompokkan ke Menengah.</p>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-2xl shadow hover:bg-blue-700 font-medium transition">
                            Update Kategori
                        </button>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
@endsection
