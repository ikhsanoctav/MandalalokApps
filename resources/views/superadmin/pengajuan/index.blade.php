@extends('layouts.superadmin')
@section('title', 'Data Pengajuan')
@section('breadcrumb', 'Pengajuan / Daftar Pengajuan')

@section('content')
    <div class="space-y-6">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            
            <a href="{{ route('superadmin.pengajuan') }}"
                class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl p-5 shadow-lg flex flex-col justify-between h-[140px] hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-white/80 uppercase tracking-widest">TOTAL PENGAJUAN</span>
                        <h3 class="text-3xl font-extrabold text-white mt-1">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white/20 text-white rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="border-t border-white/20 pt-2.5">
                    <p class="text-xs text-white/80 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-white/95" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Bantuan & Pembiayaan
                    </p>
                </div>
            </a>

            <a href="{{ route('superadmin.pengajuan', ['status' => 'menunggu']) }}"
                class="bg-gradient-to-br from-amber-500 to-orange-600 text-white rounded-2xl p-5 shadow-lg flex flex-col justify-between h-[140px] hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-white/80 uppercase tracking-widest">MENUNGGU
                            VERIFIKASI</span>
                        <h3 class="text-3xl font-extrabold text-white mt-1 animate-pulse">{{ $stats['menunggu'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white/20 text-white rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="border-t border-white/20 pt-2.5">
                    <p class="text-xs text-white/80 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-white/95" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Perlu ditindaklanjuti segera
                    </p>
                </div>
            </a>

            <a href="{{ route('superadmin.pengajuan', ['status' => 'proses']) }}"
                class="bg-gradient-to-br from-purple-500 to-indigo-600 text-white rounded-2xl p-5 shadow-lg flex flex-col justify-between h-[140px] hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-white/80 uppercase tracking-widest">SEDANG DIPROSES</span>
                        <h3 class="text-3xl font-extrabold text-white mt-1">{{ $stats['proses'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white/20 text-white rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89l-2.286 2.287M21 4v6h-6"></path>
                        </svg>
                    </div>
                </div>
                <div class="border-t border-white/20 pt-2.5">
                    <p class="text-xs text-white/80 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-white/95" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tahap verifikasi berkas
                    </p>
                </div>
            </a>

            <a href="{{ route('superadmin.pengajuan', ['status' => 'disetujui']) }}"
                class="bg-gradient-to-br from-emerald-500 to-teal-700 text-white rounded-2xl p-5 shadow-lg flex flex-col justify-between h-[140px] hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-white/80 uppercase tracking-widest">TELAH DISETUJUI</span>
                        <h3 class="text-3xl font-extrabold text-white mt-1">{{ $stats['disetujui'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white/20 text-white rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="border-t border-white/20 pt-2.5">
                    <p class="text-xs text-white/80 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-white/95" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tinggal tahap pencairan
                    </p>
                </div>
            </a>

            <a href="{{ route('superadmin.pengajuan', ['status' => 'ditolak']) }}"
                class="bg-gradient-to-br from-rose-500 to-red-700 text-white rounded-2xl p-5 shadow-lg flex flex-col justify-between h-[140px] hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-white/80 uppercase tracking-widest">DITOLAK</span>
                        <h3 class="text-3xl font-extrabold text-white mt-1">{{ $stats['ditolak'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white/20 text-white rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="border-t border-white/20 pt-2.5">
                    <p class="text-xs text-white/80 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-white/95" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Berkas tidak sesuai kriteria
                    </p>
                </div>
            </a>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white rounded-2xl shadow-sm border border-slate-200 p-5 md:p-6 mb-1">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Manajemen Pengajuan Bantuan</h2>
                <p class="text-xs text-slate-400 mt-1">Kelola data pengajuan bantuan dan pembiayaan UMKM (Super Admin)</p>
            </div>
            <button onclick="openModalCreate()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold shadow-md shadow-blue-500/10 hover:shadow-lg transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Pengajuan
            </button>
        </div>

        <form method="GET" action="{{ route('superadmin.pengajuan') }}" id="filterForm">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 md:p-6">
                <div class="flex justify-between items-center mb-4 border-b pb-3">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        Pencarian dan Filter Bantuan
                    </h3>
                    <a href="{{ route('superadmin.pengajuan') }}"
                        class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors">Reset Filter</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                    
                    <div class="lg:col-span-2 relative">
                        <input type="text" name="search" placeholder="Cari nama usaha, pendaftaran, pemilik..."
                            value="{{ request('search') }}"
                            class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>

                    <select name="jenis_pengajuan"
                        class="border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        <option value="">Semua Jenis Bantuan</option>
                        <option value="pembiayaan" {{ request('jenis_pengajuan') == 'pembiayaan' ? 'selected' : '' }}>
                            Pembiayaan</option>
                        <option value="bantuan" {{ request('jenis_pengajuan') == 'bantuan' ? 'selected' : '' }}>Bantuan
                            Peralatan</option>
                        <option value="perizinan" {{ request('jenis_pengajuan') == 'perizinan' ? 'selected' : '' }}>
                            Perizinan / NIB</option>
                        <option value="lainnya" {{ request('jenis_pengajuan') == 'lainnya' ? 'selected' : '' }}>Lainnya
                        </option>
                    </select>

                    <select name="kelurahan"
                        class="border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        <option value="">Semua Kelurahan</option>
                        @foreach ($kelurahans as $kelurahanItem)
                            <option value="{{ $kelurahanItem }}"
                                {{ request('kelurahan') == $kelurahanItem ? 'selected' : '' }}>
                                {{ $kelurahanItem }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status"
                        class="border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        <option value="">Semua Status</option>
                        <option value="menunggu"
                            {{ request('status', request('status_verifikasi')) == 'menunggu' ? 'selected' : '' }}>Menunggu
                        </option>
                        <option value="proses"
                            {{ request('status', request('status_verifikasi')) == 'proses' ? 'selected' : '' }}>Diproses
                        </option>
                        <option value="disetujui"
                            {{ request('status', request('status_verifikasi')) == 'disetujui' ? 'selected' : '' }}>
                            Disetujui</option>
                        <option value="dicairkan"
                            {{ request('status', request('status_verifikasi')) == 'dicairkan' ? 'selected' : '' }}>
                            Dicairkan</option>
                        <option value="ditolak"
                            {{ request('status', request('status_verifikasi')) == 'ditolak' ? 'selected' : '' }}>Ditolak
                        </option>
                    </select>

                    <div class="flex gap-2">
                        <select name="per_page"
                            class="flex-1 border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 / hal</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 / hal</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 / hal</option>
                        </select>
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 active:scale-95 transition-all shadow-md shadow-blue-600/10">
                            Filter
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div id="table-container">
                @include('superadmin.pengajuan.table-data')
            </div>
        </div>
    </div>

    <div id="modalDetail" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            
            <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeModalDetail()">
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-100 animate-fade-in-up">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800" id="modalDetailTitle">Detail Pengajuan Bantuan</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Peninjauan berkas permohonan bantuan UMKM</p>
                    </div>
                    <button onclick="closeModalDetail()"
                        class="text-slate-400 hover:text-slate-600 p-1.5 hover:bg-slate-200 rounded-2xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="px-6 py-5 max-h-[70vh] overflow-y-auto" id="modalDetailContent">
                    
                    <div class="flex flex-col items-center justify-center py-10 space-y-3">
                        <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin">
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Memuat data pengajuan...</p>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end gap-3"
                    id="modalDetailActions">
                    <button onclick="closeModalDetail()"
                        class="px-4 py-2 border border-slate-200 text-slate-700 bg-white rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pengajuan -->
    <div id="modalCreate" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeModalCreate()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 animate-fade-in-up">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Tambah Pengajuan Bantuan</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Daftarkan bantuan atau pembiayaan baru untuk UMKM (Super Admin)</p>
                    </div>
                    <button onclick="closeModalCreate()" class="text-slate-400 hover:text-slate-600 p-1.5 hover:bg-slate-200 rounded-2xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="formCreate" onsubmit="submitCreate(event)">
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih UMKM Terverifikasi</label>
                            <select id="createUmkmId" required class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                                <option value="">-- Pilih UMKM --</option>
                                @foreach($umkms as $umkm)
                                    <option value="{{ $umkm->id_umkm }}">{{ $umkm->nama_usaha }} ({{ $umkm->pemilik?->nama_lengkap ?? 'Pemilik' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jenis Pengajuan</label>
                                <select id="createJenisPengajuan" required onchange="toggleNominalField()" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                                    <option value="pembiayaan">Pembiayaan</option>
                                    <option value="bantuan">Bantuan</option>
                                    <option value="perizinan">Perizinan</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Pengajuan</label>
                                <input type="date" id="createTanggalPengajuan" required value="{{ date('Y-m-d') }}" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            </div>
                        </div>
                        @php
                            $activePrograms = array_filter(array_map('trim', explode("\n", \App\Models\Setting::get('program_bantuan_list', ''))));
                        @endphp
                        <div id="fieldProgramBantuan" class="hidden">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Program Bantuan Aktif</label>
                            <select id="createNamaProgram" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                                <option value="">-- Pilih Program Bantuan --</option>
                                @foreach($activePrograms as $prog)
                                    <option value="{{ $prog }}">{{ $prog }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="fieldNominal">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nominal Pembiayaan (Rp)</label>
                            <input type="number" id="createNominal" min="0" placeholder="Contoh: 10000000" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Keterangan / Keperluan</label>
                            <textarea id="createKeterangan" rows="3" placeholder="Tulis rincian permohonan bantuan..." class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"></textarea>
                        </div>
                        <div class="border-t border-slate-100 pt-4 mt-2">
                            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Dokumen Lampiran (Optional)
                            </h4>
                            <div class="space-y-3 max-h-48 overflow-y-auto pr-1">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase mb-1">KTP Pemilik</label>
                                    <input type="file" id="createBerkasKtp" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-md p-1 bg-slate-50/50">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase mb-1">Kartu Keluarga</label>
                                    <input type="file" id="createBerkasKk" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-md p-1 bg-slate-50/50">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase mb-1">Foto Tempat Usaha</label>
                                    <input type="file" id="createBerkasFotoUsaha" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-md p-1 bg-slate-50/50">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase mb-1">Izin Usaha / NIB / SKU</label>
                                    <input type="file" id="createBerkasNibSku" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-md p-1 bg-slate-50/50">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase mb-1">Laporan Keuangan</label>
                                    <input type="file" id="createBerkasLaporanKeuangan" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-md p-1 bg-slate-50/50">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase mb-1">Rencana Anggaran Biaya (RAB)</label>
                                    <input type="file" id="createBerkasRab" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-md p-1 bg-slate-50/50">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase mb-1">Foto Produk</label>
                                    <input type="file" id="createBerkasFotoProduk" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-md p-1 bg-slate-50/50">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                        <button type="button" onclick="closeModalCreate()" class="px-4 py-2 border border-slate-200 text-slate-700 bg-white rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" id="btnConfirmCreate" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 active:scale-95 transition-all shadow-md shadow-blue-600/10">Simpan Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalVerify" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeModalVerify()">
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100 animate-fade-in-up">
                <div class="p-6 text-center space-y-4">
                    <div id="verifyIconContainer"
                        class="w-16 h-16 rounded-full flex items-center justify-center mx-auto shadow-inner">
                        
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xl font-bold text-slate-800" id="verifyTitle">Update Status Pengajuan</h3>
                        <p class="text-sm text-slate-700" id="verifyUmkmName">Apakah Anda yakin ingin memproses pengajuan
                            ini?</p>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                    <button onclick="closeModalVerify()"
                        class="px-4 py-2 border border-slate-200 text-slate-700 bg-white rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors">Batal</button>
                    <button id="btnConfirmVerify"
                        class="px-5 py-2 text-white rounded-lg text-sm font-semibold active:scale-95 transition-all shadow-md">Ya,
                        Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalReject" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeModalReject()">
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100 animate-fade-in-up">
                <div class="p-6 space-y-4">
                    <div
                        class="w-16 h-16 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto shadow-inner shadow-rose-500/10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="text-center space-y-1">
                        <h3 class="text-xl font-bold text-slate-800">Tolak Pengajuan Bantuan</h3>
                        <p class="text-sm text-slate-700" id="rejectUmkmName">Mohon berikan alasan penolakan untuk
                            permohonan ini.</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">Alasan Penolakan</label>
                        <textarea id="rejectReason" rows="3"
                            class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none transition-all"
                            placeholder="Contoh: Dokumen NIB kurang jelas atau tidak sesuai."></textarea>
                        <span id="rejectError" class="text-xs text-rose-500 hidden font-semibold">Alasan penolakan wajib
                            diisi!</span>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                    <button onclick="closeModalReject()"
                        class="px-4 py-2 border border-slate-200 text-slate-700 bg-white rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors">Batal</button>
                    <button id="btnConfirmReject"
                        class="px-5 py-2 bg-rose-600 text-white rounded-lg text-sm font-semibold hover:bg-rose-700 active:scale-95 transition-all shadow-md shadow-rose-600/10">Tolak
                        Pengajuan</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>

    <script>
        let activePengajuanId = null;
        let pendingStatusValue = null;

        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filterForm');

            // Dynamic search/filter via AJAX on input or change
            if (filterForm) {
                filterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitFilter();
                });

                // Auto submit filter on selects change
                filterForm.querySelectorAll('select').forEach(select => {
                    select.addEventListener('change', submitFilter);
                });

                // Debounced auto-submit on search input typing (600ms)
                let searchTimeout;
                const searchInput = filterForm.querySelector('input[name="search"]');
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            submitFilter();
                        }, 600);
                    });

                    // Focus and cursor positioning when loading page with query active
                    if (searchInput.value.length > 0) {
                        searchInput.focus();
                        const len = searchInput.value.length;
                        searchInput.setSelectionRange(len, len);
                    }
                }
            }

            // Listen to dynamic pagination clicks inside the table-container
            document.getElementById('table-container').addEventListener('click', function(e) {
                const link = e.target.closest('.pagination a, p.text-sm a');
                if (link) {
                    e.preventDefault();
                    fetchTableData(link.getAttribute('href'));
                }
            });
        });

        function submitFilter() {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();
            const url = form.getAttribute('action') + '?' + params;

            // Update URL path in history smoothly
            window.history.pushState({}, '', url);
            fetchTableData(url);
        }

        function fetchTableData(url) {
            const container = document.getElementById('table-container');
            container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-20 space-y-3">
                <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-sm font-semibold text-slate-700">Mencari data pengajuan...</p>
            </div>
        `;

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        container.innerHTML = data.html;
                    }
                })
                .catch(err => {
                    console.error(err);
                    container.innerHTML = `
                <div class="py-10 text-center text-rose-500 font-semibold">
                    Terjadi kesalahan saat memuat data. Silakan coba kembali.
                </div>
            `;
                });
        }

        // --- Modal Detail ---
        function showDetail(id) {
            const modal = document.getElementById('modalDetail');
            const content = document.getElementById('modalDetailContent');
            const actions = document.getElementById('modalDetailActions');

            // Open modal and show spinner
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            content.innerHTML = `
            <div class="flex flex-col items-center justify-center py-20 space-y-3">
                <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-sm font-semibold text-slate-700">Memuat berkas pengajuan...</p>
            </div>
        `;

            // Fetch dynamic modal HTML from endpoint
            fetch(`/superadmin/pengajuan/${id}/modal`)
                .then(res => res.text())
                .then(html => {
                    content.innerHTML = html;

                    // Check if the submission is open for actions
                    const isOpen = html.includes('Menunggu') || html.includes('Diproses');
                    const namaUsaha = content.querySelector('.nama-usaha-value')?.textContent?.trim() || 'UMKM';

                    if (isOpen) {
                        const isMenunggu = html.includes('Menunggu');
                        let buttons =
                            `<button onclick="closeModalDetail()" class="px-4 py-2 border border-slate-200 text-slate-700 bg-white rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors">Tutup</button>`;

                        if (isMenunggu) {
                            buttons +=
                                `<button onclick="closeModalDetail(); updateStatus('${id}', 'proses', '${namaUsaha}')" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 active:scale-95 transition-all">Proses</button>`;
                        }

                        buttons +=
                            `<button onclick="closeModalDetail(); updateStatus('${id}', 'ditolak', '${namaUsaha}')" class="px-4 py-2 bg-rose-600 text-white rounded-lg text-sm font-semibold hover:bg-rose-700 active:scale-95 transition-all">Tolak</button>`;
                        buttons +=
                            `<button onclick="closeModalDetail(); updateStatus('${id}', 'disetujui', '${namaUsaha}')" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 active:scale-95 transition-all">Setujui</button>`;

                        actions.innerHTML = buttons;
                    } else {
                        actions.innerHTML = `
                    <button onclick="closeModalDetail()" class="px-4 py-2 border border-slate-200 text-slate-700 bg-white rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors">Tutup</button>
                `;
                    }
                })
                .catch(err => {
                    content.innerHTML = `
                <div class="py-10 text-center text-rose-500 font-semibold">
                    Gagal memuat detail pengajuan. Silakan coba lagi.
                </div>
            `;
                });
        }

        function closeModalDetail() {
            document.getElementById('modalDetail').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // --- Modal Status Actions (Proses, Setuju, Tolak) ---
        function updateStatus(id, newStatus, name) {
            activePengajuanId = id;
            pendingStatusValue = newStatus;

            if (newStatus === 'ditolak') {
                document.getElementById('rejectUmkmName').textContent =
                    `Mohon berikan alasan lengkap penolakan untuk permohonan usaha "${name}".`;
                document.getElementById('rejectReason').value = '';
                document.getElementById('rejectError').classList.add('hidden');
                document.getElementById('modalReject').classList.remove('hidden');

                document.getElementById('btnConfirmReject').onclick = submitStatusUpdate;
            } else {
                const title = document.getElementById('verifyTitle');
                const desc = document.getElementById('verifyUmkmName');
                const iconContainer = document.getElementById('verifyIconContainer');
                const confirmBtn = document.getElementById('btnConfirmVerify');

                if (newStatus === 'proses') {
                    title.textContent = "Proses Pengajuan";
                    desc.textContent = `Apakah Anda yakin ingin mengubah status pengajuan "${name}" menjadi "Diproses"?`;
                    iconContainer.className =
                        "w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto shadow-inner shadow-blue-500/10";
                    iconContainer.innerHTML = `
                    <svg class="w-8 h-8 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89l-2.286 2.287M21 4v6h-6"></path>
                    </svg>
                `;
                    confirmBtn.className =
                        "px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 active:scale-95 transition-all shadow-md shadow-blue-600/10";
                } else if (newStatus === 'disetujui') {
                    title.textContent = "Setujui Pengajuan Bantuan";
                    desc.textContent =
                        `Apakah Anda yakin ingin MENYETUJUI pengajuan permohonan bantuan/pembiayaan "${name}"?`;
                    iconContainer.className =
                        "w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto shadow-inner shadow-emerald-500/10";
                    iconContainer.innerHTML = `
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
                    confirmBtn.className =
                        "px-5 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 active:scale-95 transition-all shadow-md shadow-emerald-600/10";
                }

                document.getElementById('modalVerify').classList.remove('hidden');
                confirmBtn.onclick = submitStatusUpdate;
            }
        }

        function closeModalVerify() {
            document.getElementById('modalVerify').classList.add('hidden');
            activePengajuanId = null;
            pendingStatusValue = null;
        }

        function closeModalReject() {
            document.getElementById('modalReject').classList.add('hidden');
            activePengajuanId = null;
            pendingStatusValue = null;
        }

        function submitStatusUpdate() {
            if (!activePengajuanId || !pendingStatusValue) return;

            let reason = null;
            let confirmBtn = null;

            if (pendingStatusValue === 'ditolak') {
                reason = document.getElementById('rejectReason').value.trim();
                if (!reason) {
                    document.getElementById('rejectError').classList.remove('hidden');
                    return;
                }
                confirmBtn = document.getElementById('btnConfirmReject');
            } else {
                confirmBtn = document.getElementById('btnConfirmVerify');
            }

            confirmBtn.disabled = true;
            confirmBtn.innerHTML =
                `<span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>`;

            setTimeout(() => {
                fetch(`/superadmin/pengajuan/${activePengajuanId}/status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            status: pendingStatusValue,
                            reason: reason
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (pendingStatusValue === 'ditolak') {
                            closeModalReject();
                        } else {
                            closeModalVerify();
                        }

                        if (data.success) {
                            let toastType = 'success';
                            let toastTitle = 'Berhasil!';
                            if (pendingStatusValue === 'proses') {
                                toastType = 'info';
                                toastTitle = 'Diproses!';
                            } else if (pendingStatusValue === 'ditolak') {
                                toastType = 'warning';
                                toastTitle = 'Ditolak!';
                            }

                            if (window.showToast) {
                                window.showToast(toastType, toastTitle, data.message ||
                                    'Status pengajuan berhasil diperbarui.');
                            }
                            // Refresh list
                            submitFilter();
                        } else {
                            if (window.showToast) {
                                window.showToast('error', 'Gagal!', data.message || 'Gagal memperbarui status.');
                            }
                        }
                    })
                    .catch(err => {
                        if (pendingStatusValue === 'ditolak') {
                            closeModalReject();
                        } else {
                            closeModalVerify();
                        }
                        console.error(err);
                        if (window.showToast) {
                            window.showToast('error', 'Kesalahan!', 'Terjadi gangguan sistem. Silakan coba lagi.');
                        }
                    });
            }, 1000);
        }

        function openModalCreate() {
            document.getElementById('modalCreate').classList.remove('hidden');
            toggleNominalField();
        }

        function closeModalCreate() {
            document.getElementById('modalCreate').classList.add('hidden');
            document.getElementById('formCreate').reset();
            toggleNominalField();
        }

        function toggleNominalField() {
            const jp = document.getElementById('createJenisPengajuan').value;
            const field = document.getElementById('fieldNominal');
            const input = document.getElementById('createNominal');
            if (jp === 'pembiayaan') {
                field.classList.remove('hidden');
                input.required = true;
            } else {
                field.classList.add('hidden');
                input.required = false;
                input.value = '';
            }

            const pField = document.getElementById('fieldProgramBantuan');
            const pSelect = document.getElementById('createNamaProgram');
            if (jp === 'bantuan') {
                pField.classList.remove('hidden');
                pSelect.required = true;
            } else {
                pField.classList.add('hidden');
                pSelect.required = false;
                pSelect.value = '';
            }
        }

        function submitCreate(e) {
            e.preventDefault();
            const btn = document.getElementById('btnConfirmCreate');
            btn.disabled = true;
            const originalText = btn.innerHTML;
            btn.innerHTML = `<span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>`;

            const formData = new FormData();
            formData.append('umkm_id', document.getElementById('createUmkmId').value);
            formData.append('jenis_pengajuan', document.getElementById('createJenisPengajuan').value);
            formData.append('nama_program', document.getElementById('createNamaProgram').value);
            formData.append('nominal', document.getElementById('createNominal').value);
            formData.append('tanggal_pengajuan', document.getElementById('createTanggalPengajuan').value);
            formData.append('keterangan', document.getElementById('createKeterangan').value);

            const fileFields = {
                'berkas_ktp': 'createBerkasKtp',
                'berkas_kk': 'createBerkasKk',
                'berkas_foto_usaha': 'createBerkasFotoUsaha',
                'berkas_nib_sku': 'createBerkasNibSku',
                'berkas_laporan_keuangan': 'createBerkasLaporanKeuangan',
                'berkas_rab': 'createBerkasRab',
                'berkas_foto_produk': 'createBerkasFotoProduk'
            };

            for (const [key, id] of Object.entries(fileFields)) {
                const el = document.getElementById(id);
                if (el && el.files && el.files[0]) {
                    formData.append(key, el.files[0]);
                }
            }

            fetch(`/superadmin/pengajuan`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => { throw err; });
                }
                return res.json();
            })
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                closeModalCreate();
                if (data.success) {
                    if (window.showToast) {
                        window.showToast('success', 'Berhasil!', data.message || 'Pengajuan Bantuan berhasil ditambahkan.');
                    }
                    submitFilter();
                } else {
                    if (window.showToast) {
                        window.showToast('error', 'Gagal!', data.message || 'Gagal menambahkan pengajuan.');
                    }
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                console.error(err);
                let msg = 'Terjadi gangguan sistem. Silakan coba lagi.';
                if (err.errors) {
                    msg = Object.values(err.errors).flat().join('\n');
                } else if (err.message) {
                    msg = err.message;
                }
                if (window.showToast) {
                    window.showToast('error', 'Gagal!', msg);
                }
            });
        }
    </script>
@endsection
