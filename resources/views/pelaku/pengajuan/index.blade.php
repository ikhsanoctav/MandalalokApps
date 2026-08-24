@extends('layouts.pelaku')
@section('title', 'Pengajuan Bantuan UMKM')

@section('content')
    <div class="space-y-8 animate-fade-in pb-12">

        <!-- Premium Banner Header -->
        <div class="relative overflow-hidden rounded-[2.5rem] bg-white/40 backdrop-blur-xl border border-white/60 shadow-2xl shadow-blue-200/50 p-8 md:p-12 mb-10">
            <!-- Decorative Backgrounds -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-64 h-64 bg-gradient-to-tr from-emerald-400 to-teal-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s;"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/60 border border-white/80 text-xs font-black tracking-widest uppercase mb-6 text-blue-600 shadow-sm backdrop-blur-md">
                        <i class="mdi mdi-hand-heart-outline text-base"></i> Pusat Pengajuan Bantuan
                    </div>
                    <h1 class="text-4xl md:text-5xl font-extrabold text-slate-800 tracking-tight drop-shadow-sm mb-4">
                        Riwayat & Pengajuan <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Bantuan</span>
                    </h1>
                    <p class="text-slate-600 text-lg max-w-2xl font-medium leading-relaxed">
                        Pantau status permohonan modal, pembiayaan, atau perizinan secara real-time untuk mendukung kemajuan usaha Anda.
                    </p>
                </div>
                
                @if($umkms->isNotEmpty())
                    <button onclick="openModalCreate()" class="shrink-0 group relative inline-flex items-center justify-center gap-2 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white text-base font-bold rounded-2xl transition-all shadow-xl shadow-blue-200 hover:shadow-blue-300 hover:-translate-y-1 overflow-hidden">
                        <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
                        <i class="mdi mdi-plus-circle-outline text-2xl"></i> Buat Pengajuan Baru
                    </button>
                @endif
            </div>
        </div>

        @if($umkms->isEmpty())
            <!-- Empty State for UMKMs -->
            <div class="bg-indigo-50/50 rounded-[2rem] border border-indigo-100 border-dashed p-16 text-center">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-md border border-indigo-50">
                    <i class="mdi mdi-store-remove text-5xl text-amber-500"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 mb-3">Belum Ada UMKM Terverifikasi</h3>
                <p class="text-base text-slate-500 max-w-lg mx-auto leading-relaxed mb-8">
                    Fitur pengajuan bantuan mandiri hanya terbuka untuk pemilik usaha dengan UMKM aktif dan terverifikasi otomatis. Daftarkan usaha Anda terlebih dahulu.
                </p>
                <a href="{{ route('pelaku.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 hover:border-indigo-300 hover:text-indigo-600 text-slate-700 font-bold rounded-xl transition-all shadow-sm">
                    <i class="mdi mdi-arrow-left"></i> Kembali ke Dasbor
                </a>
            </div>
        @else

            @php
            $activeFilters = array_filter([
                request('search'), request('jenis_pengajuan'), request('status'),
                request('umkm_id'), request('date_from'), request('date_to'),
            ]);
        @endphp

        {{-- ===== FILTER BAR ===== --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-[1.75rem] border border-white/80 shadow-lg shadow-slate-200/40 p-6 mb-6">
            <form method="GET" action="{{ route('pelaku.pengajuan.index') }}" id="filterForm">
                <div class="flex flex-col gap-4">

                    {{-- Row 1: Search + Reset --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                <i class="mdi mdi-magnify text-lg"></i>
                            </div>
                            <input
                                type="text"
                                name="search"
                                id="searchInput"
                                value="{{ request('search') }}"
                                placeholder="Cari nama usaha, program, keterangan..."
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all"
                            >
                        </div>
                        <button type="button" onclick="window.triggerFilter()" class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-md whitespace-nowrap">
                            <i class="mdi mdi-magnify"></i> Cari
                        </button>
                        @if(count($activeFilters) > 0)
                            <a href="{{ route('pelaku.pengajuan.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-rose-50 border border-rose-200 text-rose-600 text-sm font-bold rounded-xl hover:bg-rose-100 transition-all whitespace-nowrap">
                                <i class="mdi mdi-filter-off-outline"></i> Reset
                            </a>
                        @endif
                    </div>

                    {{-- Row 2: Filter dropdowns --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">

                        {{-- Jenis Pengajuan --}}
                        <div class="relative">
                            <select name="jenis_pengajuan" onchange="window.triggerFilter()" class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 px-4 py-3 pr-9 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all cursor-pointer {{ request('jenis_pengajuan') ? 'border-blue-400 bg-blue-50 text-blue-700' : '' }}">
                                <option value="">Semua Jenis</option>
                                <option value="pembiayaan" {{ request('jenis_pengajuan') === 'pembiayaan' ? 'selected' : '' }}>💰 Pembiayaan</option>
                                <option value="bantuan"    {{ request('jenis_pengajuan') === 'bantuan'    ? 'selected' : '' }}>🤝 Bantuan</option>
                                <option value="perizinan"  {{ request('jenis_pengajuan') === 'perizinan'  ? 'selected' : '' }}>📋 Perizinan</option>
                                <option value="lainnya"    {{ request('jenis_pengajuan') === 'lainnya'    ? 'selected' : '' }}>📌 Lainnya</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400"><i class="mdi mdi-chevron-down"></i></div>
                        </div>

                        {{-- Status --}}
                        <div class="relative">
                            <select name="status" onchange="window.triggerFilter()" class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 px-4 py-3 pr-9 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all cursor-pointer {{ request('status') ? 'border-blue-400 bg-blue-50 text-blue-700' : '' }}">
                                <option value="">Semua Status</option>
                                <option value="menunggu"   {{ request('status') === 'menunggu'   ? 'selected' : '' }}>⏳ Menunggu</option>
                                <option value="diproses"   {{ request('status') === 'diproses'   ? 'selected' : '' }}>🔄 Diproses</option>
                                <option value="disetujui"  {{ request('status') === 'disetujui'  ? 'selected' : '' }}>✅ Disetujui</option>
                                <option value="ditolak"    {{ request('status') === 'ditolak'    ? 'selected' : '' }}>❌ Ditolak</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400"><i class="mdi mdi-chevron-down"></i></div>
                        </div>

                        {{-- Filter UMKM --}}
                        @if($umkms->count() > 1)
                        <div class="relative">
                            <select name="umkm_id" onchange="window.triggerFilter()" class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 px-4 py-3 pr-9 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all cursor-pointer {{ request('umkm_id') ? 'border-blue-400 bg-blue-50 text-blue-700' : '' }}">
                                <option value="">Semua Usaha</option>
                                @foreach($umkms as $u)
                                    <option value="{{ $u->id_umkm }}" {{ request('umkm_id') == $u->id_umkm ? 'selected' : '' }}>{{ $u->nama_usaha }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400"><i class="mdi mdi-chevron-down"></i></div>
                        </div>
                        @endif

                        {{-- Date From --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400 text-xs font-bold">Dari</div>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" onchange="window.triggerFilter()"
                                class="w-full pl-10 pr-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all {{ request('date_from') ? 'border-blue-400 bg-blue-50 text-blue-700' : '' }}">
                        </div>

                        {{-- Date To --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400 text-xs font-bold">S/d</div>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" onchange="window.triggerFilter()"
                                class="w-full pl-10 pr-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all {{ request('date_to') ? 'border-blue-400 bg-blue-50 text-blue-700' : '' }}">
                        </div>
                    </div>

                    {{-- Row 3: Summary + per-page --}}
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-1 border-t border-slate-100">
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <i class="mdi mdi-format-list-bulleted text-blue-400"></i>
                            Menampilkan <span class="font-black text-slate-700">{{ $pengajuans->firstItem() ?? 0 }}–{{ $pengajuans->lastItem() ?? 0 }}</span>
                            dari <span class="font-black text-blue-600">{{ $pengajuans->total() }}</span> pengajuan
                            @if(count($activeFilters) > 0)
                                &mdash; <span class="text-blue-600 font-bold">{{ count($activeFilters) }} filter aktif</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-500 font-semibold whitespace-nowrap">Tampilkan:</span>
                            <select name="per_page" onchange="window.triggerFilter()" class="appearance-none bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 px-3 py-2 pr-7 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all cursor-pointer">
                                @foreach([10, 20, 50] as $pp)
                                    <option value="{{ $pp }}" {{ request('per_page', 10) == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                                @endforeach
                            </select>
                            <div class="relative -ml-7 pointer-events-none text-slate-400"><i class="mdi mdi-chevron-down text-sm"></i></div>
                        </div>
                    </div>

                </div>
            </form>
        </div>

        <div id="table-container">
            @include('pelaku.pengajuan.table-data')
        </div>
        @endif

    </div>

    @if($umkms->isNotEmpty())
        <!-- Modern Modal Create -->
        <div id="modalCreate" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModalCreate()" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-100 animate-fade-in-up">
                    
                    <div class="bg-white/80 backdrop-blur-md px-8 py-6 border-b border-slate-100 flex items-center justify-between sticky top-0 z-20">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                                <i class="mdi mdi-file-document-edit-outline text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-800 tracking-tight">Form Pengajuan Bantuan</h3>
                                <p class="text-xs font-bold text-slate-400 mt-0.5">Lengkapi form permohonan di bawah ini</p>
                            </div>
                        </div>
                        <button onclick="closeModalCreate()" class="text-slate-400 hover:text-rose-500 bg-slate-50 hover:bg-rose-50 rounded-full p-2.5 transition-all">
                            <i class="mdi mdi-close text-xl"></i>
                        </button>
                    </div>

                    <form id="formCreate" onsubmit="submitCreate(event)" class="bg-slate-50/50">
                        <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                            
                            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-3">Pilih Usaha UMKM Anda <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select id="createUmkmId" required class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-bold text-base rounded-lg focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 p-4 appearance-none transition-all cursor-pointer">
                                        <option value="">-- Silakan Pilih UMKM --</option>
                                        @foreach($umkms as $umkm)
                                            <option value="{{ $umkm->id_umkm }}">{{ $umkm->nama_usaha }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                        <i class="mdi mdi-chevron-down text-xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-3">Jenis Pengajuan <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <select id="createJenisPengajuan" required onchange="toggleNominalField()" class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-bold text-base rounded-lg focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 p-4 appearance-none transition-all cursor-pointer">
                                            <option value="pembiayaan">Pembiayaan</option>
                                            <option value="bantuan">Bantuan</option>
                                            <option value="perizinan">Perizinan</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                            <i class="mdi mdi-chevron-down text-xl"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-3">Tanggal Pengajuan <span class="text-rose-500">*</span></label>
                                    <input type="date" id="createTanggalPengajuan" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-bold text-base rounded-lg focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 p-4 transition-all">
                                </div>
                            </div>

                            @php
                                $activePrograms = array_filter(array_map('trim', explode("\n", \App\Models\Setting::get('program_bantuan_list', ''))));
                            @endphp
                            
                            <div id="fieldProgramBantuan" class="hidden bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-3">Program Bantuan Aktif <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select id="createNamaProgram" class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-bold text-base rounded-lg focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 p-4 appearance-none transition-all cursor-pointer">
                                        <option value="">-- Pilih Program Bantuan --</option>
                                        @foreach($activePrograms as $prog)
                                            <option value="{{ $prog }}">{{ $prog }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                        <i class="mdi mdi-chevron-down text-xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div id="fieldNominal" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-3">Nominal Pembiayaan (Rp) <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none font-black text-slate-400">Rp</div>
                                    <input type="number" id="createNominal" min="0" placeholder="10000000" class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-black font-mono text-xl rounded-lg focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 p-4 pl-12 transition-all">
                                </div>
                            </div>

                            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-3">Keterangan / Keperluan Usaha</label>
                                <textarea id="createKeterangan" rows="3" placeholder="Tulis rincian peruntukan permohonan bantuan..." class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 p-4 transition-all resize-none"></textarea>
                            </div>

                            <!-- Lampiran Dokumen Area -->
                            <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100">
                                <h4 class="text-sm font-black text-blue-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="mdi mdi-paperclip text-xl"></i> Upload Lampiran Dokumen <span class="text-xs text-blue-600 font-bold lowercase tracking-normal">(Opsional)</span>
                                </h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @php
                                        $dokumens = [
                                            ['id' => 'createBerkasKtp', 'label' => 'KTP Pemilik'],
                                            ['id' => 'createBerkasKk', 'label' => 'Kartu Keluarga'],
                                            ['id' => 'createBerkasFotoUsaha', 'label' => 'Foto Tempat Usaha'],
                                            ['id' => 'createBerkasNibSku', 'label' => 'Izin Usaha / NIB'],
                                            ['id' => 'createBerkasLaporanKeuangan', 'label' => 'Laporan Keuangan'],
                                            ['id' => 'createBerkasRab', 'label' => 'RAB (Anggaran)'],
                                            ['id' => 'createBerkasFotoProduk', 'label' => 'Foto Produk']
                                        ];
                                    @endphp

                                    @foreach($dokumens as $dok)
                                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm hover:border-blue-300 transition-colors">
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider mb-1">{{ $dok['label'] }}</label>
                                        <input type="file" id="{{ $dok['id'] }}" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Sticky Footer -->
                        <div class="bg-white px-8 py-5 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-3 sticky bottom-0 z-20">
                            <button type="button" onclick="closeModalCreate()" class="px-6 py-3 border-2 border-slate-200 text-slate-700 bg-white rounded-xl text-base font-bold hover:bg-slate-50 transition-colors">Batalkan</button>
                            <button type="submit" id="btnConfirmCreate" class="px-8 py-3 bg-blue-600 text-white rounded-xl text-base font-bold hover:bg-blue-700 active:scale-95 transition-all shadow-lg shadow-blue-200 flex items-center justify-center gap-2">
                                <i class="mdi mdi-send"></i> Kirim Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<style>
    @keyframes shimmer { 100% { transform: translateX(100%); } }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
</style>
    <script>
        function openModalCreate() {
            document.getElementById('modalCreate').classList.remove('hidden');
            toggleNominalField();
            document.body.style.overflow = 'hidden'; // prevent scrolling behind modal
        }

        function closeModalCreate() {
            document.getElementById('modalCreate').classList.add('hidden');
            document.getElementById('formCreate').reset();
            toggleNominalField();
            document.body.style.overflow = 'auto';
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
            btn.innerHTML = `<i class="mdi mdi-loading mdi-spin text-xl"></i> Memproses...`;

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

            fetch(`/pelaku/pengajuan`, {
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
                    setTimeout(() => {
                        window.triggerFilter();
                    }, 500);
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
            const form = document.getElementById('filterForm');
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

        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', debounce(() => window.triggerFilter(), 500));
        }

        function attachPaginationListeners() {
            document.querySelectorAll('.pagination a').forEach(link => {
                const newLink = link.cloneNode(true);
                link.parentNode.replaceChild(newLink, link);
                
                newLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    const form = document.getElementById('filterForm');
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
