@extends('layouts.superadmin')
@section('title', 'Rekap & Laporan')
@section('breadcrumb', 'Rekap & Laporan')

@section('content')
    <div class="space-y-6">

        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden relative">
            
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-blue-50 opacity-50 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 rounded-full bg-indigo-50 opacity-50 blur-3xl"></div>

            <div class="relative p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Laporan & Analitik</h2>
                        <p class="text-slate-500 text-sm mt-1">Saring dan pantau perkembangan UMKM secara real-time</p>
                    </div>
                </div>

                <form action="{{ route('superadmin.laporan') }}" method="GET" id="filter-form">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 bg-slate-50/50 p-5 rounded-2xl border border-slate-100/60 backdrop-blur-sm">
                        
                        <div class="group">
                            <label for="periode_awal" class="block text-xs font-bold tracking-wide text-slate-500 uppercase mb-2 ml-1">Periode Awal</label>
                            <div class="relative">
                                <input type="date" id="periode_awal" name="periode_awal" value="{{ request('periode_awal') }}" 
                                    class="w-full pl-4 pr-10 py-3 rounded-lg border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all duration-300 text-sm font-medium text-slate-700" 
                                    onchange="document.getElementById('filter-form').submit()">
                            </div>
                        </div>
                        
                        <div class="group">
                            <label for="periode_akhir" class="block text-xs font-bold tracking-wide text-slate-500 uppercase mb-2 ml-1">Periode Akhir</label>
                            <div class="relative">
                                <input type="date" id="periode_akhir" name="periode_akhir" value="{{ request('periode_akhir') }}" 
                                    class="w-full pl-4 pr-10 py-3 rounded-lg border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all duration-300 text-sm font-medium text-slate-700" 
                                    onchange="document.getElementById('filter-form').submit()">
                            </div>
                        </div>
                        
                        <div class="group">
                            <label for="kategori" class="block text-xs font-bold tracking-wide text-slate-500 uppercase mb-2 ml-1">Kategori Usaha</label>
                            <div class="relative">
                                <select id="kategori" name="kategori" 
                                    class="w-full pl-4 pr-10 py-3 rounded-lg border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all duration-300 text-sm font-medium text-slate-700 appearance-none" 
                                    onchange="document.getElementById('filter-form').submit()">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategoriList as $kat)
                                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                            {{ $kat->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="group">
                            <label for="status_verifikasi" class="block text-xs font-bold tracking-wide text-slate-500 uppercase mb-2 ml-1">Status Verifikasi</label>
                            <div class="relative">
                                <select id="status_verifikasi" name="status_verifikasi" 
                                    class="w-full pl-4 pr-10 py-3 rounded-lg border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all duration-300 text-sm font-medium text-slate-700 appearance-none" 
                                    onchange="document.getElementById('filter-form').submit()">
                                    <option value="">Semua Status</option>
                                    <option value="draft" {{ request('status_verifikasi') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="terkirim" {{ request('status_verifikasi') == 'terkirim' ? 'selected' : '' }}>Menunggu Verifikasi (Terkirim)</option>
                                    <option value="terverifikasi" {{ request('status_verifikasi') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                                    <option value="ditolak" {{ request('status_verifikasi') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <div class="lg:col-span-1 flex flex-col gap-5">
                
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl p-6 text-white shadow-[0_8px_20px_rgba(59,130,246,0.3)] relative overflow-hidden group hover:-translate-y-1 transition-all duration-300 cursor-default">
                    <div class="absolute top-0 right-0 p-4 opacity-20 transform group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div class="relative z-10">
                        <div class="bg-white/20 w-10 h-10 rounded-xl flex items-center justify-center backdrop-blur-md mb-4 border border-white/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-blue-100 text-sm font-medium mb-1">Total Entitas Ditemukan</p>
                        <h3 class="text-4xl font-extrabold tracking-tight">{{ number_format($totalUmkm) }}</h3>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-emerald-400 to-teal-600 rounded-3xl p-6 text-white shadow-[0_8px_20px_rgba(16,185,129,0.3)] relative overflow-hidden group hover:-translate-y-1 transition-all duration-300 cursor-default flex-1">
                    <div class="absolute top-0 right-0 p-4 opacity-20 transform group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div class="relative z-10">
                        <div class="bg-white/20 w-10 h-10 rounded-xl flex items-center justify-center backdrop-blur-md mb-4 border border-white/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <p class="text-teal-100 text-sm font-medium mb-1">Total Tenaga Kerja</p>
                        <h3 class="text-3xl font-extrabold tracking-tight">{{ number_format($totalTenagaKerja) }} <span class="text-lg font-medium opacity-80">Jiwa</span></h3>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 bg-white p-6 md:p-8 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex flex-col h-full relative">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Tren Pendaftaran Periode Ini</h3>
                        <p class="text-xs text-slate-500 mt-1">Visualisasi data pertumbuhan UMKM</p>
                    </div>

                    <form action="{{ route('superadmin.laporan.export') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="hidden" name="periode_awal" value="{{ request('periode_awal') }}">
                        <input type="hidden" name="periode_akhir" value="{{ request('periode_akhir') }}">
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                        <input type="hidden" name="status_verifikasi" value="{{ request('status_verifikasi') }}">
                        
                        <button type="submit" name="format" value="excel" class="group inline-flex items-center px-4 py-2.5 bg-white border-2 border-emerald-500 text-emerald-600 rounded-lg text-sm font-bold hover:bg-emerald-50 hover:shadow-lg hover:shadow-emerald-500/20 transition-all duration-300">
                            <svg class="w-4 h-4 mr-2 transform group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            XLSX
                        </button>
                        <button type="submit" name="format" value="pdf" class="group inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-rose-500 to-red-600 text-white rounded-lg text-sm font-bold shadow-lg shadow-rose-500/30 hover:shadow-rose-500/50 hover:scale-105 transition-all duration-300">
                            <svg class="w-4 h-4 mr-2 transform group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            PDF
                        </button>
                    </form>
                </div>

                <div class="flex-grow w-full relative" style="min-height: 320px;">
                    <canvas id="trenChart"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800">Detail Data UMKM</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama Usaha</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Pemilik</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kategori & Sektor</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tgl Daftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($umkms as $index => $umkm)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-slate-500">{{ $umkms->firstItem() + $index }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-slate-800">{{ $umkm->nama_usaha }}</p>
                                    <p class="text-xs text-slate-400">{{ $umkm->no_pendaftaran ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-sm text-slate-600">{{ $umkm->pemilik->nama_lengkap ?? '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ $umkm->pemilik->kelurahan ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-sm text-slate-600">{{ $umkm->kategori->nama_kategori ?? '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ $umkm->sektor->nama_sektor ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($umkm->status_verifikasi == 'terverifikasi')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            Terverifikasi
                                        </span>
                                    @elseif($umkm->status_verifikasi == 'terkirim')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                            Menunggu
                                        </span>
                                    @elseif($umkm->status_verifikasi == 'ditolak')
                                        <div class="flex flex-col gap-1 items-start">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                                Ditolak
                                            </span>
                                            <span class="text-[10px] text-red-600 font-medium bg-red-50 px-2 py-0.5 rounded-md border border-red-100 max-w-[200px] truncate" title="{{ $umkm->catatan_penolakan }}">
                                                Alasan: {{ $umkm->catatan_penolakan ?? 'Tidak ada' }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                            <span class="w-1.5 h-1.5 bg-slate-500 rounded-full"></span>
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500">
                                    {{ $umkm->tanggal_pendataan ? \Carbon\Carbon::parse($umkm->tanggal_pendataan)->format('d/m/Y') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                                    Tidak ada data UMKM yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($umkms->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                    {{ $umkms->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('trenChart').getContext('2d');
        const chartLabels = {!! json_encode($chartLabels) !!};
        const chartValues = {!! json_encode($chartValues) !!};

        // Create gradient for chart
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)'); // blue-500
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels.length > 0 ? chartLabels : ['Belum ada data'],
                datasets: [{
                    label: 'Pendaftaran UMKM',
                    data: chartValues.length > 0 ? chartValues : [0],
                    borderColor: '#3b82f6', // blue-500
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#3b82f6',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)', // slate-900 with opacity
                        padding: 12,
                        titleFont: { size: 14, family: "'Inter', sans-serif", weight: 'bold' },
                        bodyFont: { size: 13, family: "'Inter', sans-serif" },
                        displayColors: false,
                        cornerRadius: 8,
                        caretSize: 6,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#94a3b8',
                            font: { family: "'Inter', sans-serif", size: 11 }
                        },
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false,
                            borderDash: [5, 5]
                        },
                        border: { display: false }
                    },
                    x: {
                        ticks: {
                            color: '#94a3b8',
                            font: { family: "'Inter', sans-serif", size: 11 }
                        },
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        border: { display: false }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    });
</script>
@endpush
