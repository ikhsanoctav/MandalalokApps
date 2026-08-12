@extends('layouts.pelaku')

@section('title', 'Revisi Data UMKM')
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
                    <i class="mdi mdi-file-document-edit-outline text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Revisi Data UMKM</h2>
                    <p class="text-slate-500 mt-1 font-medium">Perbarui informasi usaha UMKM Anda untuk evaluasi ulang.</p>
                </div>
            </div>
            <a href="{{ route('pelaku.dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-slate-200 hover:border-indigo-300 hover:text-indigo-600 text-slate-700 font-bold rounded-lg transition-all shadow-sm">
                <i class="mdi mdi-arrow-left"></i> Kembali ke Dasbor
            </a>
        </div>

        @if ($umkm->status_verifikasi == 'ditolak')
            <div class="bg-rose-50 p-6 rounded-2xl border-2 border-rose-200 flex items-start gap-4 shadow-sm animate-pulse-slow">
                <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center shrink-0 border border-rose-200">
                    <i class="mdi mdi-alert-circle-outline text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-rose-800 text-lg mb-1">Pendaftaran Sebelumnya Ditolak</h3>
                    <p class="text-sm text-rose-700 font-bold uppercase tracking-wider mb-2">Catatan Evaluator:</p>
                    <div class="bg-white p-4 rounded-xl border border-rose-100 text-rose-800 font-medium">
                        {{ $umkm->catatan_penolakan ?? 'Harap periksa kembali kelengkapan dan kebenaran data Anda.' }}
                    </div>
                </div>
            </div>
        @endif

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

        <form action="{{ route('pelaku.umkm.update', $umkm->id_umkm) }}" method="POST" enctype="multipart/form-data" class="space-y-8 relative">
            @csrf
            @method('PUT')

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
                        <input type="text" name="nama_usaha" value="{{ old('nama_usaha', $umkm->nama_usaha) }}" required placeholder="Contoh: Warung Makan Budi"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-bold text-lg rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Bentuk Jualan <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="bentuk_jualan" required
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-700 font-medium text-base rounded-lg focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 appearance-none transition-all cursor-pointer">
                                <option value="" disabled selected>Pilih Bentuk Jualan...</option>
                                <option value="Toko Fisik" {{ old('bentuk_jualan', $umkm->bentuk_jualan) == 'Toko Fisik' ? 'selected' : '' }}>Toko Fisik</option>
                                <option value="Toko Online" {{ old('bentuk_jualan', $umkm->bentuk_jualan) == 'Toko Online' ? 'selected' : '' }}>Toko Online</option>
                                <option value="Hybrid (Fisik & Online)" {{ old('bentuk_jualan', $umkm->bentuk_jualan) == 'Hybrid (Fisik & Online)' ? 'selected' : '' }}>Hybrid (Fisik & Online)</option>
                                <option value="Keliling" {{ old('bentuk_jualan', $umkm->bentuk_jualan) == 'Keliling' ? 'selected' : '' }}>Keliling</option>
                                <option value="Rumahan / Pre-order" {{ old('bentuk_jualan', $umkm->bentuk_jualan) == 'Rumahan / Pre-order' ? 'selected' : '' }}>Rumahan / Pre-order</option>
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
                                    <option value="{{ $sek->id }}" {{ old('id_sektor', $umkm->id_sektor) == $sek->id ? 'selected' : '' }}>{{ $sek->nama_sektor }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                <i class="mdi mdi-chevron-down text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tahun Berdiri <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="number" name="tahun_berdiri" value="{{ old('tahun_berdiri', $umkm->tahun_berdiri) }}" placeholder="Contoh: 2020" min="1900" max="{{ date('Y') }}"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah Tenaga Kerja <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="number" name="jumlah_tenaga_kerja" value="{{ old('jumlah_tenaga_kerja', $umkm->jumlah_tenaga_kerja) }}" min="0"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 transition-all">
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
                        <input type="text" name="no_izin_usaha" value="{{ old('no_izin_usaha', $umkm->no_izin_usaha) }}" placeholder="Nomor Induk Berusaha"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 block p-4 transition-all uppercase">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">NPWP Usaha <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="npwp_usaha" value="{{ old('npwp_usaha', $umkm->npwp_usaha) }}" placeholder="Format: 12.345.678.9-012.000"
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 block p-4 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Telepon/WA Usaha <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 font-bold">
                                <i class="mdi mdi-whatsapp text-lg"></i>
                            </div>
                            <input type="text" name="telp_usaha" value="{{ old('telp_usaha', $umkm->telp_usaha) }}" placeholder="08123..."
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 block p-4 pl-12 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Email Usaha <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 font-bold">
                                <i class="mdi mdi-email-outline text-lg"></i>
                            </div>
                            <input type="email" name="email_usaha" value="{{ old('email_usaha', $umkm->email_usaha) }}" placeholder="usaha@domain.com"
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 block p-4 pl-12 transition-all">
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
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 block p-4 transition-all resize-none">{{ old('alamat_usaha', $umkm->alamat_usaha) }}</textarea>
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
                                <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $umkm->latitude ?? '-6.90389') }}" readonly
                                    class="w-full bg-slate-100 border-2 border-slate-200 text-slate-500 font-medium text-sm rounded-lg p-2.5 outline-none cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Longitude</label>
                                <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $umkm->longitude ?? '107.66861') }}" readonly
                                    class="w-full bg-slate-100 border-2 border-slate-200 text-slate-500 font-medium text-sm rounded-lg p-2.5 outline-none cursor-not-allowed">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Produk/Jasa <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <textarea name="deskripsi" rows="4" placeholder="Ceritakan tentang keunggulan, produk utama, atau sejarah singkat usaha Anda..."
                            class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 block p-4 transition-all resize-none">{{ old('deskripsi', $umkm->deskripsi) }}</textarea>
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
                    <label class="block text-sm font-bold text-slate-700 mb-2">Foto Utama (Banner/Toko/Produk) <span class="text-slate-400 font-normal">(Opsional, jika ingin diganti)</span></label>
                    <p class="text-sm text-slate-500 mb-6 font-medium">Foto ini akan ditampilkan sebagai sampul usaha Anda di e-katalog.</p>
                    
                    <div class="flex flex-col md:flex-row gap-8">
                        @if ($umkm->foto_utama)
                        <div class="w-full md:w-1/3">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Foto Saat Ini</p>
                            <div class="relative rounded-2xl overflow-hidden border border-slate-200 shadow-sm group">
                                <img src="{{ Storage::url($umkm->foto_utama) }}" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                                <label class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm p-2 rounded-xl shadow-sm border border-slate-200 cursor-pointer flex items-center gap-2 hover:bg-rose-50 hover:border-rose-200 transition-colors">
                                    <input type="checkbox" name="hapus_foto_utama" value="1" class="w-4 h-4 text-rose-600 rounded-lg border-slate-300 focus:ring-rose-500">
                                    <span class="text-xs font-bold text-rose-600">Hapus Foto</span>
                                </label>
                            </div>
                        </div>
                        @endif

                        <div class="w-full @if($umkm->foto_utama) md:w-2/3 @endif">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Unggah Foto Baru</p>
                            <label for="foto_utama"
                                class="flex flex-col items-center justify-center w-full h-48 border-2 border-pink-300 border-dashed rounded-2xl cursor-pointer hover:border-pink-500 hover:bg-pink-50/50 bg-slate-50 transition-all group overflow-hidden relative">
                                
                                <div id="fotoUtamaPlaceholder" class="text-center group-hover:scale-110 transition-transform duration-300">
                                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-3 text-pink-500">
                                        <i class="mdi mdi-cloud-upload-outline text-3xl"></i>
                                    </div>
                                    <p class="text-base text-pink-700 font-bold">Pilih File Baru</p>
                                    <p class="text-xs text-slate-500 mt-1 font-medium">Maks 5MB (JPG, PNG)</p>
                                </div>
                                
                                <img id="fotoUtamaPreview" class="hidden absolute inset-0 w-full h-full object-cover z-10" src="" alt="Preview">
                                
                                <input id="foto_utama" name="foto_utama" type="file" class="sr-only" accept="image/*">
                            </label>
                            
                        <div class="flex justify-end mt-4">
                                <button type="button" id="btnHapusUtama"
                                    onclick="hapusFoto('foto_utama','fotoUtamaPreview','fotoUtamaPlaceholder')"
                                    class="hidden px-4 py-2 bg-rose-100 text-rose-700 font-bold rounded-lg hover:bg-rose-200 transition-colors text-sm flex items-center gap-2">
                                    <i class="mdi mdi-delete-outline text-lg"></i> Batal Unggah
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 border-t border-slate-100 pt-8">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Foto Lokasi Usaha <span class="text-slate-400 font-normal">(Maks 4 Foto)</span></label>
                        <p class="text-sm text-slate-500 mb-4 font-medium">Tambahkan 2-4 foto yang menunjukkan bentuk fisik tempat/bangunan usaha Anda (Maks. 5MB per foto).</p>
                        
                        <!-- Existing Gallery -->
                        @php 
                            $fotoGallery = is_string($umkm->foto_gallery) ? json_decode($umkm->foto_gallery, true) : $umkm->foto_gallery;
                        @endphp
                        @if($fotoGallery && count($fotoGallery) > 0)
                            <div class="mb-6">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Foto Lokasi Saat Ini</p>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="existingGalleryContainer">
                                    @foreach($fotoGallery as $index => $foto)
                                        <div class="relative group aspect-square rounded-xl overflow-hidden border border-slate-200 shadow-sm" id="gallery-item-{{ $index }}">
                                            <img src="{{ Storage::url($foto) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                            <button type="button" onclick="hapusFotoGallery({{ $index }})" class="absolute bottom-3 right-3 bg-rose-500 text-white px-3 py-1.5 rounded-lg shadow-sm hover:bg-rose-600 transition-colors opacity-0 group-hover:opacity-100 flex items-center gap-2" title="Hapus Foto">
                                                <i class="mdi mdi-delete-outline text-lg"></i> <span class="text-xs font-bold">Hapus</span>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Unggah Foto Lokasi Baru</p>
                        <label for="foto_gallery" class="inline-flex items-center gap-2 px-5 py-2.5 bg-pink-50 text-pink-700 font-bold text-sm rounded-lg hover:bg-pink-100 transition-colors border border-pink-200 cursor-pointer">
                            <i class="mdi mdi-image-multiple text-lg"></i> Pilih Foto Lokasi Tambahan (Maks 4)
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
                    <p class="text-sm text-slate-500 mb-6 font-medium">Unggah dokumen ini untuk memudahkan proses pendataan saat ada program pelatihan atau bantuan di kemudian hari. Unggah file baru jika ingin mengganti dokumen sebelumnya.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Dokumen NIB -->
                        <div class="p-4 border border-slate-200 rounded-2xl bg-slate-50/50">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Bukti Perizinan (NIB)</label>
                            @if($umkm->dokumen_nib)
                                <div class="mb-3">
                                    <a href="{{ Storage::url($umkm->dokumen_nib) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-md hover:bg-emerald-200 transition-colors">
                                        <i class="mdi mdi-eye-outline text-base"></i> Lihat Dokumen
                                    </a>
                                </div>
                            @endif
                            <input type="file" name="dokumen_nib" accept=".jpg,.jpeg,.png,.pdf"
                                class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all border border-slate-200 rounded-md p-2 bg-white hover:bg-slate-50 cursor-pointer">
                        </div>

                        <!-- Dokumen Lainnya -->
                        <div class="p-4 border border-slate-200 rounded-2xl bg-slate-50/50">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Dokumen Lainnya</label>
                            @if($umkm->dokumen_lainnya)
                                <div class="mb-3">
                                    <a href="{{ Storage::url($umkm->dokumen_lainnya) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-md hover:bg-emerald-200 transition-colors">
                                        <i class="mdi mdi-eye-outline text-base"></i> Lihat Dokumen
                                    </a>
                                </div>
                            @endif
                            <input type="file" name="dokumen_lainnya" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all border border-slate-200 rounded-md p-2 bg-white hover:bg-slate-50 cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Actions Bar -->
            <div class="sticky bottom-4 z-50 mt-10">
                <div class="bg-slate-900/90 backdrop-blur-xl p-4 sm:px-8 sm:py-5 rounded-[2rem] shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-4 border border-slate-700">
                    <p class="text-slate-300 text-sm font-medium hidden sm:block">Pastikan semua data diisi dengan <span class="text-white font-bold">benar</span> dan <span class="text-white font-bold">lengkap</span>.</p>
                    
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <button type="submit" name="action" value="simpan"
                            class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-black rounded-xl transition-all shadow-lg shadow-indigo-500/30 hover:-translate-y-1 hover:shadow-indigo-500/50 flex items-center justify-center gap-2">
                            <i class="mdi mdi-content-save-outline text-xl"></i> Simpan Perubahan
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

            // Delete Foto Gallery AJAX
            window.hapusFotoGallery = function(index) {
                if(confirm('Apakah Anda yakin ingin menghapus foto lokasi ini?')) {
                    fetch(`{{ url('pelaku/umkm/'.$umkm->id_umkm.'/foto-gallery') }}/${index}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`gallery-item-${index}`).remove();
                        } else {
                            alert('Gagal menghapus foto.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus foto.');
                    });
                }
            };

            // Preview foto lokasi usaha
            document.getElementById('foto_gallery').addEventListener('change', function(e) {
                const container = document.getElementById('galleryPreviewContainer');
                container.innerHTML = ''; // reset preview
                const files = e.target.files;
                if (!files.length) return;

                if (files.length > 4) {
                    alert('Maksimal 4 foto yang diizinkan untuk diunggah.');
                    this.value = '';
                    return;
                }
                
                Array.from(files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        const div = document.createElement('div');
                        div.className = 'relative group aspect-square rounded-xl overflow-hidden border border-slate-200';
                        div.innerHTML = `
                            <img src="${ev.target.result}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-bold px-2 py-1 bg-slate-900/80 rounded-md">Foto Baru ${index + 1}</span>
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
