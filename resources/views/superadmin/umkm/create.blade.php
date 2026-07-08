@extends('layouts.superadmin')

@section('title', 'Input Data UMKM Baru')
@section('breadcrumb', 'Input UMKM')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
<style>
    .leaflet-container { z-index: 10 !important; }
</style>
@endpush


@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-800">Input Data UMKM Baru</h2>
            <a href="{{ route('superadmin.umkm') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-200">
                <div class="font-semibold mb-2">Terjadi Kesalahan:</div>
                <ul class="list-disc pl-5 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('superadmin.umkm.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-green-50 border-b border-green-200 px-6 py-4">
                    <h3 class="text-base font-bold text-green-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Data Pemilik UMKM
                    </h3>
                    <p class="text-xs text-green-600 mt-0.5">Jika NIK sudah terdaftar, data pemilik akan dihubungkan
                        otomatis.</p>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex items-center">
                            <span>Nomor Induk Kependudukan (NIK) <span class="text-red-500">*</span></span>
                            <span id="nik-loading" class="hidden ml-3 text-xs text-blue-600 italic flex items-center">
                                <svg class="animate-spin -ml-1 mr-1.5 h-3 w-3 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sedang mencari data...
                            </span>
                        </label>
                        <input type="text" name="nik" value="{{ old('nik') }}" required maxlength="16"
                            placeholder="Masukkan 16 digit NIK"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('nik')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap (Sesuai KTP) <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                            placeholder="Nama Lengkap"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('nama_lengkap')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Kelamin <span
                                class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    
                        @error('jenis_kelamin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                            placeholder="Contoh: Bandung"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('tempat_lahir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('tanggal_lahir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor HP / WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="08123456789"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('no_hp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email Pemilik</label>
                        <input type="email" name="email_pemilik" value="{{ old('email_pemilik') }}"
                            placeholder="email@contoh.com"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('email_pemilik')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div x-data="{
                        selectedKel: '{{ old('kelurahan', '') }}',
                        selectedRw: '{{ old('rw', '') }}',
                        selectedRt: '{{ old('rt', '') }}',
                        kelurahansData: {{ isset($kelurahans) ? $kelurahans->toJson() : (isset($kelurahan) ? $kelurahan->toJson() : '[]') }},
                        get currentRws() {
                            if (!this.selectedKel) return [];
                            const kel = this.kelurahansData.find(k => k.nama_kelurahan == this.selectedKel);
                            return kel && kel.rws ? kel.rws : [];
                        },
                        get currentRts() {
                            if (!this.selectedRw) return [];
                            const rws = this.currentRws;
                            const rw = rws.find(r => r.nomor_rw == this.selectedRw);
                            return rw && rw.rts ? rw.rts : [];
                        }
                    }"
                    @autofill-wilayah.window="
                        selectedKel = $event.detail.kelurahan || '';
                        $nextTick(() => {
                            selectedRw = $event.detail.rw || '';
                            $nextTick(() => {
                                selectedRt = $event.detail.rt || '';
                            });
                        });
                    ">
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kelurahan <span
                                    class="text-red-500">*</span></label>
                            <select name="kelurahan" x-model="selectedKel" @change="if ($event.isTrusted) { selectedRw = ''; selectedRt = '' }"
                                required
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <option value="">Pilih Kelurahan</option>
                                <template x-for="kel in kelurahansData" :key="kel.id">
                                    <option :value="kel.nama_kelurahan" x-text="kel.nama_kelurahan"></option>
                                </template>
                            </select>
                        
                        @error('kelurahan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">RW</label>
                                <select name="rw" x-model="selectedRw" @change="if ($event.isTrusted) { selectedRt = '' }"
                                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    <option value="">Pilih RW</option>
                                    <template x-for="rw in currentRws" :key="rw.id">
                                        <option :value="rw.nomor_rw"
                                            x-text="'RW ' + String(rw.nomor_rw).padStart(3, '0')"></option>
                                    </template>
                                </select>
                            
                        @error('rw')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">RT</label>
                                <select name="rt" x-model="selectedRt"
                                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    <option value="">Pilih RT</option>
                                    <template x-for="rt in currentRts" :key="rt.id">
                                        <option :value="rt.nomor_rt"
                                            x-text="'RT ' + String(rt.nomor_rt).padStart(3, '0')"></option>
                                    </template>
                                </select>
                            
                        @error('rt')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Lengkap Pemilik <span
                                class="text-red-500">*</span></label>
                        <textarea name="alamat_pemilik" required rows="2" placeholder="Nama Jalan, Nomor Rumah, RT/RW, dsb"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">{{ old('alamat_pemilik') }}</textarea>
                    
                        @error('alamat_pemilik')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-blue-50 border-b border-blue-200 px-6 py-4">
                    <h3 class="text-base font-bold text-blue-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Informasi Usaha (UMKM)
                    </h3>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Usaha / Merek <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_usaha" value="{{ old('nama_usaha') }}" required
                            placeholder="Contoh: Warung Makan Pak Budi"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('nama_usaha')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 border p-4 rounded-xl bg-slate-50 border-slate-200" x-data="{
                        omset: '{{ old('perkiraan_omset', $umkm->perkiraan_omset ?? '') }}',
                        kategoriMap: {
                            @foreach($kategoris ?? $kategori ?? \App\Models\KategoriUMKM::all() as $kat)
                                '{{ strtolower($kat->nama_kategori) }}': '{{ $kat->id }}',
                            @endforeach
                        },
                        get kategoriName() {
                            if (this.omset === '< Rp 5 Juta' || this.omset === 'Rp 5 Juta - Rp 10 Juta') return 'mikro';
                            if (this.omset === 'Rp 10 Juta - Rp 50 Juta') return 'kecil';
                            if (this.omset === 'Rp 50 Juta - Rp 100 Juta' || this.omset === '> Rp 100 Juta') return 'menengah';
                            return '';
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
                            <label class="block text-sm font-bold text-slate-700 mb-2">Perkiraan Omset Bulanan <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="perkiraan_omset" x-model="omset" required
                                    class="w-full bg-white border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-2.5 appearance-none cursor-pointer transition-all">
                                    <option value="" disabled>Pilih Perkiraan Omset...</option>
                                    <option value="< Rp 5 Juta">< Rp 5 Juta</option>
                                    <option value="Rp 5 Juta - Rp 10 Juta">Rp 5 Juta - Rp 10 Juta</option>
                                    <option value="Rp 10 Juta - Rp 50 Juta">Rp 10 Juta - Rp 50 Juta</option>
                                    <option value="Rp 50 Juta - Rp 100 Juta">Rp 50 Juta - Rp 100 Juta</option>
                                    <option value="> Rp 100 Juta">> Rp 100 Juta</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            @error('perkiraan_omset')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kategori Usaha (Otomatis)</label>
                            <input type="hidden" name="id_kategori" :value="kategoriId">
                            <div class="px-4 py-2.5 rounded-lg border border-indigo-200 bg-indigo-50 text-indigo-700 font-semibold text-sm flex items-center gap-2 h-[42px]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                <span x-text="kategoriDisplay"></span>
                            </div>
                            @error('id_kategori')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Sektor Usaha <span
                                class="text-red-500">*</span></label>
                        <select name="id_sektor" required
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="" disabled selected>Pilih Sektor</option>
                            @foreach ($sektors as $sek)
                                <option value="{{ $sek->id }}" {{ old('id_sektor') == $sek->id ? 'selected' : '' }}>
                                    {{ $sek->nama_sektor }}</option>
                            @endforeach
                        </select>
                    
                        @error('id_sektor')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Bentuk Jualan <span
                                class="text-red-500">*</span></label>
                        <select name="bentuk_jualan" required
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="" disabled selected>Pilih Bentuk Jualan</option>
                            <option value="Toko Fisik" {{ old('bentuk_jualan') == 'Toko Fisik' ? 'selected' : '' }}>Toko Fisik</option>
                            <option value="Toko Online" {{ old('bentuk_jualan') == 'Toko Online' ? 'selected' : '' }}>Toko Online</option>
                            <option value="Hybrid (Fisik & Online)" {{ old('bentuk_jualan') == 'Hybrid (Fisik & Online)' ? 'selected' : '' }}>Hybrid (Fisik & Online)</option>
                            <option value="Keliling" {{ old('bentuk_jualan') == 'Keliling' ? 'selected' : '' }}>Keliling</option>
                            <option value="Rumahan / Pre-order" {{ old('bentuk_jualan') == 'Rumahan / Pre-order' ? 'selected' : '' }}>Rumahan / Pre-order</option>
                        </select>
                    
                        @error('bentuk_jualan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status Usaha</label>
                        <select name="status_usaha"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="aktif" {{ old('status_usaha', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif
                            </option>
                            <option value="non_aktif" {{ old('status_usaha') == 'non_aktif' ? 'selected' : '' }}>Non Aktif
                            </option>
                            <option value="tutup" {{ old('status_usaha') == 'tutup' ? 'selected' : '' }}>Tutup</option>
                            <option value="pindah" {{ old('status_usaha') == 'pindah' ? 'selected' : '' }}>Pindah</option>
                        </select>
                    
                        @error('status_usaha')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Berdiri</label>
                        <input type="number" name="tahun_berdiri" value="{{ old('tahun_berdiri') }}"
                            placeholder="Contoh: 2021" min="1900" max="{{ date('Y') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('tahun_berdiri')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">No. Izin Usaha</label>
                        <input type="text" name="no_izin_usaha" value="{{ old('no_izin_usaha') }}"
                            placeholder="Nomor izin"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('no_izin_usaha')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Izin</label>
                        <select name="jenis_izin"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="">-- Pilih Jenis Izin --</option>
                            <option value="NIB" {{ old('jenis_izin') == 'NIB' ? 'selected' : '' }}>NIB</option>
                            <option value="SIUP" {{ old('jenis_izin') == 'SIUP' ? 'selected' : '' }}>SIUP</option>
                            <option value="SKU" {{ old('jenis_izin') == 'SKU' ? 'selected' : '' }}>SKU</option>
                            <option value="Lainnya" {{ old('jenis_izin') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                            </option>
                        </select>
                    
                        @error('jenis_izin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">NPWP Usaha</label>
                        <input type="text" name="npwp_usaha" value="{{ old('npwp_usaha') }}"
                            placeholder="xx.xxx.xxx.x-xxx.xxx"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('npwp_usaha')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Telepon Usaha</label>
                        <input type="text" name="telp_usaha" value="{{ old('telp_usaha') }}"
                            placeholder="022-1234567"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('telp_usaha')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email Usaha</label>
                        <input type="email" name="email_usaha" value="{{ old('email_usaha') }}"
                            placeholder="usaha@contoh.com"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('email_usaha')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Website</label>
                        <input type="text" name="website" value="{{ old('website') }}"
                            placeholder="https://usahasaya.com"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('website')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Tempat Usaha <span
                                class="text-red-500">*</span></label>
                        <textarea name="alamat_usaha" required rows="2" placeholder="Nama Jalan, RT/RW, Patokan"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">{{ old('alamat_usaha') }}</textarea>
                    
                        @error('alamat_usaha')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Titik Koordinat Lokasi (GPS) <span class="text-xs text-slate-500 font-normal ml-2">Geser pin merah untuk menyesuaikan lokasi</span></label>
                        <div id="map" class="w-full h-72 rounded-2xl border border-slate-300 z-10 mb-3 shadow-inner"></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Latitude</label>
                                <input type="text" id="latitude" name="latitude" value="{{ old('latitude') }}" readonly class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-slate-600 text-sm focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Longitude</label>
                                <input type="text" id="longitude" name="longitude" value="{{ old('longitude') }}" readonly class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-slate-600 text-sm focus:outline-none">
                            </div>
                        </div>
                    </div>


                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi Produk / Usaha</label>
                        <textarea name="deskripsi" rows="3" placeholder="Jelaskan produk atau jasa yang ditawarkan..."
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">{{ old('deskripsi') }}</textarea>
                    
                        @error('deskripsi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>

                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-purple-50 border-b border-purple-200 px-6 py-4">
                    <h3 class="text-base font-bold text-purple-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Data Ketenagakerjaan
                    </h3>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jumlah Total Tenaga Kerja</label>
                        <input type="number" name="jumlah_tenaga_kerja" value="{{ old('jumlah_tenaga_kerja', 0) }}"
                            min="0"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('jumlah_tenaga_kerja')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tenaga Kerja Laki-laki</label>
                        <input type="number" name="tenaga_kerja_laki" value="{{ old('tenaga_kerja_laki', 0) }}"
                            min="0"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('tenaga_kerja_laki')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tenaga Kerja Perempuan</label>
                        <input type="number" name="tenaga_kerja_perempuan"
                            value="{{ old('tenaga_kerja_perempuan', 0) }}" min="0"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('tenaga_kerja_perempuan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-indigo-50 border-b border-indigo-200 px-6 py-4">
                    <h3 class="text-base font-bold text-indigo-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Riwayat Bantuan / Pengajuan
                    </h3>
                </div>
                <div class="p-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pernah mengajukan / menerima bantuan
                        apa saja sebelumnya?</label>
                    <textarea name="riwayat_bantuan" rows="3"
                        placeholder="Contoh: Bantuan BPUM 2021, Pelatihan Kewirausahaan 2022, dll... (Kosongkan jika belum pernah)"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">{{ old('riwayat_bantuan', $umkm->riwayat_bantuan ?? '') }}</textarea>
                
                        @error('riwayat_bantuan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-yellow-50 border-b border-yellow-200 px-6 py-4">
                    <h3 class="text-base font-bold text-yellow-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        Media Sosial <span class="text-xs font-normal text-yellow-600 ml-1">(Opsional)</span>
                    </h3>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Facebook</label>
                        <input type="text" name="media_sosial[facebook]" value="{{ old('media_sosial.facebook') }}"
                            placeholder="https://facebook.com/username"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('media_sosial.facebook')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Instagram</label>
                        <input type="text" name="media_sosial[instagram]" value="{{ old('media_sosial.instagram') }}"
                            placeholder="https://instagram.com/username"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('media_sosial.instagram')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">TikTok</label>
                        <input type="text" name="media_sosial[tiktok]" value="{{ old('media_sosial.tiktok') }}"
                            placeholder="https://tiktok.com/@username"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('media_sosial.tiktok')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">WhatsApp Bisnis</label>
                        <input type="text" name="media_sosial[whatsapp]" value="{{ old('media_sosial.whatsapp') }}"
                            placeholder="https://wa.me/628xxx"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    
                        @error('media_sosial.whatsapp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-pink-50 border-b border-pink-200 px-6 py-4">
                    <h3 class="text-base font-bold text-pink-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Foto UMKM
                    </h3>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Foto Utama / Produk <span
                                class="text-red-500">*</span></label>
                        <p class="text-xs text-slate-500 mb-2">Bisa langsung foto dari kamera HP atau pilih dari galeri.
                            Maks 5MB.</p>
                        <label for="foto_utama"
                            class="flex flex-col items-center justify-center w-full h-36 border-2 border-pink-300 border-dashed rounded-xl cursor-pointer hover:border-pink-500 hover:bg-pink-50 transition-all"
                            id="fotoUtamaLabel">
                            <div id="fotoUtamaPlaceholder" class="text-center">
                                <svg class="mx-auto w-8 h-8 text-pink-400 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="text-xs text-pink-600 font-semibold">Tap untuk foto / pilih gambar</p>
                                <p class="text-xs text-slate-400 mt-0.5">JPG, PNG, WEBP • Maks 5MB</p>
                            </div>
                            <img id="fotoUtamaPreview" class="hidden max-h-32 rounded-2xl object-contain" src=""
                                alt="Preview">
                            <input id="foto_utama" name="foto_utama" type="file" class="sr-only" accept="image/*"
                                capture="environment" required>
                        </label>
                        <button type="button" id="btnHapusUtama"
                            onclick="hapusFoto('foto_utama','fotoUtamaPreview','fotoUtamaPlaceholder')"
                            class="hidden mt-2 text-xs text-red-500 hover:text-red-700">✕ Hapus foto</button>
                        @error('foto_utama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Foto Tambahan / Gallery <span
                                class="text-slate-400 font-normal">(Opsional)</span></label>
                        <p class="text-xs text-slate-500 mb-2">Bisa pilih banyak foto sekaligus. Maks 5MB per foto.</p>
                        <label for="foto_gallery"
                            class="flex flex-col items-center justify-center w-full h-36 border-2 border-pink-300 border-dashed rounded-xl cursor-pointer hover:border-pink-500 hover:bg-pink-50 transition-all">
                            <svg class="w-8 h-8 text-pink-400 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-xs text-pink-600 font-semibold">Pilih beberapa foto</p>
                            <p id="galleryCount" class="text-xs text-slate-400 mt-0.5">Belum ada foto dipilih</p>
                            <input id="foto_gallery" name="foto_gallery[]" type="file" class="sr-only"
                                accept="image/*" multiple>
                        </label>
                        <div id="galleryPreview" class="mt-2 flex flex-wrap gap-2"></div>
                    </div>

                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pb-4">
                <a href="{{ route('superadmin.umkm') }}"
                    class="px-6 py-2.5 text-slate-600 font-medium hover:bg-slate-100 rounded-xl transition-colors">Batal</a>
                <button type="submit"
                    class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan & Kirim Data UMKM
                </button>
            </div>

        </form>
    </div>

    @push('scripts')
        <script>
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
                        img.className = 'w-16 h-16 object-cover rounded-2xl border border-slate-200';
                        container.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
                if (files.length > 6) {
                    const more = document.createElement('div');
                    more.className =
                        'w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center text-xs text-slate-500 font-semibold';
                    more.textContent = '+' + (files.length - 6);
                    container.appendChild(more);
                }
            });

            // Peta Koordinat (Leaflet)
            document.addEventListener('DOMContentLoaded', function() {
                var initialLat = -6.914744; // Default to Bandung or current region
                var initialLng = 107.609810;
                var zoomLevel = 13;
                
                // Initialize map
                var map = L.map('map').setView([initialLat, initialLng], zoomLevel);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                var marker = L.marker([initialLat, initialLng], {draggable: true}).addTo(map);

                // Update input fields on drag
                marker.on('dragend', function (e) {
                    document.getElementById('latitude').value = marker.getLatLng().lat.toFixed(8);
                    document.getElementById('longitude').value = marker.getLatLng().lng.toFixed(8);
                });

                // Update marker and input fields on map click
                map.on('click', function(e) {
                    marker.setLatLng(e.latlng);
                    document.getElementById('latitude').value = e.latlng.lat.toFixed(8);
                    document.getElementById('longitude').value = e.latlng.lng.toFixed(8);
                });

                // Request user location if available
                if ("geolocation" in navigator) {
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
            totalInput.setAttribute('readonly', true);
            totalInput.classList.add('bg-slate-100', 'cursor-not-allowed');
            
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
