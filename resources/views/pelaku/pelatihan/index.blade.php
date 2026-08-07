@extends('layouts.pelaku')
@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Pelatihan UMKM</h1>
        <p class="text-sm text-slate-500 mt-1">Daftar pelatihan yang tersedia dan diwajibkan untuk pelaku UMKM.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($pelatihans as $p)
        @php
            $isRegistered = in_array($p->id, $registeredIds);
            $pesertaCount = $p->peserta_count ?? $p->peserta()->count();
            $isFull = $pesertaCount >= $p->kuota;
        @endphp
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl border shadow-sm overflow-hidden flex flex-col transition-all hover:shadow-md hover:border-slate-300">
            @if($p->banner)
                <div class="h-40 w-full overflow-hidden bg-slate-100">
                    <img src="{{ asset($p->banner) }}" alt="Banner Pelatihan" class="w-full h-full object-cover">
                </div>
            @endif
            <!-- Header Card -->
            <div class="p-6 border-b border-slate-100 flex-grow">
                <div class="flex justify-between items-start mb-3">
                    <h2 class="text-lg font-bold text-slate-800 line-clamp-2 leading-tight">{{ $p->judul }}</h2>
                    @if($isRegistered)
                        <span class="inline-flex px-2 py-1 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700 flex-shrink-0"><i class="fa-solid fa-check-circle mr-1"></i> Terdaftar</span>
                    @elseif($isFull)
                        <span class="inline-flex px-2 py-1 text-[10px] font-bold rounded-full bg-red-100 text-red-700 flex-shrink-0">Penuh</span>
                    @else
                        <span class="inline-flex px-2 py-1 text-[10px] font-bold rounded-full bg-blue-100 text-blue-700 flex-shrink-0">Buka</span>
                    @endif
                </div>

                <div class="space-y-2 mt-4 text-sm text-slate-600">
                    <div class="flex items-start gap-2.5">
                        <i class="fa-regular fa-calendar mt-1 text-slate-400 w-4 text-center"></i>
                        <div>
                            <span class="font-semibold text-slate-700 block">{{ $p->tanggal_mulai->format('d M Y') }}</span>
                            <span class="text-xs text-slate-500">{{ $p->tanggal_mulai->format('H:i') }} - {{ $p->tanggal_selesai->format('H:i') }} WIB</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-location-dot mt-1 text-slate-400 w-4 text-center"></i>
                        <span class="leading-snug">{{ $p->lokasi }}</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-users mt-1 text-slate-400 w-4 text-center"></i>
                        <span>Sisa Kuota: <strong>{{ max(0, $p->kuota - $pesertaCount) }}</strong> dari {{ $p->kuota }}</span>
                    </div>
                    @if(!$isRegistered && !empty($p->syarat_dokumen) && is_array($p->syarat_dokumen) && count($p->syarat_dokumen) > 0)
                    <div class="flex items-start gap-2.5 text-amber-600">
                        <i class="fa-solid fa-file-invoice mt-1 w-4 text-center"></i>
                        <span class="text-xs font-semibold">Wajib Upload Dokumen</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Footer Card -->
            <div class="p-4 bg-slate-50 border-t border-slate-100">
                <a href="{{ route('pelaku.pelatihan.show', $p->id) }}" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl font-semibold text-sm transition-colors {{ $isRegistered ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-slate-800 hover:bg-slate-900 text-white' }}">
                    @if($isRegistered)
                        <i class="fa-solid fa-ticket"></i> Lihat Tiket
                    @else
                        Daftar Pelatihan
                    @endif
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-3xl border border-dashed border-slate-300 p-12 text-center">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-calendar-xmark text-2xl text-slate-400"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Pelatihan</h3>
            <p class="text-sm text-slate-500">Saat ini tidak ada pelatihan yang sedang dibuka. Silakan cek kembali nanti.</p>
        </div>
    @endforelse
</div>

@endsection
