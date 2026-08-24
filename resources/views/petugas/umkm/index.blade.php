@extends('layouts.petugas')

@section('title', 'Data UMKM')
@section('breadcrumb', 'Data UMKM')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Data UMKM</h2>
                <p class="text-sm text-slate-700 mt-0.5">Kelurahan <span class="font-semibold text-slate-700">{{ $kelurahan ?: 'Belum Diatur' }}</span></p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="openScanModal()"
                    class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-md transition-colors shadow-sm shadow-emerald-600/20 whitespace-nowrap gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 3.5a.5.5 0 11-1 0 .5.5 0 011 0zm-5-7.5a.5.5 0 11-1 0 .5.5 0 011 0zm-8 8a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                    </svg>
                    Scan QR
                </button>
                <a href="{{ route('operator.umkm.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors shadow-sm shadow-blue-600/20 whitespace-nowrap gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah UMKM
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-xl border border-green-100 shadow-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 text-red-700 p-4 rounded-xl border border-red-100 shadow-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Statistik Cards (Wilayah) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            
            <!-- Total Wilayah -->
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                <div class="absolute -right-6 -top-6 text-white/10 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                </div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-blue-100 text-xs font-semibold tracking-wider uppercase mb-1">Total Wilayah</p>
                            <h3 class="text-3xl font-bold">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/20 shadow-inner">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terverifikasi -->
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                <div class="absolute -right-6 -top-6 text-white/10 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                </div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-emerald-100 text-xs font-semibold tracking-wider uppercase mb-1">Terverifikasi</p>
                            <h3 class="text-3xl font-bold">{{ $stats['terverifikasi'] }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/20 shadow-inner">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ditolak -->
            <div class="bg-gradient-to-br from-rose-500 to-red-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                <div class="absolute -right-6 -top-6 text-white/10 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
                </div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-rose-100 text-xs font-semibold tracking-wider uppercase mb-1">Ditolak</p>
                            <h3 class="text-3xl font-bold">{{ $stats['ditolak'] }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/20 shadow-inner">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        {{-- Filter Bar --}}
        @php $activeFiltersUmkm = array_filter([request('search'), request('status')]); @endphp
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
            <form id="filter-form" method="GET" action="{{ route('operator.umkm.index') }}" class="flex flex-wrap gap-3 items-center">
                <input type="hidden" name="tab" id="active-tab-input" value="{{ $tab }}">
                
                <div class="relative flex-1 min-w-[200px]">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><i class="mdi mdi-magnify"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama usaha, no. pendaftaran, pemilik..."
                        class="pl-9 pr-4 py-2.5 w-full bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>
                <div class="relative">
                    <select name="status" onchange="window.triggerFilter()" class="appearance-none bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 pl-4 pr-9 py-2.5 focus:outline-none transition-all cursor-pointer {{ request('status') ? 'border-blue-400 bg-blue-50 text-blue-700' : '' }}">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                        <option value="terverifikasi" {{ request('status') === 'terverifikasi' ? 'selected' : '' }}>✅ Terverifikasi</option>
                        <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400"><i class="mdi mdi-chevron-down text-sm"></i></div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500 font-semibold">Tampilkan:</span>
                    <select name="per_page" onchange="window.triggerFilter()" class="appearance-none bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 pl-3 pr-8 py-2.5 focus:outline-none transition-all cursor-pointer">
                        @foreach([10, 20, 50, 100] as $pp)
                            <option value="{{ $pp }}" {{ request('per_page', 20) == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" onclick="window.triggerFilter()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-all shadow-sm">Cari</button>
                @if(count($activeFiltersUmkm) > 0)
                    <a href="{{ route('operator.umkm.index', ['tab' => $tab]) }}" class="px-4 py-2.5 bg-rose-50 border border-rose-200 text-rose-600 text-sm font-bold rounded-lg hover:bg-rose-100 transition-all">Reset</a>
                @endif
            </form>
        </div>

        {{-- Tab Switcher --}}
        <div x-data="{ activeTab: '{{ $tab === 'peta' ? 'peta' : ($tab === 'wilayah' ? 'wilayah' : 'saya') }}' }" class="space-y-4">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                {{-- Tab Header --}}
                <div class="flex border-b border-slate-200">
                    <button @click="activeTab = 'saya'; document.getElementById('active-tab-input').value = 'saya';"
                        :class="activeTab === 'saya' ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50/50' : 'text-slate-700 hover:text-slate-700 hover:bg-slate-50'"
                        class="flex-1 px-6 py-3.5 text-sm font-semibold transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Input Saya
                        <span id="badge-count-saya" class="inline-flex items-center justify-center w-6 h-6 rounded-full text-[10px] font-bold"
                            :class="activeTab === 'saya' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600'">{{ $umkmSaya->total() }}</span>
                    </button>
                    <button @click="activeTab = 'wilayah'; document.getElementById('active-tab-input').value = 'wilayah';"
                        :class="activeTab === 'wilayah' ? 'border-b-2 border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'text-slate-700 hover:text-slate-700 hover:bg-slate-50'"
                        class="flex-1 px-6 py-3.5 text-sm font-semibold transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Semua Wilayah
                        <span id="badge-count-wilayah" class="inline-flex items-center justify-center w-6 h-6 rounded-full text-[10px] font-bold"
                            :class="activeTab === 'wilayah' ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-600'">{{ $umkmWilayah instanceof \Illuminate\Pagination\LengthAwarePaginator ? $umkmWilayah->total() : ($umkmWilayah ? $umkmWilayah->count() : 0) }}</span>
                    </button>
                    <button @click="activeTab = 'peta'"
                        :class="activeTab === 'peta' ? 'border-b-2 border-teal-600 text-teal-600 bg-teal-50/50' : 'text-slate-700 hover:text-slate-700 hover:bg-slate-50'"
                        class="flex-1 px-6 py-3.5 text-sm font-semibold transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Peta Wilayah
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-[10px] font-bold"
                            :class="activeTab === 'peta' ? 'bg-teal-600 text-white' : 'bg-slate-200 text-slate-600'">{{ $mapData->count() }}</span>
                    </button>
                </div>

                {{-- Tab: Input Saya --}}
                <div x-show="activeTab === 'saya'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div id="table-saya-container">
                        @include('petugas.umkm.table-saya')
                    </div>
                </div>


                {{-- Tab: Semua Wilayah --}}
                <div x-show="activeTab === 'wilayah'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @if(empty($kelurahan))
                        <div class="p-8 text-center">
                            <p class="text-slate-700">Akun Anda belum memiliki pengaturan Kelurahan penugasan. Silakan hubungi Admin.</p>
                        </div>
                    @elseif($umkmWilayah->isEmpty())
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-1">Belum ada data</h3>
                            <p class="text-slate-700 text-sm">Belum ada UMKM yang terdaftar di Kelurahan {{ $kelurahan }}.</p>
                        </div>
                    @else
                        {{-- Distribusi Kategori --}}
                        @if($stats['per_kategori']->isNotEmpty())
                        <div class="px-6 pt-4 pb-2">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Distribusi Kategori</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($stats['per_kategori'] as $kategori => $count)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $kategori }} <span class="bg-indigo-600 text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px]">{{ $count }}</span>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        {{-- Distribusi Kategori (kept as is) --}}

                        <div id="table-wilayah-container">
                            @include('petugas.umkm.table-wilayah')
                        </div>
                    @endif
                </div>

                {{-- Tab: Peta Wilayah --}}
                <div x-show="activeTab === 'peta'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-init="$watch('activeTab', val => { if(val === 'peta') { setTimeout(() => { if(window.umkmMap) window.umkmMap.invalidateSize(); }, 100); } })">
                    @if($mapData->isEmpty())
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 bg-teal-50 text-teal-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-1">Belum ada data koordinat</h3>
                            <p class="text-slate-700 text-sm">Belum ada UMKM di wilayah ini yang memiliki data GPS/koordinat.</p>
                        </div>
                    @else
                        <div class="p-4">
                            <div id="umkmMap" class="w-full rounded-xl overflow-hidden border border-slate-200 shadow-sm" style="height: 70vh; min-height: 600px;"></div>
                            <p class="text-xs text-slate-400 text-center mt-2">📍 {{ $mapData->count() }} UMKM dengan data koordinat ditampilkan di peta</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Status Badge Partial --}}
    {{-- Note: Create _status-badge.blade.php partial --}}

    <style>
        .tooltip { position: relative; }
        .tooltip:before {
            content: attr(data-tip); position: absolute; bottom: 100%; left: 50%;
            transform: translateX(-50%) translateY(-4px); background: #1e293b; color: white;
            padding: 4px 8px; border-radius: 6px; font-size: 10px; white-space: nowrap;
            opacity: 0; visibility: hidden; transition: all 0.2s;
        }
        .tooltip:hover:before { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(-8px); }
        #reader { border: none !important; }
        #reader video { border-radius: 12px; }
        #reader__scan_region { border-radius: 12px; overflow: hidden; }
        #reader__dashboard_section_swaplink { display: none !important; }
    </style>

    {{-- Modal QR Scanner --}}
    <div id="scanModal" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-sm flex items-center justify-center">
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">📷 Scan QR Code UMKM</h3>
                    <p class="text-xs text-slate-700 mt-0.5">Arahkan kamera ke QR Code UMKM</p>
                </div>
                <button onclick="closeScanModal()" class="text-slate-400 hover:text-slate-600 bg-slate-50 hover:bg-slate-100 rounded-full p-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <div id="reader" class="w-full rounded-xl overflow-hidden bg-slate-900"></div>
                <div id="scan-status" class="mt-4 text-center text-sm text-slate-700 hidden">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 animate-spin text-emerald-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span id="scan-status-text">Memproses QR Code...</span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 text-center mt-3">QR Code akan terdeteksi secara otomatis. Pastikan cahaya cukup.</p>
                <div class="mt-4 border-t border-slate-100 pt-4">
                    <p class="text-xs text-center text-slate-700 mb-2">Kamera tidak muncul (akses via IP)?</p>
                    <label class="block w-full text-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-md cursor-pointer transition-colors">
                        <input type="file" id="qr-input-file" accept="image/*" class="hidden">
                        Upload / Ambil Foto QR Code
                    </label>
                </div>
            </div>
        </div>
    </div>

    {{-- Leaflet CSS & JS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        // ====== Peta Leaflet ======
        document.addEventListener('DOMContentLoaded', function () {
            const mapData = @json($mapData);

            if (mapData.length > 0 && document.getElementById('umkmMap')) {
                const map = L.map('umkmMap').setView([mapData[0].lat, mapData[0].lng], 15);
                window.umkmMap = map;

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors',
                    maxZoom: 19,
                }).addTo(map);

                const statusColors = {
                    'terverifikasi': '#059669',
                    'menunggu_verifikasi': '#d97706',
                    'ditolak': '#dc2626',
                    'draft': '#64748b',
                };

                const bounds = [];

                mapData.forEach(function (u) {
                    const color = statusColors[u.status] || '#64748b';
                    const marker = L.circleMarker([u.lat, u.lng], {
                        radius: 8,
                        fillColor: color,
                        color: '#fff',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.85,
                    }).addTo(map);

                    marker.bindPopup(
                        '<div style="min-width:200px; padding:2px; font-family: ui-sans-serif, system-ui, sans-serif;">' +
                        '<h4 style="font-weight:800; font-size:15px; color:#1e293b; margin:0 0 8px; line-height:1.3; border-bottom:1px solid #f1f5f9; padding-bottom:8px;">' + u.nama + '</h4>' +
                        '<div style="display:flex; align-items:flex-start; gap:8px; margin-bottom:6px;">' +
                            '<span style="font-size:13px; line-height:1;">👤</span>' +
                            '<span style="font-size:12px; color:#475569; font-weight:600; line-height:1.2;">' + u.pemilik + '</span>' +
                        '</div>' +
                        '<div style="display:flex; align-items:flex-start; gap:8px; margin-bottom:6px;">' +
                            '<span style="font-size:13px; line-height:1;">📂</span>' +
                            '<span style="font-size:12px; color:#475569; line-height:1.2;">' + u.kategori + '</span>' +
                        '</div>' +
                        '<div style="display:flex; align-items:flex-start; gap:8px; margin-bottom:12px;">' +
                            '<span style="font-size:13px; line-height:1;">📍</span>' +
                            '<span style="font-size:12px; color:#475569; line-height:1.3;">' + u.alamat + '</span>' +
                        '</div>' +
                        '<a href="/operator/umkm/' + u.id + '/show" style="display:block; text-align:center; background-color:#2563eb; color:#ffffff; padding:8px 12px; border-radius:6px; font-size:12px; font-weight:600; text-decoration:none; transition: background-color 0.2s;">Lihat Detail Info</a>' +
                        '</div>'
                    );

                    bounds.push([u.lat, u.lng]);
                });

                if (bounds.length > 1) {
                    map.fitBounds(bounds, { padding: [30, 30] });
                }
            }
        });

        // ====== QR Scanner ======
        let html5QrCode = null;
        let scanHandled = false;

        function openScanModal() {
            document.getElementById('scanModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            scanHandled = false;

            setTimeout(() => {
                html5QrCode = new Html5Qrcode("reader");
                const config = {
                    fps: 10,
                    qrbox: function(viewfinderWidth, viewfinderHeight) {
                        let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                        let qrboxSize = Math.floor(minEdgeSize * 0.75);
                        return { width: qrboxSize, height: qrboxSize };
                    }
                };
                const onScanSuccess = (decodedText) => {
                    if (scanHandled) return;
                    scanHandled = true;
                    document.getElementById('scan-status').classList.remove('hidden');
                    document.getElementById('scan-status-text').textContent = 'QR Code terbaca! Mengalihkan...';
                    html5QrCode.stop().then(() => {
                        window.location.href = "{{ route('operator.umkm.scan-result') }}?q=" + encodeURIComponent(decodedText);
                    }).catch(() => {
                        window.location.href = "{{ route('operator.umkm.scan-result') }}?q=" + encodeURIComponent(decodedText);
                    });
                };
                html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess, () => {}).catch(err => {
                    Html5Qrcode.getCameras().then(devices => {
                        if (devices && devices.length) {
                            let cameraId = devices[devices.length - 1].id;
                            html5QrCode.start(cameraId, config, onScanSuccess, () => {}).catch(() => {
                                document.getElementById('scan-status').classList.remove('hidden');
                                document.getElementById('scan-status-text').textContent = 'Kamera tidak dapat diakses.';
                            });
                        }
                    }).catch(() => {
                        document.getElementById('scan-status').classList.remove('hidden');
                        document.getElementById('scan-status-text').textContent = location.protocol !== 'https:' && location.hostname !== 'localhost' ? 'Akses kamera butuh HTTPS!' : 'Kamera diblokir / tidak diizinkan.';
                    });
                });
            }, 200);
        }

        function closeScanModal() {
            // Segera sembunyikan modal agar UI responsif dan tidak terblokir error scanner
            document.getElementById('scan-status').classList.add('hidden');
            document.getElementById('scanModal').classList.add('hidden');
            document.body.style.overflow = '';

            if (html5QrCode) {
                try {
                    // Check if it's currently scanning
                    if (html5QrCode.getState && html5QrCode.getState() === 2) {
                        html5QrCode.stop().catch(() => {}).finally(() => {
                            html5QrCode.clear();
                            html5QrCode = null;
                        });
                    } else {
                        html5QrCode.clear();
                        html5QrCode = null;
                    }
                } catch(e) {
                    // Ignore errors if clear fails
                    html5QrCode = null;
                }
            }
        }

        document.getElementById('qr-input-file').addEventListener('change', e => {
            if (e.target.files.length === 0) return;
            const file = e.target.files[0];
            document.getElementById('scan-status').classList.remove('hidden');
            document.getElementById('scan-status-text').textContent = 'Membaca gambar...';
            if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");
            html5QrCode.scanFile(file, true)
                .then(decodedText => {
                    document.getElementById('scan-status-text').textContent = 'QR Code terbaca! Mengalihkan...';
                    window.location.href = "{{ route('operator.umkm.scan-result') }}?q=" + encodeURIComponent(decodedText);
                })
                .catch(() => {
                    document.getElementById('scan-status-text').textContent = 'QR Code tidak ditemukan pada gambar.';
                    e.target.value = '';
                });
        });
    </script>
@endsection
@push('scripts')
<script>
    // Debounce function for search
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

    // Global trigger function for filtering
    window.triggerFilter = function() {
        const form = document.getElementById('filter-form');
        const url = new URL(form.action);
        const formData = new FormData(form);
        
        for (const [key, value] of formData.entries()) {
            if (value) {
                url.searchParams.append(key, value);
            }
        }
        
        // Show loading state
        const containers = ['#table-saya-container', '#table-wilayah-container'];
        containers.forEach(id => {
            const el = document.querySelector(id);
            if (el) {
                el.style.opacity = '0.5';
                el.style.pointerEvents = 'none';
            }
        });
        
        url.searchParams.append('ajax', '1');
        
        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.html_saya && document.getElementById('table-saya-container')) {
                    document.getElementById('table-saya-container').innerHTML = data.html_saya;
                }
                if (data.html_wilayah && document.getElementById('table-wilayah-container')) {
                    document.getElementById('table-wilayah-container').innerHTML = data.html_wilayah;
                }
                if (document.getElementById('badge-count-saya')) {
                    document.getElementById('badge-count-saya').textContent = data.count_saya;
                }
                if (document.getElementById('badge-count-wilayah')) {
                    document.getElementById('badge-count-wilayah').textContent = data.count_wilayah;
                }
                // Update URL without reload
                url.searchParams.delete('ajax');
                window.history.pushState({}, '', url.toString());
                attachPaginationListeners();
            }
        })
        .catch(error => console.error('Error fetching data:', error))
        .finally(() => {
            containers.forEach(id => {
                const el = document.querySelector(id);
                if (el) {
                    el.style.opacity = '1';
                    el.style.pointerEvents = 'auto';
                }
            });
        });
    };

    // Attach search input listener
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(() => window.triggerFilter(), 500));
    }

    // Attach pagination link listeners
    function attachPaginationListeners() {
        document.querySelectorAll('.pagination a').forEach(link => {
            // Remove existing listener to prevent duplicates
            const newLink = link.cloneNode(true);
            link.parentNode.replaceChild(newLink, link);
            
            newLink.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const form = document.getElementById('filter-form');
                const formData = new FormData(form);
                
                // Keep form parameters
                for (const [key, value] of formData.entries()) {
                    if (value && !url.searchParams.has(key)) {
                        url.searchParams.append(key, value);
                    }
                }
                
                // Show loading state
                const containers = ['#table-saya-container', '#table-wilayah-container'];
                containers.forEach(id => {
                    const el = document.querySelector(id);
                    if (el) {
                        el.style.opacity = '0.5';
                        el.style.pointerEvents = 'none';
                    }
                });
                
                url.searchParams.append('ajax', '1');
                
                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.html_saya && document.getElementById('table-saya-container')) {
                            document.getElementById('table-saya-container').innerHTML = data.html_saya;
                        }
                        if (data.html_wilayah && document.getElementById('table-wilayah-container')) {
                            document.getElementById('table-wilayah-container').innerHTML = data.html_wilayah;
                        }
                        if (document.getElementById('badge-count-saya')) {
                            document.getElementById('badge-count-saya').textContent = data.count_saya;
                        }
                        if (document.getElementById('badge-count-wilayah')) {
                            document.getElementById('badge-count-wilayah').textContent = data.count_wilayah;
                        }
                        
                        url.searchParams.delete('ajax');
                        window.history.pushState({}, '', url.toString());
                        attachPaginationListeners();
                        
                        // Scroll to top
                        window.scrollTo({top: 0, behavior: 'smooth'});
                    }
                })
                .catch(error => console.error('Error fetching data:', error))
                .finally(() => {
                    containers.forEach(id => {
                        const el = document.querySelector(id);
                        if (el) {
                            el.style.opacity = '1';
                            el.style.pointerEvents = 'auto';
                        }
                    });
                });
            });
        });
    }

    // Initial attachment
    document.addEventListener('DOMContentLoaded', () => {
        attachPaginationListeners();
    });
</script>
@endpush
