@extends('layouts.superadmin')
@section('title', 'Edit RW')
@section('breadcrumb', 'Master Data / RW / Edit')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-slate-800">Edit RW</h2>
            <a href="{{ route('superadmin.rw.index') }}"
                class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg shadow hover:bg-slate-300 transition">
                Kembali
            </a>
        </div>

        <x-card>
            <form action="{{ route('superadmin.rw.update', $rw->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kelurahan <span
                                class="text-red-500">*</span></label>
                        <select name="kelurahan_id" required
                            class="w-full border-slate-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($kelurahans as $kel)
                                <option value="{{ $kel->id }}"
                                    {{ old('kelurahan_id', $rw->kelurahan_id) == $kel->id ? 'selected' : '' }}>
                                    {{ $kel->nama_kelurahan }}</option>
                            @endforeach
                        </select>
                        @error('kelurahan_id')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nomor RW <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="nomor_rw" value="{{ old('nomor_rw', $rw->nomor_rw) }}" required
                            min="1" max="999"
                            class="w-full border-slate-300 rounded-2xl p-2.5 focus:ring-blue-500 focus:border-blue-500">
                        @error('nomor_rw')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah RT</label>
                        <input type="number" name="jumlah_rt"
                            value="{{ old('jumlah_rt', $rw->rts()->count() > 0 ? $rw->rts()->count() : '') }}"
                            min="1" max="100"
                            class="w-full border-slate-300 rounded-2xl p-2.5 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Biarkan kosong jika belum ada">
                        <p class="text-[10px] text-slate-500 mt-1">*Ubah angka ini jika Anda ingin sistem otomatis
                            menambahkan RT baru untuk RW ini.</p>
                        @error('jumlah_rt')
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
