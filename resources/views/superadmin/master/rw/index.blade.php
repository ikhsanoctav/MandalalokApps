@extends('layouts.superadmin')
@section('title', 'Data RW')
@section('breadcrumb', 'Master Data / RW')

@section('content')
    <div class="space-y-6">
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
                                <td class="py-3 px-4 text-slate-500 text-center">{{ $loop->iteration }}</td>
                                <td class="py-3 px-4 text-slate-800">{{ $rw->kelurahan->nama_kelurahan ?? '-' }}</td>
                                <td class="py-3 px-4 font-mono font-medium">RW
                                    {{ str_pad($rw->nomor_rw, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-3 px-4 text-center text-slate-600">{{ $rw->rts_count }}</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('superadmin.rw.edit', $rw->id) }}"
                                            class="p-1.5 bg-amber-100 text-amber-600 rounded-2xl hover:bg-amber-200"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('superadmin.rw.destroy', $rw->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Yakin ingin menghapus RW ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 bg-red-100 text-red-600 rounded-2xl hover:bg-red-200"
                                                title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
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
@endsection
