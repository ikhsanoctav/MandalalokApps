@extends('layouts.admin')
@section('title', 'Dashboard Admin Kecamatan')

@section('content')
    <div class="space-y-8 pb-10">

        <!-- Hero Banner Premium (Light Theme) -->
        <div class="relative overflow-hidden rounded-3xl bg-slate-50 p-8 md:p-10 shadow-sm border border-slate-200 mb-8">
            <!-- Batik Background Accent -->
            <div class="absolute inset-0 z-0 opacity-10 mix-blend-multiply" style="background-image: url('{{ asset('img/batik-bg.png') }}'); background-size: 300px; background-repeat: repeat;"></div>

            <!-- Animated Background Gradients -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-pulse"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-rose-200 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-amber-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-pulse" style="animation-delay: 4s;"></div>

            <!-- Content Container with Glassmorphism -->
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6 backdrop-blur-md bg-white/60 border border-white/80 rounded-2xl p-6 shadow-sm">
                <div class="text-slate-800">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-100/80 border border-indigo-200 text-xs font-bold tracking-wider uppercase mb-4 text-indigo-700 shadow-sm">
                        <i class="mdi mdi-office-building text-indigo-600"></i> Admin Kecamatan
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 bg-clip-text text-transparent bg-gradient-to-r from-indigo-700 via-purple-700 to-indigo-800">
                        Selamat Datang, {{ Auth::user()->name }}!
                    </h1>
                    <p class="text-slate-600 text-sm md:text-base max-w-2xl font-medium">
                        Pantau dan kelola pendaftaran UMKM di wilayah kecamatan Anda. Verifikasi data dengan cepat dan akurat.
                    </p>
                </div>

                <div class="flex-shrink-0 text-right backdrop-blur-xl bg-white/90 rounded-xl p-4 border border-white shadow-sm">
                    <p class="text-xs text-slate-500 uppercase tracking-widest font-bold mb-1">Hari Ini</p>
                    <p class="text-xl font-black text-slate-800">{{ now()->translatedFormat('l, d F Y') }}</p>
                    <p class="text-sm text-slate-600 mt-1 flex items-center justify-end gap-1 font-semibold"><i class="mdi mdi-clock-outline text-indigo-600"></i> <span id="realtimeClock">{{ now()->format('H:i') }}</span></p>
                </div>
            </div>
        </div>

        <script>
            // Simple realtime clock for banner
            setInterval(() => {
                const now = new Date();
                const clock = document.getElementById('realtimeClock');
                if(clock) {
                    clock.innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');
                }
            }, 60000);
        </script>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <h2 class="text-sm font-bold tracking-wider text-slate-600 uppercase mb-4 flex items-center gap-2">
                <i class="mdi mdi-lightning-bolt text-amber-500 text-lg"></i> Aksi Cepat
            </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <a href="{{ route('admin.umkm.create') }}"
                    class="group block p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 hover:border-blue-300 transition-all">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2.5 bg-white text-blue-600 rounded-2xl shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="mdi mdi-plus-circle-outline text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800 group-hover:text-blue-700">Daftarkan UMKM</h3>
                            <p class="text-[11px] text-slate-500">Input data baru</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.umkm.index') }}"
                    class="group block p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-indigo-50 hover:border-indigo-300 transition-all">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2.5 bg-white text-indigo-600 rounded-2xl shadow-sm group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                            <i class="mdi mdi-format-list-bulleted text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800 group-hover:text-indigo-700">Daftar UMKM</h3>
                            <p class="text-[11px] text-slate-500">Lihat semua data</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.laporan.index') }}"
                    class="group block p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-purple-50 hover:border-purple-300 transition-all">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2.5 bg-white text-purple-600 rounded-2xl shadow-sm group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <i class="mdi mdi-printer text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800 group-hover:text-purple-700">Cetak Laporan</h3>
                            <p class="text-[11px] text-slate-500">Unduh dalam Excel/PDF</p>
                        </div>
                    </div>
                </a>

            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div
                class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-3xl p-6 text-white shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:shadow-[0_8px_30px_rgba(37,99,235,0.4)] transition-all duration-300 hover:-translate-y-1.5 cursor-pointer relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white rounded-full mix-blend-overlay opacity-10 blur-xl group-hover:scale-150 transition-transform duration-700">
                </div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-[11px] font-bold opacity-80 uppercase tracking-widest mb-1">TOTAL UMKM</p>
                        <p class="text-4xl font-black tracking-tight" id="totalUmkm">{{ number_format($totalUmkm) }}</p>
                        <div class="mt-3 flex items-center gap-1.5">
                            @if (floatval($growthUmkm) >= 0)
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-400/25 text-emerald-50 border border-emerald-400/30 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                    <i class="mdi mdi-trending-up"></i>
                                    <span id="growthUmkm">{{ $growthUmkm }}</span>%
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-red-400/25 text-red-50 border border-red-400/30 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                    <i class="mdi mdi-trending-down"></i>
                                    <span id="growthUmkm">{{ abs(floatval($growthUmkm)) }}</span>%
                                </span>
                            @endif
                            <span class="text-[10px] opacity-70">dari bulan lalu</span>
                        </div>
                    </div>
                    <div
                        class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 group-hover:bg-white/20 transition-colors shadow-inner">
                        <i class="mdi mdi-store text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-white/20">
                    <div class="flex justify-between text-xs">
                        <span>Tercatat Aktif: <span id="umkmAktif">{{ number_format($umkmAktif) }}</span></span>
                    </div>
                </div>
            </div>


            <div
                class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-6 text-white shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:shadow-[0_8px_30px_rgba(16,185,129,0.4)] transition-all duration-300 hover:-translate-y-1.5 cursor-pointer relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white rounded-full mix-blend-overlay opacity-10 blur-xl group-hover:scale-150 transition-transform duration-700">
                </div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-[11px] font-bold opacity-80 uppercase tracking-widest mb-1">UMKM AKTIF</p>
                        <p class="text-4xl font-black tracking-tight">{{ number_format($umkmAktif) }}</p>
                        <div class="mt-3 flex items-center gap-1.5">
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/20 text-white border border-white/30 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="mdi mdi-check-decagram"></i>
                                Terverifikasi
                            </span>
                            <span class="text-[10px] opacity-70">Siap beroperasi</span>
                        </div>
                    </div>
                    <div
                        class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 group-hover:bg-white/20 transition-colors shadow-inner">
                        <i class="mdi mdi-check-circle text-2xl"></i>
                    </div>
                </div>

            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
                <div class="px-4 md:px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                    <div class="flex justify-between items-center flex-wrap gap-4">
                        <div>
                            <h3 class="text-base md:text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="mdi mdi-chart-line text-blue-500 text-xl"></i>
                                Tren Pendaftaran & Verifikasi
                            </h3>
                            <p class="text-xs md:text-sm text-slate-500 mt-1">Data statistik pendaftaran dan verifikasi UMKM per periode</p>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                                <span class="text-xs text-slate-600 font-medium">Pendaftaran</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 bg-emerald-500 rounded-full"></span>
                                <span class="text-xs text-slate-600 font-medium">Verifikasi</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-4 md:px-6 py-3 border-b border-slate-100 bg-white">
                    <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3">
                        <div class="flex flex-wrap gap-2">
                            <button onclick="setChartFilter('daily')" id="filterDailyBtn"
                                class="px-3 py-1.5 text-xs font-medium rounded-md transition-all bg-white text-slate-600 border border-slate-200 hover:bg-slate-50">
                                <i class="mdi mdi-calendar-today text-sm mr-1 hidden sm:inline"></i> Harian
                            </button>
                            <button onclick="setChartFilter('weekly')" id="filterWeeklyBtn"
                                class="px-3 py-1.5 text-xs font-medium rounded-md transition-all bg-white text-slate-600 border border-slate-200 hover:bg-slate-50">
                                <i class="mdi mdi-calendar-week text-sm mr-1 hidden sm:inline"></i> Mingguan
                            </button>
                            <button onclick="setChartFilter('monthly')" id="filterMonthlyBtn"
                                class="px-3 py-1.5 text-xs font-medium rounded-md transition-all bg-blue-600 text-white shadow-sm">
                                <i class="mdi mdi-calendar-month text-sm mr-1 hidden sm:inline"></i> Bulanan
                            </button>
                            <button onclick="setChartFilter('yearly')" id="filterYearlyBtn"
                                class="px-3 py-1.5 text-xs font-medium rounded-md transition-all bg-white text-slate-600 border border-slate-200 hover:bg-slate-50">
                                <i class="mdi mdi-calendar text-sm mr-1 hidden sm:inline"></i> Tahunan
                            </button>
                        </div>
                        <div class="flex items-center justify-between sm:justify-end gap-3">
                            <div class="text-xs text-slate-400">
                                <i class="mdi mdi-information-outline"></i>
                                <span id="chartPeriodLabel" class="text-[11px] sm:text-xs">Menampilkan data 12 bulan terakhir</span>
                            </div>
                            <button id="refreshChartBtn"
                                class="p-1.5 text-slate-400 hover:text-blue-600 transition-all rounded-2xl hover:bg-blue-50 flex-shrink-0">
                                <i class="mdi mdi-refresh text-lg" id="refreshIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-4 md:p-6">
                    <div class="relative w-full" style="min-height: 280px;">
                        <canvas id="trendChart" style="max-height: 320px; width: 100%; height: auto;"></canvas>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Sektor Chart Card -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Distribusi Sektor</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Per Sektor Usaha</p>
                        </div>
                        <i class="mdi mdi-chart-donut text-2xl text-slate-400"></i>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 sm:h-[160px]">
                        <div class="w-full sm:w-1/2 h-[160px] sm:h-full flex items-center justify-center">
                            <canvas id="sektorChart"></canvas>
                        </div>
                        <div id="sektorLegend" class="w-full sm:w-1/2 space-y-2 text-xs max-h-[160px] sm:max-h-full overflow-y-auto pr-1 sidebar-scroll">
                            <!-- Dynamic list will be rendered here -->
                        </div>
                    </div>
                </div>

                <!-- Distribusi Wilayah Card -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all">
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Distribusi Wilayah</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Per Kelurahan</p>
                        </div>
                        <i class="mdi mdi-map-marker text-2xl text-slate-400"></i>
                    </div>

                    <div id="regionalData" class="space-y-4 max-h-[160px] overflow-y-auto pr-2 sidebar-scroll">
                        @forelse($distribusiWilayah as $wilayah)
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs font-medium text-slate-700">{{ $wilayah->nama_kelurahan }}</span>
                                    <span class="text-xs font-bold text-slate-600">{{ $wilayah->total }} UMKM</span>
                                </div>
                                <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full transition-all"
                                        style="width: {{ $totalUmkm > 0 ? ($wilayah->total / $totalUmkm) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-slate-400 py-8">Belum ada data wilayah</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800">UMKM Terbaru</h3>
                <a href="{{ route('admin.umkm.index') }}" class="text-sm text-blue-600 hover:text-blue-700">Lihat
                    semua &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama Usaha</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Pemilik / NIK
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kelurahan</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody id="recentUmkmTable" class="divide-y divide-slate-100">
                        @forelse($umkmTerbaru as $umkm)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-800">{{ $umkm->nama_usaha }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $umkm->kategori->nama_kategori ?? '-' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-slate-600">{{ $umkm->pemilik->nama_lengkap ?? '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ $umkm->pemilik->nik_masked ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-slate-600">{{ $umkm->pemilik->kelurahan ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($umkm->status_verifikasi == 'terverifikasi')
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Terverifikasi</span>
                                    @elseif($umkm->status_verifikasi == 'terkirim')
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Menunggu</span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">Draft</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada UMKM terbaru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let trendChart;
        let sektorChart;
        let initialSektorData = @json($distribusiSektor);
        const sectorColors = [
            '#3b82f6', // blue
            '#10b981', // emerald
            '#f59e0b', // amber
            '#8b5cf6', // violet
            '#ec4899', // pink
            '#06b6d4', // cyan
            '#f43f5e', // rose
            '#64748b'  // slate
        ];
        let currentFilter = 'monthly';
        let autoRefreshInterval;
        let lastPendingCount = {{ $pendingCount ?? 0 }};

        document.addEventListener('DOMContentLoaded', function() {
            initializeChart();
            loadChartData('monthly');
            startAutoRefresh();

            // Add click listener for refresh button spin animation
            const refreshBtn = document.getElementById('refreshChartBtn');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', () => {
                    const icon = document.getElementById('refreshIcon');
                    if (icon) {
                        icon.classList.add('animate-spin');
                        setTimeout(() => icon.classList.remove('animate-spin'), 1000);
                    }
                    loadChartData(currentFilter);
                });
            }
        });

        function startAutoRefresh() {
            // Cek data baru setiap 10 detik
            autoRefreshInterval = setInterval(() => refreshAllData(), 10000);
        }

        async function refreshAllData() {
            try {
                const url = `{{ route('admin.dashboard') }}?ajax=1&filter=${currentFilter}`;
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();

                if (data.success) {
                    // Notifikasi Toast Jika Ada UMKM Baru
                    if (data.pendingCount > lastPendingCount) {
                        const selisih = data.pendingCount - lastPendingCount;
                        if (window.showToast) {
                            window.showToast('info', 'Pendaftaran Baru!',
                                `Ada ${selisih} UMKM baru yang mendaftar dan perlu diverifikasi.`);
                        }

                        // Mainkan suara notifikasi kecil jika ada (opsional)
                        try {
                            let audio = new Audio('{{ asset('sounds/notification.mp3') }}');
                            audio.play().catch(e => console.log('Audio autoplay prevented'));
                        } catch (e) {}
                    }
                    lastPendingCount = data.pendingCount;

                    // Update Statistik
                    if (document.getElementById('totalUmkm')) document.getElementById('totalUmkm').innerText = new Intl
                        .NumberFormat('id-ID').format(data.totalUmkm);
                    if (document.getElementById('umkmAktif')) document.getElementById('umkmAktif').innerText = new Intl
                        .NumberFormat('id-ID').format(data.umkmAktif);
                    if (document.getElementById('growthUmkm')) document.getElementById('growthUmkm').innerText = data
                        .growthUmkm;

                    // Update badge notifikasi di aksi cepat
                    const pendingBadge = document.querySelector('.animate-bounce');
                    if (pendingBadge) {
                        if (data.pendingCount > 0) {
                            pendingBadge.innerText = data.pendingCount;
                            pendingBadge.style.display = 'block';
                        } else {
                            pendingBadge.style.display = 'none';
                        }
                    }

                    // Update tabel UMKM Terbaru
                    if (data.umkmTerbaru) {
                        updateRecentUmkmTable(data.umkmTerbaru);
                    }

                    // Update Distribusi Wilayah
                    if (data.distribusiWilayah && data.totalUmkm > 0) {
                        updateRegionalData(data.distribusiWilayah, data.totalUmkm);
                    }

                    // Update Distribusi Sektor
                    if (data.distribusiSektor && sektorChart) {
                        const filteredSektor = data.distribusiSektor.filter(s => s.total > 0);
                        const labels = filteredSektor.map(s => s.nama_sektor);
                        const totals = filteredSektor.map(s => s.total);
                        const colors = filteredSektor.map((_, i) => sectorColors[i % sectorColors.length]);

                        sektorChart.data.labels = labels;
                        sektorChart.data.datasets[0].data = totals;
                        sektorChart.data.datasets[0].backgroundColor = colors;
                        sektorChart.update();

                        updateSektorLegend(filteredSektor, colors);
                    }

                    // Update Grafik
                    if (data.labels && trendChart) {
                        trendChart.data.labels = data.labels;
                        trendChart.data.datasets[0].data = data.pendaftaran;
                        trendChart.data.datasets[1].data = data.verifikasi;
                        trendChart.update();
                    }
                }
            } catch (error) {
                console.error('Error refreshing data:', error);
            }
        }

        function updateSektorLegend(filteredData, colors) {
            const legendContainer = document.getElementById('sektorLegend');
            if (!legendContainer) return;
            
            if (filteredData.length === 0) {
                legendContainer.innerHTML = '<div class="text-slate-400 text-center py-8">Tidak ada data</div>';
                return;
            }
            
            legendContainer.innerHTML = filteredData.map((s, i) => {
                const color = colors[i % colors.length];
                return `
                    <div class="flex items-center justify-between py-1 border-b border-slate-50 last:border-0">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2 h-2 rounded-full shrink-0" style="background-color: ${color}"></span>
                            <span class="text-slate-600 font-medium truncate">${s.nama_sektor}</span>
                        </div>
                        <span class="text-slate-800 font-semibold ml-2 shrink-0">${s.total}</span>
                    </div>
                `;
            }).join('');
        }

        function updateRecentUmkmTable(umkmTerbaru) {
            const tbody = document.getElementById('recentUmkmTable');
            if (!tbody) return;

            if (umkmTerbaru.length === 0) {
                tbody.innerHTML =
                    '<tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada UMKM terbaru.</td></tr>';
                return;
            }

            let html = '';
            umkmTerbaru.forEach(umkm => {
                let statusHtml = '';
                if (umkm.status_verifikasi == 'terverifikasi') {
                    statusHtml =
                        '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Terverifikasi</span>';
                } else if (umkm.status_verifikasi == 'terkirim') {
                    statusHtml =
                        '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Menunggu</span>';
                } else {
                    statusHtml =
                        '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">Draft</span>';
                }

                html += `
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <p class="font-medium text-slate-800">${umkm.nama_usaha}</p>
                        <p class="text-xs text-slate-400 mt-0.5">${umkm.no_pendaftaran || '-'}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-slate-600">${umkm.pemilik ? umkm.pemilik.nama_lengkap : '-'}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-600">${umkm.pemilik ? umkm.pemilik.kelurahan : '-'}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        ${statusHtml}
                    </td>
                </tr>
            `;
            });

            tbody.innerHTML = html;
        }

        function updateRegionalData(kelurahans, totalUmkm) {
            const container = document.getElementById('regionalData');
            if (!container) return;
            if (!kelurahans || kelurahans.length === 0) {
                container.innerHTML = '<p class="text-center text-slate-400 py-8">Belum ada data wilayah</p>';
                return;
            }

            let html = '';
            kelurahans.forEach(kel => {
                const percentage = totalUmkm > 0 ? (kel.total / totalUmkm * 100) : 0;
                html += `
            <div>
                <div class="flex justify-between items-center mb-1">
                    <span class="text-sm font-medium text-slate-700">${kel.nama_kelurahan}</span>
                    <span class="text-sm font-bold text-slate-600">${kel.total} UMKM</span>
                </div>
                <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full transition-all duration-500" 
                         style="width: ${percentage}%">
                    </div>
                </div>
            </div>`;
            });
            container.innerHTML = html;
        }

        async function loadChartData(filter) {
            try {
                const url = `{{ route('admin.dashboard.chart-data') }}?filter=${filter}`;
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();

                if (data.success && trendChart) {
                    trendChart.data.labels = data.labels;
                    trendChart.data.datasets[0].data = data.pendaftaran;
                    trendChart.data.datasets[1].data = data.verifikasi;
                    trendChart.update();
                }
            } catch (error) {
                console.error(`Error loading chart data:`, error);
            }
        }

        function setChartFilter(filter) {
            currentFilter = filter;

            // Update button styles
            const filters = ['daily', 'weekly', 'monthly', 'yearly'];
            filters.forEach(f => {
                const btn = document.getElementById(`filter${f.charAt(0).toUpperCase() + f.slice(1)}Btn`);
                if (btn) {
                    if (f === filter) {
                        btn.className =
                            "px-3 py-1.5 text-xs font-medium rounded-md transition-all bg-blue-600 text-white shadow-sm";
                    } else {
                        btn.className =
                            "px-3 py-1.5 text-xs font-medium rounded-md transition-all bg-white text-slate-600 border border-slate-200 hover:bg-slate-50";
                    }
                }
            });

            // Update period label
            const periodLabel = document.getElementById('chartPeriodLabel');
            const periodTexts = {
                daily: 'Menampilkan data 30 hari terakhir',
                weekly: 'Menampilkan data 12 minggu terakhir',
                monthly: 'Menampilkan data 12 bulan terakhir',
                yearly: 'Menampilkan data 5 tahun terakhir'
            };
            if (periodLabel) periodLabel.innerHTML = periodTexts[filter];

            loadChartData(filter);
        }

        function initializeChart() {
            const ctx = document.getElementById('trendChart').getContext('2d');
            trendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                            label: 'Pendaftaran',
                            data: [],
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Verifikasi',
                            data: [],
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 1.8,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#fff',
                            bodyColor: '#cbd5e1',
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw + ' UMKM';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#e2e8f0',
                                drawBorder: false
                            },
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return value + ' UMKM';
                                }
                            },
                            title: {
                                display: true,
                                text: 'Jumlah UMKM',
                                color: '#64748b',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 35,
                                autoSkip: true,
                                maxTicksLimit: 8,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });

            // Sektor Chart
            const sektorCtx = document.getElementById('sektorChart').getContext('2d');
            const filteredSektor = initialSektorData.filter(s => s.total > 0);
            const sectorLabels = filteredSektor.map(s => s.nama_sektor);
            const sectorTotals = filteredSektor.map(s => s.total);
            const colors = filteredSektor.map((_, i) => sectorColors[i % sectorColors.length]);
            
            sektorChart = new Chart(sektorCtx, {
                type: 'doughnut',
                data: {
                    labels: sectorLabels,
                    datasets: [{
                        data: sectorTotals,
                        backgroundColor: colors,
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    cutout: '70%'
                }
            });

            // Render custom legend
            updateSektorLegend(filteredSektor, colors);
        }
    </script>
@endsection
