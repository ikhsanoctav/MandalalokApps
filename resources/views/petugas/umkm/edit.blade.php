@extends('layouts.petugas')

@section('title', 'Edit Data UMKM')
@section('breadcrumb', 'Edit UMKM')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
<style>
    .leaflet-container { z-index: 10 !important; border-radius: 1rem; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
    <div class="w-full space-y-8 pb-12">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-600 shadow-inner">
                    <i class="mdi mdi-store-edit text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Edit Data UMKM</h2>
                    <p class="text-slate-700 mt-1 font-medium">Perbarui informasi pemilik dan profil usaha UMKM.</p>
                </div>
            </div>
            <a href="{{ route('operator.dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-slate-200 hover:border-indigo-300 hover:text-indigo-600 text-slate-700 font-bold rounded-lg transition-all shadow-sm">
                <i class="mdi mdi-arrow-left"></i> Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-rose-50 p-6 rounded-2xl border-2 border-rose-200 flex items-start gap-4">
                <div class="w-10 h-10 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center shrink-0">
                    <i class="mdi mdi-alert text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-rose-800 text-lg mb-2">Terjadi Kesalahan Validasi</h3>
                    <ul class="list-disc pl-5 text-sm space-y-1 text-rose-700 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('operator.umkm.update', $umkm->id_umkm) }}" method="POST" enctype="multipart/form-data" class="space-y-8 relative">
            @csrf
            @method('PUT')

            <!-- Card 1: Data Pemilik -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white/80 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none transform translate-x-1/2 -translate-y-1/2"></div>
                
                <div class="px-8 pt-8 pb-6 border-b border-slate-100 relative z-10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 font-black">1</div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Data Pemilik UMKM</h3>
                        <p class="text-xs text-slate-700 font-medium mt-1">Jika NIK sudah terdaftar, data pemilik akan dihubungkan otomatis.</p>
                    </div>
                </div>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2 flex items-center">
                            <span>Nomor Induk Kependudukan (NIK) <span class="text-rose-500">*</span></span>
                            <span id="nik-loading" class="hidden ml-3 text-xs text-indigo-600 italic flex items-center font-normal">
                                <i class="mdi mdi-loading mdi-spin mr-1 text-base"></i> Sedang mencari data...
                            </span>
                        </label>
                        <input type="text" name="nik" value="{{ old('nik', $umkm->pemilik?->nik ?? '') }}" required maxlength="16" placeholder="Masukkan 16 digit NIK"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-bold text-lg rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 block p-4 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap (Sesuai KTP) <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $umkm->pemilik?->nama_lengkap ?? '') }}" required placeholder="Nama Lengkap"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 block p-4 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="jenis_kelamin" required class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-700 font-medium text-base rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 block p-4 appearance-none transition-all cursor-pointer">
                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin', $umkm->pemilik?->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $umkm->pemilik?->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400"><i class="mdi mdi-chevron-down text-xl"></i></div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $umkm->pemilik?->tempat_lahir ?? '') }}" placeholder="Contoh: Bandung"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 block p-4 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', ($umkm->pemilik && $umkm->pemilik?->tanggal_lahir) ? $umkm->pemilik?->tanggal_lahir->format('Y-m-d') : '') }}"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-700 font-medium text-base rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 block p-4 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nomor HP / WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $umkm->pemilik?->no_hp ?? '') }}" placeholder="08123456789"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 block p-4 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Email Pemilik</label>
                        <input type="email" name="email_pemilik" value="{{ old('email_pemilik', $umkm->pemilik?->email ?? '') }}" placeholder="email@contoh.com"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 block p-4 transition-all">
                    </div>

                    <div x-data="umkmWilayahDropdown()" @autofill-wilayah.window="
                        selectedKel = $event.detail.kelurahan || '';
                        $nextTick(() => {
                            selectedRw = normalizeWilayah($event.detail.rw || '');
                            $nextTick(() => {
                                selectedRt = normalizeWilayah($event.detail.rt || '');
                            });
                        });
                    " class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4 border-t border-slate-100 pt-6 mt-2">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kelurahan <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <select name="kelurahan" x-model="selectedKel" @change="if ($event.isTrusted) { selectedRw = ''; selectedRt = '' }" required
                                    class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-700 font-medium text-base rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 block p-4 appearance-none transition-all cursor-pointer">
                                    <option value="">Pilih Kelurahan</option>
                                    <template x-for="kel in kelurahansData" :key="kel.id">
                                        <option :value="kel.nama_kelurahan" x-text="kel.nama_kelurahan"></option>
                                    </template>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400"><i class="mdi mdi-chevron-down text-xl"></i></div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">RW</label>
                            <div class="relative">
                                <select name="rw" x-model="selectedRw" @change="if ($event.isTrusted) { selectedRt = '' }"
                                    class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-700 font-medium text-base rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 block p-4 appearance-none transition-all cursor-pointer">
                                    <option value="">Pilih RW</option>
                                    <template x-for="rw in currentRws" :key="rw.id">
                                        <option :value="normalizeWilayah(rw.nomor_rw)" x-text="'RW ' + String(rw.nomor_rw).padStart(3, '0')"></option>
                                    </template>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400"><i class="mdi mdi-chevron-down text-xl"></i></div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">RT</label>
                            <div class="relative">
                                <select name="rt" x-model="selectedRt"
                                    class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-700 font-medium text-base rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 block p-4 appearance-none transition-all cursor-pointer">
                                    <option value="">Pilih RT</option>
                                    <template x-for="rt in currentRts" :key="rt.id">
                                        <option :value="normalizeWilayah(rt.nomor_rt)" x-text="'RT ' + String(rt.nomor_rt).padStart(3, '0')"></option>
                                    </template>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400"><i class="mdi mdi-chevron-down text-xl"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap Pemilik <span class="text-rose-500">*</span></label>
                        <textarea name="alamat_pemilik" required rows="2" placeholder="Nama Jalan, Nomor Rumah, dll"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 block p-4 transition-all resize-none">{{ old('alamat_pemilik', $umkm->pemilik?->alamat ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Card 2: Identitas & Profil Usaha -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white/80 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none transform translate-x-1/2 -translate-y-1/2"></div>
                
                <div class="px-8 pt-8 pb-6 border-b border-slate-100 relative z-10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 font-black">2</div>
                    <h3 class="text-xl font-bold text-slate-800">Informasi Usaha (UMKM)</h3>
                </div>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Usaha / Merek <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_usaha" value="{{ old('nama_usaha', $umkm->nama_usaha ?? '') }}" required placeholder="Contoh: Warung Makan Budi"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-bold text-lg rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 transition-all">
                    </div>

                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 p-6 rounded-2xl bg-slate-50/50 border-2 border-slate-100" x-data="{
                        omset: '{{ old('perkiraan_omset', $umkm->perkiraan_omset ?? '') }}',
                        kategoriMap: {
                            @foreach($kategoris ?? $kategori ?? \App\Models\KategoriUMKM::all() as $kat)
                                '{{ strtolower($kat->nama_kategori) }}': '{{ $kat->id }}',
                            @endforeach
                        },
                        get kategoriName() {
                            let val = parseInt(this.omset);
                            if (isNaN(val)) return '';
                            if (val <= {{ \App\Models\Setting::get('kategori_mikro_max_omset', 10000000) }}) return 'mikro';
                            if (val <= {{ \App\Models\Setting::get('kategori_kecil_max_omset', 50000000) }}) return 'kecil';
                            return 'menengah';
                        },
                        get kategoriId() {
                            return this.kategoriMap[this.kategoriName] || '';
                        },
                        get kategoriDisplay() {
                            if (!this.kategoriName) return 'Pilih Perkiraan Omset Dahulu';
                            return 'Usaha ' + this.kategoriName.charAt(0).toUpperCase() + this.kategoriName.slice(1);
                        }
                    }">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Perkiraan Omset Bulanan <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-700 font-bold">
                                    Rp
                                </div>
                                <input type="number" name="perkiraan_omset" x-model="omset" required
                                    placeholder="Contoh: 5000000" min="0" step="1000"
                                    class="w-full bg-white border-2 border-slate-200 text-slate-700 font-medium text-base rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block pl-12 p-4 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kategori Usaha (Otomatis)</label>
                            <input type="hidden" name="id_kategori" :value="kategoriId">
                            <div class="px-5 py-4 rounded-xl border-2 border-indigo-200 bg-indigo-50 text-indigo-700 font-black text-base flex items-center gap-3">
                                <i class="mdi mdi-check-decagram text-xl"></i>
                                <span x-text="kategoriDisplay"></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Sektor Usaha <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="id_sektor" required
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-700 font-medium text-base rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 appearance-none transition-all cursor-pointer">
                                <option value="" disabled selected>Pilih Sektor...</option>
                                @foreach ($sektors ?? $sektor ?? \App\Models\SektorUMKM::all() as $sek)
                                    <option value="{{ $sek->id }}" {{ old('id_sektor', $umkm->id_sektor ?? '') == $sek->id ? 'selected' : '' }}>{{ $sek->nama_sektor }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400"><i class="mdi mdi-chevron-down text-xl"></i></div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Bentuk Jualan <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="bentuk_jualan" required
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-700 font-medium text-base rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 appearance-none transition-all cursor-pointer">
                                <option value="" disabled selected>Pilih Bentuk Jualan</option>
                                <option value="Toko Fisik" {{ old('bentuk_jualan', $umkm->bentuk_jualan ?? '') == 'Toko Fisik' ? 'selected' : '' }}>Toko Fisik</option>
                                <option value="Toko Online" {{ old('bentuk_jualan', $umkm->bentuk_jualan ?? '') == 'Toko Online' ? 'selected' : '' }}>Toko Online</option>
                                <option value="Hybrid (Fisik & Online)" {{ old('bentuk_jualan', $umkm->bentuk_jualan ?? '') == 'Hybrid (Fisik & Online)' ? 'selected' : '' }}>Hybrid (Fisik & Online)</option>
                                <option value="Keliling" {{ old('bentuk_jualan', $umkm->bentuk_jualan ?? '') == 'Keliling' ? 'selected' : '' }}>Keliling</option>
                                <option value="Rumahan / Pre-order" {{ old('bentuk_jualan', $umkm->bentuk_jualan ?? '') == 'Rumahan / Pre-order' ? 'selected' : '' }}>Rumahan / Pre-order</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400"><i class="mdi mdi-chevron-down text-xl"></i></div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Status Usaha</label>
                        <div class="relative">
                            <select name="status_usaha"
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-700 font-medium text-base rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 appearance-none transition-all cursor-pointer">
                                <option value="aktif" {{ old('status_usaha', $umkm->status_usaha ?? '') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="non_aktif" {{ old('status_usaha', $umkm->status_usaha ?? '') == 'non_aktif' ? 'selected' : '' }}>Non Aktif</option>
                                <option value="tutup" {{ old('status_usaha', $umkm->status_usaha ?? '') == 'tutup' ? 'selected' : '' }}>Tutup</option>
                                <option value="pindah" {{ old('status_usaha', $umkm->status_usaha ?? '') == 'pindah' ? 'selected' : '' }}>Pindah</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400"><i class="mdi mdi-chevron-down text-xl"></i></div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tahun Berdiri</label>
                        <input type="number" name="tahun_berdiri" value="{{ old('tahun_berdiri', $umkm->tahun_berdiri ?? '') }}" placeholder="Contoh: 2021" min="1900" max="{{ date('Y') }}"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 transition-all">
                    </div>

                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-slate-100 pt-8 mt-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">No. Izin Usaha / NIB <span class="text-slate-400 font-normal">(Opsional)</span></label>
                            <input type="text" name="no_izin_usaha" value="{{ old('no_izin_usaha', $umkm->no_izin_usaha ?? '') }}" placeholder="Nomor Izin"
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 block p-4 transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Jenis Izin</label>
                            <div class="relative">
                                <select name="jenis_izin" class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-700 font-medium text-base rounded-xl focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 block p-4 appearance-none transition-all cursor-pointer">
                                    <option value="">-- Pilih Jenis Izin --</option>
                                    <option value="NIB" {{ old('jenis_izin', $umkm->jenis_izin ?? '') == 'NIB' ? 'selected' : '' }}>NIB</option>
                                    <option value="SIUP" {{ old('jenis_izin', $umkm->jenis_izin ?? '') == 'SIUP' ? 'selected' : '' }}>SIUP</option>
                                    <option value="SKU" {{ old('jenis_izin', $umkm->jenis_izin ?? '') == 'SKU' ? 'selected' : '' }}>SKU</option>
                                    <option value="Lainnya" {{ old('jenis_izin', $umkm->jenis_izin ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400"><i class="mdi mdi-chevron-down text-xl"></i></div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">NPWP Usaha</label>
                            <input type="text" name="npwp_usaha" value="{{ old('npwp_usaha', $umkm->npwp_usaha ?? '') }}" placeholder="Format: 12.345..."
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 block p-4 transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Telepon Usaha</label>
                            <input type="text" name="telp_usaha" value="{{ old('telp_usaha', $umkm->telp_usaha ?? '') }}" placeholder="022-123456"
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 block p-4 transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Email Usaha</label>
                            <input type="email" name="email_usaha" value="{{ old('email_usaha', $umkm->email_usaha ?? '') }}" placeholder="usaha@domain.com"
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 block p-4 transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Website</label>
                            <input type="text" name="website" value="{{ old('website', $umkm->website ?? '') }}" placeholder="https://..."
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 block p-4 transition-all">
                        </div>
                    </div>

                    <div class="md:col-span-2 border-t border-slate-100 pt-8 mt-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Tempat Usaha <span class="text-rose-500">*</span></label>
                        <textarea name="alamat_usaha" required rows="2" placeholder="Nama Jalan, Blok, RT/RW..."
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 transition-all resize-none">{{ old('alamat_usaha', $umkm->alamat_usaha ?? '') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-3 flex items-center justify-between">
                            <span>Titik Koordinat Lokasi (GPS)</span>
                            <span class="text-xs bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-semibold">Geser pin merah</span>
                        </label>
                        <div class="p-2 border-2 border-slate-200 rounded-[1.5rem] bg-white">
                            <div id="map" class="w-full h-80 rounded-xl z-10"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Latitude</label>
                                <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $umkm->latitude ?? '') }}" readonly class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-slate-50 text-slate-600 text-sm font-mono focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Longitude</label>
                                <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $umkm->longitude ?? '') }}" readonly class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-slate-50 text-slate-600 text-sm font-mono focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Produk/Jasa</label>
                        <textarea name="deskripsi" rows="4" placeholder="Ceritakan tentang keunggulan, produk utama, atau sejarah singkat..."
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 transition-all resize-none">{{ old('deskripsi', $umkm->deskripsi ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Card 3: Data Ketenagakerjaan -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white/80 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-purple-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none transform translate-x-1/2 -translate-y-1/2"></div>
                
                <div class="px-8 pt-8 pb-6 border-b border-slate-100 relative z-10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 font-black">3</div>
                    <h3 class="text-xl font-bold text-slate-800">Data Ketenagakerjaan</h3>
                </div>
                
                <div class="p-8 grid grid-cols-1 sm:grid-cols-3 gap-8 relative z-10">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Total Tenaga Kerja</label>
                        <input type="number" name="jumlah_tenaga_kerja" value="{{ old('jumlah_tenaga_kerja', $umkm->jumlah_tenaga_kerja ?? 0) }}" min="0"
                            class="w-full bg-slate-100 border-2 border-slate-200 text-slate-800 font-black text-xl rounded-xl focus:outline-none block p-4 cursor-not-allowed" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Laki-laki</label>
                        <input type="number" name="tenaga_kerja_laki" value="{{ old('tenaga_kerja_laki', $umkm->tenaga_kerja_laki ?? 0) }}" min="0"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-bold text-lg rounded-xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 block p-4 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Perempuan</label>
                        <input type="number" name="tenaga_kerja_perempuan" value="{{ old('tenaga_kerja_perempuan', $umkm->tenaga_kerja_perempuan ?? 0) }}" min="0"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-bold text-lg rounded-xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 block p-4 transition-all">
                    </div>
                </div>
            </div>

            <!-- Card 4: Riwayat Bantuan & Media Sosial -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Riwayat Bantuan -->
                <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white/80 overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none transform translate-x-1/2 -translate-y-1/2"></div>
                    <div class="px-8 pt-8 pb-6 border-b border-slate-100 relative z-10 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-cyan-100 flex items-center justify-center text-cyan-600 font-black">4</div>
                        <h3 class="text-xl font-bold text-slate-800">Riwayat Bantuan</h3>
                    </div>
                    <div class="p-8 relative z-10">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Pernah menerima bantuan apa sebelumnya?</label>
                        <textarea name="riwayat_bantuan" rows="5" placeholder="Contoh: BPUM 2021, Bantuan Alat 2022... (Kosongkan jika belum pernah)"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-cyan-500/20 focus:border-cyan-500 block p-4 transition-all resize-none">{{ old('riwayat_bantuan', $umkm->riwayat_bantuan ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Media Sosial -->
                <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white/80 overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-amber-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none transform translate-x-1/2 -translate-y-1/2"></div>
                    <div class="px-8 pt-8 pb-6 border-b border-slate-100 relative z-10 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 font-black">5</div>
                        <h3 class="text-xl font-bold text-slate-800">Media Sosial</h3>
                    </div>
                    @php
                        $mediaSosial = [];
                        if (!empty($umkm->media_sosial)) {
                            $mediaSosial = is_string($umkm->media_sosial) ? json_decode($umkm->media_sosial, true) ?: [] : $umkm->media_sosial;
                        }
                    @endphp
                    <div class="p-8 relative z-10 space-y-4">
                        <div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 font-bold"><i class="mdi mdi-facebook text-lg text-blue-600"></i></div>
                                <input type="text" name="media_sosial[facebook]" value="{{ old('media_sosial.facebook', $mediaSosial['facebook'] ?? '') }}" placeholder="Username Facebook"
                                    class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 block p-3.5 pl-12 transition-all">
                            </div>
                        </div>
                        <div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 font-bold"><i class="mdi mdi-instagram text-lg text-pink-500"></i></div>
                                <input type="text" name="media_sosial[instagram]" value="{{ old('media_sosial.instagram', $mediaSosial['instagram'] ?? '') }}" placeholder="Username Instagram"
                                    class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 block p-3.5 pl-12 transition-all">
                            </div>
                        </div>
                        <div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 font-bold"><svg class="w-5 h-5 text-slate-800 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.589 6.686a4.793 4.793 0 01-3.77-4.245V2h-3.445v13.672a2.896 2.896 0 01-5.201 1.743l-.002-.001.002.001a2.895 2.895 0 013.183-4.51v-3.5a6.329 6.329 0 00-5.394 10.692 6.33 6.33 0 0010.857-4.424V8.687a8.182 8.182 0 004.77 1.526V6.79a4.831 4.831 0 01-1.003-.104z"/></svg></div>
                                <input type="text" name="media_sosial[tiktok]" value="{{ old('media_sosial.tiktok', $mediaSosial['tiktok'] ?? '') }}" placeholder="Username TikTok"
                                    class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 block p-3.5 pl-12 transition-all">
                            </div>
                        </div>
                        <div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 font-bold"><i class="mdi mdi-whatsapp text-lg text-emerald-500"></i></div>
                                <input type="text" name="media_sosial[whatsapp]" value="{{ old('media_sosial.whatsapp', $mediaSosial['whatsapp'] ?? '') }}" placeholder="No WhatsApp Bisnis"
                                    class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 block p-3.5 pl-12 transition-all">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 5: Media & Galeri -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white/80 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-pink-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none transform translate-x-1/2 -translate-y-1/2"></div>
                
                <div class="px-8 pt-8 pb-6 border-b border-slate-100 relative z-10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-pink-100 flex items-center justify-center text-pink-600 font-black">6</div>
                    <h3 class="text-xl font-bold text-slate-800">Foto UMKM</h3>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Foto Utama (Sampul) <span class="text-rose-500">*</span></label>
                        <p class="text-xs text-slate-700 mb-4 font-medium">Foto ini akan ditampilkan sebagai sampul usaha di e-katalog.</p>
                        
                        @if ($umkm->foto_utama)
                        <div class="mb-4 p-4 border-2 border-slate-100 rounded-2xl bg-white" id="fotoUtamaContainer">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Foto Saat Ini</p>
                            <div class="relative rounded-xl overflow-hidden shadow-sm group inline-block">
                                <img src="{{ Storage::url($umkm->foto_utama) }}" class="h-32 object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <button type="button" onclick="document.getElementById('hapus_foto_utama_input').value='1'; document.getElementById('fotoUtamaContainer').style.display='none';" class="absolute top-2 right-2 bg-rose-500 text-white p-2 rounded-lg shadow-md hover:bg-rose-600 transition-colors opacity-0 group-hover:opacity-100">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                            <input type="hidden" name="hapus_foto_utama" id="hapus_foto_utama_input" value="0">
                        </div>
                        @endif

                        <label for="foto_utama" class="flex flex-col items-center justify-center w-full h-48 border-2 border-pink-300 border-dashed rounded-2xl cursor-pointer hover:border-pink-500 hover:bg-pink-50/50 bg-slate-50 transition-all group overflow-hidden relative">
                            <div id="fotoUtamaPlaceholder" class="text-center group-hover:scale-110 transition-transform duration-300">
                                <div class="w-14 h-14 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-3 text-pink-500">
                                    <i class="mdi mdi-camera-plus text-2xl"></i>
                                </div>
                                <p class="text-sm text-pink-700 font-bold">Pilih File Baru</p>
                                <p class="text-xs text-slate-700 mt-1 font-medium">Maks 5MB (JPG, PNG)</p>
                            </div>
                            <img id="fotoUtamaPreview" class="hidden absolute inset-0 w-full h-full object-cover z-10" src="" alt="Preview">
                            <input id="foto_utama" name="foto_utama" type="file" class="sr-only" accept="image/*">
                        </label>
                        <button type="button" id="btnHapusUtama" onclick="hapusFoto('foto_utama','fotoUtamaPreview','fotoUtamaPlaceholder')" class="hidden mt-3 px-4 py-2 bg-rose-100 text-rose-700 font-bold rounded-lg hover:bg-rose-200 transition-colors text-xs items-center gap-1 inline-flex">
                            <i class="mdi mdi-close text-base"></i> Batal Unggah
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Foto Tambahan / Gallery <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <p class="text-xs text-slate-700 mb-4 font-medium">Bisa pilih banyak foto sekaligus. Maks 5MB per foto.</p>
                        
                        @if ($umkm->foto_gallery)
                            @php
                                $galleries = is_string($umkm->foto_gallery) ? json_decode($umkm->foto_gallery, true) : $umkm->foto_gallery;
                            @endphp
                            @if(is_array($galleries) && count($galleries) > 0)
                            <div class="mb-4 p-4 border-2 border-slate-100 rounded-2xl bg-white">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Foto Gallery Saat Ini</p>
                                <div class="flex flex-wrap gap-3">
                                @foreach($galleries as $index => $g)
                                    <div class="relative inline-block group" id="gallery_item_{{ $index }}">
                                        <img src="{{ Storage::url($g) }}" class="h-16 w-16 object-cover rounded-xl border border-slate-200 shadow-sm">
                                        <button type="button" onclick="document.getElementById('hapus_gallery_container').insertAdjacentHTML('beforeend', '<input type=\'hidden\' name=\'hapus_foto_gallery[]\' value=\'{{ $index }}\'>'); document.getElementById('gallery_item_{{ $index }}').style.display='none';" class="absolute -top-2 -right-2 bg-rose-500 text-white p-1 rounded-full shadow-md cursor-pointer flex items-center justify-center hover:bg-rose-600 transition-colors opacity-0 group-hover:opacity-100">
                                            <i class="mdi mdi-close text-xs"></i>
                                        </button>
                                    </div>
                                @endforeach
                                </div>
                                <div id="hapus_gallery_container"></div>
                            </div>
                            @endif
                        @endif

                        <label for="foto_gallery" class="flex flex-col items-center justify-center w-full h-48 border-2 border-pink-300 border-dashed rounded-2xl cursor-pointer hover:border-pink-500 hover:bg-pink-50/50 bg-slate-50 transition-all group overflow-hidden relative">
                            <div class="text-center group-hover:scale-110 transition-transform duration-300">
                                <div class="w-14 h-14 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-3 text-pink-500">
                                    <i class="mdi mdi-image-multiple text-2xl"></i>
                                </div>
                                <p class="text-sm text-pink-700 font-bold">Pilih Beberapa Foto Baru</p>
                                <p id="galleryCount" class="text-xs text-slate-700 mt-1 font-medium">Belum ada foto dipilih</p>
                            </div>
                            <input id="foto_gallery" name="foto_gallery[]" type="file" class="sr-only" accept="image/*" multiple>
                        </label>
                        <div id="galleryPreview" class="mt-3 flex flex-wrap gap-2"></div>
                    </div>
                </div>
            </div>

            <!-- Sticky Actions Bar -->
            <div class="sticky bottom-4 z-50 mt-10">
                <div class="bg-slate-900/90 backdrop-blur-xl p-4 sm:px-8 sm:py-5 rounded-[2rem] shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-4 border border-slate-700">
                    <p class="text-slate-300 text-sm font-medium hidden sm:block">Pastikan semua data diisi dengan <span class="text-white font-bold">benar</span> dan <span class="text-white font-bold">lengkap</span>.</p>
                    
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <a href="{{ route('operator.dashboard') }}" class="px-6 py-3.5 bg-slate-800 text-slate-300 hover:text-white font-bold rounded-xl transition-colors hover:bg-slate-700">Batal</a>
                        <button type="submit" name="action" value="simpan" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-black rounded-xl transition-all shadow-lg shadow-indigo-500/30 hover:-translate-y-1 hover:shadow-indigo-500/50 flex items-center justify-center gap-2">
                            <i class="mdi mdi-content-save-outline text-xl"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>

    @push('scripts')
        <script>
            window.umkmWilayahEditData = {
                initialKel: @js(old('kelurahan', $umkm->pemilik?->kelurahan ?? '')),
                initialRw: @js(old('rw', $umkm->pemilik?->rw ?? '')),
                initialRt: @js(old('rt', $umkm->pemilik?->rt ?? '')),
                kelurahansData: @js(isset($kelurahans) ? $kelurahans : (isset($kelurahan) ? $kelurahan : [])),
            };

            window.umkmWilayahDropdown = function() {
                return {
                    initialKel: window.umkmWilayahEditData.initialKel,
                    initialRw: window.umkmWilayahEditData.initialRw,
                    initialRt: window.umkmWilayahEditData.initialRt,
                    selectedKel: '',
                    selectedRw: '',
                    selectedRt: '',
                    kelurahansData: window.umkmWilayahEditData.kelurahansData,
                    normalizeWilayah(value) {
                        if (value === null || value === undefined || value === '') return '';
                        const parsed = parseInt(String(value).replace(/\D/g, ''), 10);
                        return Number.isNaN(parsed) ? '' : String(parsed);
                    },
                    init() {
                        this.$nextTick(() => {
                            this.selectedKel = this.initialKel || '';
                            this.$nextTick(() => {
                                this.selectedRw = this.normalizeWilayah(this.initialRw);
                                this.$nextTick(() => {
                                    this.selectedRt = this.normalizeWilayah(this.initialRt);
                                });
                            });
                        });
                    },
                    get currentRws() {
                        if (!this.selectedKel) return [];
                        const kel = this.kelurahansData.find(k => k.nama_kelurahan == this.selectedKel);
                        return kel && kel.rws ? kel.rws : [];
                    },
                    get currentRts() {
                        if (!this.selectedRw) return [];
                        const rw = this.currentRws.find(r => this.normalizeWilayah(r.nomor_rw) == this.selectedRw);
                        return rw && rw.rts ? rw.rts : [];
                    }
                };
            };

            // Preview foto utama
            document.getElementById('foto_utama').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function(ev) {
                    document.getElementById('fotoUtamaPreview').src = ev.target.result;
                    document.getElementById('fotoUtamaPreview').classList.remove('hidden');
                    document.getElementById('fotoUtamaPlaceholder').classList.add('hidden');
                    document.getElementById('btnHapusUtama').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            });

            function hapusFoto(inputId, previewId, placeholderId) {
                document.getElementById(inputId).value = '';
                document.getElementById(previewId).classList.add('hidden');
                document.getElementById(placeholderId).classList.remove('hidden');
                document.getElementById('btnHapusUtama').classList.add('hidden');
            }

            // Preview gallery
            document.getElementById('foto_gallery').addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                const container = document.getElementById('galleryPreview');
                const countEl = document.getElementById('galleryCount');
                container.innerHTML = '';
                countEl.textContent = files.length + ' foto dipilih';
                files.slice(0, 6).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        const img = document.createElement('img');
                        img.src = ev.target.result;
                        img.className = 'w-16 h-16 object-cover rounded-xl border-2 border-slate-200 shadow-sm';
                        container.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
                if (files.length > 6) {
                    const more = document.createElement('div');
                    more.className = 'w-16 h-16 rounded-xl bg-slate-100 flex items-center justify-center text-xs text-slate-600 font-bold border-2 border-slate-200 shadow-sm';
                    more.textContent = '+' + (files.length - 6);
                    container.appendChild(more);
                }
            });

            // Peta Koordinat (Leaflet)
            document.addEventListener('DOMContentLoaded', function() {
                var initialLat = {{ $umkm->latitude ?? -6.914744 }};
                var initialLng = {{ $umkm->longitude ?? 107.609810 }};
                var zoomLevel = 13;
                
                var map = L.map('map').setView([initialLat, initialLng], zoomLevel);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                var marker = L.marker([initialLat, initialLng], {draggable: true}).addTo(map);

                marker.on('dragend', function (e) {
                    document.getElementById('latitude').value = marker.getLatLng().lat.toFixed(8);
                    document.getElementById('longitude').value = marker.getLatLng().lng.toFixed(8);
                });

                map.on('click', function(e) {
                    marker.setLatLng(e.latlng);
                    document.getElementById('latitude').value = e.latlng.lat.toFixed(8);
                    document.getElementById('longitude').value = e.latlng.lng.toFixed(8);
                });

                if (!{{ $umkm->latitude ? 'true' : 'false' }} && "geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var userLat = position.coords.latitude;
                        var userLng = position.coords.longitude;
                        
                        map.setView([userLat, userLng], 15);
                        marker.setLatLng([userLat, userLng]);
                        
                        document.getElementById('latitude').value = userLat.toFixed(8);
                        document.getElementById('longitude').value = userLng.toFixed(8);
                    });
                }
            });
        </script>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    @endpush
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalInput = document.querySelector('input[name="jumlah_tenaga_kerja"]');
            const lakiInput = document.querySelector('input[name="tenaga_kerja_laki"]');
            const peremInput = document.querySelector('input[name="tenaga_kerja_perempuan"]');

            if(totalInput && lakiInput && peremInput) {
                function calcTotal() {
                    const l = parseInt(lakiInput.value) || 0;
                    const p = parseInt(peremInput.value) || 0;
                    totalInput.value = l + p;
                }
                lakiInput.addEventListener('input', calcTotal);
                peremInput.addEventListener('input', calcTotal);
                calcTotal();
            }
        });
    </script>
    <x-nik-autofill />
@endsection