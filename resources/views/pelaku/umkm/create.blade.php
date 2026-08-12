@extends('layouts.pelaku')

@section('title', 'Pendaftaran UMKM Baru')
@section('breadcrumb', 'Daftar Usaha')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
<style>
    .leaflet-container { z-index: 10 !important; border-radius: 1rem; }
    /* Hide scrollbar for step navigation if it overflows */
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
                    <i class="mdi mdi-store-plus text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Pendaftaran UMKM Baru</h2>
                    <p class="text-slate-500 mt-1 font-medium">Lengkapi data usaha Anda untuk masuk ke sistem katalog.</p>
                </div>
            </div>
            <a href="{{ route('pelaku.dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-slate-200 hover:border-indigo-300 hover:text-indigo-600 text-slate-700 font-bold rounded-lg transition-all shadow-sm">
                <i class="mdi mdi-arrow-left"></i> Kembali ke Dasbor
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

        <div class="bg-indigo-50/50 backdrop-blur-sm p-6 rounded-2xl border-2 border-indigo-100 flex gap-4 items-start shadow-inner">
            <div class="w-10 h-10 bg-indigo-200 text-indigo-700 rounded-full flex items-center justify-center shrink-0">
                <i class="mdi mdi-information-variant text-2xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-indigo-900 mb-1">Informasi Pemilik Terintegrasi</h4>
                <p class="text-sm text-indigo-800/80 font-medium leading-relaxed">
                    Data usaha ini akan secara otomatis terhubung dengan profil identitas Anda atas nama <span class="font-black text-indigo-900">{{ $pemilik->nama_lengkap }}</span>. 
                    Pastikan mengisi informasi usaha dengan valid sesuai dokumen asli.
                </p>
            </div>
        </div>

        <form action="{{ route('pelaku.umkm.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 relative">
            @csrf

            <!-- Card 1: Informasi Dasar -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white/80 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none transform translate-x-1/2 -translate-y-1/2"></div>
                
                <div class="px-8 pt-8 pb-6 border-b border-slate-100 relative z-10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 font-black">1</div>
                    <h3 class="text-xl font-bold text-slate-800">Identitas & Profil Usaha</h3>
                </div>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Usaha / Merek <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_usaha" value="{{ old('nama_usaha') }}" required placeholder="Contoh: Kopi Kenangan Mantan"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-bold text-lg rounded-lg focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Bentuk Jualan <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="bentuk_jualan" required
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-700 font-medium text-base rounded-lg focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 appearance-none transition-all cursor-pointer">
                                <option value="" disabled selected>Pilih Bentuk Jualan...</option>
                                <option value="Toko Fisik" {{ old('bentuk_jualan') == 'Toko Fisik' ? 'selected' : '' }}>Toko Fisik</option>
                                <option value="Toko Online" {{ old('bentuk_jualan') == 'Toko Online' ? 'selected' : '' }}>Toko Online</option>
                                <option value="Hybrid (Fisik & Online)" {{ old('bentuk_jualan') == 'Hybrid (Fisik & Online)' ? 'selected' : '' }}>Hybrid (Fisik & Online)</option>
                                <option value="Keliling" {{ old('bentuk_jualan') == 'Keliling' ? 'selected' : '' }}>Keliling</option>
                                <option value="Rumahan / Pre-order" {{ old('bentuk_jualan') == 'Rumahan / Pre-order' ? 'selected' : '' }}>Rumahan / Pre-order</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                <i class="mdi mdi-chevron-down text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Perkiraan Omset Bulanan (Opsional)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-500 font-bold">
                                Rp
                            </div>
                            <input type="number" name="perkiraan_omset" value="{{ old('perkiraan_omset', $umkm->perkiraan_omset ?? '') }}" placeholder="Contoh: 5000000" min="0" step="1000"
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-700 font-medium text-base rounded-lg focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block pl-12 p-4 transition-all">
                        </div>
                        @error('perkiraan_omset')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Sektor Usaha <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="id_sektor" required
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-700 font-medium text-base rounded-lg focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 appearance-none transition-all cursor-pointer">
                                <option value="" disabled selected>Pilih Sektor...</option>
                                @foreach ($sektor as $sek)
                                    <option value="{{ $sek->id }}" {{ old('id_sektor') == $sek->id ? 'selected' : '' }}>{{ $sek->nama_sektor }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                <i class="mdi mdi-chevron-down text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tahun Berdiri <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="number" name="tahun_berdiri" value="{{ old('tahun_berdiri') }}" placeholder="Contoh: 2020" min="1900" max="{{ date('Y') }}"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-lg focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah Tenaga Kerja <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="number" name="jumlah_tenaga_kerja" value="{{ old('jumlah_tenaga_kerja', 0) }}" min="0"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-lg focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 transition-all">
                    </div>
                </div>
            </div>

            <!-- Card 2: Legalitas & Kontak -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white/80 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-teal-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none transform translate-x-1/2 -translate-y-1/2"></div>
                
                <div class="px-8 pt-8 pb-6 border-b border-slate-100 relative z-10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600 font-black">2</div>
                    <h3 class="text-xl font-bold text-slate-800">Legalitas & Kontak</h3>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">NIB / Izin Usaha <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="no_izin_usaha" value="{{ old('no_izin_usaha') }}" placeholder="Nomor Induk Berusaha"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-lg focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 block p-4 transition-all uppercase">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">NPWP Usaha <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="npwp_usaha" value="{{ old('npwp_usaha') }}" placeholder="Format: 12.345.678.9-012.000"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-lg focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 block p-4 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Telepon/WA Usaha <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 font-bold">
                                <i class="mdi mdi-whatsapp text-lg"></i>
                            </div>
                            <input type="text" name="telp_usaha" value="{{ old('telp_usaha') }}" placeholder="08123..."
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-lg focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 block p-4 pl-12 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Email Usaha <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 font-bold">
                                <i class="mdi mdi-email-outline text-lg"></i>
                            </div>
                            <input type="email" name="email_usaha" value="{{ old('email_usaha') }}" placeholder="usaha@domain.com"
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-lg focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 block p-4 pl-12 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Lokasi & Deskripsi -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white/80 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-amber-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none transform translate-x-1/2 -translate-y-1/2"></div>
                
                <div class="px-8 pt-8 pb-6 border-b border-slate-100 relative z-10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 font-black">3</div>
                    <h3 class="text-xl font-bold text-slate-800">Detail Tempat & Deskripsi</h3>
                </div>

                <div class="p-8 grid grid-cols-1 gap-8 relative z-10">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Tempat Usaha <span class="text-rose-500">*</span></label>
                        <textarea name="alamat_usaha" required rows="3" placeholder="Nama Jalan, Blok, RT/RW, Patokan Lokasi..."
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 block p-4 transition-all resize-none">{{ old('alamat_usaha') }}</textarea>
                    </div>
                    
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-bold text-slate-700">Titik Koordinat Lokasi <span class="text-rose-500">*</span></label>
                            <button type="button" id="btn-get-location" class="text-xs bg-indigo-100 text-indigo-700 px-3 py-1.5 rounded-lg font-bold hover:bg-indigo-200 transition-colors flex items-center gap-1">
                                <i class="mdi mdi-crosshairs-gps"></i> Gunakan Lokasi Saat Ini
                            </button>
                        </div>
                        <div id="map" class="w-full h-64 rounded-xl border-2 border-slate-200 z-10 mb-2"></div>
                        <p class="text-xs text-slate-500 font-medium mb-3"><i class="mdi mdi-information-outline"></i> Geser penanda (pin) ke lokasi persis usaha Anda.</p>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Latitude</label>
                                <input type="text" id="latitude" name="latitude" value="{{ old('latitude', '-6.90389') }}" readonly
                                    class="w-full bg-slate-100 border-2 border-slate-200 text-slate-500 font-medium text-sm rounded-lg p-2.5 outline-none cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Longitude</label>
                                <input type="text" id="longitude" name="longitude" value="{{ old('longitude', '107.66861') }}" readonly
                                    class="w-full bg-slate-100 border-2 border-slate-200 text-slate-500 font-medium text-sm rounded-lg p-2.5 outline-none cursor-not-allowed">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Produk/Jasa <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <textarea name="deskripsi" rows="4" placeholder="Ceritakan tentang keunggulan, produk utama, atau sejarah singkat usaha Anda..."
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 block p-4 transition-all resize-none">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Card 4: Media & Galeri -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white/80 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-pink-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none transform translate-x-1/2 -translate-y-1/2"></div>
                
                <div class="px-8 pt-8 pb-6 border-b border-slate-100 relative z-10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-pink-100 flex items-center justify-center text-pink-600 font-black">4</div>
                    <h3 class="text-xl font-bold text-slate-800">Foto Usaha</h3>
                </div>

                <div class="p-8 relative z-10">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Foto Utama (Banner/Toko/Produk) <span class="text-rose-500">*</span></label>
                    <p class="text-sm text-slate-500 mb-6 font-medium">Foto ini akan ditampilkan sebagai sampul usaha Anda di e-katalog.</p>
                    
                    <div class="w-full">
                        <label for="foto_utama"
                            class="flex flex-col items-center justify-center w-full h-64 border-2 border-pink-300 border-dashed rounded-3xl cursor-pointer hover:border-pink-500 hover:bg-pink-50/50 bg-slate-50 transition-all group overflow-hidden relative"
                            id="fotoUtamaLabel">
                            
                            <div id="fotoUtamaPlaceholder" class="text-center group-hover:scale-110 transition-transform duration-300">
                                <div class="w-20 h-20 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4 text-pink-500">
                                    <i class="mdi mdi-camera-plus text-4xl"></i>
                                </div>
                                <p class="text-lg text-pink-700 font-bold">Klik untuk Mengunggah Foto</p>
                                <p class="text-sm text-slate-500 mt-2 font-medium">Maksimal 5MB (JPG, PNG, WEBP)</p>
                            </div>
                            
                            <img id="fotoUtamaPreview" class="hidden absolute inset-0 w-full h-full object-cover z-10" src="" alt="Preview">
                            
                            <input id="foto_utama" name="foto_utama" type="file" class="sr-only" accept="image/*" required>
                        </label>
                        
                        <div class="flex justify-end mt-4">
                            <button type="button" id="btnHapusUtama"
                                onclick="hapusFoto('foto_utama','fotoUtamaPreview','fotoUtamaPlaceholder')"
                                class="hidden px-4 py-2 bg-rose-100 text-rose-700 font-bold rounded-lg hover:bg-rose-200 transition-colors text-sm flex items-center gap-2">
                                <i class="mdi mdi-delete-outline text-lg"></i> Hapus Foto
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-8 border-t border-slate-100 pt-8">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Foto Galeri / Etalase Produk <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <p class="text-sm text-slate-500 mb-4 font-medium">Tambahkan beberapa foto lainnya untuk menampilkan produk, menu, atau suasana tempat usaha Anda (Maks. 5MB per foto).</p>
                        
                        <label for="foto_gallery" class="inline-flex items-center gap-2 px-5 py-2.5 bg-pink-50 text-pink-700 font-bold text-sm rounded-lg hover:bg-pink-100 transition-colors border border-pink-200 cursor-pointer">
                            <i class="mdi mdi-image-multiple text-lg"></i> Pilih Beberapa Foto Sekaligus
                        </label>
                        <input id="foto_gallery" name="foto_gallery[]" type="file" class="hidden" accept="image/*" multiple>
                        
                        <div id="galleryPreviewContainer" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 empty:hidden"></div>
                    </div>
                </div>
            </div>

            <!-- Card 5: Dokumen Pendukung -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white/80 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none transform translate-x-1/2 -translate-y-1/2"></div>
                
                <div class="px-8 pt-8 pb-6 border-b border-slate-100 relative z-10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 font-black">5</div>
                    <h3 class="text-xl font-bold text-slate-800">Dokumen Pendukung <span class="text-slate-400 font-normal text-sm">(Opsional)</span></h3>
                </div>

                <div class="p-8 relative z-10">
                    <p class="text-sm text-slate-500 mb-6 font-medium">Unggah dokumen ini untuk memudahkan proses pendataan saat ada program pelatihan atau bantuan di kemudian hari.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Dokumen NIB -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Bukti Perizinan (NIB)</label>
                            <input type="file" name="dokumen_nib" accept=".jpg,.jpeg,.png,.pdf"
                                class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all border border-slate-200 rounded-md p-2 bg-slate-50 hover:bg-white cursor-pointer">
                        </div>

                        <!-- Dokumen Lainnya -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Dokumen Lainnya</label>
                            <input type="file" name="dokumen_lainnya" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all border border-slate-200 rounded-md p-2 bg-slate-50 hover:bg-white cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 6: Riwayat Bantuan yang Pernah Diterima -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white/80 overflow-hidden relative" x-data="{
                bantuans: [],
                addBantuan() {
                    this.bantuans.push({ nama: '', sumber: '', tahun: '', keterangan: '' });
                },
                removeBantuan(index) {
                    this.bantuans.splice(index, 1);
                }
            }">
                <div class="absolute top-0 right-0 w-64 h-64 bg-amber-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none transform translate-x-1/2 -translate-y-1/2"></div>
                
                <div class="px-8 pt-8 pb-6 border-b border-slate-100 relative z-10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 font-black">6</div>
                    <h3 class="text-xl font-bold text-slate-800">Riwayat Bantuan <span class="text-slate-400 font-normal text-sm">(Opsional)</span></h3>
                </div>

                <div class="p-8 relative z-10">
                    <p class="text-sm text-slate-500 mb-6 font-medium">Masukkan data bantuan yang pernah diterima oleh UMKM ini sebelumnya. Klik tombol <strong>"+ Tambah Bantuan"</strong> untuk menambahkan baris baru.</p>
                    
                    <!-- Table Header -->
                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">No</th>
                                    <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Nama Bantuan</th>
                                    <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Sumber</th>
                                    <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Tahun</th>
                                    <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Keterangan</th>
                                    <th class="text-center px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in bantuans" :key="index">
                                    <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors">
                                        <td class="px-4 py-3 text-slate-500 font-bold" x-text="index + 1"></td>
                                        <td class="px-4 py-2">
                                            <input type="text" :name="'bantuans['+index+'][nama_bantuan]'" x-model="item.nama" required
                                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-md focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 outline-none transition-all bg-white" placeholder="cth: Pelatihan Digital Marketing">
                                        </td>
                                        <td class="px-4 py-2">
                                            <input type="text" :name="'bantuans['+index+'][sumber_bantuan]'" x-model="item.sumber"
                                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-md focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 outline-none transition-all bg-white" placeholder="cth: Dinas Koperasi">
                                        </td>
                                        <td class="px-4 py-2">
                                            <input type="number" :name="'bantuans['+index+'][tahun_bantuan]'" x-model="item.tahun" min="2000" max="2099"
                                                class="w-24 px-3 py-2 text-sm border border-slate-200 rounded-md focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 outline-none transition-all bg-white" placeholder="2024">
                                        </td>
                                        <td class="px-4 py-2">
                                            <input type="text" :name="'bantuans['+index+'][keterangan]'" x-model="item.keterangan"
                                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-md focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 outline-none transition-all bg-white" placeholder="Opsional...">
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <button type="button" @click="removeBantuan(index)"
                                                class="p-2 text-rose-500 hover:bg-rose-50 rounded-2xl transition-colors" title="Hapus baris">
                                                <i class="mdi mdi-delete-outline text-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <!-- Empty State -->
                                <tr x-show="bantuans.length === 0">
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-sm">
                                        <i class="mdi mdi-hand-heart-outline text-3xl mb-2 block"></i>
                                        Belum ada data bantuan. Klik tombol di bawah untuk menambahkan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Add Button -->
                    <button type="button" @click="addBantuan()"
                        class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-amber-50 text-amber-700 font-bold text-sm rounded-md hover:bg-amber-100 transition-colors border border-amber-200">
                        <i class="mdi mdi-plus-circle-outline text-lg"></i> Tambah Bantuan
                    </button>
                </div>
            </div>

            <!-- Sticky Actions Bar -->
            <div class="sticky bottom-4 z-50 mt-10">
                <div class="bg-slate-900/90 backdrop-blur-xl p-4 sm:px-8 sm:py-5 rounded-[2rem] shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-4 border border-slate-700">
                    <p class="text-slate-300 text-sm font-medium hidden sm:block">Pastikan semua data diisi dengan <span class="text-white font-bold">benar</span> dan <span class="text-white font-bold">lengkap</span>.</p>
                    
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <button type="submit" name="action" value="simpan"
                            class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-black rounded-xl transition-all shadow-lg shadow-indigo-500/30 hover:-translate-y-1 hover:shadow-indigo-500/50 flex items-center justify-center gap-2">
                            <i class="mdi mdi-content-save-outline text-xl"></i> Simpan Data UMKM
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');
                
                let initialLat = parseFloat(latInput.value) || -6.90389; // Default Mandalajati
                let initialLng = parseFloat(lngInput.value) || 107.66861;

                const map = L.map('map').setView([initialLat, initialLng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                let marker = L.marker([initialLat, initialLng], {draggable: true}).addTo(map);

                marker.on('dragend', function (e) {
                    const position = marker.getLatLng();
                    latInput.value = position.lat.toFixed(6);
                    lngInput.value = position.lng.toFixed(6);
                });

                map.on('click', function(e) {
                    marker.setLatLng(e.latlng);
                    latInput.value = e.latlng.lat.toFixed(6);
                    lngInput.value = e.latlng.lng.toFixed(6);
                });

                document.getElementById('btn-get-location').addEventListener('click', function() {
                    if (navigator.geolocation) {
                        const btn = this;
                        const originalText = btn.innerHTML;
                        btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Mencari...';
                        
                        navigator.geolocation.getCurrentPosition(function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            map.setView([lat, lng], 17);
                            marker.setLatLng([lat, lng]);
                            latInput.value = lat.toFixed(6);
                            lngInput.value = lng.toFixed(6);
                            
                            btn.innerHTML = originalText;
                        }, function(error) {
                            alert("Tidak dapat mengambil lokasi. Pastikan izin lokasi diaktifkan pada browser Anda.");
                            btn.innerHTML = originalText;
                        });
                    } else {
                        alert("Browser Anda tidak mendukung fitur lokasi otomatis.");
                    }
                });
            });

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

            // Preview foto gallery
            document.getElementById('foto_gallery').addEventListener('change', function(e) {
                const container = document.getElementById('galleryPreviewContainer');
                container.innerHTML = ''; // reset preview
                const files = e.target.files;
                if (!files.length) return;
                
                Array.from(files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        const div = document.createElement('div');
                        div.className = 'relative group aspect-square rounded-xl overflow-hidden border border-slate-200';
                        div.innerHTML = `
                            <img src="${ev.target.result}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-bold px-2 py-1 bg-slate-900/80 rounded-md">Foto ${index + 1}</span>
                            </div>
                        `;
                        container.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            });
        </script>
    @endpush
@endsection
