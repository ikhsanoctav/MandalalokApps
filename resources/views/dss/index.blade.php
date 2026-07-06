@extends($layout)

@section('title', 'Rekomendasi Kebijakan AI (DSS)')
@section('breadcrumb', 'Analisis Kebijakan AI (DSS)')

@section('content')
<div class="space-y-6" x-data="dssManager()">
    
    <!-- Top Header Card -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-700 p-6 md:p-8 shadow-lg text-white">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white rounded-full mix-blend-overlay opacity-10 blur-2xl"></div>
        <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-24 h-24 bg-white rounded-full mix-blend-overlay opacity-10 blur-xl"></div>
        
        <div class="relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white border border-white/20 mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Decision Support System (DSS)
            </span>
            <h2 class="text-2xl md:text-3xl font-bold mb-2">Analisis Strategis & Kebijakan UMKM berbasis AI 🤖</h2>
            <p class="text-indigo-100 max-w-2xl text-sm md:text-base leading-relaxed">
                Sistem ini memproses data UMKM secara real-time dari database Kecamatan Mandalajati untuk memberikan rekomendasi program kerja, bantuan modal, dan pelatihan bisnis yang objektif berdasarkan wilayah.
            </p>
        </div>
    </div>

    <!-- Stat Cards Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card 1: Kelola Kriteria -->
        <a href="{{ route('dss.kriteria.index') }}" class="block p-6 bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all group">
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mb-4 group-hover:scale-110 transition-transform">
                <i class="mdi mdi-list-ul text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">1. Kelola Kriteria</h3>
            <p class="text-sm text-slate-500">Tentukan kriteria (Benefit/Cost) untuk penilaian UMKM.</p>
        </a>

        <!-- Card 2: Pembobotan AHP -->
        <a href="{{ route('dss.ahp.index') }}" class="block p-6 bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all group">
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mb-4 group-hover:scale-110 transition-transform">
                <i class="mdi mdi-balance-scale text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">2. Pembobotan AHP</h3>
            <p class="text-sm text-slate-500">Tentukan bobot kriteria dengan perbandingan berpasangan (AHP).</p>
        </a>

        <!-- Card 3: Hasil Ranking SAW -->
        <a href="{{ route('dss.saw.hasil') }}" class="block p-6 bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all group">
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mb-4 group-hover:scale-110 transition-transform">
                <i class="mdi mdi-trophy text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">3. Hasil Ranking SAW</h3>
            <p class="text-sm text-slate-500">Lihat hasil akhir dan *traceability* skor per UMKM.</p>
        </a>
    </div>

    <!-- Stat Cards Summary (Original) -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <!-- Total UMKM -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total UMKM</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ number_format($chartData['summary']['total'], 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                    <i class="mdi mdi-store"></i>
                </div>
            </div>
            <div class="mt-3 text-xs text-slate-500">
                <span class="text-emerald-600 font-medium">{{ number_format($chartData['summary']['terverifikasi'], 0, ',', '.') }}</span> Terverifikasi
            </div>
        </div>

        <!-- Tingkat Verifikasi -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Tingkat Verifikasi</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $chartData['summary']['tingkat_verifikasi'] }}%</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <i class="mdi mdi-check-circle"></i>
                </div>
            </div>
            <div class="mt-3 text-xs text-slate-500">
                <span class="text-amber-500 font-medium">{{ number_format($chartData['summary']['pending'], 0, ',', '.') }}</span> Menunggu Persetujuan
            </div>
        </div>

        <!-- Kelurahan Teraktif -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Kelurahan Teraktif</p>
                    <h3 class="text-lg font-bold text-slate-800 truncate max-w-[120px]">{{ $chartData['summary']['kelurahan_top'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <i class="mdi mdi-map-marker-alt"></i>
                </div>
            </div>
            <div class="mt-3 text-xs text-slate-500">
                Pusat konsentrasi UMKM
            </div>
        </div>

        <!-- Rata-rata Tenaga Kerja -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Rata-rata Pekerja</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $chartData['summary']['avg_tenaga_kerja'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                    <i class="mdi mdi-users"></i>
                </div>
            </div>
            <div class="mt-3 text-xs text-slate-500">
                Per UMKM terverifikasi
            </div>
        </div>

        <!-- Sektor Terpopuler -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Sektor Terpopuler</p>
                    <h3 class="text-lg font-bold text-slate-800 truncate max-w-[120px]">{{ $chartData['summary']['sektor_top'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center text-rose-600">
                    <i class="mdi mdi-heart"></i>
                </div>
            </div>
            <div class="mt-3 text-xs text-slate-500">
                <span class="text-rose-600 font-medium">{{ number_format($chartData['summary']['sektor_top_val'], 0, ',', '.') }}</span> UMKM Terdaftar
            </div>
        </div>
    </div>

    <!-- Visualisasi Data (Charts) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Status Verifikasi (Doughnut) -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4">Distribusi Status Verifikasi</h3>
            <div class="relative h-64 w-full flex justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Pertumbuhan Bulanan (Line) -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4">Tren Registrasi UMKM (6 Bulan)</h3>
            <div class="relative h-64 w-full">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <!-- Top Sektor (Bar Horizontal) -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4">Top 8 Sektor Usaha</h3>
            <div class="relative h-72 w-full">
                <canvas id="sektorChart"></canvas>
            </div>
        </div>

        <!-- Sebaran Kelurahan (Bar) -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4">Konsentrasi per Wilayah (Kelurahan)</h3>
            <div class="relative h-72 w-full">
                <canvas id="kelurahanChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Main AI Analysis Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mt-4">
        
        <!-- Sidebar Riwayat Analisis AI -->
        <div class="lg:col-span-1 flex flex-col space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col h-full lg:min-h-[600px] lg:max-h-[800px]">
                <div class="pb-3 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2 text-sm md:text-base">
                        <i class="mdi mdi-history text-indigo-600"></i> Riwayat Analisis
                    </h3>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600" x-text="history.length"></span>
                </div>
                
                <!-- History List -->
                <div class="flex-1 overflow-y-auto mt-4 space-y-2 pr-1 scrollbar-thin max-h-[400px] lg:max-h-none">
                    <template x-if="history.length === 0">
                        <div class="flex flex-col items-center justify-center text-center py-12 text-slate-400 space-y-2">
                            <i class="mdi mdi-folder-open text-2xl"></i>
                            <p class="text-xs">Belum ada riwayat analisis.</p>
                        </div>
                    </template>
                    
                    <template x-for="item in history" :key="item.id">
                        <div 
                            class="group relative flex items-center justify-between p-3.5 rounded-xl border transition-all cursor-pointer"
                            :class="activeHistoryId === item.id 
                                ? 'border-indigo-500 bg-indigo-50/30 shadow-sm ring-1 ring-indigo-500/25' 
                                : 'border-slate-100 bg-slate-50/30 hover:border-indigo-200 hover:bg-indigo-50/10'"
                            @click="selectHistory(item.id)"
                        >
                            <div class="flex-1 min-w-0 pr-2">
                                <div class="flex items-center gap-1.5 mb-1.5">
                                    <span class="w-2 h-2 rounded-full" :class="activeHistoryId === item.id ? 'bg-indigo-600 animate-pulse' : 'bg-slate-400'"></span>
                                    <p class="text-xs font-bold truncate" :class="activeHistoryId === item.id ? 'text-indigo-900' : 'text-slate-700'" x-text="item.creator"></p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-1.5 py-0.5 rounded-xl text-[9px] font-bold uppercase tracking-wider" 
                                        :class="item.role === 'Super Admin' 
                                            ? 'bg-indigo-100 text-indigo-700' 
                                            : 'bg-slate-100 text-slate-600'" 
                                        x-text="item.role"></span>
                                    <span class="text-[10px] text-slate-400 font-medium" x-text="item.created_at"></span>
                                </div>
                            </div>
                            
                            <!-- Delete Button -->
                            <button 
                                @click.stop="confirmDeleteHistory(item.id)"
                                class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 opacity-0 group-hover:opacity-100 transition-all flex-shrink-0"
                                :class="activeHistoryId === item.id ? 'opacity-100' : ''"
                                title="Hapus Riwayat"
                            >
                                <i class="mdi mdi-trash-alt text-xs"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Laporan Rekomendasi AI (Main Content Area) -->
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 md:p-8 flex flex-col justify-between">
            
            <!-- Action Toolbar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <i class="mdi mdi-brain text-indigo-600"></i> Laporan Rekomendasi AI
                    </h3>
                    <p class="text-sm text-slate-500 mt-1 flex items-center gap-1.5" id="cache-time-indicator">
                        @if($cachedAt)
                            <i class="mdi mdi-clock text-slate-400"></i> Terakhir dianalisis: <span class="font-semibold text-slate-700">{{ $cachedAt }}</span>
                        @else
                            <i class="mdi mdi-clock text-slate-400"></i> Belum ada analisis yang disimpan.
                        @endif
                    </p>
                </div>
                
                <div class="flex gap-2">
                    <button 
                        @click="generateAnalysis(true)" 
                        :disabled="loading"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-700 disabled:opacity-50 transition-all shadow-sm shadow-indigo-200 active:scale-95"
                    >
                        <template x-if="!loading">
                            <i class="mdi mdi-sync-alt"></i>
                        </template>
                        <template x-if="loading">
                            <i class="mdi mdi-circle-notch fa-spin"></i>
                        </template>
                        <span x-text="loading ? 'Menganalisis Data...' : 'Mulai Analisis AI'"></span>
                    </button>

                    <button 
                        x-show="hasAnalysis && !loading" 
                        @click="window.print()"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-slate-100 text-slate-700 font-semibold text-sm hover:bg-slate-200 transition-all shadow-sm border border-slate-200 active:scale-95"
                        x-cloak
                    >
                        <i class="mdi mdi-print"></i> Cetak Laporan
                    </button>
                </div>
            </div>

            <!-- Intervention Input -->
            <div class="mb-6 bg-white border border-slate-200 rounded-2xl p-5 md:p-6 shadow-sm">
                <label for="intervention_focus" class="block text-sm font-bold text-slate-800 mb-2 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i class="mdi mdi-bullseye-arrow"></i>
                    </div>
                    Fokus Kebijakan / Intervensi (Opsional)
                </label>
                <textarea 
                    id="intervention_focus" 
                    x-model="interventionFocus" 
                    rows="2" 
                    class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm placeholder:text-slate-400 bg-slate-50/50 mt-2"
                    placeholder="Contoh: Tolong buatkan strategi khusus untuk meningkatkan daya saing sektor Kuliner..."
                ></textarea>
                <p class="text-xs text-slate-500 mt-3 flex items-center gap-1.5">
                    <i class="mdi mdi-information-outline text-indigo-400 text-sm"></i> 
                    Isi jika Anda ingin AI menyesuaikan rekomendasinya dengan target program kerja spesifik.
                </p>
            </div>

            <!-- Analysis Output -->
            <div>
                
                <!-- Loading State -->
                <div x-show="loading" class="space-y-8 py-12" x-cloak>
                    <div class="flex flex-col items-center justify-center text-center space-y-5">
                        <div class="relative w-20 h-20">
                            <div class="absolute inset-0 rounded-full border-4 border-indigo-100 animate-pulse"></div>
                            <div class="absolute inset-0 rounded-full border-4 border-t-indigo-600 border-r-transparent border-b-transparent border-l-transparent animate-spin"></div>
                            <div class="absolute inset-0 flex items-center justify-center text-indigo-600">
                                <i class="mdi mdi-robot text-xl"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-800 animate-pulse">AI Sedang Menyusun Strategi...</h4>
                            <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto leading-relaxed">
                                Menganalisis matriks SWOT, skor prioritas wilayah, dan tren pertumbuhan. Proses ini membutuhkan penalaran mendalam dan memakan waktu sekitar 30-60 detik.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Skeleton Loader -->
                    <div class="space-y-4 max-w-4xl mx-auto pt-8 border-t border-slate-100">
                        <div class="h-8 bg-slate-100 rounded-2xl w-1/3 animate-pulse mb-6"></div>
                        <div class="h-4 bg-slate-50 rounded-2xl w-full animate-pulse"></div>
                        <div class="h-4 bg-slate-50 rounded-2xl w-11/12 animate-pulse"></div>
                        <div class="h-4 bg-slate-50 rounded-2xl w-4/5 animate-pulse"></div>
                        
                        <div class="grid grid-cols-2 gap-4 pt-6">
                            <div class="h-32 bg-slate-50 rounded-xl border border-slate-100 animate-pulse"></div>
                            <div class="h-32 bg-slate-50 rounded-xl border border-slate-100 animate-pulse"></div>
                        </div>
                    </div>
                </div>

                <!-- Error State -->
                <div x-show="error" class="p-6 rounded-2xl bg-rose-50 border border-rose-100 text-rose-800 text-sm flex items-start gap-4" x-cloak>
                    <div class="p-2 bg-rose-100 rounded-2xl flex-shrink-0">
                        <i class="mdi mdi-exclamation-triangle text-rose-600 text-lg"></i>
                    </div>
                    <div>
                        <h5 class="font-bold text-base mb-1 text-rose-900">Gagal Memuat Analisis AI</h5>
                        <p x-text="errorMessage" class="text-rose-700"></p>
                    </div>
                </div>

                <!-- No Data State -->
                <div x-show="!hasAnalysis && !loading && !error" class="flex flex-col items-center justify-center text-center py-16 space-y-4" x-cloak>
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 border border-slate-100 mb-2">
                        <i class="mdi mdi-clipboard-list text-3xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-slate-700">Laporan Eksekutif Belum Tersedia</h4>
                        <p class="text-base text-slate-500 max-w-md mx-auto mt-2">Klik tombol "Mulai Analisis AI" di atas untuk men-generate laporan kebijakan strategis secara otomatis berdasarkan data UMKM terkini.</p>
                    </div>
                </div>

                <!-- Rendered AI Report (Alpine Template) -->
                <div x-show="hasAnalysis && !loading && !error" class="dss-report space-y-8" x-cloak>
                    
                    <!-- Ringkasan Eksekutif -->
                    <div class="bg-indigo-50/50 rounded-xl p-6 border border-indigo-100/50">
                        <h4 class="text-indigo-800 font-bold text-lg mb-3 flex items-center gap-2">
                            <i class="mdi mdi-flag text-indigo-500"></i> Ringkasan Eksekutif
                        </h4>
                        <p class="text-slate-700 leading-relaxed text-base" x-text="analysisData.ringkasan_eksekutif"></p>
                    </div>

                    <!-- Keputusan Strategis Utama -->
                    <div x-show="analysisData.keputusan_strategis_utama && analysisData.keputusan_strategis_utama.length > 0" class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl p-6 text-white shadow-md border border-emerald-500">
                        <h4 class="font-bold text-lg mb-4 flex items-center gap-2">
                            <i class="mdi mdi-bolt text-amber-300"></i> Rekomendasi Keputusan / Tindakan Eksekusi
                        </h4>
                        <div class="space-y-3">
                            <template x-for="(keputusan, index) in analysisData.keputusan_strategis_utama" :key="index">
                                <div class="flex items-start gap-3 bg-white/10 p-4 rounded-2xl border border-white/20 backdrop-blur-sm">
                                    <div class="w-8 h-8 rounded-full bg-white text-emerald-700 flex items-center justify-center font-bold text-sm flex-shrink-0 shadow-sm" x-text="index + 1"></div>
                                    <p class="text-emerald-50 font-medium leading-relaxed pt-1" x-text="keputusan"></p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Indikator Kunci -->
                    <div>
                        <!-- Menampilkan Fokus Intervensi jika ada -->
                        <template x-if="analysisData.intervensi_kebijakan">
                            <div class="mb-8 bg-gradient-to-br from-indigo-50 to-blue-50/30 border border-indigo-100 p-5 rounded-2xl shadow-sm relative overflow-hidden">
                                <div class="absolute right-0 top-0 w-32 h-32 bg-white rounded-full mix-blend-overlay opacity-60 -mt-10 -mr-10"></div>
                                <h4 class="text-sm font-bold text-indigo-900 flex items-center gap-2 mb-2 relative z-10">
                                    <div class="w-7 h-7 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-600">
                                        <i class="mdi mdi-bullseye-arrow"></i>
                                    </div>
                                    Analisis ini difokuskan pada:
                                </h4>
                                <p class="text-sm text-indigo-800 italic relative z-10 pl-9 font-medium" x-text="`&quot;${analysisData.intervensi_kebijakan}&quot;`"></p>
                            </div>
                        </template>

                        <h4 class="text-slate-800 font-bold text-lg mb-4 flex items-center gap-2 border-b border-slate-100 pb-2">
                            <i class="mdi mdi-chart-pie text-slate-400"></i> Indikator Kunci AI
                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <template x-for="(value, key) in analysisData.indikator_kunci" :key="key">
                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                    <p class="text-xs text-slate-500 font-medium mb-1 capitalize" x-text="key.replace(/_/g, ' ')"></p>
                                    <p class="text-sm font-bold text-slate-800" x-text="value"></p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- SWOT Analysis -->
                    <div>
                        <h4 class="text-slate-800 font-bold text-lg mb-4 flex items-center gap-2 border-b border-slate-100 pb-2">
                            <i class="mdi mdi-chess-knight text-slate-400"></i> Analisis SWOT Ekosistem
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Strengths -->
                            <div class="bg-emerald-50/50 rounded-xl p-5 border border-emerald-100">
                                <h5 class="font-bold text-emerald-800 mb-3 flex items-center gap-2"><i class="mdi mdi-arrow-up text-emerald-500"></i> Kekuatan (Strengths)</h5>
                                <ul class="space-y-2 text-sm text-slate-700">
                                    <template x-for="item in analysisData.analisis_swot?.kekuatan || []" :key="item">
                                        <li class="flex gap-2 items-start"><i class="mdi mdi-check text-emerald-400 mt-1 flex-shrink-0"></i> <span x-text="item"></span></li>
                                    </template>
                                </ul>
                            </div>
                            <!-- Weaknesses -->
                            <div class="bg-amber-50/50 rounded-xl p-5 border border-amber-100">
                                <h5 class="font-bold text-amber-800 mb-3 flex items-center gap-2"><i class="mdi mdi-arrow-down text-amber-500"></i> Kelemahan (Weaknesses)</h5>
                                <ul class="space-y-2 text-sm text-slate-700">
                                    <template x-for="item in analysisData.analisis_swot?.kelemahan || []" :key="item">
                                        <li class="flex gap-2 items-start"><i class="mdi mdi-minus text-amber-400 mt-1 flex-shrink-0"></i> <span x-text="item"></span></li>
                                    </template>
                                </ul>
                            </div>
                            <!-- Opportunities -->
                            <div class="bg-blue-50/50 rounded-xl p-5 border border-blue-100">
                                <h5 class="font-bold text-blue-800 mb-3 flex items-center gap-2"><i class="mdi mdi-lightbulb text-blue-500"></i> Peluang (Opportunities)</h5>
                                <ul class="space-y-2 text-sm text-slate-700">
                                    <template x-for="item in analysisData.analisis_swot?.peluang || []" :key="item">
                                        <li class="flex gap-2 items-start"><i class="mdi mdi-star text-blue-400 mt-1 flex-shrink-0"></i> <span x-text="item"></span></li>
                                    </template>
                                </ul>
                            </div>
                            <!-- Threats -->
                            <div class="bg-rose-50/50 rounded-xl p-5 border border-rose-100">
                                <h5 class="font-bold text-rose-800 mb-3 flex items-center gap-2"><i class="mdi mdi-shield-alt text-rose-500"></i> Ancaman (Threats)</h5>
                                <ul class="space-y-2 text-sm text-slate-700">
                                    <template x-for="item in analysisData.analisis_swot?.ancaman || []" :key="item">
                                        <li class="flex gap-2 items-start"><i class="mdi mdi-exclamation-circle text-rose-400 mt-1 flex-shrink-0"></i> <span x-text="item"></span></li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Prioritas Intervensi -->
                    <div>
                        <h4 class="text-slate-800 font-bold text-lg mb-4 flex items-center gap-2 border-b border-slate-100 pb-2">
                            <i class="mdi mdi-bullseye text-slate-400"></i> Prioritas Intervensi Wilayah/Sektor
                        </h4>
                        <div class="space-y-3">
                            <template x-for="(prioritas, index) in analysisData.prioritas_intervensi || []" :key="index">
                                <div class="flex items-start gap-4 p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                                    <div class="flex flex-col items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex-shrink-0">
                                        <span class="text-xs text-slate-400 font-bold uppercase">Skor</span>
                                        <span class="text-lg font-black" :class="prioritas.skor_urgensi >= 8 ? 'text-rose-600' : (prioritas.skor_urgensi >= 6 ? 'text-amber-500' : 'text-emerald-500')" x-text="prioritas.skor_urgensi"></span>
                                    </div>
                                    <div class="flex-1">
                                        <h5 class="font-bold text-slate-800 text-base" x-text="prioritas.area"></h5>
                                        <p class="text-sm text-slate-600 mt-1"><strong class="text-slate-700">Alasan:</strong> <span x-text="prioritas.alasan"></span></p>
                                        <div class="mt-2 text-sm bg-blue-50 text-blue-800 px-3 py-2 rounded-md inline-block">
                                            <i class="mdi mdi-arrow-right text-blue-400 mr-1 text-xs"></i> <span x-text="prioritas.aksi_konkret"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Analisis Domain Tertentu -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kategori & Sektor -->
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-slate-800 font-bold text-base mb-3 border-b border-slate-100 pb-2">Analisis Kategori Usaha</h4>
                                <div class="text-sm text-slate-600 space-y-2">
                                    <p><strong class="text-slate-800">Dominan:</strong> <span x-text="analysisData.analisis_kategori?.kategori_dominan"></span></p>
                                    <p><strong class="text-slate-800">Insight:</strong> <span x-text="analysisData.analisis_kategori?.insight_ekosistem"></span></p>
                                    <p><strong class="text-slate-800">Potensi Kolaborasi:</strong> <span x-text="analysisData.analisis_kategori?.potensi_kolaborasi"></span></p>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-slate-800 font-bold text-base mb-3 border-b border-slate-100 pb-2">Analisis Wilayah</h4>
                                <div class="text-sm text-slate-600 space-y-2">
                                    <p><strong class="text-slate-800">Ketimpangan:</strong> <span x-text="analysisData.analisis_wilayah?.insight_ketimpangan"></span></p>
                                    <p><strong class="text-slate-800">Strategi Pemerataan:</strong> <span x-text="analysisData.analisis_wilayah?.strategi_pemerataan"></span></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pertumbuhan & Verifikasi -->
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-slate-800 font-bold text-base mb-3 border-b border-slate-100 pb-2">Analisis Pertumbuhan</h4>
                                <div class="text-sm text-slate-600 space-y-2">
                                    <p><strong class="text-slate-800">Tren:</strong> <span x-text="analysisData.analisis_pertumbuhan?.tren_umum"></span></p>
                                    <p><strong class="text-slate-800">Proyeksi AI:</strong> <span x-text="analysisData.analisis_pertumbuhan?.proyeksi"></span></p>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-slate-800 font-bold text-base mb-3 border-b border-slate-100 pb-2">Evaluasi Verifikasi</h4>
                                <div class="text-sm text-slate-600 space-y-2">
                                    <p><strong class="text-slate-800">Insight:</strong> <span x-text="analysisData.analisis_verifikasi?.insight"></span></p>
                                    <p><strong class="text-slate-800">Rekomendasi Operasional:</strong> <span x-text="analysisData.analisis_verifikasi?.rekomendasi_operasional"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Usulan Program Kerja Kategori (Dengan Visualisasi) -->
                    <div>
                        <h4 class="text-slate-800 font-bold text-lg mb-4 flex items-center gap-2 border-b border-slate-100 pb-2">
                            <i class="mdi mdi-graduation-cap text-slate-400"></i> Rekomendasi Program & Pelatihan per Kategori
                        </h4>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- List Program -->
                            <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2">
                                <template x-for="(program, index) in analysisData.program_kerja_kategori || []" :key="index">
                                    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold bg-indigo-50 text-indigo-700" x-text="program.kategori"></span>
                                            <div class="text-xs font-bold px-2 py-1 rounded-md" :class="program.skor_kebutuhan_pelatihan > 80 ? 'bg-rose-50 text-rose-600' : 'bg-slate-50 text-slate-600'">
                                                Skor: <span x-text="program.skor_kebutuhan_pelatihan"></span>
                                            </div>
                                        </div>
                                        <h5 class="font-bold text-slate-800 text-sm mb-1" x-text="program.usulan_program"></h5>
                                        <p class="text-xs text-slate-500 leading-relaxed" x-text="program.alasan"></p>
                                    </div>
                                </template>
                            </div>
                            <!-- Radar Chart untuk Prioritas Kategori -->
                            <div class="bg-slate-50 rounded-xl border border-slate-200 flex flex-col items-center justify-center p-4 h-[400px]">
                                <h5 class="font-bold text-slate-700 text-sm mb-2 text-center">Peta Kebutuhan Pelatihan Berdasarkan Kategori</h5>
                                <div class="relative w-full h-full flex items-center justify-center">
                                    <canvas id="kategoriProgramChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rekomendasi Kebijakan Bertahap -->
                    <div>
                        <h4 class="text-slate-800 font-bold text-lg mb-4 flex items-center gap-2 border-b border-slate-100 pb-2">
                            <i class="mdi mdi-tasks text-slate-400"></i> Rekomendasi Kebijakan Bertahap
                        </h4>
                        <div class="space-y-4">
                            <!-- Pendek -->
                            <div class="border-l-4 border-rose-500 pl-4 py-1">
                                <h5 class="font-bold text-slate-800 text-sm mb-2">Jangka Pendek (1-3 Bulan) - Reaktif & Segera</h5>
                                <ul class="space-y-1.5 text-sm text-slate-600 list-disc list-inside marker:text-rose-400">
                                    <template x-for="item in analysisData.rekomendasi_kebijakan?.jangka_pendek_1_3_bulan || []" :key="item">
                                        <li x-text="item"></li>
                                    </template>
                                </ul>
                            </div>
                            <!-- Menengah -->
                            <div class="border-l-4 border-amber-500 pl-4 py-1">
                                <h5 class="font-bold text-slate-800 text-sm mb-2">Jangka Menengah (3-6 Bulan) - Taktis</h5>
                                <ul class="space-y-1.5 text-sm text-slate-600 list-disc list-inside marker:text-amber-400">
                                    <template x-for="item in analysisData.rekomendasi_kebijakan?.jangka_menengah_3_6_bulan || []" :key="item">
                                        <li x-text="item"></li>
                                    </template>
                                </ul>
                            </div>
                            <!-- Panjang -->
                            <div class="border-l-4 border-emerald-500 pl-4 py-1">
                                <h5 class="font-bold text-slate-800 text-sm mb-2">Jangka Panjang (6-12 Bulan) - Transformasional</h5>
                                <ul class="space-y-1.5 text-sm text-slate-600 list-disc list-inside marker:text-emerald-400">
                                    <template x-for="item in analysisData.rekomendasi_kebijakan?.jangka_panjang_6_12_bulan || []" :key="item">
                                        <li x-text="item"></li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan Metodologi -->
                    <div class="mt-8 pt-4 border-t border-slate-100">
                        <p class="text-xs text-slate-400 italic" x-text="analysisData.catatan_metodologi"></p>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Injeksi data chart dari server ke JS
    const chartData = @json($chartData);

    // Setup format standar Chart.js agar terlihat profesional
    Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
    Chart.defaults.color = '#64748b';
    const gridColor = '#f1f5f9';

    document.addEventListener('DOMContentLoaded', function() {
        initCharts();
    });

    function initCharts() {
        // 1. Status Chart (Doughnut)
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: chartData.status_chart.labels,
                datasets: [{
                    data: chartData.status_chart.values,
                    backgroundColor: chartData.status_chart.colors,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 20 } }
                }
            }
        });

        // 2. Growth Chart (Line)
        new Chart(document.getElementById('growthChart'), {
            type: 'line',
            data: {
                labels: chartData.growth_chart.labels,
                datasets: [{
                    label: 'Registrasi Baru',
                    data: chartData.growth_chart.values,
                    borderColor: '#4f46e5', // Indigo 600
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4, // Smooth curve
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { color: gridColor }, beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });

        // 3. Sektor Chart (Horizontal Bar)
        new Chart(document.getElementById('sektorChart'), {
            type: 'bar',
            data: {
                labels: chartData.sektor_chart.labels,
                datasets: [{
                    label: 'Jumlah UMKM',
                    data: chartData.sektor_chart.values,
                    backgroundColor: '#38bdf8', // Light Blue
                    borderRadius: 4,
                    barPercentage: 0.6
                }]
            },
            options: {
                indexAxis: 'y', // Horizontal
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: gridColor }, beginAtZero: true, ticks: { precision: 0 } },
                    y: { grid: { display: false } }
                }
            }
        });

        // 4. Kelurahan Chart (Bar)
        new Chart(document.getElementById('kelurahanChart'), {
            type: 'bar',
            data: {
                labels: chartData.kelurahan_chart.labels,
                datasets: [{
                    label: 'Konsentrasi Wilayah',
                    data: chartData.kelurahan_chart.values,
                    backgroundColor: '#818cf8', // Indigo 400
                    borderRadius: 4,
                    barPercentage: 0.7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { color: gridColor }, beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }

    function dssManager() {
        return {
            loading: false,
            error: false,
            errorMessage: '',
            analysisData: {}, // Menyimpan JSON object dari AI
            hasAnalysis: false,
            interventionFocus: '',
            rawCachedAnalysis: @json($cachedAnalysis),
            aiChartInstance: null,
            activeHistoryId: null,
            history: @json($history),
            isGenerating: @json($isGenerating),
            pollInterval: null,
            
            init() {
                if (this.rawCachedAnalysis && !this.isGenerating) {
                    this.parseAnalysis(this.rawCachedAnalysis);
                }
                
                if (this.isGenerating) {
                    this.loading = true;
                    this.startPolling();
                }
            },

            startPolling() {
                if (this.pollInterval) clearInterval(this.pollInterval);
                this.pollInterval = setInterval(async () => {
                    try {
                        const response = await fetch(`{{ url('dss/status') }}`);
                        const data = await response.json();
                        if (!data.is_generating) {
                            clearInterval(this.pollInterval);
                            if (data.has_analysis) {
                                window.location.reload(); // Muat ulang otomatis untuk menampilkan hasil
                            } else {
                                this.loading = false;
                                this.error = true;
                                this.errorMessage = "Proses analisis gagal atau terhenti di latar belakang.";
                            }
                        }
                    } catch (e) {
                        console.error("Polling error", e);
                    }
                }, 5000); // Cek tiap 5 detik
            },

            parseAnalysis(rawString) {
                if (!rawString) return;
                
                try {
                    // Extract JSON if Ollama returned some surrounding text
                    let jsonStr = rawString;
                    const jsonStart = rawString.indexOf('{');
                    const jsonEnd = rawString.lastIndexOf('}');
                    
                    if (jsonStart !== -1 && jsonEnd !== -1) {
                        jsonStr = rawString.substring(jsonStart, jsonEnd + 1);
                    }
                    
                    this.analysisData = JSON.parse(jsonStr);
                    this.hasAnalysis = true;

                    // Set active history ID and prepend if not exists
                    if (this.analysisData.id) {
                        this.activeHistoryId = this.analysisData.id;
                        
                        if (!this.history.some(h => h.id === this.analysisData.id)) {
                            this.history.unshift({
                                id: this.analysisData.id,
                                creator: this.analysisData.creator || 'Sistem',
                                role: '{{ auth()->user()->hasRole("super_admin") ? "Super Admin" : "Admin Kecamatan" }}',
                                created_at: this.analysisData.created_at || 'Baru'
                            });
                        }
                    }

                    // Render AI Chart next tick after DOM updates
                    this.$nextTick(() => {
                        this.renderProgramChart();
                    });

                } catch (e) {
                    console.error("Gagal memparsing JSON dari AI:", e);
                    console.log("Raw output:", rawString);
                    this.error = true;
                    this.errorMessage = "Format respons AI tidak valid. Harap coba analisis ulang.";
                    this.hasAnalysis = false;
                }
            },

            renderProgramChart() {
                const canvas = document.getElementById('kategoriProgramChart');
                if (!canvas) return;

                const programs = this.analysisData.program_kerja_kategori || [];
                if (programs.length === 0) return;

                const labels = programs.map(p => p.kategori);
                const data = programs.map(p => p.skor_kebutuhan_pelatihan);

                if (this.aiChartInstance) {
                    this.aiChartInstance.destroy();
                }

                this.aiChartInstance = new Chart(canvas, {
                    type: 'radar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Skor Urgensi Pelatihan',
                            data: data,
                            backgroundColor: 'rgba(79, 70, 229, 0.2)',
                            borderColor: '#4f46e5',
                            pointBackgroundColor: '#4f46e5',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: '#4f46e5'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            r: {
                                angleLines: { color: gridColor },
                                grid: { color: gridColor },
                                pointLabels: { font: { family: "'Inter', sans-serif", size: 10 }, color: '#64748b' },
                                ticks: { display: false, min: 0, max: 100 }
                            }
                        },
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            },

            async generateAnalysis(force = false) {
                this.loading = true;
                this.error = false;
                this.errorMessage = '';
                this.hasAnalysis = false;
                let keepLoading = false;

                try {
                    const response = await fetch('{{ route("dss.generate") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ force: force ? 'true' : 'false', intervention_focus: this.interventionFocus })
                    });

                    const data = await response.json();

                    if (data.success && data.processing) {
                        keepLoading = true;
                        this.isGenerating = true;
                        this.loading = true;
                        this.startPolling();
                    } else if (data.success) {
                        this.parseAnalysis(data.analysis);
                        
                        const indicator = document.getElementById('cache-time-indicator');
                        if (indicator && data.cached_at) {
                            indicator.innerHTML = `<i class="mdi mdi-clock text-slate-400 mr-1"></i> Terakhir dianalisis: <span class="font-semibold text-slate-700">${data.cached_at}</span>`;
                        }
                    } else {
                        throw new Error(data.message || 'Gagal memuat analisis dari AI.');
                    }
                } catch (err) {
                    this.error = true;
                    this.errorMessage = err.message || 'Terjadi kesalahan jaringan saat menghubungi AI.';
                } finally {
                    if (!keepLoading) {
                        this.loading = false;
                    }
                }
            },

            async selectHistory(id) {
                if (this.loading) return;
                this.loading = true;
                this.error = false;
                this.errorMessage = '';
                this.activeHistoryId = id;

                try {
                    const response = await fetch(`{{ url('dss/history') }}/${id}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.parseAnalysis(data.analysis);
                        
                        const indicator = document.getElementById('cache-time-indicator');
                        if (indicator && data.cached_at) {
                            indicator.innerHTML = `<i class="mdi mdi-clock text-slate-400 mr-1"></i> Riwayat Analisis (${data.creator}): <span class="font-semibold text-slate-700">${data.cached_at}</span>`;
                        }
                    } else {
                        throw new Error(data.message || 'Gagal memuat riwayat analisis.');
                    }
                } catch (err) {
                    this.error = true;
                    this.errorMessage = err.message || 'Terjadi kesalahan saat memuat riwayat analisis.';
                    this.hasAnalysis = false;
                } finally {
                    this.loading = false;
                }
            },

            async confirmDeleteHistory(id) {
                if (!confirm('Apakah Anda yakin ingin menghapus riwayat analisis ini?')) {
                    return;
                }

                try {
                    const response = await fetch(`{{ url('dss/history') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Remove from local history array
                        this.history = this.history.filter(h => h.id !== id);
                        
                        // If the deleted history was currently active/shown, clear the report view
                        if (this.activeHistoryId === id) {
                            this.activeHistoryId = null;
                            this.hasAnalysis = false;
                            this.analysisData = {};
                            if (this.aiChartInstance) {
                                this.aiChartInstance.destroy();
                                this.aiChartInstance = null;
                            }
                            
                            const indicator = document.getElementById('cache-time-indicator');
                            if (indicator) {
                                indicator.innerHTML = '<i class="mdi mdi-clock text-slate-400 mr-1"></i> Belum ada analisis yang disimpan.';
                            }
                        }
                        
                        alert(data.message || 'Riwayat berhasil dihapus.');
                    } else {
                        throw new Error(data.message || 'Gagal menghapus riwayat.');
                    }
                } catch (err) {
                    alert(err.message || 'Terjadi kesalahan saat menghapus riwayat.');
                }
            }
        }
    }
</script>

<style>
    /* Premium styling & Print rules */
    @media print {
        body * { visibility: hidden; }
        .dss-report, .dss-report * { visibility: visible; }
        .dss-report {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 2rem;
            background: white !important;
        }
        /* Hide backgrounds/colors on print for cleaner output */
        .bg-indigo-50\/50, .bg-emerald-50\/50, .bg-amber-50\/50, .bg-rose-50\/50, .bg-blue-50\/50, .bg-slate-50 {
            background-color: transparent !important;
            border: 1px solid #e2e8f0 !important;
        }
    }
</style>
@endpush
