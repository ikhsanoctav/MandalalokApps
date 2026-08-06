@extends('layouts.superadmin')
@section('title', 'Data RW')
@section('breadcrumb', 'Master Data / RW')

@section('content')
    <div class="space-y-6" x-data="rwIndex()">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-slate-800">Data RW</h2>
            <a href="{{ route('superadmin.rw.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                + Tambah RW
            </a>
        </div>

        <x-card>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border border-slate-200 rounded-md">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4 font-semibold text-slate-600 w-16 text-center">No</th>
                            <th class="py-3 px-4 font-semibold text-slate-600">Kelurahan</th>
                            <th class="py-3 px-4 font-semibold text-slate-600">Nomor RW</th>
                            <th class="py-3 px-4 font-semibold text-slate-600 text-center">Jumlah RT</th>
                            <th class="py-3 px-4 font-semibold text-slate-600 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($rws as $rw)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 text-slate-700 text-center">{{ $loop->iteration }}</td>
                                <td class="py-3 px-4 text-slate-800">{{ $rw->kelurahan->nama_kelurahan ?? '-' }}</td>
                                <td class="py-3 px-4 font-mono font-medium">RW
                                    {{ str_pad($rw->nomor_rw, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-3 px-4 text-center text-slate-600">{{ $rw->rts_count }}</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('superadmin.rw.edit', $rw->id) }}"
                                            class="p-2 text-amber-500 hover:text-amber-600 rounded-2xl hover:bg-amber-50 transition-colors"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </a>
                                        <button @click="deleteData('{{ $rw->id }}', '{{ 'RW ' . str_pad($rw->nomor_rw, 3, '0', STR_PAD_LEFT) }}', $event)"
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
                                <td colspan="5" class="py-10 text-center text-slate-400">Belum ada data RW.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>

    @push('scripts')
    <script>
        function rwIndex() {
            return {
                deleteData(id, name, event) {
                    const row = event.target.closest('tr');
                    this.$dispatch('open-confirm-modal', {
                        title: 'Hapus RW',
                        message: `Apakah Anda yakin ingin menghapus "${name}"?`,
                        confirmText: 'Ya, Hapus',
                        action: () => {
                            return fetch(`/superadmin/rw/${id}`, {
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
