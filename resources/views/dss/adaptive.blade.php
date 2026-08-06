@extends($layout)

@section('title', 'Adaptive DSS - Penargetan UMKM')

@section('content')
<div class="space-y-6" x-data="adaptiveDss()">
    
    <!-- Top Header Card -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-700 p-6 md:p-8 shadow-lg text-white">
        <div class="absolute top-0 right-0 p-8 opacity-10 pointer-events-none">
            <i class="mdi mdi-target-account text-9xl"></i>
        </div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight mb-2">Penargetan Cerdas (Adaptive DSS)</h1>
                <p class="text-blue-100 max-w-2xl text-sm md:text-base leading-relaxed">
                    Sistem ini menggunakan AI untuk menganalisis tujuan program Anda, menentukan indikator yang relevan, dan mencari UMKM yang paling sesuai dengan kriteria yang ditentukan secara dinamis.
                </p>
            </div>
            
            <!-- Generate Button in Header -->
            <button @click="startAnalysis" :disabled="isLoading || !intervention" 
                class="inline-flex items-center justify-center px-6 py-3 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white rounded-xl transition-all font-medium border border-white/30 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="mdi mdi-auto-fix me-2 text-xl" x-show="!isLoading"></i>
                <i class="mdi mdi-loading mdi-spin me-2 text-xl" x-show="isLoading"></i>
                <span x-text="isLoading ? 'Memproses...' : 'Mulai Pencarian'"></span>
            </button>
        </div>
    </div>

    <!-- Input Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6">
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                <i class="mdi mdi-bullhorn-outline text-indigo-500 me-1"></i> Apa tujuan intervensi atau program Anda?
            </label>
            <textarea x-model="intervention" rows="3" 
                class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50 p-4 transition-colors"
                placeholder="Contoh: Saya ingin memberikan bantuan mesin jahit dan pelatihan kepada UMKM sektor konveksi/pakaian yang sudah berjalan lebih dari 2 tahun."></textarea>
            
            <!-- Progress Status -->
            <div x-show="isLoading" class="mt-4 p-4 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                    <i class="mdi mdi-robot-outline text-xl animate-pulse"></i>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-indigo-900">AI Sedang Berpikir</h4>
                    <p class="text-xs text-indigo-600" x-text="statusText"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Overview (Only visible when hasResult) -->
    <div x-show="hasResult" x-cloak class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Focus Insight -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full blur-xl group-hover:bg-blue-100 transition-colors"></div>
            <div class="relative z-10 flex-1">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="mdi mdi-lightbulb-on-outline"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Pemahaman AI</h3>
                </div>
                <div class="text-xs text-slate-700 mb-1">Topik Utama</div>
                <div class="font-semibold text-slate-800 mb-3" x-text="indicators.focus"></div>
                <div class="text-xs text-slate-700 mb-1">Target Kategori</div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800" x-text="indicators.priority_category || 'Semua'"></span>
            </div>
        </div>

        <!-- Indicators Picked -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full blur-xl group-hover:bg-amber-100 transition-colors"></div>
            <div class="relative z-10 flex-1">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                        <i class="mdi mdi-format-list-checks"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Variabel Penyeleksi</h3>
                </div>
                <div class="flex flex-wrap gap-2">
                    <template x-for="attr in (indicators.required_attributes || [])">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                            <i class="mdi mdi-check-circle text-amber-500 me-1"></i>
                            <span x-text="attr"></span>
                        </span>
                    </template>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full blur-xl group-hover:bg-emerald-100 transition-colors"></div>
            <div class="relative z-10 flex-1 flex flex-col justify-center">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl">
                        <i class="mdi mdi-account-group-outline"></i>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-slate-700">UMKM Dievaluasi</div>
                        <div class="text-3xl font-bold text-slate-800" x-text="totalScored"></div>
                    </div>
                </div>
                <div class="text-xs text-slate-700">
                    <i class="mdi mdi-chart-bell-curve text-emerald-500 me-1"></i> Rata-rata skor kecocokan: <span class="font-bold text-emerald-700" x-text="avgTopScore"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations Table -->
    <div x-show="hasResult" x-cloak class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
            <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                <i class="mdi mdi-trophy-variant text-amber-500 text-lg"></i>
                Rekomendasi Prioritas Utama
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-medium w-16 text-slate-700">Rank</th>
                        <th class="px-6 py-4 font-medium text-slate-700">UMKM & Lokasi</th>
                        <th class="px-6 py-4 font-medium text-slate-700">Kategori</th>
                        <th class="px-6 py-4 font-medium text-slate-700">Skor Kecocokan</th>
                        <th class="px-6 py-4 font-medium text-right text-slate-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(umkm, index) in rankedUmkms" :key="umkm.id">
                        <tr class="hover:bg-slate-50 transition-colors group" :class="index < 3 ? 'bg-amber-50/30' : ''">
                            
                            <td class="px-6 py-4">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs"
                                     :class="index === 0 ? 'bg-amber-100 text-amber-700' : (index === 1 ? 'bg-slate-200 text-slate-700' : (index === 2 ? 'bg-orange-100 text-orange-700' : 'bg-slate-100 text-slate-700'))">
                                    <span x-text="index + 1"></span>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800" x-text="umkm.nama"></div>
                                <div class="text-xs text-slate-700 mt-1 flex items-center gap-1">
                                    <i class="mdi mdi-map-marker-outline"></i> <span x-text="umkm.kelurahan"></span>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200" x-text="umkm.kategori"></span>
                            </td>
                            
                            <td class="px-6 py-4 w-48">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-1000"
                                             :class="umkm.score >= 80 ? 'bg-emerald-500' : (umkm.score >= 50 ? 'bg-amber-500' : 'bg-red-500')"
                                             :style="`width: ${umkm.score}%`"></div>
                                    </div>
                                    <span class="font-bold text-slate-700 w-8" x-text="umkm.score + '%'"></span>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 text-right space-x-2">
                                <button @click="explainUmkm(umkm, index)" 
                                    class="inline-flex items-center justify-center p-2 rounded-lg transition-colors border"
                                    :class="umkm.explanation ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-indigo-600'"
                                    :disabled="umkm.isExplaining"
                                    title="Analisis Kecocokan">
                                    <i class="mdi" :class="umkm.isExplaining ? 'mdi-loading mdi-spin' : (umkm.explanation ? 'mdi-robot' : 'mdi-robot-outline')"></i>
                                    <span class="ms-1 text-xs font-medium" x-show="umkm.isExplaining">Menalar...</span>
                                </button>
                                
                                <a :href="'/admin/umkm/' + umkm.id" target="_blank" 
                                    class="inline-flex items-center justify-center p-2 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-colors"
                                    title="Lihat Profil UMKM">
                                    <i class="mdi mdi-open-in-new"></i>
                                </a>
                            </td>
                            
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Explanation Modal Overlay -->
    <div x-show="activeExplanation" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" @click.self="activeExplanation = null">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full overflow-hidden flex flex-col" x-show="activeExplanation" x-transition>
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-gradient-to-r from-indigo-50 to-white">
                <h3 class="font-semibold text-indigo-900 flex items-center gap-2">
                    <i class="mdi mdi-robot text-indigo-600 text-xl"></i>
                    Analisis Kecocokan AI
                </h3>
                <button @click="activeExplanation = null" class="text-slate-400 hover:text-slate-600">
                    <i class="mdi mdi-close text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[70vh]" x-if="activeExplanation">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 text-2xl font-bold border border-slate-200">
                        <i class="mdi mdi-storefront-outline"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg text-slate-800" x-text="activeExplanation.nama"></h4>
                        <p class="text-sm text-slate-700 flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Skor: <span x-text="activeExplanation.score + '%'" class="ml-1"></span>
                            </span>
                            <span x-text="activeExplanation.kategori"></span>
                        </p>
                    </div>
                </div>

                <div class="bg-indigo-50 rounded-xl p-5 border border-indigo-100 mb-6">
                    <p class="text-slate-700 leading-relaxed" x-text="activeExplanation.explanation"></p>
                </div>

                <h5 class="text-sm font-semibold text-slate-700 mb-3 border-b border-slate-100 pb-2">Atribut Pendukung Terdeteksi:</h5>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <template x-for="(val, key) in activeExplanation.details">
                        <div class="bg-slate-50 rounded-lg p-3 border border-slate-100">
                            <div class="text-[10px] uppercase tracking-wider font-semibold text-slate-400 mb-1" x-text="key.replace(/_/g, ' ')"></div>
                            <div class="text-sm font-medium text-slate-700" x-text="val"></div>
                        </div>
                    </template>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end">
                <button @click="activeExplanation = null" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 font-medium text-sm transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('adaptiveDss', () => ({
        intervention: '',
        isLoading: false,
        statusText: '',
        hasResult: false,
        indicators: {},
        rankedUmkms: [],
        totalScored: 0,
        avgTopScore: 0,
        activeExplanation: null,

        async startAnalysis() {
            if (!this.intervention) return;

            this.isLoading = true;
            this.hasResult = false;
            this.indicators = {};
            this.rankedUmkms = [];

            try {
                // 1. Dapatkan indikator
                this.statusText = 'Memahami konteks intervensi... (1/2)';
                const indRes = await axios.post('/api/dss/adaptive/indicators', {
                    intervention: this.intervention
                });
                this.indicators = indRes.data;

                // 2. Score & Rank
                this.statusText = 'Menganalisis dan menyeleksi UMKM... (2/2)';
                const scoreRes = await axios.post('/api/dss/adaptive/score', {
                    required_attributes: this.indicators.required_attributes || [],
                    priority_category: this.indicators.priority_category || ''
                });
                
                let results = scoreRes.data;
                this.rankedUmkms = results.map(u => ({...u, isExplaining: false, explanation: null, origExp: null}));
                this.totalScored = this.rankedUmkms.length;
                
                let top10 = this.rankedUmkms.slice(0, 10);
                let sum = top10.reduce((acc, curr) => acc + curr.score, 0);
                this.avgTopScore = top10.length > 0 ? (sum / top10.length).toFixed(1) : 0;

                this.hasResult = true;

                // 3. Otomatis generate penjelasan untuk Top 1
                if (this.rankedUmkms.length > 0) {
                    // Beri jeda sedikit agar UI rendering lancar
                    setTimeout(() => {
                        this.explainUmkm(this.rankedUmkms[0], 0);
                    }, 500);
                }

            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan: ' + (err.response?.data?.error || err.message));
            } finally {
                this.isLoading = false;
            }
        },

        async explainUmkm(umkm, index) {
            if (umkm.origExp) {
                // Show modal if already explained
                this.activeExplanation = this.rankedUmkms[index];
                return;
            }

            this.rankedUmkms[index].isExplaining = true;
            try {
                const res = await axios.post('/api/dss/adaptive/explain', {
                    intervention: this.intervention,
                    umkm: umkm
                });
                
                let expl = res.data.explanation;
                this.rankedUmkms[index].explanation = expl;
                this.rankedUmkms[index].origExp = expl;
                
                // Show modal
                this.activeExplanation = this.rankedUmkms[index];
            } catch (err) {
                console.error(err);
                alert('Gagal mendapatkan penjelasan AI');
            } finally {
                this.rankedUmkms[index].isExplaining = false;
            }
        }
    }));
});
</script>
@endsection
