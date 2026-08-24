@php
    $mediaSosial = [];
    if (!empty($umkm->media_sosial)) {
        if (is_string($umkm->media_sosial)) {
            $mediaSosial = json_decode($umkm->media_sosial, true) ?: [];
        } elseif (is_array($umkm->media_sosial)) {
            $mediaSosial = $umkm->media_sosial;
        }
    }

    $galleries = [];
    if (!empty($umkm->foto_gallery)) {
        if (is_string($umkm->foto_gallery)) {
            $galleries = is_array($umkm->foto_gallery) ? $umkm->foto_gallery : (is_string($umkm->foto_gallery) ? json_decode($umkm->foto_gallery, true) : []);
        } elseif (is_array($umkm->foto_gallery)) {
            $galleries = $umkm->foto_gallery;
        }
    }

    $fotoKunjungan = [];
    if ($umkm->latestVerifikasiLapangan && !empty($umkm->latestVerifikasiLapangan->foto_kunjungan)) {
        if (is_string($umkm->latestVerifikasiLapangan->foto_kunjungan)) {
            $fotoKunjungan = json_decode($umkm->latestVerifikasiLapangan->foto_kunjungan, true) ?: [];
        } elseif (is_array($umkm->latestVerifikasiLapangan->foto_kunjungan)) {
            $fotoKunjungan = $umkm->latestVerifikasiLapangan->foto_kunjungan;
        }
    }

    // Tentukan Cover Banner berdasarkan Sektor UMKM
    $sektorLower = strtolower($umkm->sektor?->nama_sektor ?? '');
    $defaultCover = 'https://images.unsplash.com/photo-1528698827591-e19ccd7bc23d?auto=format&fit=crop&w=1200&q=80'; // default Toko
    
    if (str_contains($sektorLower, 'kuliner') || str_contains($sektorLower, 'makanan') || str_contains($sektorLower, 'minuman')) {
        $defaultCover = 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=80';
    } elseif (str_contains($sektorLower, 'fashion') || str_contains($sektorLower, 'pakaian') || str_contains($sektorLower, 'busana') || str_contains($sektorLower, 'rajut')) {
        $defaultCover = 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1200&q=80';
    } elseif (str_contains($sektorLower, 'jasa') || str_contains($sektorLower, 'servis') || str_contains($sektorLower, 'salon')) {
        $defaultCover = 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=1200&q=80';
    } elseif (str_contains($sektorLower, 'kerajinan') || str_contains($sektorLower, 'kriya') || str_contains($sektorLower, 'seni')) {
        $defaultCover = 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&w=1200&q=80';
    } elseif (str_contains($sektorLower, 'tani') || str_contains($sektorLower, 'kebun') || str_contains($sektorLower, 'hias')) {
        $defaultCover = 'https://images.unsplash.com/photo-1492496913980-501348b61469?auto=format&fit=crop&w=1200&q=80';
    }
    
    $coverUrl = $umkm->foto_utama ? Storage::url($umkm->foto_utama) : $defaultCover;
@endphp

<div class="space-y-5">
    
    <!-- Beautiful Cover Image Banner -->
    <div class="relative h-44 sm:h-52 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-sm flex items-center justify-center group cursor-pointer" onclick="openImageModal('{{ $coverUrl }}')">
        <img src="{{ $coverUrl }}" alt="{{ $umkm->nama_usaha }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-slate-900/10 to-transparent"></div>
        <div class="absolute bottom-4 left-4 text-white">
            <h3 class="text-lg font-black tracking-wide drop-shadow-md">{{ $umkm->nama_usaha }}</h3>
            <p class="text-xs font-mono text-indigo-200 mt-0.5 drop-shadow-md flex items-center gap-1">
                <i class="mdi mdi-identifier"></i> {{ $umkm->no_pendaftaran ?? '-' }}
            </p>
        </div>
    </div>
    
    <div class="flex items-center gap-2">
        <span
            class="px-2 py-0.5 text-xs font-semibold rounded-full 
            @if ($umkm->status_verifikasi == 'terverifikasi') bg-emerald-100 text-emerald-700
            @elseif($umkm->status_verifikasi == 'menunggu_verifikasi') bg-amber-100 text-amber-700
            @elseif($umkm->status_verifikasi == 'ditolak') bg-red-100 text-red-700
            @else bg-slate-100 text-slate-600 @endif">
            {{ ucfirst($umkm->status_verifikasi ?? 'Draft') }}
        </span>
    </div>

    @if ($umkm->foto_utama || count($galleries) > 0)
        <div class="bg-slate-50 rounded-xl p-4">
            <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                Foto UMKM
            </h4>
            @if ($umkm->foto_utama)
                <div class="mb-3">
                    <img src="{{ Storage::url($umkm->foto_utama) }}" alt="Foto Utama {{ $umkm->nama_usaha }}"
                        class="w-32 h-32 object-cover rounded-xl border-2 border-pink-200 shadow-sm cursor-pointer hover:opacity-90 transition-opacity"
                        onclick="openImageModal('{{ Storage::url($umkm->foto_utama) }}')">
                </div>
            @endif
            @if (count($galleries) > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach ($galleries as $gallery)
                        <img src="{{ Storage::url($gallery) }}" alt="Gallery"
                            class="w-16 h-16 object-cover rounded-2xl border border-pink-100 cursor-pointer hover:opacity-90 transition-opacity"
                            onclick="openImageModal('{{ Storage::url($gallery) }}')">
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    @if ($umkm->latestVerifikasiLapangan)
        @php
            $verifikasiLapangan = $umkm->latestVerifikasiLapangan;
            $kondisiText = [
                'sesuai' => 'Sesuai Data',
                'tidak_sesuai' => 'Tidak Sesuai Data',
                'tutup_sementara' => 'Tutup Sementara',
                'tidak_ditemukan' => 'Tidak Ditemukan',
            ][$verifikasiLapangan->kondisi_usaha] ?? $verifikasiLapangan->kondisi_usaha;
        @endphp
        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4">
            <h4 class="text-sm font-semibold text-indigo-800 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Dokumen Pendukung Verifikasi Lapangan
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                <div>
                    <label class="text-xs text-indigo-400">Tanggal</label>
                    <p class="font-semibold text-indigo-900">{{ \Carbon\Carbon::parse($verifikasiLapangan->tanggal_kunjungan)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <label class="text-xs text-indigo-400">Kondisi</label>
                    <p class="font-semibold text-indigo-900">{{ $kondisiText }}</p>
                </div>
                <div>
                    <label class="text-xs text-indigo-400">Petugas</label>
                    <p class="font-semibold text-indigo-900">{{ $verifikasiLapangan->petugas?->name ?? 'Petugas' }}</p>
                </div>
            </div>
            @if($verifikasiLapangan->catatan_kunjungan)
                <div class="mt-3">
                    <label class="text-xs text-indigo-400">Catatan Petugas</label>
                    <p class="text-sm text-indigo-900">{{ $verifikasiLapangan->catatan_kunjungan }}</p>
                </div>
            @endif
            @if(count($fotoKunjungan) > 0)
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($fotoKunjungan as $foto)
                        <img src="{{ Storage::url($foto) }}" alt="Foto Kunjungan"
                            class="w-16 h-16 object-cover rounded-2xl border border-indigo-200 cursor-pointer hover:opacity-90 transition-opacity"
                            onclick="openImageModal('{{ Storage::url($foto) }}')">
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <div>
        <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2 border-b pb-2">
            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                </path>
            </svg>
            Informasi UMKM
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="col-span-2 md:col-span-2">
                <label class="text-xs text-slate-700">Nama Usaha</label>
                <p class="text-base font-semibold text-slate-800 nama-usaha-value">{{ $umkm->nama_usaha ?? '-' }}</p>
            </div>
            <div>
                <label class="text-xs text-slate-700">Skala Usaha</label>
                <p class="text-sm">
                    <span class="inline-flex px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-700">
                    </span>
                </p>
            </div>
            <div>
                <label class="text-xs text-slate-700">Sektor Usaha</label>
                <p class="text-sm">{{ $umkm->sektor?->nama_sektor ?? '-' }}</p>
            </div>
            <div>
                <label class="text-xs text-slate-700">Bentuk Jualan</label>
                <p class="text-sm">{{ $umkm->bentuk_jualan ?? '-' }}</p>
            </div>
            <div>
                <label class="text-xs text-slate-700">Status Usaha</label>
                <p>
                    <span
                        class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $umkm->status_usaha == 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                        <span
                            class="w-1.5 h-1.5 rounded-full {{ $umkm->status_usaha == 'aktif' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                        {{ ucfirst($umkm->status_usaha ?? '-') }}
                    </span>
                </p>
            </div>
            <div>
                <label class="text-xs text-slate-700">Tahun Berdiri</label>
                <p class="text-sm">{{ $umkm->tahun_berdiri ?? '-' }}</p>
            </div>
            <div>
                <label class="text-xs text-slate-700">No. Telepon</label>
                <p class="text-sm">{{ $umkm->telp_usaha ?? '-' }}</p>
            </div>
            <div class="col-span-2">
                <label class="text-xs text-slate-700">Alamat Usaha</label>
                <p class="text-sm">{{ $umkm->alamat_usaha ?? '-' }}</p>
            </div>
            <div class="col-span-2">
                <label class="text-xs text-slate-700">Deskripsi</label>
                <p class="text-sm">{{ Str::limit($umkm->deskripsi ?? '-', 150) }}</p>
            </div>
        </div>
    </div>

    <div>
        <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2 border-b pb-2">
            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Informasi Pemilik
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="text-xs text-slate-700">Nama Lengkap</label>
                <p class="text-sm font-medium">{{ $umkm->pemilik?->nama_lengkap ?? '-' }}</p>
            </div>
            <div>
                <label class="text-xs text-slate-700">NIK</label>
                <p class="text-sm font-mono">{{ $umkm->pemilik?->nik ?? '-' }}</p>
            </div>
            <div>
                <label class="text-xs text-slate-700">No. Telepon</label>
                <p class="text-sm">{{ $umkm->pemilik?->no_hp ?? ($umkm->pemilik?->no_telepon ?? '-') }}</p>
            </div>
            <div>
                <label class="text-xs text-slate-700">Email</label>
                <p class="text-sm">{{ $umkm->pemilik?->email ?? '-' }}</p>
            </div>
            <div>
                <label class="text-xs text-slate-700">Kelurahan</label>
                <p class="text-sm">{{ $umkm->pemilik?->kelurahan ?? '-' }}</p>
            </div>
            <div class="col-span-2">
                <label class="text-xs text-slate-700">Alamat</label>
                <p class="text-sm">{{ $umkm->pemilik?->alamat ?? '-' }}</p>
            </div>
        </div>
    </div>

    <div>
        <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2 border-b pb-2">
            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                </path>
            </svg>
            Tenaga Kerja
        </h4>
        <div class="grid grid-cols-3 gap-2 text-center">
            <div class="bg-slate-50 rounded-2xl p-2">
                <p class="text-lg font-bold text-blue-600">{{ $umkm->jumlah_tenaga_kerja ?? 0 }}</p>
                <p class="text-xs text-slate-700">Total</p>
            </div>
            <div class="bg-slate-50 rounded-2xl p-2">
                <p class="text-lg font-bold text-blue-600">{{ $umkm->tenaga_kerja_laki ?? 0 }}</p>
                <p class="text-xs text-slate-700">Laki-laki</p>
            </div>
            <div class="bg-slate-50 rounded-2xl p-2">
                <p class="text-lg font-bold text-blue-600">{{ $umkm->tenaga_kerja_perempuan ?? 0 }}</p>
                <p class="text-xs text-slate-700">Perempuan</p>
            </div>
        </div>
    </div>

    @if (
        !empty($mediaSosial) &&
            (($mediaSosial['facebook'] ?? false) ||
                ($mediaSosial['instagram'] ?? false) ||
                ($mediaSosial['tiktok'] ?? false) ||
                ($mediaSosial['ecommerce'] ?? false)))
        <div>
            <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2 border-b pb-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7">
                    </path>
                </svg>
                Media Sosial
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                @if ($mediaSosial['facebook'] ?? false)
                    <div class="bg-slate-50 rounded-2xl p-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="text-xs truncate">{{ $mediaSosial['facebook'] }}</span>
                    </div>
                @endif
                @if ($mediaSosial['instagram'] ?? false)
                    <div class="bg-slate-50 rounded-2xl p-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-xs truncate">{{ $mediaSosial['instagram'] }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if ($umkm->status_verifikasi == 'ditolak' && $umkm->catatan_penolakan)
        <div class="bg-red-50 rounded-xl p-3 border border-red-200">
            <div class="flex gap-2">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-xs font-semibold text-red-600">Catatan Penolakan</p>
                    <p class="text-sm text-red-700">{{ $umkm->catatan_penolakan }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Produk Terdaftar -->
    <div class="mt-4 pt-4 border-t border-slate-200">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                Daftar Produk Terdaftar
            </h4>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                {{ $umkm->produks ? $umkm->produks->count() : 0 }} Produk
            </span>
        </div>

        @if($umkm->produks && $umkm->produks->count() > 0)
            <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 font-bold uppercase border-b border-slate-200 text-[10px] tracking-wider">
                        <tr>
                            <th class="px-3 py-2.5 text-center w-12">No</th>
                            <th class="px-3 py-2.5">Foto</th>
                            <th class="px-3 py-2.5">Nama Produk</th>
                            <th class="px-3 py-2.5">Harga</th>
                            <th class="px-3 py-2.5">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($umkm->produks as $idx => $prod)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-3 py-2.5 text-center font-medium text-slate-400">
                                    {{ $idx + 1 }}
                                </td>
                                <td class="px-3 py-2.5">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 flex-shrink-0 cursor-pointer hover:opacity-80 transition"
                                         onclick="openImageModal('{{ $prod->foto_utama }}')">
                                        <img src="{{ $prod->foto_utama }}" alt="{{ $prod->nama_produk }}" 
                                             class="w-full h-full object-cover"
                                             onerror="this.onerror=null;this.src='{{ $prod->dummy_foto }}';">
                                    </div>
                                </td>
                                <td class="px-3 py-2.5 font-bold text-slate-800">
                                    {{ $prod->nama_produk }}
                                </td>
                                <td class="px-3 py-2.5 font-extrabold text-emerald-600 whitespace-nowrap">
                                    {{ $prod->harga ? 'Rp ' . number_format($prod->harga, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-3 py-2.5 text-slate-500 max-w-xs truncate" title="{{ $prod->deskripsi ?? '' }}">
                                    {{ $prod->deskripsi ?: '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-4 bg-slate-50 rounded-xl border border-dashed border-slate-200 text-center">
                <svg class="w-8 h-8 mx-auto text-slate-300 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <p class="text-xs font-medium text-slate-500">Belum ada produk yang didaftarkan untuk UMKM ini.</p>
            </div>
        @endif
    </div>
</div>

<style>
    #modalDetailContent .cursor-pointer {
        cursor: pointer;
    }
</style>

<script>
    if (typeof window.openImageModal !== 'function' || !document.getElementById('imageModal')) {
        window.openImageModal = function(imageUrl) {
            if (!imageUrl) return;
            let modal = document.getElementById('globalImageModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'globalImageModal';
                modal.className = 'fixed inset-0 z-[99999] hidden bg-black/90 backdrop-blur-sm transition-opacity duration-300 flex items-center justify-center p-4';
                modal.onclick = function() { window.closeImageModal(); };
                modal.innerHTML = `
                    <div class="relative max-w-4xl max-h-[90vh] flex items-center justify-center" onclick="event.stopPropagation()">
                        <button onclick="window.closeImageModal()" class="absolute -top-10 -right-2 text-white hover:text-red-400 transition-colors p-2 z-10 focus:outline-none" title="Tutup">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <img id="globalModalImage" src="" alt="Preview Gambar" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain border border-white/20">
                    </div>
                `;
                document.body.appendChild(modal);
            }
            const modalImage = document.getElementById('globalModalImage');
            if (modalImage) modalImage.src = imageUrl;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        window.closeImageModal = function() {
            const modal = document.getElementById('globalImageModal');
            if (modal) {
                modal.classList.add('hidden');
            }
            const oldModal = document.getElementById('imageModal');
            if (oldModal) {
                oldModal.classList.add('hidden');
            }
            document.body.style.overflow = '';
        };
    }
</script>
