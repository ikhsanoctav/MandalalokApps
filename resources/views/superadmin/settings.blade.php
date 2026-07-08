@extends('layouts.superadmin')

@section('title', 'Pengaturan Sistem Global')

@section('content')
    <div class="space-y-6" x-data="settingsPanel()">
        
        <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">Pengaturan Sistem</h2>
                <p class="text-xs md:text-sm text-slate-500 font-medium">Ubah parameter global, kontak dinas, kop surat, serta status
                    pendaftaran sistem secara instan.</p>
            </div>
        </div>

        <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl w-fit flex-wrap">
            <template x-for="tab in tabs" :key="tab.id">
                <button @click="activeTab = tab.id"
                    class="px-4 py-2 rounded-lg text-xs md:text-sm font-bold transition-all focus:outline-none"
                    :class="activeTab === tab.id ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'">
                    <span class="flex items-center gap-1.5">
                        <span class="mdi" :class="tab.icon"></span>
                        <span x-text="tab.label"></span>
                    </span>
                </button>
            </template>
        </div>

        {{-- TAB: Sistem & Aturan, Umum, Kontak --}}
        <form action="{{ route('superadmin.settings.update') }}" method="POST"
            class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            @csrf

            <div class="p-6 md:p-8 space-y-6">
                
                <div x-show="activeTab === 'system'" x-cloak class="space-y-6">
                    <div class="pb-3 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                            <span class="mdi mdi-cogs text-blue-600"></span>
                            Kontrol & Aturan Sistem
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Kelola batasan nominal serta akses operasional pendaftaran
                            UMKM.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        @php
                            $regOpen = \App\Models\Setting::get('registration_open', true);
                        @endphp
                        <div class="flex flex-col justify-between p-4 rounded-xl border border-slate-150/70 bg-slate-50/20">
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Registrasi
                                    UMKM</span>
                                <h4 class="font-bold text-slate-800 text-sm mt-1">Status Pendaftaran Online</h4>
                                <p class="text-xs text-slate-450 mt-1 leading-relaxed">
                                    Jika dinonaktifkan, pelaku UMKM baru tidak dapat mendaftarkan usaha mereka sementara
                                    waktu.
                                </p>
                            </div>

                            <div class="flex items-center gap-3 mt-4" x-data="{ open: {{ $regOpen ? 'true' : 'false' }} }">
                                
                                <button type="button" @click="open = !open"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                    :class="open ? 'bg-emerald-500' : 'bg-slate-300'">
                                    <span
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                        :class="open ? 'translate-x-5' : 'translate-x-0'"></span>
                                </button>
                                <span class="text-xs font-bold" :class="open ? 'text-emerald-600' : 'text-slate-500'"
                                    x-text="open ? 'Pendaftaran Buka (Aktif)' : 'Pendaftaran Tutup (Nonaktif)'"></span>
                                <input type="hidden" name="registration_open" :value="open ? 'true' : 'false'">
                            </div>
                        </div>

                        @php
                            $subActive = \App\Models\Setting::get('pelaku_submission_active', false);
                        @endphp
                        <div class="flex flex-col justify-between p-4 rounded-xl border border-slate-150/70 bg-slate-50/20">
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pengajuan Bantuan Pelaku</span>
                                <h4 class="font-bold text-slate-800 text-sm mt-1">Status Pengajuan Bantuan oleh Pelaku</h4>
                                <p class="text-xs text-slate-450 mt-1 leading-relaxed">
                                    Jika diaktifkan, pelaku UMKM dapat mengirimkan pengajuan bantuan (bantuan/pembiayaan) secara mandiri dari dashboard mereka.
                                </p>
                            </div>

                            <div class="flex items-center gap-3 mt-4" x-data="{ open: {{ $subActive ? 'true' : 'false' }} }">
                                <button type="button" @click="open = !open"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                    :class="open ? 'bg-emerald-500' : 'bg-slate-300'">
                                    <span
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                        :class="open ? 'translate-x-5' : 'translate-x-0'"></span>
                                </button>
                                <span class="text-xs font-bold" :class="open ? 'text-emerald-600' : 'text-slate-500'"
                                    x-text="open ? 'Fitur Aktif' : 'Fitur Nonaktif (Hanya Admin)'"></span>
                                <input type="hidden" name="pelaku_submission_active" :value="open ? 'true' : 'false'">
                            </div>
                        </div>

                        @php
                            $limitVal = \App\Models\Setting::get('max_financing_limit', 50000000);
                        @endphp
                        <div class="flex flex-col justify-between p-4 rounded-xl border border-slate-150/70 bg-slate-50/20">
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pembatasan
                                    Modal</span>
                                <h4 class="font-bold text-slate-800 text-sm mt-1">Limit Nominal Bantuan Maksimal</h4>
                                <p class="text-xs text-slate-450 mt-1 leading-relaxed">
                                    Batas nilai pengajuan bantuan modal yang diizinkan untuk diajukan dalam satu formulir.
                                </p>
                            </div>

                            <div class="mt-4">
                                <div class="relative rounded-xl shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-slate-500 text-sm font-bold">Rp</span>
                                    </div>
                                    <input type="number" name="max_financing_limit" id="max_financing_limit"
                                        value="{{ $limitVal }}"
                                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-250 bg-white text-sm text-slate-700 font-bold focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all">
                                </div>
                            </div>
                        </div>

                        @php
                            $programBantuanVal = \App\Models\Setting::get('program_bantuan_list', '');
                        @endphp
                        <div class="flex flex-col justify-between p-4 rounded-xl border border-slate-150/70 bg-slate-50/20 md:col-span-2">
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Program Bantuan</span>
                                <h4 class="font-bold text-slate-800 text-sm mt-1">Daftar Program Bantuan Aktif</h4>
                                <p class="text-xs text-slate-450 mt-1 leading-relaxed">
                                    Masukkan daftar jenis bantuan yang sedang berjalan sekarang. Tuliskan masing-masing program bantuan dalam <strong>satu baris baru</strong> (tekan Enter untuk memisahkan).
                                </p>
                            </div>

                            <div class="mt-4">
                                <textarea name="program_bantuan_list" id="program_bantuan_list" rows="5"
                                    class="w-full px-4 py-2.5 rounded-lg border border-slate-250 bg-white text-sm text-slate-700 font-bold focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all"
                                    placeholder="Contoh:&#10;Bantuan Modal Usaha Mikro (BPUM)&#10;Subsidi Bunga Pembiayaan Mandiri">{{ $programBantuanVal }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'general'" x-cloak class="space-y-6">
                    <div class="pb-3 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                            <span class="mdi mdi-information-outline text-blue-600"></span>
                            Identitas & Umum
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Kelola identitas utama aplikasi web portal pendataan
                            Mandalaloka.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        
                        @php
                            $appName = \App\Models\Setting::get('app_name', 'Mandalaloka');
                        @endphp
                        <div class="space-y-2">
                            <label for="app_name"
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Aplikasi
                                Portal</label>
                            <input type="text" name="app_name" id="app_name" value="{{ $appName }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-250 bg-slate-50/20 text-sm text-slate-750 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all">
                            <p class="text-[10px] text-slate-400 mt-1">Nama ini akan tercantum di seluruh lembar kop laporan
                                dan dashboard utama.</p>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'contact'" x-cloak class="space-y-6">
                    <div class="pb-3 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                            <span class="mdi mdi-phone-classic text-blue-600"></span>
                            Kontak & Bantuan Dinas
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Ubah informasi kontak dinas untuk menanggapi kendala para
                            pelaku UMKM secara cepat.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        @php
                            $phone = \App\Models\Setting::get('support_phone', '08123456789');
                        @endphp
                        <div class="space-y-2">
                            <label for="support_phone"
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Nomor WhatsApp
                                Bantuan Dinas</label>
                            <input type="text" name="support_phone" id="support_phone" value="{{ $phone }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-250 bg-slate-50/20 text-sm text-slate-750 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all">
                            <p class="text-[10px] text-slate-400 mt-1">Nomor helpdesk WhatsApp resmi yang tercantum di
                                halaman depan.</p>
                        </div>

                        @php
                            $email = \App\Models\Setting::get('support_email', 'support@mandalaloka.go.id');
                        @endphp
                        <div class="space-y-2">
                            <label for="support_email"
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Email Layanan
                                Pengaduan</label>
                            <input type="email" name="support_email" id="support_email" value="{{ $email }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-250 bg-slate-50/20 text-sm text-slate-750 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all">
                            <p class="text-[10px] text-slate-400 mt-1">Email resmi dinas penanggungjawab penanganan
                                komplain.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer tombol simpan untuk tab non-kop-surat --}}
            <div x-show="activeTab !== 'kop_surat'" class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs md:text-sm px-5 py-2.5 rounded-md shadow-sm hover:shadow-md transition-all flex items-center gap-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20 hover:scale-102">
                    <span class="mdi mdi-content-save"></span>
                    <span>Simpan Pengaturan</span>
                </button>
            </div>
        </form>

        {{-- TAB: Kop Surat (form terpisah karena multipart) --}}
        @php
            $kopNamaInstansi = \App\Models\Setting::get('kop_nama_instansi', 'PEMERINTAH KOTA BANDUNG');
            $kopNamaUnit     = \App\Models\Setting::get('kop_nama_unit', 'KECAMATAN MANDALAJATI');
            $kopAlamat       = \App\Models\Setting::get('kop_alamat', '');
            $kopTelepon      = \App\Models\Setting::get('kop_telepon', '');
            $kopEmail        = \App\Models\Setting::get('kop_email', '');
            $kopWebsite      = \App\Models\Setting::get('kop_website', '');
            $kopLogoPath     = \App\Models\Setting::get('kop_logo_path', '');
        @endphp

        <form action="{{ route('superadmin.settings.kop_surat') }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden"
            x-show="activeTab === 'kop_surat'" x-cloak>
            @csrf

            <div class="p-6 md:p-8 space-y-6">
                <div class="pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                        <span class="mdi mdi-file-document-outline text-blue-600"></span>
                        Template Kop Surat
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Kustomisasi kop surat yang akan tampil pada semua dokumen cetak PDF dan laporan ekspor.</p>
                </div>

                {{-- PREVIEW KOP SURAT --}}
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 bg-slate-50/50" id="kop-preview-container">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <span class="mdi mdi-eye-outline"></span> Preview Kop Surat
                    </p>
                    <div class="bg-white border border-slate-200 rounded-lg p-4 font-serif shadow-sm">
                        <div class="flex items-center gap-4 pb-3 border-b-2 border-double border-black">
                            {{-- Logo preview --}}
                            <div class="flex-shrink-0">
                                <img id="logo-preview-img"
                                    src="{{ $kopLogoPath ? Storage::url($kopLogoPath) : asset('images/Logo_Mandalaloka.png') }}"
                                    alt="Logo"
                                    class="w-16 h-16 object-contain"
                                    onerror="this.style.display='none'">
                                <div id="logo-placeholder" class="w-16 h-16 bg-slate-100 rounded flex items-center justify-center {{ $kopLogoPath ? 'hidden' : '' }}">
                                    <span class="mdi mdi-image-outline text-2xl text-slate-300"></span>
                                </div>
                            </div>
                            {{-- Text Kop --}}
                            <div class="flex-1 text-center">
                                <div class="text-sm font-bold uppercase tracking-wide leading-tight" id="preview-instansi">{{ $kopNamaInstansi ?: 'NAMA INSTANSI' }}</div>
                                <div class="text-base font-extrabold uppercase tracking-wide mt-0.5" id="preview-unit">{{ $kopNamaUnit ?: 'NAMA UNIT / DINAS' }}</div>
                                <div class="text-xs text-gray-600 mt-1" id="preview-alamat">{{ $kopAlamat ?: 'Alamat instansi akan tampil di sini' }}</div>
                                <div class="text-xs text-gray-600" id="preview-kontak">
                                    @if($kopTelepon || $kopEmail)
                                        Telepon: {{ $kopTelepon }}{{ $kopTelepon && $kopEmail ? ' | Email: ' : '' }}{{ $kopEmail }}
                                    @else
                                        Telepon: (xxx) xxxxxx | Email: nama@instansi.go.id
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Logo Upload --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Logo Instansi</label>
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <img id="logo-thumb"
                                    src="{{ $kopLogoPath ? Storage::url($kopLogoPath) : asset('images/Logo_Mandalaloka.png') }}"
                                    class="w-20 h-20 object-contain rounded-lg border border-slate-200 bg-white p-1 {{ !$kopLogoPath ? 'opacity-40' : '' }}"
                                    alt="Logo saat ini">
                            </div>
                            <div class="flex-1">
                                <label for="kop_logo"
                                    class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-slate-300 rounded-xl cursor-pointer bg-slate-50 hover:bg-blue-50 hover:border-blue-400 transition-all group">
                                    <span class="mdi mdi-cloud-upload-outline text-3xl text-slate-400 group-hover:text-blue-500 transition-colors"></span>
                                    <span class="text-xs font-bold text-slate-500 group-hover:text-blue-600 mt-1">Klik untuk upload logo baru</span>
                                    <span class="text-[10px] text-slate-400 mt-0.5">PNG, JPG, JPEG – Maks. 2MB</span>
                                    <input id="kop_logo" name="kop_logo" type="file" accept="image/png,image/jpg,image/jpeg" class="hidden"
                                        onchange="previewLogo(this)">
                                </label>
                                <p class="text-[10px] text-slate-400 mt-1.5">Logo akan tampil di pojok kiri kop surat pada semua dokumen cetak & PDF.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Nama Instansi --}}
                    <div class="space-y-2">
                        <label for="kop_nama_instansi" class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Instansi / Pemerintah</label>
                        <input type="text" name="kop_nama_instansi" id="kop_nama_instansi"
                            value="{{ $kopNamaInstansi }}"
                            placeholder="Contoh: PEMERINTAH KOTA BANDUNG"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-250 bg-slate-50/20 text-sm text-slate-750 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all uppercase"
                            oninput="document.getElementById('preview-instansi').textContent = this.value || 'NAMA INSTANSI'">
                        <p class="text-[10px] text-slate-400">Baris pertama kop surat (contoh: nama pemerintah kota/kabupaten)</p>
                    </div>

                    {{-- Nama Unit --}}
                    <div class="space-y-2">
                        <label for="kop_nama_unit" class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Unit / Dinas / Kecamatan</label>
                        <input type="text" name="kop_nama_unit" id="kop_nama_unit"
                            value="{{ $kopNamaUnit }}"
                            placeholder="Contoh: KECAMATAN MANDALAJATI"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-250 bg-slate-50/20 text-sm text-slate-750 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all uppercase"
                            oninput="document.getElementById('preview-unit').textContent = this.value || 'NAMA UNIT / DINAS'">
                        <p class="text-[10px] text-slate-400">Baris kedua kop surat yang lebih besar (nama kecamatan/dinas)</p>
                    </div>

                    {{-- Alamat --}}
                    <div class="space-y-2 md:col-span-2">
                        <label for="kop_alamat" class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat Lengkap</label>
                        <input type="text" name="kop_alamat" id="kop_alamat"
                            value="{{ $kopAlamat }}"
                            placeholder="Contoh: Jl. Sindanglaya No.50, Sindangjaya, Kec. Mandalajati, Kota Bandung, Jawa Barat 40195"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-250 bg-slate-50/20 text-sm text-slate-750 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all"
                            oninput="document.getElementById('preview-alamat').textContent = this.value || 'Alamat instansi akan tampil di sini'">
                    </div>

                    {{-- Telepon --}}
                    <div class="space-y-2">
                        <label for="kop_telepon" class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Nomor Telepon</label>
                        <input type="text" name="kop_telepon" id="kop_telepon"
                            value="{{ $kopTelepon }}"
                            placeholder="Contoh: (022) 7815252"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-250 bg-slate-50/20 text-sm text-slate-750 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all"
                            oninput="updateKontakPreview()">
                    </div>

                    {{-- Email --}}
                    <div class="space-y-2">
                        <label for="kop_email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Email Resmi</label>
                        <input type="email" name="kop_email" id="kop_email"
                            value="{{ $kopEmail }}"
                            placeholder="Contoh: kecamatan@bandung.go.id"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-250 bg-slate-50/20 text-sm text-slate-750 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all"
                            oninput="updateKontakPreview()">
                    </div>

                    {{-- Website --}}
                    <div class="space-y-2 md:col-span-2">
                        <label for="kop_website" class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Website <span class="text-slate-400 font-normal normal-case">(opsional)</span></label>
                        <input type="text" name="kop_website" id="kop_website"
                            value="{{ $kopWebsite }}"
                            placeholder="Contoh: https://mandalajati.bandung.go.id"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-250 bg-slate-50/20 text-sm text-slate-750 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all">
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3">
                <p class="text-xs text-slate-400 flex items-center gap-1.5">
                    <span class="mdi mdi-information-outline text-blue-400"></span>
                    Perubahan kop surat langsung berlaku pada semua dokumen PDF & cetak baru.
                </p>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs md:text-sm px-5 py-2.5 rounded-md shadow-sm hover:shadow-md transition-all flex items-center gap-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    <span class="mdi mdi-content-save"></span>
                    <span>Simpan Kop Surat</span>
                </button>
            </div>
        </form>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <script>
            function settingsPanel() {
                return {
                    activeTab: '{{ session("active_tab", "system") }}',
                    tabs: [{
                            id: 'system',
                            label: 'Sistem & Aturan',
                            icon: 'mdi-cogs'
                        },
                        {
                            id: 'general',
                            label: 'Umum & Nama',
                            icon: 'mdi-information-outline'
                        },
                        {
                            id: 'contact',
                            label: 'Kontak Bantuan',
                            icon: 'mdi-phone-classic'
                        },
                        {
                            id: 'kop_surat',
                            label: 'Kop Surat',
                            icon: 'mdi-file-document-outline'
                        }
                    ]
                }
            }

            function previewLogo(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewImg = document.getElementById('logo-preview-img');
                        const thumb = document.getElementById('logo-thumb');
                        const placeholder = document.getElementById('logo-placeholder');

                        if (previewImg) { previewImg.src = e.target.result; previewImg.style.display = ''; }
                        if (thumb) { thumb.src = e.target.result; thumb.classList.remove('opacity-40'); }
                        if (placeholder) { placeholder.classList.add('hidden'); }
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function updateKontakPreview() {
                const telp = document.getElementById('kop_telepon').value;
                const email = document.getElementById('kop_email').value;
                const el = document.getElementById('preview-kontak');
                if (!telp && !email) {
                    el.textContent = 'Telepon: (xxx) xxxxxx | Email: nama@instansi.go.id';
                } else {
                    let parts = [];
                    if (telp) parts.push('Telepon: ' + telp);
                    if (email) parts.push('Email: ' + email);
                    el.textContent = parts.join(' | ');
                }
            }
        </script>
    @endpush
@endsection
