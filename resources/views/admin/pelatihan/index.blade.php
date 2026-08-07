@extends('layouts.admin')
@section('content')

<div x-data="{ showCreateModal: {{ $errors->any() ? 'true' : 'false' }} }">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Pelatihan</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data pelatihan yang diwajibkan untuk pelaku UMKM.</p>
        </div>
        <button type="button" @click="showCreateModal = true" class="btn-primary inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white rounded-xl transition-all">
            <i class="fa-solid fa-plus"></i>
            <span>Buat Pelatihan Baru</span>
        </button>
    </div>

<div class="bg-white/90 backdrop-blur-sm shadow-sm rounded-3xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="p-4 font-semibold">Judul Pelatihan</th>
                    <th class="p-4 font-semibold">Jadwal</th>
                    <th class="p-4 font-semibold">Lokasi</th>
                    <th class="p-4 font-semibold text-center">Kuota & Pendaftar</th>
                    <th class="p-4 font-semibold text-center">Status</th>
                    <th class="p-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($pelatihans as $p)
                <tr>
                    <td class="p-4 align-top">
                        <div class="font-bold text-slate-800">{{ $p->judul }}</div>
                        @if(!empty($p->syarat_dokumen) && is_array($p->syarat_dokumen) && count($p->syarat_dokumen) > 0)
                        <span class="inline-flex items-center gap-1 mt-1 text-[11px] font-medium px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 border border-amber-200">
                            <i class="fa-solid fa-file-invoice"></i> Wajib Dokumen
                        </span>
                        @endif
                    </td>
                    <td class="p-4 align-top">
                        <div class="font-medium text-slate-700">{{ $p->tanggal_mulai->format('d M Y') }}</div>
                        <div class="text-slate-500 text-xs">{{ $p->tanggal_mulai->format('H:i') }} - {{ $p->tanggal_selesai->format('H:i') }}</div>
                    </td>
                    <td class="p-4 align-top text-slate-600">
                        {{ $p->lokasi }}
                    </td>
                    <td class="p-4 align-top text-center">
                        <div class="inline-flex flex-col items-center">
                            <span class="font-bold text-slate-800">{{ $p->peserta()->count() }} / {{ $p->kuota }}</span>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider">Peserta</span>
                        </div>
                    </td>
                    <td class="p-4 align-top text-center">
                        @if($p->status == 'published')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Aktif</span>
                        @elseif($p->status == 'draft')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">Draft</span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Selesai</span>
                        @endif
                    </td>
                    <td class="p-4 align-top text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.pelatihan.show', $p->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors">
                                <i class="fa-solid fa-users"></i> Lihat Peserta
                            </a>
                            <a href="{{ route('admin.pelatihan.edit', $p->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-amber-700 bg-amber-100 rounded-lg hover:bg-amber-200 transition-colors">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-500">
                        <div class="mb-2"><i class="fa-regular fa-calendar-xmark text-3xl opacity-50"></i></div>
                        Belum ada data pelatihan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Pelatihan -->
<div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <div x-show="showCreateModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" 
             @click="showCreateModal = false" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="showCreateModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl w-full border border-slate-100">
            
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-lg leading-6 font-bold text-slate-800" id="modal-title">
                    Tambah Pelatihan Baru
                </h3>
                <button @click="showCreateModal = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.pelatihan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="px-6 py-5 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Banner Pelatihan (Opsional)</label>
                            <input type="file" name="banner" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-[10px] text-slate-500 mt-1">Maks 2MB, rasio ideal 16:9 (Landscape).</p>
                            @error('banner') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Pelatihan <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" value="{{ old('judul') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Misal: Pelatihan Pemasaran Digital">
                            @error('judul') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi Pelatihan</label>
                            <textarea name="deskripsi" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Jelaskan tujuan dan materi yang akan dipelajari...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Waktu Mulai <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            @error('tanggal_mulai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Waktu Selesai <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            @error('tanggal_selesai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lokasi <span class="text-red-500">*</span></label>
                            <input type="text" name="lokasi" value="{{ old('lokasi') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Misal: Aula Kecamatan, atau Link Zoom">
                            @error('lokasi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kuota Peserta <span class="text-red-500">*</span></label>
                            <input type="number" name="kuota" value="{{ old('kuota', 50) }}" required min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            @error('kuota') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2" x-data="{ syarat: [''] }">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Syarat Dokumen (Opsional)</label>
                            <p class="text-xs text-slate-500 mb-2">Tambahkan daftar dokumen yang wajib diunggah pelaku UMKM saat mendaftar.</p>
                            
                            <template x-for="(s, index) in syarat" :key="index">
                                <div class="flex gap-2 mb-2">
                                    <input type="text" x-model="syarat[index]" name="syarat_dokumen[]" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Misal: Fotokopi KTP">
                                    <button type="button" @click="syarat.splice(index, 1)" x-show="syarat.length > 1" class="px-4 py-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-colors">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </template>
                            
                            <button type="button" @click="syarat.push('')" class="mt-1 text-xs text-blue-600 font-semibold hover:text-blue-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-plus-circle"></i> Tambah Syarat Lainnya
                            </button>
                            
                            @error('syarat_dokumen') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status Publikasi <span class="text-red-500">*</span></label>
                            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Belum Tampil di Pelaku)</option>
                                <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published (Tampil & Bisa Daftar)</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all">Batal</button>
                    <button type="submit" class="btn-primary px-6 py-2.5 text-sm font-bold text-white rounded-xl transition-all shadow-md">Simpan Pelatihan</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div> <!-- end x-data div -->

@endsection
