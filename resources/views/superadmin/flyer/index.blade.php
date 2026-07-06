@extends('layouts.superadmin')

@section('title', 'Manajemen Flyer Program Bantuan')

@section('content')
    <div class="space-y-6" x-data="flyerPanel()">
        
        <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">Manajemen Flyer Program</h2>
                <p class="text-xs md:text-sm text-slate-500 font-medium">Unggah, aktifkan, dan kelola pop-up flyer informasi bantuan secara instan.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('superadmin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 bg-white border border-slate-200 rounded-md transition-all focus:outline-none">
                    <span class="mdi mdi-arrow-left"></span>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>

        <form action="{{ route('superadmin.flyer.update') }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            @csrf

            <div class="p-6 md:p-8 space-y-8">
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <div class="flex flex-col justify-between p-5 rounded-2xl border border-slate-150/70 bg-slate-50/30">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Tampilan</span>
                            <h4 class="font-bold text-slate-800 text-sm mt-1">Aktifkan Pop-up Flyer</h4>
                            <p class="text-xs text-slate-450 mt-1.5 leading-relaxed">
                                Jika diaktifkan, flyer program akan otomatis muncul sebagai modal pop-up ketika pengguna mengakses halaman target yang ditentukan.
                            </p>
                        </div>

                        <div class="flex items-center gap-3 mt-5" x-data="{ active: {{ $flyerActive ? 'true' : 'false' }} }">
                            <button type="button" @click="active = !active"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                :class="active ? 'bg-indigo-600' : 'bg-slate-300'">
                                <span
                                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                    :class="active ? 'translate-x-5' : 'translate-x-0'"></span>
                            </button>
                            <span class="text-xs font-bold transition-colors" :class="active ? 'text-indigo-600' : 'text-slate-500'"
                                x-text="active ? 'Pop-up Aktif' : 'Pop-up Nonaktif'"></span>
                            <input type="hidden" name="flyer_popup_active" :value="active ? 'true' : 'false'">
                        </div>
                    </div>

                    <div class="flex flex-col justify-between p-5 rounded-2xl border border-slate-150/70 bg-slate-50/30">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lokasi Penayangan</span>
                            <h4 class="font-bold text-slate-800 text-sm mt-1">Target Halaman</h4>
                            <p class="text-xs text-slate-450 mt-1.5 leading-relaxed">
                                Tentukan halaman mana saja yang akan menampilkan pop-up flyer program ini ketika aktif.
                            </p>
                        </div>

                        <div class="mt-4">
                            <select name="flyer_popup_target" id="flyer_popup_target"
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-250 bg-white text-sm text-slate-700 font-bold focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-all">
                                <option value="both" {{ old('flyer_popup_target', $flyerTarget) === 'both' ? 'selected' : '' }}>Keduanya (Landing & Dashboard)</option>
                                <option value="welcome" {{ old('flyer_popup_target', $flyerTarget) === 'welcome' ? 'selected' : '' }}>Hanya Halaman Depan / Landing</option>
                                <option value="dashboard" {{ old('flyer_popup_target', $flyerTarget) === 'dashboard' ? 'selected' : '' }}>Hanya Dashboard Pelaku UMKM</option>
                            </select>
                            @error('flyer_popup_target')
                                <p class="text-xs text-red-500 mt-1.5 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col justify-between p-5 rounded-2xl border border-slate-150/70 bg-slate-50/30">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Navigasi Flyer</span>
                            <h4 class="font-bold text-slate-800 text-sm mt-1">Link Tujuan / Tautan Klik (Opsional)</h4>
                            <p class="text-xs text-slate-450 mt-1.5 leading-relaxed">
                                URL tujuan ketika pengguna mengklik gambar flyer. Dapat diisi dengan link pendaftaran program, pengumuman, atau artikel detail berita warta.
                            </p>
                        </div>

                        <div class="mt-4">
                            <div class="relative rounded-xl shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-slate-400 mdi mdi-link-variant text-base"></span>
                                </div>
                                <input type="url" name="flyer_popup_link" id="flyer_popup_link"
                                    value="{{ old('flyer_popup_link', $flyerLink) }}"
                                    placeholder="https://mandalaloka.go.id/program-bantuan"
                                    class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-slate-250 bg-white text-sm text-slate-700 font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-all">
                            </div>
                            @error('flyer_popup_link')
                                <p class="text-xs text-red-500 mt-1.5 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="pb-2 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            <span class="mdi mdi-image text-indigo-600 text-lg"></span>
                            Desain Flyer & Banner
                        </h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Unggah berkas desain flyer program Anda. Ukuran pop-up di layar pengguna akan mengikuti ukuran asli dari gambar yang Anda unggah.</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                        
                        <div class="lg:col-span-5 space-y-4">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Unggah Gambar Flyer Baru</label>
                            
                            <div class="relative group border-2 border-dashed border-slate-300 hover:border-indigo-400 rounded-2xl p-6 transition-all bg-slate-50/50 hover:bg-indigo-50/10 flex flex-col items-center justify-center text-center cursor-pointer min-h-[220px]">
                                <input type="file" name="flyer_popup_image" id="flyer_popup_image" accept="image/*"
                                    @change="previewImage"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                
                                <span class="mdi mdi-cloud-upload text-slate-400 group-hover:text-indigo-500 text-4xl transition-colors duration-300"></span>
                                <p class="text-xs font-bold text-slate-600 mt-2">Pilih file atau seret gambar ke sini</p>
                                <p class="text-[10px] text-slate-400 mt-1">PNG, JPG, JPEG, WEBP atau GIF (Maks. 2MB)</p>
                            </div>
                            
                            @error('flyer_popup_image')
                                <p class="text-xs text-red-500 font-bold mt-1.5">{{ $message }}</p>
                            @enderror

                            <div class="p-4 rounded-xl bg-blue-50/40 border border-blue-100/60 text-xs text-blue-700 space-y-1">
                                <p class="font-bold flex items-center gap-1">
                                    <span class="mdi mdi-information-outline"></span>
                                    Tips Proporsionalitas Flyer:
                                </p>
                                <ul class="list-disc pl-4 space-y-0.5 text-blue-600/95 leading-relaxed font-medium">
                                    <li>Gunakan aspect ratio potret (misal 4:5 atau 9:16) untuk tampilan flyer handphone yang lebih optimal.</li>
                                    <li>Gunakan format <strong>WEBP</strong> atau <strong>PNG</strong> yang sudah dikompresi agar loading pop-up cepat di sisi pengguna.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="lg:col-span-7 flex flex-col space-y-4">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Preview Flyer Aktif</label>
                            
                            <div class="flex-1 border border-slate-200 rounded-2xl bg-slate-100/50 overflow-hidden flex items-center justify-center min-h-[260px] p-4 relative">
                                
                                <div x-show="!previewUrl && !currentImageUrl" class="text-center text-slate-400">
                                    <span class="mdi mdi-image-off text-3xl"></span>
                                    <p class="text-xs mt-1 font-semibold">Belum ada flyer yang diunggah</p>
                                </div>

                                <div x-show="previewUrl" class="w-full flex justify-center h-full max-h-[400px]">
                                    <img :src="previewUrl" alt="Preview Flyer Baru" class="object-contain rounded-2xl max-w-full max-h-full shadow-md">
                                    <div class="absolute top-2 right-2 bg-indigo-600 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow">
                                        Berkas Baru
                                    </div>
                                </div>

                                <div x-show="currentImageUrl && !previewUrl" class="w-full flex justify-center h-full max-h-[400px]">
                                    <img :src="currentImageUrl" alt="Flyer Aktif Saat Ini" class="object-contain rounded-2xl max-w-full max-h-full shadow-md">
                                    <div class="absolute top-2 right-2 bg-emerald-600 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow">
                                        Aktif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3">
                <div class="text-xs text-slate-400 font-medium">
                    Terakhir diperbarui: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}
                </div>
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs md:text-sm px-6 py-2.5 rounded-md shadow-md hover:shadow-lg hover:scale-102 transition-all flex items-center gap-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    <span class="mdi mdi-content-save"></span>
                    <span>Simpan Pengaturan Flyer</span>
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function flyerPanel() {
                return {
                    previewUrl: null,
                    currentImageUrl: "{{ $flyerImage ? asset('storage/' . $flyerImage) : '' }}",
                    
                    previewImage(event) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.previewUrl = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        } else {
                            this.previewUrl = null;
                        }
                    }
                }
            }
        </script>
    @endpush
@endsection
