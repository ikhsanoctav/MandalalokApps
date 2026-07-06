@extends('layouts.superadmin')
@section('title', 'Backup & Restore')
@section('breadcrumb', 'Pengaturan / Backup & Restore')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Backup Database</h2>
            <form action="{{ route('superadmin.backup.create') }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                        </path>
                    </svg>
                    Buat Backup Baru
                </button>
            </form>
        </div>

        <x-card>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border border-slate-200 rounded-md">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4 font-semibold text-slate-600 w-16 text-center">No</th>
                            <th class="py-3 px-4 font-semibold text-slate-600">Nama File</th>
                            <th class="py-3 px-4 font-semibold text-slate-600">Ukuran</th>
                            <th class="py-3 px-4 font-semibold text-slate-600">Tanggal Backup</th>
                            <th class="py-3 px-4 font-semibold text-slate-600 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($backups as $backup)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 text-slate-500 text-center">{{ $loop->iteration }}</td>
                                <td class="py-3 px-4 font-mono text-slate-600">{{ $backup['file_name'] }}</td>
                                <td class="py-3 px-4 font-medium text-slate-800">{{ $backup['file_size'] }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ $backup['last_modified'] }}</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('superadmin.backup.download', $backup['file_name']) }}"
                                            class="p-1.5 bg-green-100 text-green-600 rounded-2xl hover:bg-green-200"
                                            title="Download">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                </path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('superadmin.backup.delete', $backup['file_name']) }}"
                                            method="POST" class="inline-block"
                                            onsubmit="return confirm('Yakin ingin menghapus file backup ini?');">
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
                                <td colspan="5" class="py-10 text-center text-slate-400">Belum ada file backup.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
@endsection
