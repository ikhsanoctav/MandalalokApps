@extends('layouts.superadmin')
@section('title', 'Tambah Kategori UMKM')
@section('breadcrumb', 'Master Data / Kategori / Tambah')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-slate-800">Tambah Kategori</h2>
            <a href="{{ route('superadmin.kategori.index') }}"
                class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg shadow hover:bg-slate-300 transition">
                Kembali
            </a>
        </div>

        <x-card>
            <form action="{{ route('superadmin.kategori.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kategori <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}" required
                            class="w-full border-slate-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Misal: Mikro, Kecil, Menengah">
                        @error('nama_kategori')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-2xl shadow hover:bg-blue-700 font-medium transition">
                            Simpan Kategori
                        </button>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
@endsection
