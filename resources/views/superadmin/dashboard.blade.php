@extends('layouts.superadmin')
@section('title', 'Dashboard')
@section('breadcrumb', 'Overview')

@section('content')
    <div class="space-y-6">
        <!-- Hero Banner Premium (Light Theme) -->
        <div class="relative overflow-hidden rounded-3xl bg-slate-50 p-8 md:p-10 shadow-sm border border-slate-200 mb-8">
            <!-- Batik Background Accent -->
            <div class="absolute inset-0 z-0 opacity-10 mix-blend-multiply" style="background-image: url('{{ asset('img/batik-bg.png') }}'); background-size: 300px; background-repeat: repeat;"></div>

            <!-- Animated Background Gradients -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-pulse"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-sky-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-pulse" style="animation-delay: 4s;"></div>

            <!-- Content Container with Glassmorphism -->
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white/80 border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <div class="text-slate-800">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100/80 border border-blue-200 text-xs font-bold tracking-wider uppercase mb-4 text-blue-700 shadow-sm">
                        <i class="mdi mdi-shield-crown text-blue-600"></i> Super Administrator
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 bg-clip-text text-transparent bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-800">
                        Selamat Datang, {{ Auth::user()->name }}!
                    </h1>
                    <p class="text-slate-600 text-sm md:text-base max-w-2xl font-medium">
                        Pantau seluruh pendaftaran UMKM, kelola hak akses pengguna, dan tinjau performa sistem secara real-time.
                    </p>
                </div>

                <div class="flex-shrink-0 text-right bg-white rounded-xl p-4 border border-slate-200/80 shadow-sm">
                    <p class="text-xs text-slate-700 uppercase tracking-widest font-bold mb-1">Hari Ini</p>
                    <p class="text-xl font-black text-slate-800">{{ now()->translatedFormat('l, d F Y') }}</p>
                    <p class="text-sm text-slate-600 mt-1 flex items-center justify-end gap-1 font-semibold"><i class="mdi mdi-clock-outline text-blue-600"></i> <span id="realtimeClock">{{ now()->format('H:i') }}</span></p>
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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
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
                <div class="mt-4 pt-3 border-t border-white/20">
                    <div class="flex justify-between text-xs">
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-3xl p-6 text-white shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:shadow-[0_8px_30px_rgba(168,85,247,0.4)] transition-all duration-300 hover:-translate-y-1.5 cursor-pointer relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white rounded-full mix-blend-overlay opacity-10 blur-xl group-hover:scale-150 transition-transform duration-700">
                </div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-[11px] font-bold opacity-80 uppercase tracking-widest mb-1">PENGGUNA AKTIF</p>
                        <p class="text-4xl font-black tracking-tight">{{ number_format($totalPengguna) }}</p>
                        <div class="mt-3 flex items-center gap-1.5">
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/20 text-white border border-white/30 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="mdi mdi-account-group"></i>
                                Total
                            </span>
                            <span class="text-[10px] opacity-70">Akun terdaftar</span>
                        </div>
                    </div>
                    <div
                        class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 group-hover:bg-white/20 transition-colors shadow-inner">
                        <i class="mdi mdi-account-group text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-white/20">
                    <div class="flex justify-between text-xs">
                        <span>Admin: <span id="totalAdmin">{{ number_format($totalAdmin) }}</span></span>
                        <span>Operator: <span id="totalOperator">{{ number_format($totalOperator) }}</span></span>
                    </div>
                </div>
            </div>
            <a href="{{ route('superadmin.verifikasi_akun.index') }}"
                class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-3xl p-6 text-white shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:shadow-[0_8px_30px_rgba(245,158,11,0.4)] transition-all duration-300 hover:-translate-y-1.5 block relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white rounded-full mix-blend-overlay opacity-10 blur-xl group-hover:scale-150 transition-transform duration-700">
                </div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-[11px] font-bold opacity-80 uppercase tracking-widest mb-1">AKUN PENDING</p>
                        <p class="text-4xl font-black tracking-tight">{{ number_format($pendingAkunCount) }}</p>
                        <div class="mt-3 flex items-center gap-1.5">
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/20 text-white border border-white/30 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="mdi mdi-account-clock"></i>
                                Verifikasi KTP
                            </span>
                        </div>
                    </div>
                    <div
                        class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 group-hover:bg-white/20 transition-colors shadow-inner">
                        <i class="mdi mdi-card-account-details-outline text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-white/20">
                    <div class="flex justify-between text-xs">
                        <span>Lihat detail antrean <i class="mdi mdi-arrow-right"></i></span>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div
                class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden flex flex-col">
                <div class="px-4 md:px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                    <div class="flex justify-between items-center flex-wrap gap-4">
                        <div>
                            <h3 class="text-base md:text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="mdi mdi-chart-line text-blue-500 text-xl"></i>
                                Tren Pendaftaran UMKM
                            </h3>
                            <p class="text-xs md:text-sm text-slate-700 mt-1">Data statistik pendaftaran dan verifikasi UMKM
                                per periode</p>
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
                                class="px-3 py-1.5 text-xs font-medium rounded-md transition-all bg-blue-600 text-white shadow-sm">
                                <i class="mdi mdi-calendar-today text-sm mr-1 hidden sm:inline"></i> Harian
                            </button>
                            <button onclick="setChartFilter('weekly')" id="filterWeeklyBtn"
                                class="px-3 py-1.5 text-xs font-medium rounded-md transition-all bg-white text-slate-600 border border-slate-200 hover:bg-slate-50">
                                <i class="mdi mdi-calendar-week text-sm mr-1 hidden sm:inline"></i> Mingguan
                            </button>
                            <button onclick="setChartFilter('monthly')" id="filterMonthlyBtn"
                                class="px-3 py-1.5 text-xs font-medium rounded-md transition-all bg-white text-slate-600 border border-slate-200 hover:bg-slate-50">
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
                                <span id="chartPeriodLabel" class="text-[11px] sm:text-xs">Menampilkan data 30 hari
                                    terakhir</span>
                            </div>
                            <button id="refreshChartBtn"
                                class="p-1.5 text-slate-400 hover:text-blue-600 transition-all rounded-2xl hover:bg-blue-50 flex-shrink-0">
                                <i class="mdi mdi-refresh text-lg" id="refreshIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-4 md:p-6 flex-1 flex flex-col">
                    <div class="relative w-full flex-1" style="min-height: 320px;">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Distribusi Chart Card -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Distribusi UMKM</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Proporsi Data UMKM</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <select id="chartTypeSelector" onchange="switchChartType()" class="text-xs border-slate-200 rounded-lg text-slate-600 focus:ring-blue-500 focus:border-blue-500 bg-slate-50">
                                <option value="sektor">Berdasarkan Sektor</option>
                                <option value="kategori">Berdasarkan Kategori</option>
                            </select>
                            <i class="mdi mdi-chart-donut text-2xl text-slate-400 hidden sm:block"></i>
                        </div>
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

                    <div id="regionalData" class="space-y-4 max-h-[160px] overflow-y-auto pr-2">
                        @forelse($distribusiWilayah as $wilayah)
                            <div class="group cursor-pointer"
                                onclick="window.location.href='{{ route('superadmin.umkm', ['kelurahan' => $wilayah->nama_kelurahan]) }}'">
                                <div class="flex justify-between items-center mb-1">
                                    <span
                                        class="text-xs font-medium text-slate-700 group-hover:text-blue-600 transition-colors">
                                        {{ $wilayah->nama_kelurahan }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-600">{{ $wilayah->total }} UMKM</span>
                                </div>
                                <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full transition-all duration-500"
                                        style="width: {{ $totalUmkm > 0 ? ($wilayah->total / $totalUmkm) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-slate-400 py-8">Belum ada data wilayah</p>
                        @endforelse
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100">
                        <a href="{{ route('superadmin.umkm') }}"
                            class="text-xs text-blue-600 hover:text-blue-700 flex items-center gap-1 group">
                            <span>Lihat semua UMKM</span>
                            <i class="mdi mdi-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div
                class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center flex-wrap gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">UMKM Terbaru</h3>
                        <p class="text-sm text-slate-700 mt-1">Data UMKM yang baru terdaftar</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('superadmin.umkm') }}"
                            class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-1">
                            <span>Lihat semua</span>
                            <i class="mdi mdi-arrow-right text-sm"></i>
                        </a>
                        <button onclick="refreshTable()"
                            class="p-1.5 text-slate-700 hover:text-blue-600 transition-all rounded-2xl hover:bg-slate-100">
                            <i class="mdi mdi-refresh text-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[600px]">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Nama Usaha
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Pemilik</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-slate-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="recentUmkmTable" class="divide-y divide-slate-100">
                            @forelse($umkmTerbaru as $umkm)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-slate-800">{{ $umkm->nama_usaha }}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $umkm->no_pendaftaran ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-slate-600">{{ $umkm->pemilik?->nama_lengkap ?? '-' }}</p>
                                        <p class="text-xs text-slate-400">{{ $umkm->pemilik?->kelurahan ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($umkm->status_verifikasi == 'terverifikasi')
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                                Terverifikasi
                                            </span>
                                        @elseif($umkm->status_verifikasi == 'menunggu_verifikasi')
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                                Menunggu
                                            </span>
                                        @elseif($umkm->status_verifikasi == 'ditolak')
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                                Ditolak
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                                <span class="w-1.5 h-1.5 bg-slate-500 rounded-full"></span>
                                                Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick="detailUmkm('{{ $umkm->id_umkm }}')"
                                            class="text-blue-600 hover:text-blue-800 transition-colors p-1.5 hover:bg-blue-50 rounded-2xl">
                                            <i class="mdi mdi-eye text-xl"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                        <i class="mdi mdi-database-off text-4xl block mb-2"></i>
                                        Belum ada data UMKM
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Aktivitas Terbaru</h3>
                        <p class="text-sm text-slate-700 mt-1">Log aktivitas sistem</p>
                    </div>
                    <div class="flex gap-2">
                        <i class="mdi mdi-bell-ring text-2xl text-slate-400"></i>
                        <button onclick="refreshActivity()"
                            class="p-1.5 text-slate-700 hover:text-blue-600 transition-all rounded-2xl hover:bg-slate-100">
                            <i class="mdi mdi-refresh text-lg"></i>
                        </button>
                    </div>
                </div>
                <div id="activityList" class="divide-y divide-slate-100 max-h-[400px] overflow-y-auto">
                    @forelse($aktivitasTerbaru as $aktivitas)
                        <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="mdi mdi-check-circle text-blue-600 text-xl"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-slate-700">{{ $aktivitas->deskripsi }}</p>
                                    <div class="flex items-center gap-3 mt-1">
                                        <p class="text-xs text-slate-400 flex items-center gap-1">
                                            <i class="mdi mdi-account-circle text-xs"></i>
                                            {{ $aktivitas->user->name ?? 'Sistem' }}
                                        </p>
                                        <p class="text-xs text-slate-400 flex items-center gap-1">
                                            <i class="mdi mdi-clock-outline text-xs"></i>
                                            {{ $aktivitas->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-slate-400">
                            <i class="mdi mdi-bell-off text-4xl block mb-2"></i>
                            Belum ada aktivitas
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex justify-between items-center mb-4 flex-wrap gap-4">
                <div class="flex items-center gap-2">
                    <i class="mdi mdi-server text-blue-600 text-xl"></i>
                    <h3 class="text-lg font-bold text-slate-800">Status Sistem</h3>
                </div>
                <div class="flex items-center gap-4">
                    <span id="lastUpdate" class="text-xs text-slate-400">Update terakhir:
                        {{ now()->format('H:i:s') }}</span>
                    <button onclick="refreshSystemStatus()"
                        class="p-1.5 text-slate-700 hover:text-blue-600 transition-all rounded-2xl hover:bg-slate-100">
                        <i class="mdi mdi-refresh text-lg"></i>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-slate-50 rounded-xl">
                    <div class="text-xs text-slate-700 uppercase tracking-wider mb-2">DATABASE</div>
                    <div class="flex items-center justify-center gap-2">
                        <span id="dbIndicator"
                            class="w-2 h-2 rounded-full {{ $systemStatus['database']['status'] ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></span>
                        <span id="dbStatus"
                            class="text-sm font-semibold {{ $systemStatus['database']['status'] ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $systemStatus['database']['status'] ? 'Online' : 'Offline' }}
                        </span>
                    </div>
                    <div id="dbLatency" class="text-xs text-slate-400 mt-1">Latency:
                        {{ $systemStatus['database']['latency_ms'] }} ms</div>
                    <div id="dbSize" class="text-xs text-slate-400">Size: {{ $systemStatus['database']['size_mb'] }}
                        MB</div>
                </div>

                <div class="text-center p-4 bg-slate-50 rounded-xl">
                    <div class="text-xs text-slate-700 uppercase tracking-wider mb-2">BEBAN SERVER</div>
                    <div id="serverLoad"
                        class="text-lg font-bold {{ $systemStatus['server']['load'] < 1 ? 'text-emerald-600' : ($systemStatus['server']['load'] < 2 ? 'text-amber-600' : 'text-red-600') }}">
                        {{ $systemStatus['server']['load'] }}
                    </div>
                    <div class="text-xs text-slate-400 mt-1">PHP: {{ $systemStatus['server']['php_version'] }}</div>
                    <div class="text-xs text-slate-400">Laravel: {{ $systemStatus['server']['laravel_version'] }}</div>
                </div>

                <div class="text-center p-4 bg-slate-50 rounded-xl">
                    <div class="text-xs text-slate-700 uppercase tracking-wider mb-2">CACHE</div>
                    <div id="cacheStatus"
                        class="text-sm font-semibold {{ $systemStatus['cache']['working'] ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ $systemStatus['cache']['working'] ? 'Berjalan' : 'Bermasalah' }}
                    </div>
                    <div id="cacheHit" class="text-xs text-slate-400 mt-1">Hit Rate:
                        {{ $systemStatus['cache']['hits_percentage'] }}%</div>
                </div>

                <div class="text-center p-4 bg-slate-50 rounded-xl">
                    <div class="text-xs text-slate-700 uppercase tracking-wider mb-2">SESI AKTIF</div>
                    <div id="activeSessions" class="text-lg font-bold text-slate-800">
                        {{ $systemStatus['active_sessions'] }}</div>
                    <div id="uptime" class="text-xs text-slate-400 mt-1">Uptime: {{ $systemStatus['uptime'] }}</div>
                </div>
            </div>

            <div
                class="mt-4 pt-4 border-t border-slate-100 flex justify-between items-center flex-wrap gap-2 text-xs text-slate-700">
                <span id="overallStatus"><i class="mdi mdi-check-circle text-emerald-500"></i> Semua sistem berjalan
                    normal</span>
            </div>
        </div>
    </div>

    <x-modal name="detailModal" maxWidth="2xl">
        <div class="relative bg-white rounded-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center z-10">
                <h3 class="text-xl font-bold text-slate-800">
                    <i class="mdi mdi-store text-blue-600 mr-2"></i>
                    Detail UMKM
                </h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="mdi mdi-close text-2xl"></i>
                </button>
            </div>
            <div id="modalContent" class="p-6">
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-2 text-slate-700">Memuat data...</p>
                </div>
            </div>
            <div id="modalFooter" class="sticky bottom-0 bg-white border-t px-6 py-4 flex justify-end gap-3 z-10">
                <button onclick="closeModal()"
                    class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </x-modal>

    <x-modal name="verifyModal" maxWidth="md">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-t-2xl">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                    <i class="mdi mdi-check-circle text-3xl text-emerald-600"></i>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-bold text-slate-900" id="modal-title">Verifikasi UMKM</h3>
                    <div class="mt-2">
                        <p class="text-sm text-slate-500 font-medium">Apakah Anda yakin ingin memverifikasi UMKM ini?</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100 rounded-b-2xl">
            <button onclick="confirmVerify()"
                class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-semibold text-white hover:bg-emerald-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                Ya, Verifikasi
            </button>
            <button onclick="closeVerifyModal()"
                class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                Batal
            </button>
        </div>
    </x-modal>

    <x-modal name="rejectModal" maxWidth="md">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-t-2xl">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <i class="mdi mdi-close-circle text-3xl text-red-600"></i>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-bold text-slate-900" id="modal-title">Tolak UMKM</h3>
                    <div class="mt-2">
                        <p class="text-sm text-slate-500 font-medium mb-4">Silakan masukkan alasan penolakan.</p>
                        <div class="text-left">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                            <textarea id="rejectReason" rows="3"
                                class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none"
                                placeholder="Masukkan alasan penolakan..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100 rounded-b-2xl">
            <button onclick="confirmReject()"
                class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-semibold text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                Ya, Tolak
            </button>
            <button onclick="closeRejectModal()"
                class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                Batal
            </button>
        </div>
    </x-modal>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let trendChart;
        let sektorChart;
        let initialSektorData = @json($distribusiSektor);
        let initialWilayahData = @json($distribusiWilayah);
        let initialKategoriData = @json($distribusiKategori);
        let lastDistribusiSektor = initialSektorData;
        let lastDistribusiWilayah = initialWilayahData;
        let lastDistribusiKategori = initialKategoriData;
        let currentPieChartType = 'sektor';

        function switchChartType() {
            const selector = document.getElementById('chartTypeSelector');
            if (selector) {
                currentPieChartType = selector.value;
                renderPieChart();
            }
        }

        function renderPieChart() {
            if (!sektorChart) return;
            
            let dataToRender = [];
            let labelKey = '';
            
            if (currentPieChartType === 'sektor') {
                dataToRender = lastDistribusiSektor.filter(s => s.total > 0);
                labelKey = 'nama_sektor';
            } else if (currentPieChartType === 'kategori') {
                dataToRender = lastDistribusiKategori.filter(k => k.total > 0);
                labelKey = 'nama_kategori';
            } else {
                dataToRender = lastDistribusiWilayah.filter(w => w.total > 0);
                labelKey = 'nama_kelurahan';
            }
            
            const labels = dataToRender.map(item => item[labelKey]);
            const totals = dataToRender.map(item => item.total);
            const colors = dataToRender.map((_, i) => sectorColors[i % sectorColors.length]);

            sektorChart.data.labels = labels;
            sektorChart.data.datasets[0].data = totals;
            sektorChart.data.datasets[0].backgroundColor = colors;
            sektorChart.update();

            updateSektorLegend(dataToRender, colors, labelKey);
        }
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
        let autoRefreshInterval;
        let currentFilter = 'daily';
        let lastPendingCount = {{ $pendingCount }};
        let soundEnabled = true;

        let chartData = {
            daily: {
                labels: [],
                pendaftaran: [],
                verifikasi: []
            },
            weekly: {
                labels: [],
                pendaftaran: [],
                verifikasi: []
            },
            monthly: {
                labels: [],
                pendaftaran: [],
                verifikasi: []
            },
            yearly: {
                labels: [],
                pendaftaran: [],
                verifikasi: []
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            initializeChart();
            startAutoRefresh();
            initChartFilter();

            // Load all chart data
            loadChartData('daily');
            loadChartData('weekly');
            loadChartData('monthly');
            loadChartData('yearly');

            window.addEventListener('resize', function() {
                if (trendChart) trendChart.resize();
                if (sektorChart) sektorChart.resize();
            });
        });

        async function loadChartData(filter) {
            try {
                // PERBAIKAN: Gunakan URL absolut
                const url = `/superadmin/dashboard/chart-data?filter=${filter}`;
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await response.json();

                if (data.success) {
                    chartData[filter] = {
                        labels: data.labels,
                        pendaftaran: data.pendaftaran,
                        verifikasi: data.verifikasi
                    };

                    if (currentFilter === filter && trendChart) {
                        updateChartData(filter);
                    }
                }
            } catch (error) {
                console.error(`Error loading ${filter} chart data:`, error);
            }
        }

        function updateChartData(filter) {
            if (!trendChart) return;
            const data = chartData[filter];

            if (data && data.labels && data.labels.length > 0) {
                trendChart.data.labels = data.labels;
                trendChart.data.datasets[0].data = data.pendaftaran;
                trendChart.data.datasets[1].data = data.verifikasi;
                trendChart.update();
            }
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
                    maintainAspectRatio: false,
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

            // Sektor Chart (Distribusi UMKM)
            const sektorCtx = document.getElementById('sektorChart').getContext('2d');
            sektorChart = new Chart(sektorCtx, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: [],
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
            renderPieChart();
        }

        function initChartFilter() {
            const filters = ['daily', 'weekly', 'monthly', 'yearly'];
            filters.forEach(filter => {
                const btn = document.getElementById(`filter${filter.charAt(0).toUpperCase() + filter.slice(1)}Btn`);
                if (btn) {
                    btn.addEventListener('click', () => setChartFilter(filter));
                }
            });

            const refreshBtn = document.getElementById('refreshChartBtn');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', () => {
                    loadChartData(currentFilter);
                    rotateBtn(refreshBtn);
                });
            }
        }

        function setChartFilter(filter) {
            currentFilter = filter;

            // Update button styles
            const buttons = ['daily', 'weekly', 'monthly', 'yearly'];
            buttons.forEach(f => {
                const btn = document.getElementById(`filter${f.charAt(0).toUpperCase() + f.slice(1)}Btn`);
                if (btn) {
                    if (f === filter) {
                        btn.className =
                            'px-3 py-1.5 text-xs font-medium rounded-md transition-all bg-blue-600 text-white shadow-sm';
                    } else {
                        btn.className =
                            'px-3 py-1.5 text-xs font-medium rounded-md transition-all bg-white text-slate-600 border border-slate-200 hover:bg-slate-50';
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

            // Update chart
            if (chartData[filter] && chartData[filter].labels && chartData[filter].labels.length > 0) {
                updateChartData(filter);
            } else {
                loadChartData(filter);
            }
        }
        function startAutoRefresh() {
            autoRefreshInterval = setInterval(() => refreshAllData(), 10000); // 10 seconds for real-time feel
        }

        async function refreshAllData() {
            try {
                let url = `{{ route('superadmin.dashboard') }}?ajax=1&filter=${currentFilter}`;

                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                
                if (data.success) {
                        // Check for new pending UMKM
                        if (data.pendingCount > lastPendingCount) {
                            const selisih = data.pendingCount - lastPendingCount;
                            if (window.showToast) {
                                window.showToast('info', 'Pendaftaran Baru!',
                                    `Ada ${selisih} UMKM baru yang mendaftar dan perlu diverifikasi.`);
                            }
                            if (typeof playNotificationSound === 'function') {
                                playNotificationSound();
                            }
                        }
                        lastPendingCount = data.pendingCount;

                        document.getElementById('totalUmkm').innerText = formatNumber(data.totalUmkm);
                        document.getElementById('umkmAktif').innerText = formatNumber(data.umkmAktif);
                        document.getElementById('umkmPending').innerText = formatNumber(data.umkmPending);
                        document.getElementById('growthUmkm').innerText = data.growthUmkm;
                        document.getElementById('totalAdmin').innerText = formatNumber(data.totalAdmin);
                        document.getElementById('totalOperator').innerText = formatNumber(data.totalOperator);

                        if (data.distribusiWilayah) {
                            lastDistribusiWilayah = data.distribusiWilayah;
                            updateRegionalData(data.distribusiWilayah, data.totalUmkm);
                        }
                        if (data.distribusiKategori) {
                            lastDistribusiKategori = data.distribusiKategori;
                        }
                        if (data.distribusiSektor && sektorChart) {
                            lastDistribusiSektor = data.distribusiSektor;
                            renderPieChart();
                        }
                        if (data.umkmTerbaru) updateRecentUmkmTable(data.umkmTerbaru);
                        if (data.systemStatus) updateSystemStatusUI(data.systemStatus);
                        if (data.aktivitasTerbaru) updateActivityListUI(data.aktivitasTerbaru);

                        if (data.labels && data.pendaftaran && data.verifikasi) {
                            chartData[currentFilter] = {
                                labels: data.labels,
                                pendaftaran: data.pendaftaran,
                                verifikasi: data.verifikasi
                            };
                            updateChartData(currentFilter);
                        }

                        const now = new Date();
                        document.getElementById('lastUpdate').innerHTML =
                            `Update terakhir: ${now.toLocaleTimeString('id-ID')}`;
                        document.getElementById('lastChecked').innerHTML = now.toLocaleTimeString('id-ID');
                    }
            } catch (error) {
                console.error('Error refreshing data:', error);
            }
        }

        function updateSektorLegend(filteredData, colors, labelKey = 'nama_sektor') {
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
                            <span class="text-slate-600 font-medium truncate">${s[labelKey]}</span>
                        </div>
                        <span class="text-slate-800 font-semibold ml-2 shrink-0">${s.total}</span>
                    </div>
                `;
            }).join('');
        }

        function updateActivityListUI(aktivitas) {
            const container = document.getElementById('activityList');
            if (!container || !aktivitas) return;

            if (aktivitas.length === 0) {
                container.innerHTML = `
                <div class="px-6 py-12 text-center text-slate-400">
                    <i class="mdi mdi-bell-off-outline text-4xl mb-3"></i>
                    <p>Belum ada aktivitas</p>
                </div>
            `;
                return;
            }

            let html = '';
            aktivitas.forEach(akt => {
                html += `
                <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="mdi mdi-check-circle text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-slate-700">${escapeHtml(akt.deskripsi)}</p>
                            <div class="flex items-center gap-3 mt-1">
                                <p class="text-xs text-slate-400 flex items-center gap-1">
                                    <i class="mdi mdi-account-circle text-xs"></i>
                                    ${escapeHtml(akt.user_name)}
                                </p>
                                <p class="text-xs text-slate-400 flex items-center gap-1">
                                    <i class="mdi mdi-clock-outline text-xs"></i>
                                    ${escapeHtml(akt.created_at)}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            });
            container.innerHTML = html;
        }

        function updateSystemStatusUI(status) {
            if (!status) return;

            const dbIndicator = document.getElementById('dbIndicator');
            const dbStatus = document.getElementById('dbStatus');
            const dbLatency = document.getElementById('dbLatency');
            const dbSize = document.getElementById('dbSize');
            const serverLoad = document.getElementById('serverLoad');
            const cacheStatus = document.getElementById('cacheStatus');
            const cacheHit = document.getElementById('cacheHit');
            const activeSessions = document.getElementById('activeSessions');
            const uptime = document.getElementById('uptime');

            if (dbIndicator) {
                dbIndicator.className =
                    `w-2 h-2 rounded-full ${status.database?.status ? 'bg-emerald-500 animate-pulse' : 'bg-red-500'}`;
            }
            if (dbStatus) {
                dbStatus.innerHTML = status.database?.status ? 'Online' : 'Offline';
                dbStatus.className =
                    `text-sm font-semibold ${status.database?.status ? 'text-emerald-600' : 'text-red-600'}`;
            }
            if (dbLatency) dbLatency.innerHTML = `Latency: ${status.database?.latency_ms || 0}ms`;
            if (dbSize) dbSize.innerHTML = `Size: ${status.database?.size_mb || 0} MB`;
            if (serverLoad) {
                const load = status.server?.load || 0;
                serverLoad.innerHTML = load;
                serverLoad.className =
                    `text-lg font-bold ${load < 1 ? 'text-emerald-600' : (load < 2 ? 'text-amber-600' : 'text-red-600')}`;
            }
            if (cacheStatus) {
                cacheStatus.innerHTML = status.cache?.working ? 'Berjalan' : 'Bermasalah';
                cacheStatus.className =
                    `text-sm font-semibold ${status.cache?.working ? 'text-emerald-600' : 'text-red-600'}`;
            }
            if (cacheHit) cacheHit.innerHTML = `Hit Rate: ${status.cache?.hits_percentage || 0}%`;
            if (activeSessions) activeSessions.innerHTML = status.active_sessions || 0;
            if (uptime) uptime.innerHTML = `Uptime: ${status.uptime || '-'}`;

            const overallStatus = document.getElementById('overallStatus');
            if (overallStatus) {
                const isDbWorking = status.database?.status;
                const isCacheWorking = status.cache?.working;

                if (isDbWorking && isCacheWorking) {
                    overallStatus.innerHTML =
                        `<i class="mdi mdi-check-circle text-emerald-500"></i> Semua sistem berjalan normal`;
                } else if (!isDbWorking) {
                    overallStatus.innerHTML =
                        `<i class="mdi mdi-alert-circle text-red-500 animate-pulse"></i> Database Offline! Hubungi Administrator`;
                } else {
                    overallStatus.innerHTML =
                        `<i class="mdi mdi-alert-circle text-amber-500"></i> Cache Bermasalah! Kinerja sistem dapat menurun`;
                }
            }
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
                html += `<div class="group cursor-pointer" onclick="window.location.href='{{ route('superadmin.umkm') }}?kelurahan=${encodeURIComponent(kel.nama_kelurahan)}'">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-sm font-medium text-slate-700 group-hover:text-blue-600 transition-colors">${escapeHtml(kel.nama_kelurahan)}</span>
                    <span class="text-sm font-bold text-slate-600">${formatNumber(kel.total)} UMKM</span>
                </div>
                <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full transition-all duration-500" style="width: ${percentage}%"></div>
                </div>
            </div>`;
            });
            container.innerHTML = html;
        }

        function updateRecentUmkmTable(umkmList) {
            const tbody = document.getElementById('recentUmkmTable');
            if (!tbody) return;
            if (!umkmList || umkmList.length === 0) {
                tbody.innerHTML =
                    '<tr><td colspan="4" class="px-6 py-12 text-center text-slate-400"><i class="mdi mdi-database-off text-4xl block mb-2"></i>Belum ada data UMKM</td></tr>';
                return;
            }

            let html = '';
            umkmList.forEach(umkm => {
                let statusHtml = '';
                if (umkm.status_verifikasi === 'terverifikasi') {
                    statusHtml =
                        '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Terverifikasi</span>';
                } else if (umkm.status_verifikasi === 'menunggu_verifikasi') {
                    statusHtml =
                        '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700"><span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>Menunggu</span>';
                } else if (umkm.status_verifikasi === 'ditolak') {
                    statusHtml =
                        '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700"><span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>Ditolak</span>';
                } else {
                    statusHtml =
                        '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600"><span class="w-1.5 h-1.5 bg-slate-500 rounded-full"></span>Draft</span>';
                }
                html += `<tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4"><p class="font-medium text-slate-800">${escapeHtml(umkm.nama_usaha) || '-'}</p><p class="text-xs text-slate-400 mt-0.5">${umkm.no_pendaftaran || '-'}</p></td>
                <td class="px-6 py-4"><p class="text-sm text-slate-600">${escapeHtml(umkm.pemilik?.nama_lengkap) || '-'}</p><p class="text-xs text-slate-400">${escapeHtml(umkm.pemilik?.kelurahan) || '-'}</p></td>
                <td class="px-6 py-4">${statusHtml}</td>
                <td class="px-6 py-4 text-center"><button onclick="detailUmkm('${umkm.id_umkm}')" class="text-blue-600 hover:text-blue-800 transition-colors p-1.5 hover:bg-blue-50 rounded-2xl"><i class="mdi mdi-eye text-xl"></i></button></td>
            </tr>`;
            });
            tbody.innerHTML = html;
        }

        function refreshSystemStatus() {
            refreshAllData();
        }

        function refreshTable() {
            refreshAllData();
        }

        function refreshActivity() {
            refreshAllData();
        }

        function rotateBtn(btn) {
            if (!btn) return;
            btn.classList.add('animate-spin');
            setTimeout(() => btn.classList.remove('animate-spin'), 1000);
        }

        function formatNumber(num) {
            if (!num && num !== 0) return '0';
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }

        // Modern synthesized notification sound
        function playNotificationSound() {
            if (!soundEnabled) return;
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;

                const audioCtx = new AudioContext();
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);

                // Sweet notification "ding" sound
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(880, audioCtx.currentTime);
                oscillator.frequency.exponentialRampToValueAtTime(1320, audioCtx.currentTime + 0.05); // Slide up slightly

                // Volume envelope
                gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
                gainNode.gain.linearRampToValueAtTime(0.5, audioCtx.currentTime + 0.02); // Quick fade in
                gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.3); // Smooth fade out

                oscillator.start(audioCtx.currentTime);
                oscillator.stop(audioCtx.currentTime + 0.3);
            } catch (e) {
                console.log('Browser blocked audio playback. User interaction required first.');
            }
        }

        function detailUmkm(id) {
            const modalContent = document.getElementById('modalContent');
            const modalFooter = document.getElementById('modalFooter');

            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'detailModal' }));
            modalContent.innerHTML =
                `<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div><p class="mt-2 text-slate-700">Memuat data...</p></div>`;

            fetch(`/superadmin/umkm/${id}/modal`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modalContent.innerHTML = data.html;
                        if (data.isPending) {
                            modalFooter.innerHTML = `
                        <button onclick="verifyUmkm('${id}')" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-all flex items-center gap-2">
                            <i class="mdi mdi-check-circle"></i> Verifikasi
                        </button>
                        <button onclick="rejectUmkm('${id}')" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-all flex items-center gap-2">
                            <i class="mdi mdi-close-circle"></i> Tolak
                        </button>
                        <button onclick="closeModal()" class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">
                            Tutup
                        </button>
                    `;
                        } else {
                            modalFooter.innerHTML =
                                `<button onclick="closeModal()" class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">Tutup</button>`;
                        }
                    } else {
                        modalContent.innerHTML = `<div class="text-center text-red-500 py-8">Gagal memuat data</div>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalContent.innerHTML = `<div class="text-center text-red-500 py-8">Terjadi kesalahan</div>`;
                });
        }

        function closeModal() {
            window.dispatchEvent(new CustomEvent('close-modal', { detail: 'detailModal' }));
        }

        let currentActionId = null;

        function verifyUmkm(id) {
            currentActionId = id;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'verifyModal' }));
        }

        function closeVerifyModal() {
            window.dispatchEvent(new CustomEvent('close-modal', { detail: 'verifyModal' }));
            currentActionId = null;
        }

        function confirmVerify() {
            if (!currentActionId) return;

            const id = currentActionId;
            closeVerifyModal();
            if (window.showToast) window.showToast('info', 'Memproses...', 'Sedang memverifikasi UMKM');

            setTimeout(() => {
                fetch(`/superadmin/umkm/${id}/verify`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeModal();
                            refreshAllData();
                            if (window.showToast) window.showToast('success', 'Berhasil', data.message || 'UMKM berhasil diverifikasi');
                        } else {
                            window.showToast('danger', 'Gagal', data.message || 'Terjadi kesalahan.');
                        }
                    })
                    .catch(error => {
                        window.showToast('danger', 'Error', 'Terjadi kesalahan sistem.');
                    });
            }, 1500);
        }

        function rejectUmkm(id) {
            currentActionId = id;
            document.getElementById('rejectReason').value = '';
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'rejectModal' }));
        }

        function closeRejectModal() {
            window.dispatchEvent(new CustomEvent('close-modal', { detail: 'rejectModal' }));
            currentActionId = null;
        }

        function confirmReject() {
            if (!currentActionId) return;

            const reason = document.getElementById('rejectReason').value.trim();
            if (!reason) {
                window.showToast('warning', 'Perhatian!', 'Alasan penolakan harus diisi');
                return;
            }

            const id = currentActionId;
            closeRejectModal();
            if (window.showToast) window.showToast('info', 'Memproses...', 'Sedang menolak UMKM');

            setTimeout(() => {
                fetch(`/superadmin/umkm/${id}/reject`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            alasan: reason
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeModal();
                            refreshAllData();
                            if (window.showToast) window.showToast('success', 'Berhasil', data.message || 'UMKM berhasil ditolak');
                        } else {
                            window.showToast('danger', 'Gagal', data.message || 'Terjadi kesalahan.');
                        }
                    })
                    .catch(error => {
                        window.showToast('danger', 'Error', 'Terjadi kesalahan sistem.');
                    });
            }, 1500);
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });
    </script>

    <style>
        @media (max-width: 768px) {
            canvas {
                max-height: 280px !important;
            }
        }

        @media (max-width: 640px) {
            canvas {
                max-height: 250px !important;
            }
        }

        #regionalData::-webkit-scrollbar,
        #activityList::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        #regionalData::-webkit-scrollbar-track,
        #activityList::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        #regionalData::-webkit-scrollbar-thumb,
        #activityList::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        #regionalData::-webkit-scrollbar-thumb:hover,
        #activityList::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
@endsection
