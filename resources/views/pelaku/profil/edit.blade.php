@extends('layouts.pelaku')
@section('title', 'Profil Saya')

@section('content')
    <div class="w-full space-y-8 pb-12" x-data="{ 
        isEditing: {{ ($errors->any() || !$pemilik || empty($pemilik->tempat_lahir) || empty($pemilik->jenis_kelamin) || empty($pemilik->alamat) || empty($pemilik->kelurahan) || empty($pemilik->foto_ktp) || request()->has('edit')) ? 'true' : 'false' }},
        selectedKel: '{!! addslashes(old('kelurahan', $pemilik->kelurahan ?? $user->kelurahan ?? '')) !!}',
        selectedRw: '',
        selectedRt: '',
        kelurahansData: {{ isset($kelurahans) ? $kelurahans->toJson() : (isset($kelurahan) ? $kelurahan->toJson() : '[]') }},
        normalizeWilayah(value) {
            if (value === null || value === undefined || value === '') return '';
            const parsed = parseInt(String(value).replace(/\D/g, ''), 10);
            return Number.isNaN(parsed) ? '' : String(parsed).padStart(2, '0');
        },
        init() {
            this.selectedRw = this.normalizeWilayah('{{ old('rw', $pemilik->rw ?? $user->rw ?? '') }}');
            this.selectedRt = this.normalizeWilayah('{{ old('rt', $pemilik->rt ?? $user->rt ?? '') }}');
        },
        get currentRws() {
            if (!this.selectedKel) return [];
            const kel = this.kelurahansData.find(k => k.nama_kelurahan == this.selectedKel);
            return kel && kel.rws ? kel.rws : [];
        },
        get currentRts() {
            if (!this.selectedRw) return [];
            const rws = this.currentRws;
            const rw = rws.find(r => this.normalizeWilayah(r.nomor_rw) == this.selectedRw);
            return rw && rw.rts ? rw.rts : [];
        }
    }">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-600 shadow-inner">
                    <i class="mdi mdi-account-circle-outline text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Profil Saya</h2>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Lengkapi dan kelola data identitas Anda sebagai pemilik usaha.</p>
                </div>
            </div>
        </div>

        <!-- View Mode (Read-only Profile) -->
        <template x-if="!isEditing">
            <div class="bg-white/60 backdrop-blur-xl rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white/80 p-8">
                
                <!-- Profile Header -->
                <div class="flex flex-col sm:flex-row items-center gap-6 pb-8 border-b border-slate-200/60 mb-8">
                    <div class="w-24 h-24 bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-[2rem] flex items-center justify-center text-4xl font-black shrink-0 shadow-lg shadow-indigo-200 transform rotate-3">
                        <div class="-rotate-3">{{ strtoupper(substr($pemilik->nama_lengkap ?? $user->name, 0, 1)) }}</div>
                    </div>
                    <div class="text-center sm:text-left flex-grow">
                        <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $pemilik->nama_lengkap ?? $user->name }}</h3>
                        <p class="text-slate-500 font-medium mt-1">{{ $user->email }}</p>
                        <div class="mt-4">
                            @if($pemilik)
                                @if($pemilik->status_verifikasi_ktp === 'terverifikasi')
                                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-100/80 text-emerald-700 border border-emerald-200 shadow-sm backdrop-blur-md">
                                        <i class="mdi mdi-check-decagram text-lg"></i> Akun Terverifikasi
                                    </span>
                                @elseif($pemilik->status_verifikasi_ktp === 'pending')
                                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-amber-100/80 text-amber-700 border border-amber-200 shadow-sm backdrop-blur-md">
                                        <i class="mdi mdi-clock-outline text-lg"></i> Menunggu Verifikasi Admin
                                    </span>
                                @elseif($pemilik->status_verifikasi_ktp === 'ditolak')
                                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-rose-100/80 text-rose-700 border border-rose-200 shadow-sm backdrop-blur-md">
                                        <i class="mdi mdi-close-circle text-lg"></i> Verifikasi Ditolak
                                    </span>
                                    <p class="text-xs text-rose-600 mt-2 font-medium bg-rose-50 p-2 rounded-md border border-rose-100">Alasan: {{ $pemilik->catatan }}</p>
                                @endif
                            @else
                                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-rose-100/80 text-rose-700 border border-rose-200 shadow-sm backdrop-blur-md">
                                    <i class="mdi mdi-alert-circle text-lg"></i> Profil Belum Lengkap
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="shrink-0 mt-4 sm:mt-0">
                        <button @click="isEditing = true" class="w-full sm:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all flex items-center justify-center gap-2 hover:-translate-y-1">
                            <i class="mdi mdi-pencil"></i> Edit Data Diri
                        </button>
                    </div>
                </div>

                <!-- ID Card Visual -->
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 rounded-[2rem] p-8 shadow-2xl text-white transform transition-transform duration-500 border border-slate-700" x-data="{ showNik: false }">
                    
                    <div class="absolute right-0 bottom-0 opacity-5 transform translate-x-1/4 translate-y-1/4 pointer-events-none">
                        <i class="mdi mdi-card-account-details-outline" style="font-size: 300px;"></i>
                    </div>

                    <div class="flex justify-between items-start mb-10 relative z-10">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-inner">
                                <i class="mdi mdi-badge-account-horizontal-outline text-3xl text-indigo-300"></i>
                            </div>
                            <div>
                                <p class="text-indigo-300 text-[10px] font-black tracking-widest uppercase">Identitas Resmi</p>
                                <p class="text-xl font-bold font-mono tracking-widest mt-1">{{ $pemilik->nik_masked ?? $user->nik_masked ?? '0000000000000000' }}</p>
                            </div>
                        </div>
                        <button @click="showNik = !showNik" class="px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md rounded-lg text-xs font-bold tracking-wider transition-colors flex items-center gap-2">
                            <i class="mdi" :class="showNik ? 'mdi-eye-off' : 'mdi-eye'"></i>
                            <span x-text="showNik ? 'Tutup' : 'Intip'"></span>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 relative z-10">
                        <div class="space-y-1">
                            <p class="text-indigo-300/70 text-[10px] font-bold uppercase tracking-widest">Nama Lengkap</p>
                            <p class="text-lg font-bold">{{ $pemilik->nama_lengkap ?? $user->name ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-indigo-300/70 text-[10px] font-bold uppercase tracking-widest">NIK Valid</p>
                            <p class="text-lg font-bold font-mono text-indigo-300" x-text="showNik ? '{{ $pemilik->nik ?? $user->nik ?? '-' }}' : '{{ $pemilik->nik_masked ?? $user->nik_masked ?? '-' }}'"></p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-indigo-300/70 text-[10px] font-bold uppercase tracking-widest">Tempat, Tanggal Lahir</p>
                            <p class="text-lg font-bold">
                                {{ $pemilik->tempat_lahir ?? '-' }}, {{ $pemilik && $pemilik->tanggal_lahir ? $pemilik->tanggal_lahir->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-indigo-300/70 text-[10px] font-bold uppercase tracking-widest">Jenis Kelamin</p>
                            <p class="text-lg font-bold">
                                {{ ($pemilik->jenis_kelamin ?? '') === 'L' ? 'Laki-laki' : (($pemilik->jenis_kelamin ?? '') === 'P' ? 'Perempuan' : '-') }}
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-indigo-300/70 text-[10px] font-bold uppercase tracking-widest">Nomor Handphone</p>
                            <p class="text-lg font-bold">{{ $pemilik->no_hp ?? $user->no_hp ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-indigo-300/70 text-[10px] font-bold uppercase tracking-widest">Kelurahan & RT/RW</p>
                            <p class="text-lg font-bold">
                                {{ $pemilik->kelurahan ?? $user->kelurahan ?? '-' }}
                                <span class="text-slate-400 font-normal">
                                    (RT {{ $pemilik && $pemilik->rt ? str_pad($pemilik->rt, 3, '0', STR_PAD_LEFT) : '-' }}/RW {{ $pemilik && $pemilik->rw ? str_pad($pemilik->rw, 3, '0', STR_PAD_LEFT) : ($user->rw ? str_pad($user->rw, 3, '0', STR_PAD_LEFT) : '-') }})
                                </span>
                            </p>
                        </div>
                        <div class="space-y-1 md:col-span-2 border-t border-white/10 pt-6 mt-2">
                            <p class="text-indigo-300/70 text-[10px] font-bold uppercase tracking-widest">Alamat Lengkap KTP</p>
                            <p class="text-lg font-bold leading-relaxed">{{ $pemilik->alamat ?? $user->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Edit Mode (Form Fields) -->
        <div x-show="isEditing" x-cloak class="relative">
            <form action="{{ route('pelaku.profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8 relative">
                @csrf
                @method('PUT')

                <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white/80 overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none transform translate-x-1/2 -translate-y-1/2"></div>
                    
                    <div class="px-8 pt-8 pb-6 border-b border-slate-100 relative z-10 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 font-black">
                                <i class="mdi mdi-account-edit-outline text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800">Form Edit Data Diri</h3>
                        </div>
                        <span class="px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full text-xs font-bold text-indigo-600 tracking-widest uppercase hidden sm:block">Perbarui Data</span>
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                        
                        <div class="md:col-span-2" x-data="{ showNik: false }">
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wider">NIK KTP Aktif</label>
                            <div class="relative flex items-center group">
                                <input :type="showNik ? 'text' : 'password'" value="{{ $user->nik }}" readonly
                                    class="w-full bg-slate-50 border-2 border-slate-200 text-slate-500 font-bold text-lg rounded-lg block p-4 pr-32 cursor-not-allowed font-mono tracking-widest transition-all">
                                <button type="button" @click="showNik = !showNik" class="absolute right-3 text-slate-500 hover:text-indigo-600 focus:outline-none flex items-center gap-2 text-xs font-bold bg-white shadow-sm px-4 py-2 rounded-md border border-slate-200 transition-all">
                                    <i class="mdi text-lg" :class="showNik ? 'mdi-eye-off' : 'mdi-eye'"></i>
                                    <span x-text="showNik ? 'Tutup' : 'Lihat'"></span>
                                </button>
                            </div>
                            <p class="mt-2 text-xs font-medium text-slate-400 flex items-center gap-1"><i class="mdi mdi-lock-outline"></i> NIK terhubung otomatis dari akun terdaftar dan terkunci.</p>
                        </div>

                        <div class="md:col-span-2">
                            <label for="nama_lengkap" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap Sesuai KTP <span class="text-rose-500">*</span></label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap"
                                value="{{ old('nama_lengkap', $pemilik->nama_lengkap ?? $user->name) }}" required placeholder="Contoh: Budi Santoso"
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-lg focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 transition-all">
                            @error('nama_lengkap')
                                <span class="text-rose-500 text-xs font-bold mt-1 block"><i class="mdi mdi-alert"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="tempat_lahir" class="block text-sm font-bold text-slate-700 mb-2">Tempat Lahir <span class="text-rose-500">*</span></label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir" required placeholder="Contoh: Bandung"
                                value="{{ old('tempat_lahir', $pemilik->tempat_lahir ?? '') }}"
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-lg focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 transition-all">
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-bold text-slate-700 mb-2">Tanggal Lahir <span class="text-rose-500">*</span></label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" required
                                value="{{ old('tanggal_lahir', $pemilik ? ($pemilik->tanggal_lahir ? $pemilik->tanggal_lahir->format('Y-m-d') : '') : '') }}"
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-lg focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 transition-all">
                        </div>

                        <div>
                            <label for="jenis_kelamin" class="block text-sm font-bold text-slate-700 mb-2">Jenis Kelamin <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <select id="jenis_kelamin" name="jenis_kelamin" required
                                    class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-lg focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 appearance-none transition-all">
                                    <option value="">Pilih...</option>
                                    <option value="L" {{ old('jenis_kelamin', $pemilik->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $pemilik->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <i class="mdi mdi-chevron-down text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="no_hp" class="block text-sm font-bold text-slate-700 mb-2">Nomor HP/WhatsApp <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 font-bold">
                                    +62
                                </div>
                                <input type="text" id="no_hp" name="no_hp" required placeholder="81234567890"
                                    value="{{ old('no_hp', str_replace('+62', '', $pemilik->no_hp ?? $user->no_hp ?? '')) }}"
                                    class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-lg focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 pl-12 transition-all">
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap Sesuai KTP <span class="text-rose-500">*</span></label>
                            <textarea id="alamat" name="alamat" rows="3" required placeholder="Contoh: Jl. Merdeka No. 123..."
                                class="w-full bg-slate-50 hover:bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-lg focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 transition-all resize-none">{{ old('alamat', $pemilik->alamat ?? $user->alamat ?? '') }}</textarea>
                        </div>

                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-6 rounded-2xl border-2 border-slate-100">
                            <div class="md:col-span-2">
                                <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2"><i class="mdi mdi-map-marker-outline text-indigo-500 text-xl"></i> Data Domisili Kecamatan</h4>
                                <label for="kelurahan" class="block text-sm font-bold text-slate-700 mb-2">Kelurahan <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select id="kelurahan" name="kelurahan" x-model="selectedKel"
                                        @change="if ($event.isTrusted) { selectedRw = ''; selectedRt = '' }" required
                                        class="w-full bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-lg focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 appearance-none transition-all">
                                        <option value="">Pilih Kelurahan</option>
                                        <template x-for="kel in kelurahansData" :key="kel.id">
                                            <option :value="kel.nama_kelurahan" x-text="kel.nama_kelurahan"></option>
                                        </template>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                        <i class="mdi mdi-chevron-down text-xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="rw" class="block text-sm font-bold text-slate-700 mb-2">RW <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select id="rw" name="rw" x-model="selectedRw" @change="if ($event.isTrusted) { selectedRt = '' }" required
                                        class="w-full bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-lg focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 appearance-none transition-all">
                                        <option value="">Pilih RW</option>
                                        <template x-for="rw in currentRws" :key="rw.id">
                                            <option :value="normalizeWilayah(rw.nomor_rw)" x-text="'RW ' + String(rw.nomor_rw).padStart(3, '0')"></option>
                                        </template>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                        <i class="mdi mdi-chevron-down text-xl"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="rt" class="block text-sm font-bold text-slate-700 mb-2">RT <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select id="rt" name="rt" x-model="selectedRt" required
                                        class="w-full bg-white border-2 border-slate-200 text-slate-800 font-medium text-base rounded-lg focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 appearance-none transition-all">
                                        <option value="">Pilih RT</option>
                                        <template x-for="rt in currentRts" :key="rt.id">
                                            <option :value="normalizeWilayah(rt.nomor_rt)" x-text="'RT ' + String(rt.nomor_rt).padStart(3, '0')"></option>
                                        </template>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                        <i class="mdi mdi-chevron-down text-xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2 mt-4">
                            <div class="p-6 rounded-2xl border-2 border-indigo-100 bg-indigo-50/30">
                                <label for="foto_ktp" class="block text-sm font-bold text-slate-700 mb-2">
                                    Foto KTP Asli <span class="text-rose-500">*</span>
                                </label>
                                
                                @if($pemilik && $pemilik->foto_ktp)
                                    <div class="mb-4 flex items-center gap-4 p-4 bg-white border-2 border-emerald-200 rounded-xl shadow-sm">
                                        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center shrink-0">
                                            <i class="mdi mdi-check-bold text-2xl"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-emerald-800">Foto KTP Telah Terunggah</p>
                                            <p class="text-xs text-slate-500 font-medium mt-0.5">Pilih file baru di bawah ini hanya jika Anda ingin mengganti KTP saat ini.</p>
                                        </div>
                                    </div>
                                @endif

                                <div class="relative">
                                    <input type="file" id="foto_ktp" name="foto_ktp" {{ ($pemilik && $pemilik->foto_ktp) ? '' : 'required' }} accept="image/*"
                                        class="w-full bg-white border-2 border-dashed border-indigo-200 hover:border-indigo-400 text-slate-600 text-sm rounded-md focus:outline-none block p-8 cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all text-center">
                                </div>
                                
                                <p class="mt-3 text-xs font-bold text-indigo-600 flex items-start gap-2 bg-indigo-100/50 p-3 rounded-md">
                                    <i class="mdi mdi-shield-check text-lg leading-none"></i>
                                    <span>Privasi Aman: Sistem secara otomatis akan menempelkan "Watermark Verifikasi" pada KTP Anda agar tidak dapat disalahgunakan pihak lain.</span>
                                </p>
                                @error('foto_ktp')
                                    <span class="text-rose-500 text-xs font-bold mt-1 block"><i class="mdi mdi-alert"></i> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Sticky Actions Bar -->
                <div class="sticky bottom-4 z-50 mt-10">
                    <div class="bg-slate-900/90 backdrop-blur-xl p-4 sm:px-8 sm:py-5 rounded-[2rem] shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-4 border border-slate-700">
                        <p class="text-slate-300 text-sm font-medium hidden sm:block">Pastikan semua data diisi dengan <span class="text-white font-bold">benar</span> dan <span class="text-white font-bold">lengkap</span>.</p>
                        
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            @if($pemilik)
                                <button type="button" @click="isEditing = false"
                                    class="w-full sm:w-auto px-6 py-3.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold rounded-xl transition-colors">Batalkan</button>
                            @else
                                <a href="{{ route('pelaku.dashboard') }}"
                                    class="w-full sm:w-auto px-6 py-3.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold rounded-xl transition-colors text-center">Kembali</a>
                            @endif
                            <button type="submit"
                                class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-black rounded-xl transition-all shadow-lg shadow-indigo-500/30 hover:-translate-y-1 hover:shadow-indigo-500/50 flex items-center justify-center gap-2">
                                <i class="mdi mdi-content-save"></i> Simpan Data Diri
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
