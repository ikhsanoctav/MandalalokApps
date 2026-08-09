@extends('layouts.superadmin')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Laporan Masyarakat (AI Sentiment)</h1>
        <p class="text-sm text-slate-700">Daftar pengaduan warga beserta analisis sentimen otomatis dari Ollama AI.</p>
    </div>
</div>
@endsection

@section('content')
@php $activeFiltersPengaduan = array_filter([request('search'), request('status'), request('date_from'), request('date_to')]); @endphp
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-4">
    <form method="GET" action="{{ route('superadmin.pengaduan.index') }}" class="flex flex-wrap gap-3 items-center">
        <div class="relative flex-1 min-w-[200px]">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><i class="mdi mdi-magnify"></i></span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pelapor, judul, atau isi..."
                class="pl-9 pr-4 py-2.5 w-full bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
        </div>
        <div class="relative">
            <select name="status" onchange="this.form.submit()" class="appearance-none bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 pl-4 pr-9 py-2.5 focus:outline-none transition-all cursor-pointer {{ request('status') ? 'border-blue-400 bg-blue-50 text-blue-700' : '' }}">
                <option value="">Semua Status</option>
                <option value="Menunggu" {{ request('status') === 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                <option value="Diproses" {{ request('status') === 'Diproses' ? 'selected' : '' }}>🔄 Diproses</option>
                <option value="Selesai"  {{ request('status') === 'Selesai'  ? 'selected' : '' }}>✅ Selesai</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400"><i class="mdi mdi-chevron-down text-sm"></i></div>
        </div>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none text-slate-400 text-xs font-bold">Dari</div>
            <input type="date" name="date_from" value="{{ request('date_from') }}" onchange="this.form.submit()"
                class="pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 focus:outline-none transition-all {{ request('date_from') ? 'border-blue-400 bg-blue-50 text-blue-700' : '' }}">
        </div>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none text-slate-400 text-xs font-bold">S/d</div>
            <input type="date" name="date_to" value="{{ request('date_to') }}" onchange="this.form.submit()"
                class="pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 focus:outline-none transition-all {{ request('date_to') ? 'border-blue-400 bg-blue-50 text-blue-700' : '' }}">
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-500 font-semibold">Tampilkan:</span>
            <select name="per_page" onchange="this.form.submit()" class="appearance-none bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 pl-3 pr-8 py-2.5 focus:outline-none transition-all cursor-pointer">
                @foreach([15, 25, 50] as $pp)
                    <option value="{{ $pp }}" {{ request('per_page', 15) == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-all shadow-sm">Cari</button>
        @if(count($activeFiltersPengaduan) > 0)
            <a href="{{ route('superadmin.pengaduan.index') }}" class="px-4 py-2.5 bg-rose-50 border border-rose-200 text-rose-600 text-sm font-bold rounded-lg hover:bg-rose-100 transition-all">Reset</a>
        @endif
        @if(isset($pengaduans) && method_exists($pengaduans, 'total'))
        <div class="ml-auto text-xs text-slate-500">
            Menampilkan <span class="font-black text-slate-700">{{ $pengaduans->firstItem() ?? 0 }}–{{ $pengaduans->lastItem() ?? 0 }}</span> dari <span class="font-black text-blue-600">{{ $pengaduans->total() }}</span>
        </div>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 border-b border-green-200">
            {{ session('success') }}
        </div>
    @endif
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Pelapor</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4 w-1/3">Isi Laporan & Ringkasan AI</th>
                    <th class="px-6 py-4 text-center">Sentimen AI</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($pengaduans as $laporan)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-800">{{ $laporan->nama_pelapor }}</div>
                        <div class="text-xs text-slate-700">{{ $laporan->no_hp ?? '-' }}</div>
                        <div class="text-xs text-slate-400 mt-1">{{ $laporan->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $laporan->kategori }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-slate-700 mb-2 italic">"{{ Str::limit($laporan->isi_laporan, 100) }}"</div>
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="text-xs font-semibold text-blue-600 mb-1 flex items-center gap-1">
                                <i class="fas fa-robot"></i> Ringkasan Ollama:
                            </div>
                            <p class="text-xs text-slate-600">{{ $laporan->ringkasan_ai ?? 'Menunggu analisis...' }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($laporan->sentimen_ai == 'Positif')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 border border-emerald-200">
                                <i class="fas fa-smile"></i> Positif
                            </span>
                        @elseif($laporan->sentimen_ai == 'Negatif')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700 border border-rose-200 shadow-sm shadow-rose-100">
                                <i class="fas fa-angry"></i> Negatif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                <i class="fas fa-meh"></i> Netral
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $laporan->status == 'Selesai' ? 'bg-green-100 text-green-800' : ($laporan->status == 'Diproses' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-800') }}">
                            {{ $laporan->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('superadmin.pengaduan.status', $laporan->id) }}" method="POST" class="inline-block">
                            @csrf
                            <select name="status" onchange="this.form.submit()" class="text-xs border-slate-300 rounded-md py-1 px-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="Menunggu" {{ $laporan->status == 'Menunggu' ? 'selected' : '' }}>Set: Menunggu</option>
                                <option value="Diproses" {{ $laporan->status == 'Diproses' ? 'selected' : '' }}>Set: Diproses</option>
                                <option value="Selesai" {{ $laporan->status == 'Selesai' ? 'selected' : '' }}>Set: Selesai</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-700">
                        <div class="mb-3 text-slate-300">
                            <i class="fas fa-inbox text-4xl"></i>
                        </div>
                        Belum ada laporan masuk dari masyarakat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
