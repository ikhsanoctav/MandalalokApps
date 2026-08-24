        {{-- ===== RESULTS ===== --}}
        @if($pengajuans->isEmpty())
            <div class="bg-white/60 backdrop-blur-xl rounded-[2rem] border border-white/80 p-16 text-center shadow-xl shadow-slate-200/50">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-200">
                    <i class="mdi mdi-folder-search-outline text-4xl text-slate-400"></i>
                </div>
                <h4 class="text-xl font-bold text-slate-700 mb-2">
                    {{ count($activeFilters) > 0 ? 'Tidak Ada Hasil Pencarian' : 'Belum Ada Riwayat Pengajuan' }}
                </h4>
                <p class="text-slate-500 max-w-md mx-auto mb-6">
                    {{ count($activeFilters) > 0 ? 'Coba ubah kata kunci atau filter yang digunakan.' : 'Anda belum pernah mengajukan permohonan bantuan apapun. Klik "Buat Pengajuan Baru" untuk memulai.' }}
                </p>
                @if(count($activeFilters) > 0)
                    <a href="{{ route('pelaku.pengajuan.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-rose-50 border border-rose-200 text-rose-600 font-bold rounded-xl hover:bg-rose-100 transition-all text-sm">
                        <i class="mdi mdi-filter-off-outline"></i> Hapus Semua Filter
                    </a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($pengajuans as $pengajuan)
                    <div class="bg-white/80 backdrop-blur-xl rounded-[1.5rem] border border-white/80 p-6 shadow-lg shadow-slate-200/40 hover:shadow-xl hover:shadow-indigo-100 transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden group">
                        
                        <!-- Card Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex flex-col gap-2 items-start">
                                <span class="px-3 py-1 rounded-lg text-xs font-bold tracking-wider uppercase border
                                    @if($pengajuan->jenis_pengajuan === 'pembiayaan') bg-blue-50 text-blue-700 border-blue-200
                                    @elseif($pengajuan->jenis_pengajuan === 'bantuan') bg-emerald-50 text-emerald-700 border-emerald-200
                                    @elseif($pengajuan->jenis_pengajuan === 'perizinan') bg-purple-50 text-purple-700 border-purple-200
                                    @else bg-slate-50 text-slate-700 border-slate-200 @endif">
                                    {{ ucfirst($pengajuan->jenis_pengajuan) }}
                                </span>
                                {!! $pengajuan->status_badge !!}
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $pengajuan->tanggal_pengajuan ? $pengajuan->tanggal_pengajuan->translatedFormat('d M Y') : '-' }}</p>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Usaha Terkait</p>
                                <p class="font-black text-slate-800 text-lg truncate">{{ $pengajuan->umkm->nama_usaha ?? 'N/A' }}</p>
                                <p class="text-xs text-indigo-500 font-mono mt-0.5"><i class="mdi mdi-identifier"></i> {{ $pengajuan->umkm->no_pendaftaran ?? 'N/A' }}</p>
                            </div>

                            @if($pengajuan->jenis_pengajuan === 'bantuan' && $pengajuan->nama_program)
                                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Program Bantuan</p>
                                    <p class="text-sm font-medium text-slate-700">{{ $pengajuan->nama_program }}</p>
                                </div>
                            @endif

                            @if($pengajuan->nominal)
                                <div class="bg-indigo-50 p-3 rounded-xl border border-indigo-100">
                                    <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-1">Nominal Diajukan</p>
                                    <p class="text-lg font-black text-indigo-700 font-mono">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                                </div>
                            @endif

                            @if($pengajuan->catatan)
                                <div class="mt-4 p-3 bg-amber-50 rounded-xl border border-amber-100">
                                    <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1 flex items-center gap-1"><i class="mdi mdi-message-alert-outline"></i> Catatan Evaluator</p>
                                    <p class="text-xs font-medium text-amber-800 line-clamp-2" title="{{ $pengajuan->catatan }}">{{ $pengajuan->catatan }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($pengajuans->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $pengajuans->links() }}
                </div>
            @endif
        @endif
