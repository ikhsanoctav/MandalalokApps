@extends('layouts.petugas')

@section('title', 'Upload Foto - ' . $umkm->nama_usaha)
@section('breadcrumb', 'Upload Foto')

@section('content')
    <div class="space-y-5">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800">📸 Upload Foto UMKM</h2>
                <p class="text-sm text-slate-700 mt-0.5">{{ $umkm->nama_usaha }} &mdash;
                    {{ $umkm->pemilik?->nama_lengkap ?? '-' }}</p>
            </div>
            <a href="{{ route('operator.umkm.index') }}"
                class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm">
                <div class="font-semibold mb-1">Terjadi kesalahan:</div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('operator.umkm.upload-foto.store', $umkm->id_umkm) }}" method="POST"
            enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-pink-50 border-b border-pink-200 px-6 py-4">
                    <h3 class="font-bold text-pink-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Foto Utama / Produk
                    </h3>
                    <p class="text-xs text-pink-600 mt-0.5">Foto terbaik produk atau tampak depan tempat usaha</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        
                        <div>
                            <p class="text-xs font-semibold text-slate-700 uppercase tracking-wide mb-2">Foto Saat Ini</p>
                            @if ($umkm->foto_utama)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $umkm->foto_utama) }}" alt="Foto Utama"
                                        class="w-full h-48 object-cover rounded-xl border border-slate-200 shadow-sm">
                                    <div
                                        class="absolute inset-0 bg-black/40 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span class="text-white text-sm font-medium">Akan diganti jika upload baru</span>
                                    </div>
                                </div>
                            @else
                                <div
                                    class="w-full h-48 bg-slate-100 rounded-xl border-2 border-dashed border-slate-300 flex flex-col items-center justify-center text-slate-400">
                                    <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm">Belum ada foto utama</p>
                                </div>
                            @endif
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-slate-700 uppercase tracking-wide mb-2">Upload Foto Baru
                            </p>
                            <label for="foto_utama" id="labelFotoUtama"
                                class="flex flex-col items-center justify-center w-full h-48 border-2 border-pink-300 border-dashed rounded-xl cursor-pointer hover:border-pink-500 hover:bg-pink-50 transition-all">
                                <div id="fotoUtamaPlaceholder" class="text-center">
                                    <svg class="mx-auto w-9 h-9 text-pink-400 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p class="text-sm font-semibold text-pink-600">Tap untuk pilih foto</p>
                                    <p class="text-xs text-slate-400 mt-1">JPG, PNG, WEBP • Maks 5MB</p>
                                </div>
                                <img id="previewUtama" src="" alt=""
                                    class="hidden max-h-44 rounded-2xl object-contain">
                                <input id="foto_utama" name="foto_utama" type="file" class="sr-only" accept="image/*">
                            </label>
                            
                            <div class="flex gap-2 mt-2">
                                <button type="button" onclick="bukaInput('foto_utama', true)"
                                    class="flex-1 flex items-center justify-center gap-1.5 py-2 text-xs font-semibold text-white bg-pink-500 hover:bg-pink-600 rounded-md transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Buka Kamera
                                </button>
                                <button type="button" onclick="bukaInput('foto_utama', false)"
                                    class="flex-1 flex items-center justify-center gap-1.5 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-md transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Pilih Galeri
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-blue-50 border-b border-blue-200 px-6 py-4">
                    <h3 class="font-bold text-blue-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Foto Gallery / Tambahan
                    </h3>
                    <p class="text-xs text-blue-600 mt-0.5">Foto produk lain, interior usaha, dll. Bisa tambah berkali-kali
                    </p>
                </div>
                <div class="p-6">

                    @php $gallery = is_array($umkm->foto_gallery) ? $umkm->foto_gallery : (is_string($umkm->foto_gallery) ? json_decode($umkm->foto_gallery, true) : []); @endphp
                    @if (count($gallery) > 0)
                        <div class="mb-5">
                            <p class="text-xs font-semibold text-slate-700 uppercase tracking-wide mb-3">Foto Gallery Saat
                                Ini ({{ count($gallery) }} foto)</p>
                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3" id="galleryContainer">
                                @foreach ($gallery as $idx => $foto)
                                    <div class="relative group" id="gallery-item-{{ $idx }}">
                                        <img src="{{ asset('storage/' . $foto) }}" alt="Gallery {{ $idx + 1 }}"
                                            class="w-full h-24 object-cover rounded-xl border border-slate-200 shadow-sm">
                                        <button type="button"
                                            onclick="hapusGallery({{ $umkm->id_umkm }}, {{ $idx }}, this)"
                                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity shadow-md hover:bg-red-600">
                                            ✕
                                        </button>
                                        <p class="text-[10px] text-center text-slate-400 mt-1">Foto {{ $idx + 1 }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div>
                        <p class="text-xs font-semibold text-slate-700 uppercase tracking-wide mb-2">Tambah Foto Gallery
                            Baru</p>
                        <label for="foto_gallery"
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-blue-300 border-dashed rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all">
                            <svg class="w-9 h-9 text-blue-400 mb-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <p class="text-sm font-semibold text-blue-600">Tap area ini untuk pilih / foto</p>
                            <p id="galleryCountLabel" class="text-xs text-slate-400 mt-0.5">JPG, PNG, WEBP • Maks 5MB/foto
                            </p>
                            <input id="foto_gallery" name="foto_gallery[]" type="file" class="sr-only"
                                accept="image/*" multiple>
                        </label>
                        
                        <div class="flex gap-2 mt-2">
                            <button type="button" onclick="bukaInput('foto_gallery', true)"
                                class="flex-1 flex items-center justify-center gap-1.5 py-2 text-xs font-semibold text-white bg-blue-500 hover:bg-blue-600 rounded-md transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Foto Kamera
                            </button>
                            <button type="button" onclick="bukaInput('foto_gallery', false)"
                                class="flex-1 flex items-center justify-center gap-1.5 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-md transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Pilih Galeri
                            </button>
                        </div>
                        <div id="galleryNewPreview" class="mt-3 flex flex-wrap gap-2"></div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pb-4">
                <a href="{{ route('operator.umkm.index') }}"
                    class="px-6 py-2.5 text-slate-600 font-medium hover:bg-slate-100 rounded-xl transition-colors">Batal</a>
                <button type="submit"
                    class="px-8 py-2.5 bg-pink-600 hover:bg-pink-700 text-white font-semibold rounded-xl transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Simpan Foto
                </button>
            </div>
        </form>

    </div>
@endsection

@push('scripts')
    <script>
        /**
         * bukaInput(inputId, useCamera)
         * - useCamera = true  → set capture="environment" → langsung buka kamera
         * - useCamera = false → hapus capture → buka file picker / galeri
         */
        function bukaInput(inputId, useCamera) {
            const input = document.getElementById(inputId);
            if (useCamera) {
                input.setAttribute('capture', 'environment');
            } else {
                input.removeAttribute('capture');
            }
            input.click();
        }

        // Preview foto utama
        document.getElementById('foto_utama').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('previewUtama');
                const placeholder = document.getElementById('fotoUtamaPlaceholder');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });

        function hapusFotoUtama() {
            document.getElementById('foto_utama').value = '';
            document.getElementById('previewUtama').classList.add('hidden');
            document.getElementById('fotoUtamaPlaceholder').classList.remove('hidden');
        }

        // Preview gallery baru
        document.getElementById('foto_gallery').addEventListener('change', function() {
            const files = Array.from(this.files);
            const container = document.getElementById('galleryNewPreview');
            const label = document.getElementById('galleryCountLabel');
            container.innerHTML = '';
            label.textContent = files.length + ' foto dipilih';
            files.slice(0, 8).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className =
                        'w-16 h-16 object-cover rounded-xl border border-slate-200 shadow-sm';
                    container.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
            if (files.length > 8) {
                const more = document.createElement('div');
                more.className =
                    'w-16 h-16 rounded-xl bg-slate-100 flex items-center justify-center text-xs text-slate-700 font-bold';
                more.textContent = '+' + (files.length - 8);
                container.appendChild(more);
            }
        });

        // Hapus foto gallery via AJAX
        function hapusGallery(umkmId, index, btn) {
            window.dispatchEvent(new CustomEvent('open-confirm-modal', {
                detail: {
                    title: 'Hapus Foto',
                    message: 'Apakah Anda yakin ingin menghapus foto ini dari gallery?',
                    confirmText: 'Ya, Hapus',
                    action: () => {
                        const item = document.getElementById('gallery-item-' + index);
                        btn.disabled = true;
                        btn.textContent = '...';

                        return fetch(`/operator/umkm/${umkmId}/foto-gallery/${index}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    item.style.transition = 'opacity 0.3s';
                                    item.style.opacity = '0';
                                    setTimeout(() => {
                                        item.remove();
                                        if (window.showToast) window.showToast('success', 'Berhasil!', 'Foto gallery berhasil dihapus.');
                                    }, 300);
                                }
                            })
                            .catch(() => {
                                btn.disabled = false;
                                btn.textContent = '✕';
                            });
                    }
                }
            }));
        }
    </script>
@endpush
