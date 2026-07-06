@extends('layouts.superadmin')

@section('title', 'DSS Mandalaloka - Papan Peringkat')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-slate-900 to-indigo-900 rounded-[2.5rem] p-8 lg:p-12 text-white relative overflow-hidden shadow-2xl">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full mix-blend-overlay filter blur-3xl opacity-50 transform translate-x-1/3 -translate-y-1/3"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-xl rounded-2xl flex items-center justify-center border border-white/20 shadow-inner">
                    <i class="mdi mdi-brain text-4xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-black mb-2 tracking-tight">DSS Mandalaloka <span class="text-indigo-300">Intelligence</span></h1>
                    <p class="text-indigo-100 text-lg max-w-2xl">Sistem Penunjang Keputusan berbasis Algoritma SAW untuk menyeleksi UMKM yang paling layak menerima bantuan dan pendampingan pelatihan secara transparan dan data-driven.</p>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-4 mt-8">
                <a href="{{ route('superadmin.dss.saw.kriteria') }}" class="px-6 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-xl font-bold transition-all flex items-center gap-2 border border-white/20">
                    <i class="mdi mdi-tune-vertical"></i>
                    Atur Bobot Kriteria Dinamis
                </a>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div x-data="{ tab: 'bantuan' }" class="space-y-6">
        <div class="flex gap-4 p-2 bg-white/50 backdrop-blur-xl rounded-2xl border border-slate-200/60 shadow-sm w-fit">
            <button @click="tab = 'bantuan'" :class="{'bg-indigo-600 text-white shadow-md': tab === 'bantuan', 'text-slate-600 hover:bg-slate-100': tab !== 'bantuan'}" class="px-8 py-3 rounded-xl font-bold transition-all">
                <i class="mdi mdi-hand-coin mr-2"></i> Rekomendasi Bantuan Modal
            </button>
            <button @click="tab = 'pelatihan'" :class="{'bg-emerald-600 text-white shadow-md': tab === 'pelatihan', 'text-slate-600 hover:bg-slate-100': tab !== 'pelatihan'}" class="px-8 py-3 rounded-xl font-bold transition-all">
                <i class="mdi mdi-school mr-2"></i> Rekomendasi Pelatihan
            </button>
        </div>

        <!-- BANTUAN TAB -->
        <div x-show="tab === 'bantuan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">Top 20 Rekomendasi Bantuan Modal</h2>
                        <p class="text-slate-500 text-sm mt-1">Diprioritaskan untuk UMKM yang sudah legal, stabil, memiliki pekerja lokal, dan belum pernah dibantu.</p>
                    </div>
                    <div class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg font-bold text-sm">
                        <i class="mdi mdi-check-decagram mr-1"></i> Data Terverifikasi
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-sm uppercase tracking-wider">
                                <th class="p-4 font-bold rounded-tl-3xl text-center w-20">Rank</th>
                                <th class="p-4 font-bold">Pemilik & Usaha</th>
                                <th class="p-4 font-bold text-center">Tahun Berdiri</th>
                                <th class="p-4 font-bold text-center">Pekerja</th>
                                <th class="p-4 font-bold text-center">Legalitas</th>
                                <th class="p-4 font-bold text-right rounded-tr-3xl">Skor SAW</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($rankingBantuan as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition-colors {{ $index < 3 ? 'bg-amber-50/30' : '' }}">
                                <td class="p-4 text-center">
                                    @if($index == 0)
                                        <div class="w-10 h-10 mx-auto bg-amber-400 text-white rounded-full flex items-center justify-center font-black shadow-lg shadow-amber-400/30 text-lg border-2 border-white"><i class="mdi mdi-trophy"></i></div>
                                    @elseif($index == 1)
                                        <div class="w-10 h-10 mx-auto bg-slate-300 text-white rounded-full flex items-center justify-center font-black shadow-lg shadow-slate-400/30 text-lg border-2 border-white"><i class="mdi mdi-trophy"></i></div>
                                    @elseif($index == 2)
                                        <div class="w-10 h-10 mx-auto bg-orange-400 text-white rounded-full flex items-center justify-center font-black shadow-lg shadow-orange-400/30 text-lg border-2 border-white"><i class="mdi mdi-trophy"></i></div>
                                    @else
                                        <div class="w-10 h-10 mx-auto bg-slate-100 text-slate-500 rounded-full flex items-center justify-center font-bold">{{ $index + 1 }}</div>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-slate-800 text-base">{{ $item['umkm']->nama_usaha }}</div>
                                    <div class="text-sm text-slate-500">{{ $item['umkm']->pemilik->nama_lengkap }}</div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium">{{ $item['umkm']->tahun_berdiri ?? '-' }}</span>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-medium">{{ $item['umkm']->jumlah_tenaga_kerja }} Orang</span>
                                </td>
                                <td class="p-4 text-center">
                                    @if(!empty($item['umkm']->no_izin_usaha) && !empty($item['umkm']->npwp_usaha))
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-md text-xs font-bold">NIB & NPWP</span>
                                    @elseif(!empty($item['umkm']->no_izin_usaha) || !empty($item['umkm']->npwp_usaha))
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md text-xs font-bold">Ada Legalitas</span>
                                    @else
                                        <span class="px-2 py-1 bg-rose-100 text-rose-700 rounded-md text-xs font-bold">Belum Ada</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <div class="h-2 w-24 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $item['skor_akhir'] }}%"></div>
                                        </div>
                                        <span class="font-black text-lg {{ $index < 3 ? 'text-amber-500' : 'text-slate-800' }}">{{ $item['skor_akhir'] }}</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-500">Belum ada data UMKM terverifikasi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- PELATIHAN TAB -->
        <div x-show="tab === 'pelatihan'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">Top 20 Rekomendasi Pelatihan Digitalisasi & NIB</h2>
                        <p class="text-slate-500 text-sm mt-1">Diprioritaskan untuk UMKM pemula (baru buka), belum punya izin (NIB), dan berjualan secara konvensional (Keliling/Rumahan).</p>
                    </div>
                    <div class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg font-bold text-sm">
                        <i class="mdi mdi-school mr-1"></i> Target Pembinaan
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-sm uppercase tracking-wider">
                                <th class="p-4 font-bold rounded-tl-3xl text-center w-20">Rank</th>
                                <th class="p-4 font-bold">Pemilik & Usaha</th>
                                <th class="p-4 font-bold text-center">Bentuk Jualan</th>
                                <th class="p-4 font-bold text-center">Legalitas</th>
                                <th class="p-4 font-bold text-right rounded-tr-3xl">Skor Rentan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($rankingPelatihan as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition-colors {{ $index < 3 ? 'bg-emerald-50/30' : '' }}">
                                <td class="p-4 text-center">
                                    <div class="w-10 h-10 mx-auto {{ $index < 3 ? 'bg-emerald-500 text-white border-2 border-emerald-200' : 'bg-slate-100 text-slate-500' }} rounded-full flex items-center justify-center font-bold">{{ $index + 1 }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-slate-800 text-base">{{ $item['umkm']->nama_usaha }}</div>
                                    <div class="text-sm text-slate-500">{{ $item['umkm']->pemilik->nama_lengkap }} &bull; Est. {{ $item['umkm']->tahun_berdiri ?? '?' }}</div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium">{{ $item['umkm']->bentuk_jualan ?? '-' }}</span>
                                </td>
                                <td class="p-4 text-center">
                                    @if(empty($item['umkm']->no_izin_usaha) && empty($item['umkm']->npwp_usaha))
                                        <span class="px-2 py-1 bg-rose-100 text-rose-700 rounded-md text-xs font-bold"><i class="mdi mdi-alert-circle mr-1"></i> Tidak Ada</span>
                                    @else
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-md text-xs font-bold"><i class="mdi mdi-check"></i> Ada</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <span class="font-black text-lg {{ $index < 3 ? 'text-emerald-600' : 'text-slate-800' }}">{{ $item['skor_akhir'] }}</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-500">Belum ada data UMKM terverifikasi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
