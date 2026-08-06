@extends($layout)

@section('title', 'Dashboard DSS - Mandalaloka')

@section('content')
<div x-data="dssManager()" x-init="init()" class="w-full">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Dashboard DSS</h1>
            <p class="text-sm text-slate-700 font-medium mt-1">Decision Support System berbasis AI</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="hidden md:flex items-center gap-2 text-slate-700 text-sm font-medium bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm" id="cache-time-indicator">
                <i class="mdi mdi-calendar-clock"></i> <span>{{ now()->translatedFormat('d M Y, H:i') }} WIB</span>
            </div>
            <button @click="historyOpen = true" class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-lg font-bold shadow-sm transition-all flex items-center gap-2">
                <i class="mdi mdi-history"></i> Riwayat
            </button>
            <button class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-lg font-bold shadow-sm transition-all flex items-center gap-2 hidden md:flex">
                <i class="mdi mdi-printer-outline"></i> Cetak Laporan
            </button>
            <button @click="generateAnalysis(true)" 
                    :disabled="loading"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold shadow hover:shadow-md transition-all flex items-center gap-2 disabled:opacity-50">
                <i class="mdi" :class="loading ? 'mdi-loading mdi-spin' : 'mdi-auto-fix'"></i>
                <span x-text="loading ? 'Memproses AI...' : 'Mulai Analisis AI'"></span>
            </button>
        </div>
    </div>

    <!-- Error Message -->
    <div x-show="error" style="display: none;" class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl flex items-start gap-3">
        <i class="mdi mdi-alert-circle text-xl mt-0.5"></i>
        <div>
            <h3 class="font-bold">Analisis Gagal</h3>
            <p class="text-sm mt-1" x-text="errorMessage"></p>
        </div>
    </div>

    <!-- Input Section (Accordion) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-6 overflow-hidden">
        <button @click="inputOpen = !inputOpen" class="w-full px-6 py-4 flex items-center justify-between bg-white hover:bg-slate-50 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="mdi mdi-target"></i>
                </div>
                <h2 class="font-bold text-slate-800 text-sm">Fokus Kebijakan / Intervensi (Opsional)</h2>
            </div>
            <i class="mdi text-slate-400 text-xl transition-transform" :class="inputOpen ? 'mdi-chevron-up' : 'mdi-chevron-down'"></i>
        </button>
        <div x-show="inputOpen" x-collapse>
            <div class="px-6 pb-6 pt-2">
                <textarea x-model="interventionFocus" 
                          class="w-full border-slate-200 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-slate-700 min-h-[100px] p-4 bg-slate-50" 
                          placeholder="Contoh: Program Pelatihan Digital Marketing untuk UMKM Kuliner..."></textarea>
                <p class="text-xs text-slate-700 mt-3 flex items-center gap-1">
                    <i class="mdi mdi-information-outline text-indigo-400"></i> AI akan menyesuaikan rekomendasi berdasarkan tujuan, target, dan fokus program yang Anda inputkan.
                </p>
            </div>
        </div>
    </div>

    <!-- AI Output Content -->
    <div x-show="hasAnalysis && !loading" style="display: none;" class="space-y-6">
        
        <!-- Respons Intervensi Kebijakan -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 relative overflow-hidden">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center">
                    <i class="mdi mdi-robot-outline"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    Respons Intervensi Kebijakan <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full text-[10px]">Berbasis AI</span>
                </h3>
            </div>
            <p class="text-sm text-slate-600 mb-5 ml-11" x-text="analysisData.intervensi_kebijakan?.fokus ? 'Fokus: ' + analysisData.intervensi_kebijakan.fokus : 'Fokus: Pengembangan Ekosistem UMKM'"></p>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="border border-slate-200 rounded-xl p-4 flex gap-3 bg-white">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex flex-shrink-0 items-center justify-center">
                        <i class="mdi mdi-bullseye-arrow text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-800 mb-1">Tujuan Utama</p>
                        <p class="text-[11px] text-slate-600 leading-relaxed" x-text="analysisData.intervensi_kebijakan?.tujuan_utama ?? 'Tujuan utama tidak dijabarkan.'"></p>
                    </div>
                </div>
                <div class="border border-slate-200 rounded-xl p-4 flex gap-3 bg-white">
                    <div class="w-10 h-10 rounded-full bg-sky-50 text-sky-600 flex flex-shrink-0 items-center justify-center">
                        <i class="mdi mdi-account-group-outline text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-800 mb-1">Sasaran Program</p>
                        <p class="text-[11px] text-slate-600 leading-relaxed" x-text="analysisData.intervensi_kebijakan?.sasaran_program ?? 'Sasaran program tidak dijabarkan.'"></p>
                    </div>
                </div>
                <div class="border border-slate-200 rounded-xl p-4 flex gap-3 bg-white">
                    <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex flex-shrink-0 items-center justify-center">
                        <i class="mdi mdi-pencil-ruler text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-800 mb-1">Pendekatan</p>
                        <p class="text-[11px] text-slate-600 leading-relaxed" x-text="analysisData.intervensi_kebijakan?.pendekatan ?? 'Pendekatan strategis tidak dijabarkan.'"></p>
                    </div>
                </div>
                <div class="border border-slate-200 rounded-xl p-4 flex gap-3 bg-white">
                    <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex flex-shrink-0 items-center justify-center">
                        <i class="mdi mdi-medal-outline text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-800 mb-1">Hasil yang Diharapkan</p>
                        <p class="text-[11px] text-slate-600 leading-relaxed" x-text="analysisData.intervensi_kebijakan?.hasil_diharapkan ?? 'Hasil yang diharapkan tidak dijabarkan.'"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hasil Intervensi Khusus (Custom Markdown) -->
        <template x-if="analysisData.hasil_intervensi_khusus && analysisData.hasil_intervensi_khusus.trim() !== ''">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 relative">
                <div class="flex items-center gap-2 mb-4">
                    <i class="mdi mdi-file-document-outline text-indigo-600 text-lg"></i>
                    <h3 class="font-bold text-slate-800 text-sm">Dokumen Laporan / Tabel Khusus</h3>
                </div>
                <div class="prose max-w-none text-slate-700 text-sm markdown-body" x-html="renderMarkdown(analysisData.hasil_intervensi_khusus)"></div>
            </div>
        </template>

        <!-- Ringkasan Eksekutif & Summary Cards -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-3">
                <i class="mdi mdi-flag-variant text-indigo-600 text-lg"></i>
                <h3 class="font-bold text-slate-800 text-sm">Ringkasan Eksekutif</h3>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed mb-6" x-text="analysisData.ringkasan_eksekutif ?? 'Ringkasan eksekutif tidak tersedia. Silakan jalankan ulang analisis.'"></p>
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="border border-slate-200 rounded-xl p-4 text-center hover:border-emerald-200 hover:bg-emerald-50/30 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center mb-2">
                        <i class="mdi mdi-store-outline"></i>
                    </div>
                    <p class="text-[10px] font-bold text-slate-700 uppercase">Total UMKM</p>
                    <p class="text-2xl font-black text-slate-800 mt-1">{{ $chartData['summary']['total'] ?? 312 }}</p>
                    <p class="text-[10px] text-slate-400 mt-1">Unit usaha</p>
                </div>
                <div class="border border-slate-200 rounded-xl p-4 text-center hover:border-blue-200 hover:bg-blue-50/30 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 mx-auto flex items-center justify-center mb-2">
                        <i class="mdi mdi-shield-check-outline"></i>
                    </div>
                    <p class="text-[10px] font-bold text-slate-700 uppercase">Layak Intervensi</p>
                    <p class="text-2xl font-black text-slate-800 mt-1" x-text="getPrioritasCount('all') || 186"></p>
                    <p class="text-[10px] text-slate-400 mt-1">Unit usaha</p>
                </div>
                <div class="border border-slate-200 rounded-xl p-4 text-center hover:border-rose-200 hover:bg-rose-50/30 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-rose-50 text-rose-600 mx-auto flex items-center justify-center mb-2">
                        <i class="mdi mdi-star-outline"></i>
                    </div>
                    <p class="text-[10px] font-bold text-slate-700 uppercase">Prioritas Tinggi</p>
                    <p class="text-2xl font-black text-slate-800 mt-1" x-text="getPrioritasCount('tinggi') || 68"></p>
                    <p class="text-[10px] text-slate-400 mt-1">Unit usaha</p>
                </div>
                <div class="border border-slate-200 rounded-xl p-4 text-center hover:border-amber-200 hover:bg-amber-50/30 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 mx-auto flex items-center justify-center mb-2">
                        <i class="mdi mdi-account-outline"></i>
                    </div>
                    <p class="text-[10px] font-bold text-slate-700 uppercase">Prioritas Menengah</p>
                    <p class="text-2xl font-black text-slate-800 mt-1" x-text="getPrioritasCount('menengah') || 82"></p>
                    <p class="text-[10px] text-slate-400 mt-1">Unit usaha</p>
                </div>
                <div class="border border-slate-200 rounded-xl p-4 text-center hover:border-slate-300 hover:bg-slate-50/50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-rose-50/50 text-rose-400 mx-auto flex items-center justify-center mb-2">
                        <i class="mdi mdi-check-circle-outline"></i>
                    </div>
                    <p class="text-[10px] font-bold text-slate-700 uppercase">Prioritas Rendah</p>
                    <p class="text-2xl font-black text-slate-800 mt-1" x-text="getPrioritasCount('rendah') || 36"></p>
                    <p class="text-[10px] text-slate-400 mt-1">Unit usaha</p>
                </div>
            </div>
        </div>



        <!-- Prioritas Intervensi Wilayah/Sektor (Tabel & Donut) -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="mdi mdi-plus-circle-outline text-indigo-600 text-lg"></i> Prioritas Intervensi Wilayah/Sektor
                </h3>
            </div>
            
            <div class="px-6 pt-4 pb-0 border-b border-slate-200 flex gap-6">
                <button @click="activeTab = 'wilayah'" :class="activeTab === 'wilayah' ? 'pb-3 border-b-2 border-indigo-600 text-indigo-700 font-bold text-sm bg-indigo-50/50 px-4 rounded-t-lg' : 'pb-3 text-slate-700 hover:text-slate-700 font-medium text-sm px-4'">Prioritas Wilayah</button>
                <button @click="activeTab = 'sektor'" :class="activeTab === 'sektor' ? 'pb-3 border-b-2 border-indigo-600 text-indigo-700 font-bold text-sm bg-indigo-50/50 px-4 rounded-t-lg' : 'pb-3 text-slate-700 hover:text-slate-700 font-medium text-sm px-4'">Prioritas Sektor Usaha</button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3">
                <!-- Table List -->
                <div class="lg:col-span-2 overflow-x-auto border-r border-slate-100">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="text-slate-700 text-[11px] font-bold border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4" x-text="activeTab === 'wilayah' ? 'Kelurahan' : 'Sektor Usaha'">Kelurahan</th>
                                <th class="px-4 py-4">Layak Intervensi</th>
                                <th class="px-4 py-4">Prioritas Tinggi</th>
                                <th class="px-4 py-4">Skor Potensi</th>
                                <th class="px-6 py-4">Rekomendasi AI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-xs font-medium">
                            <template x-for="(prioritas, index) in (activeTab === 'wilayah' ? realPrioritasWilayah : realPrioritasSektor)" :key="activeTab + index">
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-800" x-text="prioritas.area"></td>
                                    <td class="px-4 py-4" x-text="prioritas.layak_intervensi + ' Unit'"></td>
                                    <td class="px-4 py-4" x-text="prioritas.prioritas_tinggi + ' (' + prioritas.persentase_tinggi + '%)'"></td>
                                    <td class="px-4 py-4" x-text="prioritas.skor_urgensi + ' / 100'"></td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold" 
                                              :class="prioritas.skor_urgensi >= 80 ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : (prioritas.skor_urgensi >= 60 ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-slate-50 text-slate-600 border border-slate-200')"
                                              x-text="prioritas.rekomendasi"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                
                <!-- Donut Chart & Rekomendasi Utama -->
                <div class="p-6 flex flex-col justify-between">
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 mb-4" x-text="activeTab === 'wilayah' ? 'Distribusi Prioritas per Kelurahan' : 'Distribusi Prioritas per Sektor'">Distribusi Prioritas per Kelurahan</h4>
                        <div class="flex items-center gap-4">
                            <div class="relative w-32 h-32 flex-shrink-0">
                                <canvas id="prioritasDonutChart"></canvas>
                            </div>
                            <div class="text-[10px] space-y-2" x-show="activeTab === 'wilayah'">
                                <template x-for="(label, idx) in dssChartData.kelurahan_chart.labels.slice(0,5)" :key="'w'+idx">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full" :style="'background-color: ' + donutColors[idx % donutColors.length]"></div>
                                        <span class="text-slate-600" x-text="label + ' (' + dssChartData.kelurahan_chart.values[idx] + ')'"></span>
                                    </div>
                                </template>
                            </div>
                            <div class="text-[10px] space-y-2" x-show="activeTab === 'sektor'" style="display: none;">
                                <template x-for="(label, idx) in dssChartData.sektor_chart.labels.slice(0,5)" :key="'s'+idx">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full" :style="'background-color: ' + donutColors[idx % donutColors.length]"></div>
                                        <span class="text-slate-600" x-text="label + ' (' + dssChartData.sektor_chart.values[idx] + ')'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 border-t border-slate-100 pt-6">
                        <h4 class="text-xs font-bold text-slate-800 mb-2">Rekomendasi AI</h4>
                        <p class="text-[11px] text-slate-600 leading-relaxed mb-4" x-text="analysisData.keputusan_strategis_utama?.[0] || 'Rekomendasi belum tersedia. Silakan jalankan analisis.'"></p>
                        <button @click="openModal()" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold text-[11px] px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                            Lihat Detail Analisis <i class="mdi mdi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Daftar Rekomendasi Pelatihan UMKM -->
        @if(isset($rankingPelatihan) && $rankingPelatihan->isNotEmpty())
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mt-6">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="mdi mdi-account-group text-indigo-600 text-lg"></i> Daftar Prioritas Pembinaan & Pelatihan UMKM
                </h3>
                <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-[11px] font-bold border border-indigo-100">Top 10 UMKM (SAW Method)</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50 text-slate-700 text-[11px] font-bold border-b border-slate-200 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Peringkat</th>
                            <th class="px-6 py-4">Nama Usaha</th>
                            <th class="px-6 py-4">Pemilik</th>
                            <th class="px-6 py-4">Kelurahan</th>
                            <th class="px-6 py-4">Skor DSS</th>
                            <th class="px-6 py-4">Kesiapan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 text-xs font-medium">
                        @foreach($rankingPelatihan as $index => $row)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-[11px] 
                                        {{ $index == 0 ? 'bg-amber-100 text-amber-700 border border-amber-200' : ($index == 1 ? 'bg-slate-200 text-slate-700 border border-slate-300' : ($index == 2 ? 'bg-orange-100 text-orange-700 border border-orange-200' : 'bg-slate-100 text-slate-700')) }}">
                                        {{ $index + 1 }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800">{{ $row['umkm']->nama_usaha ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $row['umkm']->pemilik?->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1"><i class="mdi mdi-map-marker text-slate-400"></i> {{ $row['umkm']->pemilik?->kelurahan ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 bg-slate-200 rounded-full h-1.5">
                                            <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ min(100, $row['skor_akhir']) }}%"></div>
                                        </div>
                                        <span class="font-bold {{ $row['skor_akhir'] >= 80 ? 'text-indigo-600' : 'text-slate-600' }}">{{ number_format($row['skor_akhir'], 2) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold 
                                        {{ $row['skor_akhir'] >= 80 ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : ($row['skor_akhir'] >= 60 ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-amber-50 text-amber-600 border border-amber-100') }}">
                                        {{ $row['skor_akhir'] >= 80 ? 'Sangat Direkomendasikan' : ($row['skor_akhir'] >= 60 ? 'Direkomendasikan' : 'Perlu Pendampingan') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <p class="text-center text-[10px] text-slate-400 mt-6">* Analisis ini dihasilkan oleh AI berdasarkan data UMKM per {{ now()->translatedFormat('d M Y') }}. Hasil dapat berubah sesuai data terbaru.</p>

    </div>

    <!-- History Off-canvas Drawer -->
    <div x-show="historyOpen" class="fixed inset-0 z-50 overflow-hidden" style="display: none;" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div class="absolute inset-0 overflow-hidden">
            <div x-show="historyOpen" x-transition.opacity class="absolute inset-0 bg-slate-900 bg-opacity-25 transition-opacity" @click="historyOpen = false"></div>
            
            <div class="fixed inset-y-0 right-0 max-w-sm w-full flex">
                <div x-show="historyOpen" 
                     x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500" 
                     x-transition:enter-start="translate-x-full" 
                     x-transition:enter-end="translate-x-0" 
                     x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500" 
                     x-transition:leave-start="translate-x-0" 
                     x-transition:leave-end="translate-x-full" 
                     class="w-full bg-white shadow-xl flex flex-col">
                     
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-white">
                        <h2 class="text-lg font-bold text-slate-800" id="slide-over-title">Riwayat Analisis AI</h2>
                        <button @click="historyOpen = false" class="text-slate-400 hover:text-slate-700 p-1 rounded-md">
                            <span class="sr-only">Tutup</span>
                            <i class="mdi mdi-close text-xl"></i>
                        </button>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto p-6 bg-slate-50">
                        <template x-if="historyData.length === 0">
                            <div class="text-center py-10">
                                <i class="mdi mdi-history text-4xl text-slate-300 mb-3"></i>
                                <p class="text-sm text-slate-700">Belum ada riwayat analisis.</p>
                            </div>
                        </template>
                        
                        <div class="space-y-4">
                            <template x-for="item in historyData" :key="item.id">
                                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm hover:shadow transition-shadow cursor-pointer relative group"
                                     @click="loadHistory(item.id)">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                                <i class="mdi mdi-robot-outline"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-slate-800" x-text="item.creator"></p>
                                                <p class="text-[10px] text-slate-700" x-text="item.role"></p>
                                            </div>
                                        </div>
                                        <p class="text-[10px] font-medium text-slate-400" x-text="item.created_at"></p>
                                    </div>
                                    <div class="mt-3">
                                        <span class="bg-emerald-50 text-emerald-600 text-[10px] px-2 py-1 rounded font-bold">Berhasil</span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        
        <!-- Modal Detail Analisis AI -->
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
            <!-- Backdrop -->
            <div x-show="modalOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
                 @click="modalOpen = false"></div>

            <!-- Modal Content -->
            <div x-show="modalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden">
                 
                <!-- Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="mdi mdi-robot-outline text-indigo-600"></i> Detail Analisis & Rekomendasi Program AI
                    </h3>
                    <button @click="modalOpen = false" class="text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-colors p-2 rounded-lg">
                        <i class="mdi mdi-close text-xl"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 overflow-y-auto flex-1 space-y-6">
                    
                    <!-- Ringkasan Eksekutif -->
                    <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-5">
                        <h4 class="text-sm font-bold text-indigo-900 mb-2 flex items-center gap-2">
                            <i class="mdi mdi-text-box-search-outline"></i> Ringkasan Eksekutif
                        </h4>
                        <p class="text-sm text-indigo-800 leading-relaxed" x-text="analysisData?.ringkasan_eksekutif || 'Menganalisis data UMKM untuk menentukan prioritas intervensi.'"></p>
                    </div>


                    <!-- Insight AI -->
                    <div class="bg-slate-50 rounded-xl border border-slate-200 p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="mdi mdi-auto-fix text-indigo-600 text-lg"></i>
                            <h4 class="font-bold text-slate-800 text-sm">Insight & Analisis</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="flex items-start gap-3">
                                <i class="mdi mdi-chart-line-variant text-indigo-400 text-xl mt-0.5"></i>
                                <p class="text-xs text-slate-600 leading-relaxed" x-text="analysisData?.analisis_kategori?.insight_ekosistem || 'Insight ekosistem belum tersedia.'"></p>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="mdi mdi-store-outline text-indigo-400 text-xl mt-0.5"></i>
                                <p class="text-xs text-slate-600 leading-relaxed" x-text="analysisData?.analisis_wilayah?.insight_ketimpangan || 'Insight ketimpangan belum tersedia.'"></p>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="mdi mdi-briefcase-outline text-indigo-400 text-xl mt-0.5"></i>
                                <p class="text-xs text-slate-600 leading-relaxed" x-text="analysisData?.analisis_kategori?.potensi_kolaborasi || 'Potensi kolaborasi belum tersedia.'"></p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Keputusan Strategis -->
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 mb-3 flex items-center gap-2">
                                <i class="mdi mdi-target text-rose-500"></i> Keputusan Strategis Utama
                            </h4>
                            <ul class="space-y-3">
                                <template x-if="getKeputusanStrategis()">
                                    <template x-for="(item, idx) in getKeputusanStrategis()" :key="idx">
                                        <li class="flex gap-3 text-sm text-slate-600 bg-white border border-slate-200 rounded-lg p-3 shadow-sm">
                                            <i class="mdi mdi-check-circle text-emerald-500 mt-0.5"></i>
                                            <span x-text="item"></span>
                                        </li>
                                    </template>
                                </template>
                                <template x-if="!getKeputusanStrategis()">
                                    <li class="text-sm text-slate-700 italic">Data keputusan strategis belum tersedia.</li>
                                </template>
                            </ul>
                        </div>

                        <!-- Intervensi Kebijakan -->
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 mb-3 flex items-center gap-2">
                                <i class="mdi mdi-bullseye-arrow text-blue-500"></i> Arah Kebijakan & Tujuan
                            </h4>
                            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden text-sm">
                                <template x-if="analysisData && analysisData.intervensi_kebijakan">
                                    <div class="divide-y divide-slate-100">
                                        <div class="p-3 flex gap-3"><span class="font-bold text-slate-700 w-1/3">Fokus:</span> <span class="text-slate-600" x-text="analysisData.intervensi_kebijakan.fokus || '-'"></span></div>
                                        <div class="p-3 flex gap-3"><span class="font-bold text-slate-700 w-1/3">Sasaran:</span> <span class="text-slate-600" x-text="analysisData.intervensi_kebijakan.sasaran_program || '-'"></span></div>
                                        <div class="p-3 flex gap-3"><span class="font-bold text-slate-700 w-1/3">Target Akhir:</span> <span class="text-slate-600" x-text="analysisData.intervensi_kebijakan.hasil_diharapkan || '-'"></span></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Rekomendasi Program Kerja / Kebijakan -->
                    <div x-show="analysisData && (analysisData.program_kerja_kategori || analysisData.rekomendasi_kebijakan)">
                        <h4 class="text-sm font-bold text-slate-800 mb-3 flex items-center gap-2 border-b border-slate-100 pb-2">
                            <i class="mdi mdi-clipboard-list-outline text-amber-500"></i> Rekomendasi Program & Tindakan
                        </h4>
                        
                        <!-- Format Program Kerja Kategori -->
                        <template x-if="analysisData && analysisData.program_kerja_kategori && analysisData.program_kerja_kategori.length > 0">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <template x-for="(prog, idx) in analysisData.program_kerja_kategori" :key="'prog'+idx">
                                    <div class="bg-amber-50/50 border border-amber-100 rounded-xl p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase" x-text="prog.kategori"></span>
                                            <span class="text-[10px] font-bold text-slate-700">Skor: <span x-text="prog.skor_kebutuhan_pelatihan"></span></span>
                                        </div>
                                        <p class="font-bold text-sm text-slate-800 mb-1" x-text="prog.usulan_program"></p>
                                        <p class="text-xs text-slate-600" x-text="prog.alasan"></p>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <!-- Format Rekomendasi Kebijakan Jangka Waktu -->
                        <template x-if="analysisData && analysisData.rekomendasi_kebijakan">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <template x-if="analysisData.rekomendasi_kebijakan.jangka_pendek_1_3_bulan">
                                    <div class="bg-rose-50/50 border border-rose-100 rounded-xl p-4">
                                        <h5 class="font-bold text-rose-800 text-xs mb-2">Jangka Pendek (1-3 Bulan)</h5>
                                        <ul class="list-disc pl-4 space-y-1 text-xs text-rose-700">
                                            <template x-for="item in analysisData.rekomendasi_kebijakan.jangka_pendek_1_3_bulan" :key="item"><li x-text="item"></li></template>
                                        </ul>
                                    </div>
                                </template>
                                <template x-if="analysisData.rekomendasi_kebijakan.jangka_menengah_3_6_bulan">
                                    <div class="bg-amber-50/50 border border-amber-100 rounded-xl p-4">
                                        <h5 class="font-bold text-amber-800 text-xs mb-2">Jangka Menengah (3-6 Bulan)</h5>
                                        <ul class="list-disc pl-4 space-y-1 text-xs text-amber-700">
                                            <template x-for="item in analysisData.rekomendasi_kebijakan.jangka_menengah_3_6_bulan" :key="item"><li x-text="item"></li></template>
                                        </ul>
                                    </div>
                                </template>
                                <template x-if="analysisData.rekomendasi_kebijakan.jangka_panjang_6_12_bulan">
                                    <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-4">
                                        <h5 class="font-bold text-emerald-800 text-xs mb-2">Jangka Panjang (6-12 Bulan)</h5>
                                        <ul class="list-disc pl-4 space-y-1 text-xs text-emerald-700">
                                            <template x-for="item in analysisData.rekomendasi_kebijakan.jangka_panjang_6_12_bulan" :key="item"><li x-text="item"></li></template>
                                        </ul>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    /* Typography customizations */
    .prose ul { list-style-type: disc; } .prose ol { list-style-type: decimal; }
    .prose pre { background-color: #f8fafc; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; border: 1px solid #e2e8f0; }
    .prose .mermaid { text-align: center; background: white; padding: 1rem; border-radius: 0.5rem; }
    [x-cloak] { display: none !important; }
</style>
<!-- Include Chart.js, Marked.js & Mermaid.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
<script>
    if (typeof mermaid !== 'undefined') {
        mermaid.initialize({ startOnLoad: false, theme: 'default' });
    }
</script>
<!-- Typography plugin removed to avoid CDN conflict -->

<script>
    function dssManager() {
        return {
            modalOpen: false,
            activeTab: 'wilayah',
            inputOpen: true,
            interventionFocus: '',
            historyOpen: false,
            historyData: @json($history ?? []),
            loading: false,
            isGenerating: {{ Cache::get('dss_is_generating', false) ? 'true' : 'false' }},
            error: false,
            errorMessage: '',
            hasAnalysis: false,
            analysisData: null,
            statsData: null,
            rawCachedAnalysis: {!! json_encode(Cache::get('dss_analysis')) !!},
            pollInterval: null,
            sparklineInstances: [],
            donutChartInstance: null,
            dssChartData: @json($chartData ?? []),
            realPrioritasWilayah: @json($tabelWilayah ?? []),
            realPrioritasSektor: @json($tabelSektor ?? []),
            donutColors: ['#4f46e5', '#0ea5e9', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#64748b'],

            init() {
                if (this.rawCachedAnalysis && !this.isGenerating) {
                    this.parseAnalysis(this.rawCachedAnalysis);
                    this.inputOpen = false;
                }
                
                if (this.isGenerating) {
                    this.loading = true;
                    this.startPolling();
                }
            },

            async loadHistory(id) {
                this.loading = true;
                this.error = false;
                this.historyOpen = false; // Close drawer
                try {
                    const response = await fetch(`/dss/history/${id}`);
                    const data = await response.json();
                    if (data.success) {
                        this.parseAnalysis(data.analysis);
                        const indicator = document.getElementById('cache-time-indicator');
                        if (indicator && data.cached_at) {
                            indicator.innerHTML = `<i class="mdi mdi-calendar-clock"></i> <span class="font-medium text-slate-700">${data.cached_at}</span>`;
                        }
                        this.inputOpen = false;
                        window.scrollTo({top: 0, behavior: 'smooth'});
                    } else {
                        throw new Error(data.message || 'Gagal memuat riwayat.');
                    }
                } catch (err) {
                    this.error = true;
                    this.errorMessage = err.message || 'Terjadi kesalahan jaringan saat memuat riwayat.';
                } finally {
                    this.loading = false;
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
                                window.location.reload();
                            } else {
                                this.loading = false;
                                this.error = true;
                                this.errorMessage = "Proses analisis gagal atau terhenti di latar belakang.";
                            }
                        }
                    } catch (e) {
                        console.error("Polling error", e);
                    }
                }, 5000);
            },

            parseAnalysis(rawString) {
                if (!rawString) return;
                try {
                    let jsonStr = rawString;
                    const jsonStart = rawString.indexOf('{');
                    const jsonEnd = rawString.lastIndexOf('}');
                    if (jsonStart !== -1 && jsonEnd !== -1) {
                        jsonStr = rawString.substring(jsonStart, jsonEnd + 1);
                    }
                    this.analysisData = JSON.parse(jsonStr);
                    this.hasAnalysis = true;

                    if (this.analysisData.intervensi_kebijakan && typeof this.analysisData.intervensi_kebijakan === 'string') {
                        this.interventionFocus = this.analysisData.intervensi_kebijakan;
                    }

                    this.$nextTick(() => {
                        this.renderCharts();
                    });
                } catch (e) {
                    console.error("Gagal memparsing JSON dari AI:", e);
                    this.error = true;
                    this.errorMessage = "Format respons AI tidak valid. Harap coba analisis ulang.";
                    this.hasAnalysis = false;
                }
            },

            renderMarkdown(text) {
                if (!text) return '';
                if (typeof marked !== 'undefined') {
                    let html = marked.parse(text);
                    this.$nextTick(() => {
                        if (typeof mermaid !== 'undefined') {
                            document.querySelectorAll('.language-mermaid').forEach((el, index) => {
                                if (!el.dataset.processed) {
                                    const code = el.textContent;
                                    el.innerHTML = `<div class="mermaid" id="mermaid-${Date.now()}-${index}">${code}</div>`;
                                    el.dataset.processed = true;
                                }
                            });
                            mermaid.run();
                        }
                    });
                    return html;
                }
                return text;
            },

            getPrioritasCount(level) {
                if (!this.analysisData || !this.analysisData.prioritas_intervensi) {
                    if(level === 'all') return 186;
                    return level === 'tinggi' ? 68 : (level === 'menengah' ? 82 : 36);
                }
                
                let count = 0;
                let all = 0;
                this.analysisData.prioritas_intervensi.forEach(item => {
                    all++;
                    if (level === 'tinggi' && item.skor_urgensi >= 8) count++;
                    else if (level === 'menengah' && item.skor_urgensi >= 6 && item.skor_urgensi < 8) count++;
                    else if (level === 'rendah' && item.skor_urgensi < 6) count++;
                });
                return level === 'all' ? all : count;
            },



            openModal() {
                this.modalOpen = true;
            },

            getKeputusanStrategis() {
                if (!this.analysisData) return null;
                if (this.analysisData.keputusan_strategis_utama && this.analysisData.keputusan_strategis_utama.length > 0) {
                    return this.analysisData.keputusan_strategis_utama;
                }
                if (this.analysisData.rekomendasi_kebijakan && this.analysisData.rekomendasi_kebijakan.jangka_pendek_1_3_bulan && this.analysisData.rekomendasi_kebijakan.jangka_pendek_1_3_bulan.length > 0) {
                    return this.analysisData.rekomendasi_kebijakan.jangka_pendek_1_3_bulan;
                }
                if (this.analysisData.prioritas_intervensi && this.analysisData.prioritas_intervensi.length > 0) {
                    return this.analysisData.prioritas_intervensi.map(p => p.aksi_konkret || p.alasan || p.area).filter(Boolean);
                }
                return [
                    'Meningkatkan kapasitas pemasaran digital bagi UMKM potensial.',
                    'Mendorong standardisasi kualitas produk dan pengemasan.',
                    'Memfasilitasi akses pembiayaan dan kemitraan strategis secara berkelanjutan.'
                ];
            },

            renderDonutChart() {
                if (this.donutChartInstance) this.donutChartInstance.destroy();
                const donutCtx = document.getElementById('prioritasDonutChart');
                if (donutCtx) {
                    this.donutChartInstance = new Chart(donutCtx, {
                        type: 'doughnut',
                        data: {
                            labels: this.activeTab === 'wilayah' ? this.dssChartData.kelurahan_chart.labels.slice(0,5) : this.dssChartData.sektor_chart.labels.slice(0,5),
                            datasets: [{
                                data: this.activeTab === 'wilayah' ? this.dssChartData.kelurahan_chart.values.slice(0,5) : this.dssChartData.sektor_chart.values.slice(0,5),
                                backgroundColor: this.donutColors,
                                borderWidth: 2,
                                borderColor: '#ffffff',
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '65%',
                            plugins: { legend: { display: false } }
                        }
                    });
                    
                    // Re-render chart when tab changes
                    this.$watch('activeTab', (value) => {
                        this.donutChartInstance.data.labels = value === 'wilayah' ? this.dssChartData.kelurahan_chart.labels.slice(0,5) : this.dssChartData.sektor_chart.labels.slice(0,5);
                        this.donutChartInstance.data.datasets[0].data = value === 'wilayah' ? this.dssChartData.kelurahan_chart.values.slice(0,5) : this.dssChartData.sektor_chart.values.slice(0,5);
                        this.donutChartInstance.update();
                    });
                }
            },



            renderCharts() {
                this.renderDonutChart();
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
                        this.startPolling();
                    } else if (data.success) {
                        this.parseAnalysis(data.analysis);
                        const indicator = document.getElementById('cache-time-indicator');
                        if (indicator && data.cached_at) {
                            indicator.innerHTML = `<i class="mdi mdi-calendar-clock"></i> <span class="font-medium text-slate-700">${data.cached_at}</span>`;
                        }
                        this.inputOpen = false;
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
            }
        }
    }
</script>
@endpush
