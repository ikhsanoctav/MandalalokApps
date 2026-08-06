@extends(auth()->user()->hasRole('super_admin') ? 'layouts.superadmin' : (auth()->user()->hasRole('admin_kecamatan') ? 'layouts.admin' : 'layouts.petugas'))

@section('title', 'Pembobotan AHP')
@section('breadcrumb', 'Pembobotan AHP')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Pembobotan Kriteria AHP</h2>
            <p class="text-sm text-slate-700">Bandingkan tingkat kepentingan antar kriteria menggunakan skala Saaty (1-9).</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl flex items-center gap-3">
        <i class="mdi mdi-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('warning'))
    <div class="bg-amber-50 text-amber-600 p-4 rounded-xl flex items-center gap-3">
        <i class="mdi mdi-exclamation-triangle"></i> {{ session('warning') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-rose-50 text-rose-600 p-4 rounded-xl flex items-center gap-3">
        <i class="mdi mdi-times-circle"></i> {{ session('error') }}
    </div>
    @endif

    <!-- Panduan Penggunaan Metode AHP -->
    <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-indigo-900 flex items-center gap-2 mb-3">
            <i class="mdi mdi-info-circle text-indigo-600"></i> Memahami Metode AHP (Analytical Hierarchy Process)
        </h3>
        <p class="text-indigo-800 text-sm mb-4 leading-relaxed">
            Metode AHP digunakan untuk menentukan <strong>bobot prioritas</strong> dari masing-masing kriteria. Alih-alih menebak bobot secara acak, AHP meminta Anda membandingkan tingkat kepentingan antara dua kriteria (Perbandingan Berpasangan) menggunakan skala Saaty (1 hingga 9). Sistem kemudian secara matematis menghitung bobot yang paling optimal.
        </p>
        <div class="bg-white/60 p-4 rounded-xl border border-indigo-200/50">
            <h4 class="font-bold text-indigo-900 text-sm mb-2">💡 Cara Membaca Hasil Consistency Ratio (CR):</h4>
            <ul class="space-y-2 text-sm text-indigo-800">
                <li class="flex items-start gap-2">
                    <i class="mdi mdi-check text-emerald-500 mt-1"></i> 
                    <div><strong>CR ≤ 0.1 (Konsisten)</strong>: Penilaian perbandingan Anda logis dan dapat diandalkan. Bobot yang dihasilkan sah untuk digunakan.</div>
                </li>
                <li class="flex items-start gap-2">
                    <i class="mdi mdi-times text-rose-500 mt-1"></i> 
                    <div><strong>CR > 0.1 (Tidak Konsisten)</strong>: Terdapat paradoks logika dalam penilaian Anda (Misal: A > B, B > C, tetapi Anda mengisi C > A). Anda harus merevisi angka matriks perbandingan.</div>
                </li>
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Matriks Perbandingan -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h3 class="font-bold text-slate-800 mb-4">Matriks Perbandingan Berpasangan</h3>
            
            <form action="{{ route('dss.ahp.calculate') }}" method="POST">
                @csrf
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="p-3 border border-slate-200 text-slate-700">Kriteria</th>
                                @foreach($kriterias as $k)
                                    <th class="p-3 border border-slate-200 text-center text-slate-700 font-semibold" title="{{ $k->nama_kriteria }}">{{ $k->kode }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kriterias as $i => $row)
                            <tr>
                                <th class="p-3 border border-slate-200 bg-slate-50 text-slate-700 font-semibold" title="{{ $row->nama_kriteria }}">{{ $row->kode }}</th>
                                @foreach($kriterias as $j => $col)
                                    <td class="p-2 border border-slate-200 text-center">
                                        @if($i == $j)
                                            <span class="text-slate-400 font-medium">1</span>
                                        @elseif($i < $j)
                                            <select name="matrix[{{ $row->id }}][{{ $col->id }}]" class="w-full text-sm rounded-md border-slate-300 py-1 px-2 focus:ring-indigo-500">
                                                <option value="9">9 - Mutlak Lebih Penting</option>
                                                <option value="7">7 - Sangat Lebih Penting</option>
                                                <option value="5">5 - Lebih Penting</option>
                                                <option value="3">3 - Sedikit Lebih Penting</option>
                                                <option value="1" selected>1 - Sama Penting</option>
                                                <option value="0.3333">1/3 - Sedikit Kurang Penting</option>
                                                <option value="0.2">1/5 - Kurang Penting</option>
                                                <option value="0.1428">1/7 - Sangat Kurang Penting</option>
                                                <option value="0.1111">1/9 - Mutlak Kurang Penting</option>
                                            </select>
                                        @else
                                            <span class="text-slate-400 text-xs italic">Otomatis</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2 transition-colors">
                        <i class="mdi mdi-calculator"></i> Hitung Bobot AHP
                    </button>
                </div>
            </form>
        </div>

        <!-- Hasil Bobot -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h3 class="font-bold text-slate-800 mb-4">Bobot Kriteria Saat Ini</h3>
            
            @if($bobots->count() > 0)
                @php $first = $bobots->first(); @endphp
                <div class="mb-4 p-3 rounded-xl {{ $first->cr_value <= 0.1 ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                    <div class="text-xs font-semibold mb-1">Consistency Ratio (CR)</div>
                    <div class="text-xl font-bold">{{ round($first->cr_value, 4) }}</div>
                    <div class="text-xs mt-1">{{ $first->cr_value <= 0.1 ? 'Konsisten (CR ≤ 0.1)' : 'Tidak Konsisten (CR > 0.1)' }}</div>
                </div>

                <div class="space-y-3">
                    @foreach($kriterias as $k)
                        @if(isset($bobots[$k->id]))
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-slate-700" title="{{ $k->nama_kriteria }}">{{ $k->kode }}</span>
                                <span class="text-indigo-600 font-bold">{{ round($bobots[$k->id]->bobot * 100, 2) }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $bobots[$k->id]->bobot * 100 }}%"></div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-slate-400">
                    <i class="mdi mdi-weight-hanging text-4xl mb-3 opacity-50"></i>
                    <p class="text-sm text-center">Belum ada bobot yang dihitung.<br>Silakan isi matriks di samping.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
