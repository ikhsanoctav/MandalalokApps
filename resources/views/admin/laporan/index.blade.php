@extends('layouts.admin')
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

                <form action="{{ route('admin.laporan.index') }}" method="GET" id="filter-form">
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-5 bg-slate-50/50 p-5 rounded-2xl border border-slate-100/60 backdrop-blur-sm">
                        
                        <div class="group col-span-1 md:col-span-1 lg:col-span-2">
                            <label for="search" class="block text-xs font-bold tracking-wide text-slate-500 uppercase mb-2 ml-1">Pencarian</label>
                            <div class="relative">
                                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Cari nama, no. pendaftaran, pemilik..."
                                    class="w-full pl-4 pr-10 py-3 rounded-lg border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all duration-300 text-sm font-medium text-slate-700" 
                                    onchange="window.triggerFilter()">
                            </div>
                        </div>

                        <div class="group">
                            <label for="periode_awal" class="block text-xs font-bold tracking-wide text-slate-500 uppercase mb-2 ml-1">Periode Awal</label>
                            <div class="relative">
                                <input type="date" id="periode_awal" name="periode_awal" value="{{ request('periode_awal') }}" 
                                    class="w-full pl-4 pr-3 py-3 rounded-lg border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all duration-300 text-sm font-medium text-slate-700" 
                                    onchange="window.triggerFilter()">
                            </div>
                        </div>
                        
                        <div class="group">
                            <label for="periode_akhir" class="block text-xs font-bold tracking-wide text-slate-500 uppercase mb-2 ml-1">Periode Akhir</label>
                            <div class="relative">
                                <input type="date" id="periode_akhir" name="periode_akhir" value="{{ request('periode_akhir') }}" 
                                    class="w-full pl-4 pr-3 py-3 rounded-lg border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all duration-300 text-sm font-medium text-slate-700" 
                                    onchange="window.triggerFilter()">
                            </div>
                        </div>
                        
                        <div class="group">
                            <label for="kategori" class="block text-xs font-bold tracking-wide text-slate-500 uppercase mb-2 ml-1">Kategori Usaha</label>
                            <div class="relative">
                                <select id="kategori" name="kategori" 
                                    class="w-full pl-4 pr-10 py-3 rounded-lg border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all duration-300 text-sm font-medium text-slate-700 appearance-none" 
                                    onchange="window.triggerFilter()">
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
                                    onchange="window.triggerFilter()">
                                    <option value="">Semua Status</option>
                                    <option value="draft" {{ request('status_verifikasi') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="menunggu_verifikasi" {{ request('status_verifikasi') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi (Terkirim)</option>
                                    <option value="terverifikasi" {{ request('status_verifikasi') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                                    <option value="ditolak" {{ request('status_verifikasi') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                        </div>

                        <div class="group flex items-end">
                            <div class="w-full">
                                <label for="per_page" class="block text-xs font-bold tracking-wide text-slate-500 uppercase mb-2 ml-1">Tampilkan</label>
                                <select id="per_page" name="per_page" 
                                    class="w-full pl-4 pr-10 py-3 rounded-lg border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all duration-300 text-sm font-medium text-slate-700 appearance-none" 
                                    onchange="window.triggerFilter()">
                                    @foreach([10, 25, 50] as $pp)
                                        <option value="{{ $pp }}" {{ request('per_page', 10) == $pp ? 'selected' : '' }}>{{ $pp }} Baris</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @if(count(array_filter([request('search'), request('kategori'), request('status_verifikasi'), request('periode_awal'), request('periode_akhir')])) > 0)
                        <div class="mt-4 flex justify-end">
                            <a href="{{ route('admin.laporan.index') }}" class="px-4 py-2 bg-rose-50 text-rose-600 rounded-lg text-sm font-semibold hover:bg-rose-100 transition-colors">Reset Filter</a>
                        </div>
                    @endif
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
                        <h3 id="total-umkm" class="text-4xl font-extrabold tracking-tight">{{ number_format($totalUmkm) }}</h3>
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
                        <h3 class="text-3xl font-extrabold tracking-tight"><span id="total-tenaga-kerja">{{ number_format($totalTenagaKerja) }}</span> <span class="text-lg font-medium opacity-80">Jiwa</span></h3>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 bg-white p-6 md:p-8 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex flex-col h-full relative">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Tren Pendaftaran Periode Ini</h3>
                        <p class="text-xs text-slate-500 mt-1">Visualisasi data pertumbuhan UMKM</p>
                    </div>

                    <div class="flex gap-2">
                        <button type="button" onclick="document.getElementById('exportModal').classList.remove('hidden')" class="group inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-bold shadow-md hover:bg-indigo-700 hover:shadow-lg transition-all duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export Data
                        </button>
                    </div>
                </div>

                <div class="flex-grow w-full relative" style="min-height: 320px;">
                    <canvas id="trenChart"></canvas>
                </div>
            </div>
        </div>

        <div id="table-container">
            @include('admin.laporan.table-data')
        </div>

    </div>

    <div id="exportModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('exportModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <form action="{{ route('admin.laporan.export') }}" method="POST" target="_blank" onsubmit="setTimeout(() => document.getElementById('exportModal').classList.add('hidden'), 500);">
                    @csrf
                    <input type="hidden" name="periode_awal" value="{{ request('periode_awal') }}">
                    <input type="hidden" name="periode_akhir" value="{{ request('periode_akhir') }}">
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    <input type="hidden" name="status_verifikasi" value="{{ request('status_verifikasi') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Export Data UMKM</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">Pilih format file dan kolom yang ingin disertakan dalam laporan (data akan difilter sesuai tampilan saat ini).</p>
                                    
                                    <div class="mt-4 mb-5 border-t border-slate-100 pt-4" x-data="exportColumnSelector()">
                                        <p class="text-sm font-semibold text-slate-700 mb-2">Pilih Kolom Data:</p>
                                        
                                        <!-- Active columns as tags -->
                                        <div class="flex flex-wrap gap-2 mb-3">
                                            <template x-for="(col, index) in activeColumns" :key="col.value">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                    <span x-text="col.label"></span>
                                                    <button type="button" @click="removeColumn(index)" class="hover:bg-indigo-200 hover:text-indigo-900 rounded-full p-0.5 transition-colors focus:outline-none">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                    <input type="hidden" name="columns[]" :value="col.value">
                                                </span>
                                            </template>
                                        </div>

                                        <!-- Dropdown to add more -->
                                        <div class="relative" x-show="availableColumns.length > 0">
                                            <select @change="addColumn($event.target.value); $event.target.value=''" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50 py-2 px-3 cursor-pointer">
                                                <option value="">+ Tambah Kolom Lainnya...</option>
                                                <template x-for="col in availableColumns" :key="col.value">
                                                    <option :value="col.value" x-text="col.label"></option>
                                                </template>
                                            </select>
                                        </div>
                                        
                                        <div x-show="activeColumns.length === 0" class="text-xs text-red-500 mt-2 font-medium">
                                            * Pilih setidaknya satu kolom untuk diexport.
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <button type="submit" name="format" value="excel" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform hover:-translate-y-0.5">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            Export ke Excel (.xlsx)
                                        </button>
                                        <button type="submit" name="format" value="pdf" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-all transform hover:-translate-y-0.5">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            Export ke PDF (.pdf)
                                        </button>
                                    </div>
                                    
                                    <script>
                                        function exportColumnSelector() {
                                            const allCols = [
                                                { value: 'no_pendaftaran', label: 'No. Pendaftaran' },
                                                { value: 'nama_usaha', label: 'Nama Usaha' },
                                                { value: 'pemilik', label: 'Nama Pemilik' },
                                                { value: 'kategori', label: 'Kategori' },
                                                { value: 'sektor', label: 'Sektor' },
                                                { value: 'status_usaha', label: 'Status Usaha' },
                                                { value: 'tahun_berdiri', label: 'Thn Berdiri' },
                                                { value: 'no_izin', label: 'No. Izin' },
                                                { value: 'jenis_izin', label: 'Jenis Izin' },
                                                { value: 'npwp', label: 'NPWP Usaha' },
                                                { value: 'telepon', label: 'Telepon Usaha' },
                                                { value: 'email', label: 'Email Usaha' },
                                                { value: 'jumlah_tk', label: 'Jumlah TK' },
                                                { value: 'alamat', label: 'Alamat Usaha' },
                                                { value: 'petugas', label: 'Petugas Pendata' },
                                                { value: 'tgl_pendataan', label: 'Tgl Pendataan' },
                                                { value: 'status_verifikasi', label: 'Status Verifikasi' },
                                                { value: 'alasan_penolakan', label: 'Alasan Penolakan' }
                                            ];
                                            const defaultVals = ['no_pendaftaran', 'nama_usaha', 'pemilik', 'kategori', 'sektor', 'tahun_berdiri', 'no_izin', 'jumlah_tk', 'status_verifikasi'];
                                            
                                            return {
                                                activeColumns: allCols.filter(c => defaultVals.includes(c.value)),
                                                get availableColumns() {
                                                    const activeVals = this.activeColumns.map(c => c.value);
                                                    return allCols.filter(c => !activeVals.includes(c.value));
                                                },
                                                addColumn(val) {
                                                    if (!val) return;
                                                    const col = allCols.find(c => c.value === val);
                                                    if (col) this.activeColumns.push(col);
                                                },
                                                removeColumn(index) {
                                                    this.activeColumns.splice(index, 1);
                                                }
                                            }
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" onclick="document.getElementById('exportModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
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

        window.myTrenChart = new Chart(ctx, {
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

    // AJAX Filtering logic
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    window.triggerFilter = function() {
        const form = document.getElementById('filter-form');
        const url = new URL(form.action);
        const formData = new FormData(form);
        
        for (const [key, value] of formData.entries()) {
            if (value) {
                url.searchParams.append(key, value);
            }
        }
        
        const container = document.getElementById('table-container');
        if (container) {
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';
        }
        
        url.searchParams.append('ajax', '1');
        
        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && container) {
                container.innerHTML = data.html;
                
                document.getElementById('total-umkm').innerText = data.totalUmkm;
                document.getElementById('total-tenaga-kerja').innerText = data.totalTenagaKerja;
                
                if (window.myTrenChart) {
                    window.myTrenChart.data.labels = data.chartLabels.length > 0 ? data.chartLabels : ['Belum ada data'];
                    window.myTrenChart.data.datasets[0].data = data.chartValues.length > 0 ? data.chartValues : [0];
                    window.myTrenChart.update();
                }

                url.searchParams.delete('ajax');
                window.history.pushState({}, '', url.toString());
                attachPaginationListeners();
            }
        })
        .catch(error => console.error('Error fetching data:', error))
        .finally(() => {
            if (container) {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            }
        });
    };

    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.onchange = null;
        searchInput.addEventListener('input', debounce(() => window.triggerFilter(), 500));
    }

    function attachPaginationListeners() {
        document.querySelectorAll('.pagination a').forEach(link => {
            const newLink = link.cloneNode(true);
            link.parentNode.replaceChild(newLink, link);
            
            newLink.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const form = document.getElementById('filter-form');
                const formData = new FormData(form);
                
                for (const [key, value] of formData.entries()) {
                    if (value && !url.searchParams.has(key)) {
                        url.searchParams.append(key, value);
                    }
                }
                
                const container = document.getElementById('table-container');
                if (container) {
                    container.style.opacity = '0.5';
                    container.style.pointerEvents = 'none';
                }
                
                url.searchParams.append('ajax', '1');
                
                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && container) {
                        container.innerHTML = data.html;
                        url.searchParams.delete('ajax');
                        window.history.pushState({}, '', url.toString());
                        attachPaginationListeners();
                        window.scrollTo({top: 0, behavior: 'smooth'});
                    }
                })
                .catch(error => console.error('Error fetching data:', error))
                .finally(() => {
                    if (container) {
                        container.style.opacity = '1';
                        container.style.pointerEvents = 'auto';
                    }
                });
            });
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        attachPaginationListeners();
    });
</script>
@endpush
