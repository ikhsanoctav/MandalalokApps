@extends('layouts.petugas')

@section('title', 'Dashboard Petugas Lapangan')
@section('breadcrumb', 'Dashboard')

@section('content')
    <div class="space-y-6">
        
        <!-- Hero Banner Premium (Light Theme) -->
        <div class="relative overflow-hidden rounded-3xl bg-slate-50 p-8 md:p-10 shadow-sm border border-slate-200 mb-8">
            <!-- Batik Background Accent -->
            <div class="absolute inset-0 z-0 opacity-10 mix-blend-multiply" style="background-image: url('{{ asset('img/batik-bg.png') }}'); background-size: 300px; background-repeat: repeat;"></div>

            <!-- Animated Background Gradients -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-200 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-pulse"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-emerald-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-pulse" style="animation-delay: 4s;"></div>

            <!-- Content Container with Glassmorphism -->
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6 backdrop-blur-md bg-white/60 border border-white/80 rounded-2xl p-6 shadow-sm">
                <div class="text-slate-800">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-100/80 border border-cyan-200 text-xs font-bold tracking-wider uppercase mb-4 text-cyan-700 shadow-sm">
                        <i class="mdi mdi-account-hard-hat text-cyan-600"></i> Petugas Lapangan
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 bg-clip-text text-transparent bg-gradient-to-r from-cyan-600 via-blue-600 to-cyan-700">
                        Halo, {{ Auth::user()->name }}! 👋
                    </h1>
                    <p class="text-slate-600 text-sm md:text-base max-w-2xl font-medium mb-1">
                        Operator Lapangan Kelurahan <span class="font-bold text-cyan-700">{{ Auth::user()->kelurahan ?: 'Belum Diatur' }}</span>
                    </p>
                    <p class="text-slate-600 text-sm md:text-base max-w-2xl font-medium">
                        Portal pendataan UMKM lapangan. Kumpulkan dan input data UMKM secara langsung dari lapangan dengan akurat.
                    </p>
                </div>

                <div class="flex-shrink-0 text-right backdrop-blur-xl bg-white/90 rounded-xl p-4 border border-white shadow-sm">
                    <p class="text-xs text-slate-700 uppercase tracking-widest font-bold mb-1">Hari Ini</p>
                    <p class="text-xl font-black text-slate-800">{{ now()->translatedFormat('l, d F Y') }}</p>
                    <p class="text-sm text-slate-600 mt-1 flex items-center justify-end gap-1 font-semibold"><i class="mdi mdi-clock-outline text-cyan-600"></i> <span id="realtimeClock">{{ now()->format('H:i') }}</span></p>
                </div>
            </div>
        </div>

        <script>
            // Simple realtime clock for banner
            setInterval(() => {
                const now = new Date();
                const clock = document.getElementById('realtimeClock');
                if(clock) {
                    clock.innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');
                }
            }, 60000);
        </script>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-3xl p-6 text-white shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:shadow-[0_8px_30px_rgba(37,99,235,0.4)] transition-all duration-300 hover:-translate-y-1.5 cursor-pointer relative overflow-hidden group">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white rounded-full mix-blend-overlay opacity-10 blur-xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-[11px] font-bold opacity-80 uppercase tracking-widest mb-1">TOTAL INPUT</p>
                        <p class="text-4xl font-black tracking-tight">{{ number_format($totalInputted ?? 0) }}</p>
                        <div class="mt-3 flex items-center gap-1.5">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/20 text-white border border-white/30 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="mdi mdi-format-list-bulleted"></i> Data yang Anda Masukkan
                            </span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 group-hover:bg-white/20 transition-colors shadow-inner">
                        <i class="mdi mdi-plus-circle-outline text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-6 text-white shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:shadow-[0_8px_30px_rgba(16,185,129,0.4)] transition-all duration-300 hover:-translate-y-1.5 cursor-pointer relative overflow-hidden group">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white rounded-full mix-blend-overlay opacity-10 blur-xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-[11px] font-bold opacity-80 uppercase tracking-widest mb-1">DIVERIFIKASI</p>
                        <p class="text-4xl font-black tracking-tight">{{ number_format($totalVerified ?? 0) }}</p>
                        <div class="mt-3 flex items-center gap-1.5">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/20 text-white border border-white/30 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="mdi mdi-check-decagram"></i> Disetujui Admin
                            </span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 group-hover:bg-white/20 transition-colors shadow-inner">
                        <i class="mdi mdi-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Bagian Kiri: Progress & Status --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Progress Verifikasi Lapangan --}}
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-bl-full -mr-8 -mt-8 opacity-50 z-0"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-sm font-bold tracking-wider text-slate-700 uppercase flex items-center gap-2">
                                <i class="mdi mdi-map-marker-distance text-indigo-500 text-lg"></i> Target Kunjungan Lapangan
                            </h2>
                            <span class="text-xs font-semibold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-full">Bulan Ini</span>
                        </div>
                        
                        @php
                            $totalTarget = $totalPerluKunjungan + $totalSudahDikunjungi;
                            $percentage = $totalTarget > 0 ? round(($totalSudahDikunjungi / $totalTarget) * 100) : 0;
                        @endphp

                        <div class="flex items-end gap-2 mb-2">
                            <span class="text-3xl font-black text-slate-800">{{ $percentage }}%</span>
                            <span class="text-sm font-medium text-slate-700 mb-1">Selesai ({{ $totalSudahDikunjungi }} dari {{ $totalTarget }} UMKM)</span>
                        </div>

                        <div class="w-full bg-slate-100 rounded-full h-3.5 mb-4 overflow-hidden border border-slate-200/60 shadow-inner">
                            <div class="bg-gradient-to-r from-indigo-500 to-blue-500 h-3.5 rounded-full transition-all duration-1000 ease-out" style="width: {{ $percentage }}%"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-5">
                            <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-100 flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-1">Telah Dikunjungi</p>
                                    <p class="text-xl font-black text-emerald-700">{{ $totalSudahDikunjungi }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                    <i class="mdi mdi-check-all text-xl"></i>
                                </div>
                            </div>
                            <div class="bg-amber-50 rounded-2xl p-4 border border-amber-100 flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider mb-1">Perlu Kunjungan</p>
                                    <p class="text-xl font-black text-amber-700">{{ $totalPerluKunjungan }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                                    <i class="mdi mdi-walk text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status Breakdown --}}
                <div class="grid grid-cols-1 gap-4">
                    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center flex-shrink-0">
                            <i class="mdi mdi-close-circle-outline text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-0.5">Ditolak Admin</p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-2xl font-black text-slate-800">{{ $totalRejected }}</span>
                                <span class="text-[10px] text-slate-400 font-medium">UMKM</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Bagian Kanan: Aktivitas Terbaru --}}
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h2 class="text-sm font-bold tracking-wider text-slate-700 uppercase flex items-center gap-2">
                        <i class="mdi mdi-history text-slate-400 text-lg"></i> Input Terbaru Anda
                    </h2>
                    <a href="{{ route('operator.umkm.index') }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-800">Lihat Semua</a>
                </div>
                
                <div class="p-2 flex-1 overflow-y-auto">
                    @forelse($recentUmkm as $umkm)
                        <a href="{{ route('operator.umkm.show', $umkm->id_umkm) }}" class="flex items-start gap-3 p-3 hover:bg-slate-50 rounded-xl transition-colors group">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex flex-shrink-0 items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="mdi mdi-storefront-outline text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-800 truncate group-hover:text-blue-600 transition-colors">{{ $umkm->nama_usaha }}</p>
                                <p class="text-xs text-slate-700 truncate mb-1">{{ $umkm->pemilik?->nama_lengkap ?? 'Tanpa Pemilik' }}</p>
                                <div class="flex items-center gap-2 text-[10px] font-medium text-slate-400">
                                    <span>{{ $umkm->created_at->diffForHumans() }}</span>
                                    <span>&bull;</span>
                                    <span class="{{ $umkm->status_verifikasi == 'terverifikasi' ? 'text-emerald-500' : ($umkm->status_verifikasi == 'ditolak' ? 'text-rose-500' : 'text-orange-500') }}">
                                        {{ ucfirst($umkm->status_verifikasi) }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-6 text-center h-full flex flex-col items-center justify-center">
                            <div class="w-12 h-12 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-3">
                                <i class="mdi mdi-text-box-search-outline text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-700 mb-1">Belum ada inputan</p>
                            <p class="text-xs text-slate-700">UMKM yang baru Anda tambahkan akan muncul di sini.</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="p-4 border-t border-slate-100">
                    <a href="{{ route('operator.umkm.create') }}" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl text-sm font-bold transition-colors">
                        <i class="mdi mdi-plus"></i> Tambah UMKM Baru
                    </a>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider px-1">Lihat Data</h3>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <a href="{{ route('operator.umkm.index') }}"
                        class="flex items-start gap-4 p-4 hover:bg-slate-50 transition-colors border-b border-slate-100 group">
                        <div
                            class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                            <i class="mdi mdi-database-outline text-xl"></i>
                        </div>
                        <div>
                            <h4
                                class="text-sm font-bold text-slate-800 mb-0.5 group-hover:text-indigo-600 transition-colors">
                                Data UMKM</h4>
                            <p class="text-[11px] text-slate-700 leading-relaxed">Lihat data UMKM yang Anda input dan seluruh UMKM di wilayah kerja Anda.</p>
                        </div>
                    </a>

                    <a href="{{ route('operator.verifikasi.index') }}"
                        class="flex items-start gap-4 p-4 hover:bg-slate-50 transition-colors group">
                        <div
                            class="w-10 h-10 bg-cyan-50 text-cyan-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                            <i class="mdi mdi-map-marker-check-outline text-xl"></i>
                        </div>
                        <div>
                            <h4
                                class="text-sm font-bold text-slate-800 mb-0.5 group-hover:text-cyan-600 transition-colors">
                                Verifikasi Lapangan</h4>
                            <p class="text-[11px] text-slate-700 leading-relaxed">Lakukan kunjungan lapangan untuk memverifikasi kondisi fisik UMKM.</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider px-1">Akun Pribadi</h3>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-start gap-4 p-4 hover:bg-slate-50 transition-colors border-b border-slate-100 group">
                        <div
                            class="w-10 h-10 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-slate-800 group-hover:text-white transition-all">
                            <i class="mdi mdi-account-edit-outline text-xl"></i>
                        </div>
                        <div>
                            <h4
                                class="text-sm font-bold text-slate-800 mb-0.5 group-hover:text-slate-900 transition-colors">
                                Edit Profil</h4>
                            <p class="text-[11px] text-slate-700 leading-relaxed">Ubah nama, informasi pribadi, foto, dan password akun Anda.</p>
                        </div>
                    </a>

                    <a href="{{ route('operator.verifikasi.index') }}" class="flex items-start gap-4 p-4 hover:bg-slate-50 transition-colors group">
                        <div
                            class="w-10 h-10 bg-violet-50 text-violet-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                            <i class="mdi mdi-bell-outline text-xl"></i>
                        </div>
                        <div>
                            <h4
                                class="text-sm font-bold text-slate-800 mb-0.5 group-hover:text-violet-600 transition-colors">
                                Notifikasi Verifikasi</h4>
                            <p class="text-[11px] text-slate-700 leading-relaxed">Dapatkan notifikasi jika data yang Anda input diverifikasi atau ditolak.</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
