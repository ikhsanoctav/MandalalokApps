@extends('layouts.superadmin')

@section('title', 'DSS Mandalaloka - Pengaturan Kriteria')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800">Pengaturan Bobot Kriteria</h1>
            <p class="text-slate-700 mt-1">Ubah persentase prioritas untuk memanipulasi rekomendasi DSS (Algoritma SAW) secara langsung.</p>
        </div>
        <a href="{{ route('superadmin.dss.saw.index') }}" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all">
            <i class="mdi mdi-arrow-left mr-2"></i> Kembali ke Papan Peringkat
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl">
        <div class="flex items-center text-emerald-800">
            <i class="mdi mdi-check-circle text-2xl mr-3"></i>
            <p class="font-bold">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Form Kriteria Bantuan -->
        <form action="{{ route('superadmin.dss.saw.update_kriteria') }}" method="POST" class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8">
            @csrf
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl font-black"><i class="mdi mdi-hand-coin"></i></div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">DSS Bantuan Modal</h2>
                    <p class="text-slate-700 text-sm">Total bobot sebaiknya 100%</p>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($kriteriaBantuan as $kb)
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                    <div>
                        <p class="font-bold text-slate-800">{{ $kb->nama_kriteria }}</p>
                        <p class="text-xs text-slate-700 mt-0.5"><span class="uppercase font-bold text-{{ $kb->jenis == 'benefit' ? 'emerald' : 'rose' }}-600">{{ $kb->jenis }}</span> - Kode: {{ $kb->kode_kriteria }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="number" name="kriteria[{{ $kb->id }}][bobot]" value="{{ $kb->bobot }}" step="0.01" min="0" max="100" required class="w-24 text-center bg-white border-2 border-slate-300 rounded-2xl p-2 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <span class="text-slate-700 font-bold">%</span>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="submit" class="w-full mt-6 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all flex items-center justify-center gap-2">
                <i class="mdi mdi-content-save"></i> Simpan Bobot Bantuan
            </button>
        </form>

        <!-- Form Kriteria Pelatihan -->
        <form action="{{ route('superadmin.dss.saw.update_kriteria') }}" method="POST" class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8">
            @csrf
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl font-black"><i class="mdi mdi-school"></i></div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">DSS Pelatihan & Pendampingan</h2>
                    <p class="text-slate-700 text-sm">Total bobot sebaiknya 100%</p>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($kriteriaPelatihan as $kp)
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                    <div>
                        <p class="font-bold text-slate-800">{{ $kp->nama_kriteria }}</p>
                        <p class="text-xs text-slate-700 mt-0.5"><span class="uppercase font-bold text-{{ $kp->jenis == 'benefit' ? 'emerald' : 'rose' }}-600">{{ $kp->jenis }}</span> - Kode: {{ $kp->kode_kriteria }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="number" name="kriteria[{{ $kp->id }}][bobot]" value="{{ $kp->bobot }}" step="0.01" min="0" max="100" required class="w-24 text-center bg-white border-2 border-slate-300 rounded-2xl p-2 font-bold focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <span class="text-slate-700 font-bold">%</span>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="submit" class="w-full mt-6 px-6 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 transition-all flex items-center justify-center gap-2">
                <i class="mdi mdi-content-save"></i> Simpan Bobot Pelatihan
            </button>
        </form>
    </div>
</div>
@endsection
