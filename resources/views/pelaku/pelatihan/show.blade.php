@extends('layouts.pelaku')
@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <a href="{{ route('pelaku.pelatihan.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium inline-flex items-center gap-1 mb-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pelatihan
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Detail Pelatihan</h1>
    </div>
</div>

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200">
    <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 rounded-xl bg-red-50 text-red-700 border border-red-200">
    <i class="fa-solid fa-triangle-exclamation mr-2"></i> {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Bagian Kiri: Info Pelatihan -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl border shadow-sm overflow-hidden p-6">
            @if($pelatihan->banner)
                <div class="w-full h-64 md:h-80 mb-6 bg-slate-100 rounded-2xl overflow-hidden">
                    <img src="{{ asset($pelatihan->banner) }}" alt="Banner Pelatihan" class="w-full h-full object-cover">
                </div>
            @endif
            <h2 class="text-xl font-bold text-slate-800 mb-4">{{ $pelatihan->judul }}</h2>
            
            <div class="prose prose-sm text-slate-600 max-w-none mb-6">
                <p>{{ $pelatihan->deskripsi ?: 'Tidak ada deskripsi rinci untuk pelatihan ini.' }}</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-2xl">
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <i class="fa-regular fa-calendar-check text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Jadwal</p>
                        <p class="text-sm font-bold text-slate-800">{{ $pelatihan->tanggal_mulai->format('d M Y') }}</p>
                        <p class="text-xs text-slate-600">{{ $pelatihan->tanggal_mulai->format('H:i') }} - {{ $pelatihan->tanggal_selesai->format('H:i') }} WIB</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-2xl">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-location-dot text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Lokasi</p>
                        <p class="text-sm font-bold text-slate-800">{{ $pelatihan->lokasi }}</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-2xl">
                    <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-users text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Kuota Tersisa</p>
                        <p class="text-sm font-bold text-slate-800">{{ max(0, $pelatihan->kuota - $pelatihan->peserta()->count()) }} Peserta</p>
                        <p class="text-xs text-slate-600">Total Kuota: {{ $pelatihan->kuota }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-2xl">
                    <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-file-invoice text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Syarat Dokumen</p>
                        @if(!empty($pelatihan->syarat_dokumen) && is_array($pelatihan->syarat_dokumen))
                            <p class="text-sm font-bold text-slate-800">{{ count($pelatihan->syarat_dokumen) }} Syarat Wajib</p>
                            <ul class="text-xs text-slate-600 list-disc list-inside mt-1">
                                @foreach($pelatihan->syarat_dokumen as $syarat)
                                    <li>{{ $syarat }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm font-bold text-slate-800">Tidak Ada</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Kanan: Aksi (Daftar / Tiket) -->
    <div class="space-y-6">
        @if($peserta)
            <!-- Tampilan Tiket -->
            <div class="bg-white/90 backdrop-blur-sm rounded-3xl border shadow-sm p-6 text-center">
                <div class="inline-flex px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700 mb-6">
                    <i class="fa-solid fa-check-circle mr-1"></i> Anda Terdaftar
                </div>
                
                <h3 class="font-bold text-slate-800 mb-2">Tiket Kehadiran</h3>
                <p class="text-xs text-slate-500 mb-6">Tunjukkan QR Code ini kepada petugas saat acara.</p>
                
                <div class="bg-white p-4 rounded-2xl inline-block border border-slate-200 mb-4 shadow-inner">
                    {!! QrCode::size(200)->generate($peserta->kode_tiket) !!}
                </div>
                
                <p class="font-mono font-bold text-slate-700 tracking-widest text-lg">{{ $peserta->kode_tiket }}</p>

                @if($peserta->status_kehadiran == 'hadir')
                    <div class="mt-6 p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                        <p class="text-sm font-bold text-emerald-700">Telah Hadir</p>
                        <p class="text-xs text-emerald-600">{{ $peserta->waktu_hadir->format('d M Y, H:i') }}</p>
                    </div>
                @else
                    <div class="mt-6 p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <p class="text-sm font-semibold text-slate-500">Status: Belum Hadir</p>
                    </div>
                @endif
            </div>
        @else
            <!-- Tampilan Form Pendaftaran -->
            <div class="bg-white/90 backdrop-blur-sm rounded-3xl border shadow-sm p-6">
                <h3 class="font-bold text-lg text-slate-800 mb-4">Pendaftaran Pelatihan</h3>
                
                @if($pelatihan->peserta()->count() >= $pelatihan->kuota)
                    <div class="p-4 bg-red-50 text-red-700 rounded-xl border border-red-200 text-sm text-center">
                        <i class="fa-solid fa-ban text-2xl mb-2"></i>
                        <p class="font-bold">Mohon Maaf</p>
                        <p>Kuota pelatihan ini sudah penuh.</p>
                    </div>
                @else
                    <form id="daftar-form" action="{{ route('pelaku.pelatihan.daftar', $pelatihan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        @if(!empty($pelatihan->syarat_dokumen) && is_array($pelatihan->syarat_dokumen))
                            <div class="mb-5 space-y-4">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Unggah Dokumen Syarat <span class="text-red-500">*</span></label>
                                @foreach($pelatihan->syarat_dokumen as $index => $syarat)
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                                    <p class="text-sm font-bold text-slate-800 mb-2">{{ $syarat }}</p>
                                    <input type="file" name="dokumen_syarat[{{ $index }}]" accept=".pdf,.jpg,.jpeg,.png" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-white file:text-slate-700 hover:file:bg-slate-100 transition-all border border-slate-200 rounded-xl bg-white">
                                    <p class="text-[10px] text-slate-500 mt-2">Maks. 5MB. Format: PDF, JPG, PNG.</p>
                                    @error('dokumen_syarat.'.$index) <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="mb-5 p-4 bg-blue-50 text-blue-700 rounded-xl border border-blue-200 text-sm">
                                <i class="fa-solid fa-circle-info mr-1"></i> Pelatihan ini tidak mewajibkan unggah dokumen syarat. Anda bisa langsung mendaftar.
                            </div>
                        @endif

                        <button type="button" x-data @click="$dispatch('open-confirm-modal', {
                            title: 'Daftar Pelatihan',
                            message: 'Apakah Anda yakin ingin mendaftar pelatihan ini?',
                            confirmText: 'Ya, Daftar',
                            action: () => {
                                document.getElementById('daftar-form').submit();
                                return new Promise(() => {});
                            }
                        })" class="w-full btn-primary px-4 py-3 text-sm font-bold text-white rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                            <i class="fa-solid fa-clipboard-check"></i> Daftar Sekarang
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>
</div>

@endsection
