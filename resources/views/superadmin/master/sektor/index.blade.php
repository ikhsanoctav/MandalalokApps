@extends('layouts.superadmin')
@section('title', 'Sektor UMKM')
@section('breadcrumb', 'Master Data / Sektor UMKM')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-slate-800">Data Sektor UMKM</h2>
            <div class="flex gap-2">
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'importModal')"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg shadow hover:bg-emerald-700 transition flex items-center gap-2">
                    Import
                </button>
                <a href="{{ route('superadmin.sektor.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition flex items-center gap-2">
                    + Tambah Sektor
                </a>
            </div>
        </div>

        <x-card>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border border-slate-200 rounded-md">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4 font-semibold text-slate-600 w-16 text-center">No</th>
                            <th class="py-3 px-4 font-semibold text-slate-600">Nama Sektor</th>
                            <th class="py-3 px-4 font-semibold text-slate-600">Deskripsi</th>
                            <th class="py-3 px-4 font-semibold text-slate-600 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($sektors as $sektor)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 text-slate-500 text-center">{{ $loop->iteration }}</td>
                                <td class="py-3 px-4 font-medium text-slate-800">{{ $sektor->nama_sektor }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ Str::limit($sektor->deskripsi, 50) }}</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('superadmin.sektor.edit', $sektor->id) }}"
                                            class="p-1.5 bg-amber-100 text-amber-600 rounded-2xl hover:bg-amber-200"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('superadmin.sektor.destroy', $sektor->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Yakin ingin menghapus sektor ini?');">
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
                                <td colspan="4" class="py-10 text-center text-slate-400">Belum ada data sektor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>

    <x-modal name="importModal" maxWidth="lg">
        <form action="{{ route('superadmin.sektor.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-t-lg">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Import Data Sektor</h3>
                <div class="mt-4 mb-4">
                    <a href="{{ route('superadmin.sektor.import.template') }}"
                        class="inline-flex items-center gap-2 text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Unduh Template Data
                    </a>
                </div>
                <div class="mt-2">
                    <input type="file" name="file_excel" accept=".xlsx, .csv, .xls" required
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-200 rounded-md">
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg">
                <button type="submit"
                    class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">Upload
                    & Import</button>
                <button type="button" x-on:click="$dispatch('close')"
                    class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
            </div>
        </form>
    </x-modal>
@endsection
