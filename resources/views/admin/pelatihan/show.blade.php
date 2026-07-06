@extends('layouts.admin')
@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <a href="{{ route('admin.pelatihan.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium inline-flex items-center gap-1 mb-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pelatihan
        </a>
        <h1 class="text-2xl font-bold text-slate-800">{{ $pelatihan->judul }}</h1>
        <div class="flex items-center gap-4 mt-2 text-sm text-slate-600">
            <span class="inline-flex items-center gap-1"><i class="fa-regular fa-calendar"></i> {{ $pelatihan->tanggal_mulai->format('d M Y') }}</span>
            <span class="inline-flex items-center gap-1"><i class="fa-solid fa-location-dot"></i> {{ $pelatihan->lokasi }}</span>
            <span class="inline-flex items-center gap-1"><i class="fa-solid fa-users"></i> {{ $pelatihan->peserta()->count() }} / {{ $pelatihan->kuota }} Peserta</span>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.pelatihan.edit', $pelatihan->id) }}" class="btn-primary inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white rounded-xl transition-all">
            <i class="fa-solid fa-pen"></i> Edit
        </a>
    </div>
</div>

@if(session('success'))
<div class="mb-4 p-4 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200">
    <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-4 p-4 rounded-xl bg-red-50 text-red-700 border border-red-200">
    <i class="fa-solid fa-triangle-exclamation mr-2"></i> {{ session('error') }}
</div>
@endif

<!-- Daftar Peserta -->
<div class="bg-white/90 backdrop-blur-sm shadow-sm rounded-3xl border overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-lg font-bold text-slate-800">Daftar Peserta Terdaftar</h2>
        
        <!-- Form Daftarkan Peserta Manual -->
        <form action="{{ route('admin.pelatihan.register_user', $pelatihan->id) }}" method="POST" class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
            @csrf
            <select name="user_id" required class="bg-slate-50 border border-slate-200 text-sm rounded-lg block w-full p-2.5">
                <option value="">-- Pilih Pelaku UMKM --</option>
                @foreach($users as $u)
                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                @endforeach
            </select>
            <button type="submit" class="whitespace-nowrap px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold rounded-lg transition-colors">
                Daftarkan Manual
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="p-4 font-semibold">Nama UMKM & Pemilik</th>
                    <th class="p-4 font-semibold">Tiket</th>
                    <th class="p-4 font-semibold">Syarat Dokumen</th>
                    <th class="p-4 font-semibold text-center">Status Kehadiran</th>
                    <th class="p-4 font-semibold">Waktu Hadir</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($pelatihan->peserta as $p)
                <tr>
                    <td class="p-4">
                        <div class="font-bold text-slate-800">{{ $p->user?->pemilik?->umkm?->first()?->nama_usaha ?? 'Data UMKM Belum Lengkap' }}</div>
                        <div class="text-slate-500 text-xs">{{ $p->user->name }}</div>
                    </td>
                    <td class="p-4">
                        <span class="font-mono bg-slate-100 px-2 py-1 rounded text-slate-700">{{ $p->kode_tiket }}</span>
                    </td>
                    <td class="p-4">
                        @if(!empty($p->dokumen_syarat) && is_array($p->dokumen_syarat))
                            <div class="flex flex-col gap-1">
                                @foreach($p->dokumen_syarat as $namaSyarat => $path)
                                    <a href="{{ asset($path) }}" target="_blank" class="text-blue-600 hover:underline inline-flex items-center gap-1 text-xs">
                                        <i class="fa-solid fa-download"></i> {{ $namaSyarat }}
                                    </a>
                                @endforeach
                            </div>
                        @elseif($p->dokumen_syarat)
                            <a href="{{ asset($p->dokumen_syarat) }}" target="_blank" class="text-blue-600 hover:underline inline-flex items-center gap-1">
                                <i class="fa-solid fa-download"></i> Lihat Dokumen
                            </a>
                        @else
                            <span class="text-slate-400 italic">Tidak ada dokumen</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        @if($p->status_kehadiran == 'hadir')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Hadir</span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">Belum Hadir</span>
                        @endif
                    </td>
                    <td class="p-4 text-slate-600">
                        {{ $p->waktu_hadir ? $p->waktu_hadir->format('d M Y H:i:s') : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-500">
                        <div class="mb-2"><i class="fa-regular fa-folder-open text-3xl opacity-50"></i></div>
                        Belum ada peserta yang mendaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
