@extends('layouts.superadmin')
@section('title', 'Manajemen Warta')
@section('breadcrumb', 'Master Data / Manajemen Warta')

@section('content')
    <div class="space-y-6" x-data="beritaIndex()">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Warta</h2>
            <a href="{{ route('superadmin.berita.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                + Tambah Warta
            </a>
        </div>

        <x-card>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border border-slate-200 rounded-md">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4 font-semibold text-slate-600 w-16 text-center">No</th>
                            <th class="py-3 px-4 font-semibold text-slate-600">Judul & Cuplikan</th>
                            <th class="py-3 px-4 font-semibold text-slate-600">Kategori</th>
                            <th class="py-3 px-4 font-semibold text-slate-600">Penulis</th>
                            <th class="py-3 px-4 font-semibold text-slate-600">Status</th>
                            <th class="py-3 px-4 font-semibold text-slate-600">Tanggal Publish</th>
                            <th class="py-3 px-4 font-semibold text-slate-600 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($berita as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 text-slate-700 text-center">
                                    {{ $loop->iteration + ($berita->currentPage() - 1) * $berita->perPage() }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        @if ($item->gambar)
                                            <img src="{{ Storage::url($item->gambar) }}"
                                                class="w-10 h-10 rounded-xl object-cover bg-slate-100">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-slate-800">{{ Str::limit($item->judul, 40) }}</p>
                                            <p class="text-xs text-slate-700">
                                                {{ Str::limit(strip_tags($item->konten), 40) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-slate-700">
                                    @if($item->kategori)
                                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full border border-blue-100">{{ $item->kategori }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-slate-700">{{ $item->penulis }}</td>
                                <td class="py-3 px-4">
                                    @if ($item->status == 'published')
                                        <span
                                            class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-md">Published</span>
                                    @else
                                        <span
                                            class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded-md">Draft</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-slate-700">
                                    {{ $item->published_at ? $item->published_at->format('d M Y') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('superadmin.berita.edit', $item->id) }}"
                                            class="p-2 text-amber-500 hover:text-amber-600 rounded-2xl hover:bg-amber-50 transition-colors"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </a>
                                        <button @click="deleteData('{{ $item->id }}', '{{ e($item->judul) }}', $event)"
                                            class="p-2 text-red-500 hover:text-red-600 rounded-2xl hover:bg-red-50 transition-colors"
                                            title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-slate-400">Belum ada data warta.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($berita->hasPages())
                <div class="mt-4">
                    {{ $berita->links() }}
                </div>
            @endif
        </x-card>
    </div>

    @push('scripts')
    <script>
        function beritaIndex() {
            return {
                deleteData(id, title, event) {
                    const row = event.target.closest('tr');
                    this.$dispatch('open-confirm-modal', {
                        title: 'Hapus Warta',
                        message: `Apakah Anda yakin ingin menghapus warta "${title}"?`,
                        confirmText: 'Ya, Hapus',
                        action: () => {
                            return fetch(`/superadmin/berita/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    window.showToast('success', 'Berhasil!', data.message);
                                    if (row) {
                                        row.style.transition = 'all 0.3s ease';
                                        row.style.opacity = '0';
                                        row.style.transform = 'translateX(-20px)';
                                        setTimeout(() => row.remove(), 300);
                                    }
                                } else {
                                    window.showToast('error', 'Gagal!', data.message || 'Gagal menghapus data.');
                                }
                            })
                            .catch(() => {
                                window.showToast('error', 'Gagal!', 'Terjadi kesalahan jaringan.');
                            });
                        }
                    });
                }
            }
        }
    </script>
    @endpush
@endsection
