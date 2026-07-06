@extends('layouts.superadmin')

@section('title', 'Pengaturan Sistem Global')

@section('content')
    <div class="space-y-6" x-data="settingsPanel()">
        
        <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">Pengaturan Sistem</h2>
                <p class="text-xs md:text-sm text-slate-500 font-medium">Ubah parameter global, kontak dinas, serta status
                    pendaftaran sistem secara instan.</p>
            </div>
        </div>

        <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl w-fit">
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

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs md:text-sm px-5 py-2.5 rounded-md shadow-sm hover:shadow-md transition-all flex items-center gap-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20 hover:scale-102">
                    <span class="mdi mdi-content-save"></span>
                    <span>Simpan Pengaturan</span>
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
                    activeTab: 'system',
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
                        }
                    ]
                }
            }
        </script>
    @endpush
@endsection
