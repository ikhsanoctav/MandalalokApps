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
