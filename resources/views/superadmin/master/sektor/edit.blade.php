@extends('layouts.superadmin')
@section('title', 'Edit Sektor UMKM')
@section('breadcrumb', 'Master Data / Sektor / Edit')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-slate-800">Edit Sektor</h2>
            <a href="{{ route('superadmin.sektor.index') }}"
                class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg shadow hover:bg-slate-300 transition">
                Kembali
            </a>
        </div>

        <x-card>
            <form action="{{ route('superadmin.sektor.update', $sektor->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Sektor <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_sektor" value="{{ old('nama_sektor', $sektor->nama_sektor) }}"
                            required
                            class="w-full border-slate-300 rounded-2xl p-2.5 focus:ring-blue-500 focus:border-blue-500">
                        @error('nama_sektor')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Sektor</label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full border-slate-300 rounded-2xl p-2.5 focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi', $sektor->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-2xl shadow hover:bg-blue-700 font-medium transition">
                            Update Sektor
                        </button>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
@endsection
