@extends(auth()->user()->hasRole('super_admin') ? 'layouts.superadmin' : (auth()->user()->hasRole('admin_kecamatan') ? 'layouts.admin' : 'layouts.petugas'))

@section('title', 'Hasil DSS')
@section('breadcrumb', 'Hasil SPK AHP-SAW')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Hasil Perankingan UMKM</h2>
            <p class="text-sm text-slate-700">Rekomendasi prioritas berdasarkan algoritma AHP-SAW.</p>
        </div>
        
        <form action="{{ route('dss.saw.calculate') }}" method="POST">
            @csrf
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2">
                <i class="mdi mdi-sync-alt"></i> Hitung Ulang Perankingan
            </button>
        </form>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl flex items-center gap-3">
        <i class="mdi mdi-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    
    @if(session('error'))
    <div class="bg-rose-50 text-rose-600 p-4 rounded-xl flex items-center gap-3">
        <i class="mdi mdi-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    <!-- Panduan Penggunaan Metode SAW -->
    <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-indigo-900 flex items-center gap-2 mb-3">
            <i class="mdi mdi-info-circle text-indigo-600"></i> Memahami Metode SAW (Simple Additive Weighting)
        </h3>
        <p class="text-indigo-800 text-sm mb-4 leading-relaxed">
            Metode SAW (atau sering disebut metode penjumlahan terbobot) digunakan untuk mencari alternatif terbaik dari sejumlah UMKM. Sistem menormalisasikan data mentah dari UMKM—apakah itu kriteria <em>Benefit</em> (omset, dll) atau <em>Cost</em> (tunggakan, dll)—lalu mengalikannya dengan bobot yang Anda tetapkan melalui AHP, menghasilkan <strong>Total Skor</strong> akhir.
        </p>
        <div class="bg-white/60 p-4 rounded-xl border border-indigo-200/50">
            <h4 class="font-bold text-indigo-900 text-sm mb-2">🏆 Cara Membaca Hasil Perankingan:</h4>
            <ul class="space-y-2 text-sm text-indigo-800">
                <li class="flex items-start gap-2">
                    <span class="font-bold text-indigo-600 mt-0.5">Rank 1 - 10:</span> 
                    <div>UMKM dengan nilai tertinggi, artinya paling layak menjadi <strong>prioritas utama</strong> untuk diberikan bantuan permodalan, pembinaan, atau program khusus sesuai kebijakan dinas terkait.</div>
                </li>
                <li class="flex items-start gap-2">
                    <span class="font-bold text-indigo-500 mt-0.5">Rank 11 - 50:</span> 
                    <div>UMKM potensial lapis kedua yang dapat dijadikan daftar tunggu (<em>waiting list</em>) atau ditargetkan untuk program pengembangan skala menengah.</div>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-slate-700 mt-0.5"><i class="mdi mdi-search"></i></span> 
                    <div>Anda bisa mengeklik tombol <strong>"Lihat Rincian"</strong> pada tiap baris UMKM untuk membedah skor apa saja yang mendongkrak atau menjatuhkan peringkat mereka (Transparansi Hasil).</div>
                </li>
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-700">
                        <th class="p-4 font-medium w-16 text-center">Rank</th>
                        <th class="p-4 font-medium">UMKM & Pemilik</th>
                        <th class="p-4 font-medium text-center">Total Skor</th>
                        <th class="p-4 font-medium">Traceability (Rincian)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($ranks as $r)
                    <tr class="hover:bg-slate-50">
                        <td class="p-4 font-bold text-center {{ $r->ranking <= 3 ? 'text-indigo-600 text-lg' : 'text-slate-700' }}">
                            #{{ $r->ranking }}
                        </td>
                        <td class="p-4">
                            <div class="font-semibold text-slate-800">{{ $r->umkm->nama_usaha ?? 'Unknown' }}</div>
                            <div class="text-xs text-slate-700">{{ $r->umkm->pemilik?->nama_lengkap ?? '' }}</div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-2xl font-bold bg-indigo-50 text-indigo-700">
                                {{ round($r->total_skor, 4) }}
                            </span>
                        </td>
                        <td class="p-4">
                            <button type="button" onclick="document.getElementById('detail-{{ $r->umkm_id }}').classList.toggle('hidden')" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium underline flex items-center gap-1">
                                Lihat Rincian <i class="mdi mdi-chevron-down"></i>
                            </button>
                            
                            <!-- Traceability Breakdown -->
                            <div id="detail-{{ $r->umkm_id }}" class="hidden mt-3 p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs">
                                <table class="w-full text-left">
                                    <tr class="text-slate-400 border-b border-slate-200">
                                        <th class="pb-1 font-medium">Kriteria</th>
                                        <th class="pb-1 font-medium text-right">Norm</th>
                                        <th class="pb-1 font-medium text-right">Bobot</th>
                                        <th class="pb-1 font-medium text-right">Skor (Norm × Bobot)</th>
                                    </tr>
                                    @if(isset($breakdownsRaw[$r->umkm_id]))
                                        @foreach($breakdownsRaw[$r->umkm_id] as $b)
                                        <tr class="text-slate-600">
                                            <td class="py-1" title="{{ $b->kriteria->nama_kriteria ?? '' }}">
                                                {{ $b->kriteria->kode ?? '?' }}
                                            </td>
                                            <td class="py-1 text-right">{{ round($b->nilai_normalisasi, 4) }}</td>
                                            <td class="py-1 text-right">{{ round($b->bobot, 4) }}</td>
                                            <td class="py-1 text-right font-medium">{{ round($b->kontribusi_skor, 4) }}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-slate-700">Belum ada hasil perankingan. Silakan hitung ulang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
