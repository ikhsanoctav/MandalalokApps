@extends('layouts.superadmin')
@section('title', 'Edit Kelurahan')
@section('breadcrumb', 'Master Data / Kelurahan / Edit')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-slate-800">Edit Kelurahan</h2>
            <a href="{{ route('superadmin.kelurahan.index') }}"
                class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg shadow hover:bg-slate-300 transition">
                Kembali
            </a>
        </div>

        <x-card>
            <form action="{{ route('superadmin.kelurahan.update', $kelurahan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kode Kelurahan <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="kode_kelurahan"
                            value="{{ old('kode_kelurahan', $kelurahan->kode_kelurahan) }}" required
                            class="w-full border-slate-300 rounded-2xl p-2.5 focus:ring-blue-500 focus:border-blue-500">
                        @error('kode_kelurahan')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kelurahan <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_kelurahan"
                            value="{{ old('nama_kelurahan', $kelurahan->nama_kelurahan) }}" required
                            class="w-full border-slate-300 rounded-2xl p-2.5 focus:ring-blue-500 focus:border-blue-500">
                        @error('nama_kelurahan')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah RW</label>
                        <input type="number" name="jumlah_rw"
                            value="{{ old('jumlah_rw', $kelurahan->rws()->count() > 0 ? $kelurahan->rws()->count() : '') }}"
                            min="1" max="100"
                            class="w-full border-slate-300 rounded-2xl p-2.5 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Biarkan kosong jika belum ada">
                        <p class="text-[10px] text-slate-500 mt-1">*Ubah angka ini jika Anda ingin sistem otomatis
                            menambahkan RW baru (misal: jika dinaikkan dari 10 ke 15, sistem akan membuat RW 011 - 015).</p>
                        @error('jumlah_rw')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-2xl shadow hover:bg-blue-700 font-medium transition">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
@endsection
