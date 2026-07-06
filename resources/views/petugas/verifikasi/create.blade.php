@extends('layouts.petugas')

@section('title', 'Catat Kunjungan Lapangan')
@section('breadcrumb', 'Catat Kunjungan')

@section('content')
    <div class="space-y-6 max-w-4xl mx-auto">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Catat Kunjungan Lapangan</h2>
                <p class="text-sm text-slate-500 mt-0.5">Formulir hasil verifikasi fisik UMKM di lapangan.</p>
            </div>
            <a href="{{ route('operator.verifikasi.show', $umkm->id_umkm) }}" class="text-sm text-slate-500 hover:text-slate-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Batal & Kembali
            </a>
        </div>

        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-1">Target Verifikasi</p>
                <h3 class="font-bold text-indigo-900 text-lg">{{ $umkm->nama_usaha }}</h3>
                <p class="text-sm text-indigo-700">Milik: {{ $umkm->pemilik->nama_lengkap ?? '-' }} • Kategori: {{ $umkm->kategori->nama_kategori ?? '-' }}</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-700 p-4 rounded-xl border border-red-100 shadow-sm">
                <div class="flex items-center gap-2 font-bold mb-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Terjadi Kesalahan
                </div>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('operator.verifikasi.store', $umkm->id_umkm) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" x-data="formController()">
            @csrf

            <div class="p-6 md:p-8 space-y-8">
                
                {{-- Data Dasar Kunjungan --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_kunjungan" value="{{ old('tanggal_kunjungan', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all p-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Keputusan Kunjungan <span class="text-red-500">*</span></label>
                        <select name="status_kunjungan" required
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all p-2.5">
                            <option value="dikunjungi" {{ old('status_kunjungan') == 'dikunjungi' ? 'selected' : '' }}>Selesai Dikunjungi (Aman)</option>
                            <option value="butuh_tindak_lanjut" {{ old('status_kunjungan') == 'butuh_tindak_lanjut' ? 'selected' : '' }}>Butuh Tindak Lanjut (Bermasalah)</option>
                        </select>
                    </div>
                </div>

                {{-- Hasil Kunjungan --}}
                <div class="border-t border-slate-100 pt-6">
                    <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Hasil Peninjauan
                    </h4>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-3">Kondisi Usaha di Lapangan <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                                <label class="relative flex items-center justify-center gap-2 p-3 border rounded-xl cursor-pointer transition-all hover:bg-slate-50 {{ old('kondisi_usaha') == 'sesuai' ? 'border-emerald-500 bg-emerald-50/50 ring-1 ring-emerald-500' : 'border-slate-200' }}">
                                    <input type="radio" name="kondisi_usaha" value="sesuai" class="hidden" {{ old('kondisi_usaha', 'sesuai') == 'sesuai' ? 'checked' : '' }} onchange="updateRadioStyles(this)">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-sm font-medium text-slate-700">Sesuai Data</span>
                                </label>
                                <label class="relative flex items-center justify-center gap-2 p-3 border rounded-xl cursor-pointer transition-all hover:bg-slate-50 {{ old('kondisi_usaha') == 'tidak_sesuai' ? 'border-red-500 bg-red-50/50 ring-1 ring-red-500' : 'border-slate-200' }}">
                                    <input type="radio" name="kondisi_usaha" value="tidak_sesuai" class="hidden" {{ old('kondisi_usaha') == 'tidak_sesuai' ? 'checked' : '' }} onchange="updateRadioStyles(this)">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                    <span class="text-sm font-medium text-slate-700">Tidak Sesuai</span>
                                </label>
                                <label class="relative flex items-center justify-center gap-2 p-3 border rounded-xl cursor-pointer transition-all hover:bg-slate-50 {{ old('kondisi_usaha') == 'tutup_sementara' ? 'border-amber-500 bg-amber-50/50 ring-1 ring-amber-500' : 'border-slate-200' }}">
                                    <input type="radio" name="kondisi_usaha" value="tutup_sementara" class="hidden" {{ old('kondisi_usaha') == 'tutup_sementara' ? 'checked' : '' }} onchange="updateRadioStyles(this)">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    <span class="text-sm font-medium text-slate-700">Tutup Sementara</span>
                                </label>
                                <label class="relative flex items-center justify-center gap-2 p-3 border rounded-xl cursor-pointer transition-all hover:bg-slate-50 {{ old('kondisi_usaha') == 'tidak_ditemukan' ? 'border-slate-500 bg-slate-100 ring-1 ring-slate-500' : 'border-slate-200' }}">
                                    <input type="radio" name="kondisi_usaha" value="tidak_ditemukan" class="hidden" {{ old('kondisi_usaha') == 'tidak_ditemukan' ? 'checked' : '' }} onchange="updateRadioStyles(this)">
                                    <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                                    <span class="text-sm font-medium text-slate-700">Tidak Ditemukan</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan Kunjungan</label>
                            <textarea name="catatan_kunjungan" rows="4" placeholder="Tuliskan temuan atau observasi di lapangan..."
                                class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all p-3">{{ old('catatan_kunjungan') }}</textarea>
                            <p class="text-xs text-slate-500 mt-1">Sangat disarankan untuk diisi jika kondisi 'Tidak Sesuai' atau 'Butuh Tindak Lanjut'.</p>
                        </div>
                    </div>
                </div>

                {{-- Bukti Fisik & Lokasi --}}
                <div class="border-t border-slate-100 pt-6">
                    <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Bukti Fisik & Lokasi
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Kunjungan (Opsional)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer" onclick="document.getElementById('foto_kunjungan').click()">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-slate-600 justify-center">
                                        <span class="relative font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Pilih Foto</span>
                                            <input id="foto_kunjungan" name="foto_kunjungan[]" type="file" class="sr-only" multiple accept="image/*" onchange="updateFileList(this)">
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500">Bisa pilih lebih dari satu foto (Max 5MB/foto)</p>
                                </div>
                            </div>
                            <div id="file-list" class="mt-2 flex flex-wrap gap-2 text-xs text-slate-600"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Kordinat Lokasi (Opsional)</label>
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50">
                                <div class="flex items-center gap-3 mb-3">
                                    <button type="button" @click="getLocation" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Ambil Lokasi Saat Ini
                                    </button>
                                    <span x-text="gpsStatus" class="text-xs font-medium" :class="{'text-emerald-600': lat !== '', 'text-slate-500': lat === ''}"></span>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <span class="block text-[10px] uppercase text-slate-500 font-bold mb-1">Latitude</span>
                                        <input type="text" name="latitude_kunjungan" x-model="lat" class="w-full bg-white border border-slate-200 rounded-lg p-1.5 text-sm text-slate-600 font-mono" readonly placeholder="-">
                                    </div>
                                    <div>
                                        <span class="block text-[10px] uppercase text-slate-500 font-bold mb-1">Longitude</span>
                                        <input type="text" name="longitude_kunjungan" x-model="lng" class="w-full bg-white border border-slate-200 rounded-lg p-1.5 text-sm text-slate-600 font-mono" readonly placeholder="-">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
                <a href="{{ route('operator.verifikasi.show', $umkm->id_umkm) }}" class="px-5 py-2 rounded-lg text-sm font-medium text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 shadow-sm shadow-blue-600/20 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Data Kunjungan
                </button>
            </div>
        </form>
    </div>

    <script>
        function updateRadioStyles(input) {
            // Reset all labels
            document.querySelectorAll('input[name="kondisi_usaha"]').forEach(el => {
                const label = el.closest('label');
                label.className = 'relative flex items-center justify-center gap-2 p-3 border rounded-xl cursor-pointer transition-all hover:bg-slate-50 border-slate-200';
            });
            
            // Set active label
            const label = input.closest('label');
            const val = input.value;
            if(val === 'sesuai') label.className = 'relative flex items-center justify-center gap-2 p-3 border rounded-xl cursor-pointer transition-all border-emerald-500 bg-emerald-50/50 ring-1 ring-emerald-500';
            else if(val === 'tidak_sesuai') label.className = 'relative flex items-center justify-center gap-2 p-3 border rounded-xl cursor-pointer transition-all border-red-500 bg-red-50/50 ring-1 ring-red-500';
            else if(val === 'tutup_sementara') label.className = 'relative flex items-center justify-center gap-2 p-3 border rounded-xl cursor-pointer transition-all border-amber-500 bg-amber-50/50 ring-1 ring-amber-500';
            else if(val === 'tidak_ditemukan') label.className = 'relative flex items-center justify-center gap-2 p-3 border rounded-xl cursor-pointer transition-all border-slate-500 bg-slate-100 ring-1 ring-slate-500';
        }

        function updateFileList(input) {
            const list = document.getElementById('file-list');
            list.innerHTML = '';
            if(input.files.length > 0) {
                Array.from(input.files).forEach(file => {
                    const span = document.createElement('span');
                    span.className = 'inline-block px-2 py-1 bg-slate-100 rounded-md border border-slate-200 truncate max-w-[150px]';
                    span.textContent = file.name;
                    list.appendChild(span);
                });
            }
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('formController', () => ({
                lat: '{{ old('latitude_kunjungan') }}',
                lng: '{{ old('longitude_kunjungan') }}',
                gpsStatus: 'GPS belum aktif',
                
                getLocation() {
                    this.gpsStatus = 'Mengambil koordinat...';
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                this.lat = position.coords.latitude.toFixed(7);
                                this.lng = position.coords.longitude.toFixed(7);
                                this.gpsStatus = 'Koordinat berhasil didapatkan!';
                            },
                            (error) => {
                                this.gpsStatus = 'Gagal mengakses GPS: ' + error.message;
                            },
                            { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
                        );
                    } else {
                        this.gpsStatus = "Geolocation tidak didukung browser ini.";
                    }
                }
            }));
        });
    </script>
@endsection
