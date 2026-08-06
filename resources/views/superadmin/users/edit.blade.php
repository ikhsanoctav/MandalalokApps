@extends('layouts.superadmin')
@section('title', 'Edit User')
@section('breadcrumb', 'User Manajemen / Edit User')

@section('content')
    <div class="space-y-6" x-data="{
        showEditPassword: false,
        showEditConfirmPassword: false,
        editPhotoPreview: '{{ $user->profile_photo_url }}',
        roleVal: '{{ old('role', $user->roles->first()->name ?? '') }}'
    }">
        
        <div
            class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/60 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Edit Data Pengguna</h1>
                <p class="text-xs text-slate-700 mt-0.5">Perbarui rincian akun, status profil, dan tipe hak akses user</p>
            </div>
            <a href="{{ route('superadmin.users') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 font-semibold text-xs text-slate-700 shadow-sm transition-all">
                <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 w-full">
            <form action="{{ route('superadmin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                <div
                    class="bg-slate-50 p-4 rounded-xl border border-slate-200/60 flex flex-col sm:flex-row items-center gap-4">
                    <div class="relative group flex-shrink-0">
                        <template x-if="editPhotoPreview">
                            <img :src="editPhotoPreview"
                                class="w-20 h-20 rounded-full object-cover border border-slate-200 ring-4 ring-blue-500/10 shadow-md">
                        </template>
                        <template x-if="!editPhotoPreview">
                            <div
                                class="w-20 h-20 rounded-full bg-slate-200 border border-slate-200 flex items-center justify-center text-slate-400 text-xs font-bold uppercase tracking-wider">
                                No Photo
                            </div>
                        </template>
                    </div>
                    <div class="text-center sm:text-left space-y-1">
                        <label
                            class="inline-block px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 cursor-pointer shadow-sm transition-all hover:scale-[1.02] active:scale-[0.98]">
                            Ubah Foto Profil
                            <input type="file" name="foto"
                                @change="
                            const file = $event.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = (e) => { editPhotoPreview = e.target.result; };
                                reader.readAsDataURL(file);
                            }
                        "
                                class="hidden" accept="image/*">
                        </label>
                        <p class="text-[10px] text-slate-400">Maksimal 2MB (JPG, JPEG, PNG, GIF)</p>
                    </div>
                </div>

                <!-- Card: Profil & Identitas -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                    <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                        <div class="p-2 bg-blue-100 text-blue-600 rounded-2xl">
                            <i class="fa-regular fa-id-card text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">Profil & Identitas</h3>
                            <p class="text-xs text-slate-700">Informasi dasar dan kontak pengguna</p>
                        </div>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none @error('name') border-red-400 @enderror"
                                placeholder="Masukkan nama lengkap..." value="{{ old('name', $user->name) }}">
                            @error('name')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Alamat Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none @error('email') border-red-400 @enderror"
                                placeholder="alamat@domain.com" value="{{ old('email', $user->email) }}">
                            @error('email')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">NIK (KTP)
                                <span x-show="roleVal === 'pelaku_umkm'" class="text-red-500" style="display: none;">*</span>
                            </label>
                            <input type="text" name="nik"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none @error('nik') border-red-400 @enderror"
                                placeholder="320xxxxxxxxxxxxx" value="{{ old('nik', $user->nik) }}">
                            @error('nik')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">No. Telepon / HP
                                <span x-show="roleVal === 'pelaku_umkm'" class="text-red-500" style="display: none;">*</span>
                            </label>
                            <input type="text" name="no_hp"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none @error('no_hp') border-red-400 @enderror"
                                placeholder="08xxxxxxxxxx" value="{{ old('no_hp', $user->no_hp) }}">
                            @error('no_hp')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:col-span-2" x-show="roleVal === 'pelaku_umkm'" x-cloak>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Tempat Lahir
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="tempat_lahir"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none @error('tempat_lahir') border-red-400 @enderror"
                                    placeholder="Contoh: Bandung" value="{{ old('tempat_lahir', $user->tempat_lahir) }}">
                                @error('tempat_lahir')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Tanggal Lahir
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_lahir"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none @error('tanggal_lahir') border-red-400 @enderror"
                                    value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}">
                                @error('tanggal_lahir')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-2" x-show="roleVal === 'pelaku_umkm'" x-cloak>
                            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Jenis Kelamin
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_kelamin"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none @error('jenis_kelamin') border-red-400 @enderror">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2 md:col-span-2" x-show="roleVal === 'pelaku_umkm'" x-cloak>
                            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Alamat Lengkap
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alamat" rows="3"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none resize-none @error('alamat') border-red-400 @enderror"
                                placeholder="Masukkan alamat lengkap...">{{ old('alamat', $user->alamat) }}</textarea>
                            @error('alamat')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Card: Hak Akses & Penugasan Wilayah -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                    <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                        <div class="p-2 bg-purple-100 text-purple-600 rounded-2xl">
                            <i class="fa-solid fa-user-shield text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">Hak Akses & Penugasan</h3>
                            <p class="text-xs text-slate-700">Atur peran (*role*) dan cakupan wilayah</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Role / Hak Akses <span class="text-red-500">*</span></label>
                            <select name="role" required id="role-selector" x-model="roleVal" @change="roleVal = $event.target.value"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none @error('role') border-red-400 @enderror">
                                @php
                                    $userRole = $user->roles->first()->name ?? '';
                                @endphp
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role', $userRole) === $role->name ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2" x-show="roleVal && roleVal !== 'pelaku_umkm'" x-cloak>
                            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Jabatan Kerja</label>
                            <input type="text" name="jabatan"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none @error('jabatan') border-red-400 @enderror"
                                placeholder="Contoh: Kepala Seksi, Staff Pelaksana"
                                value="{{ old('jabatan', $user->jabatan) }}">
                            @error('jabatan')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div x-data="{
                            selectedKel: '{{ old('id_kelurahan', $user->id_kelurahan) }}',
                            selectedRw: '',
                            selectedRt: '',
                            kelurahans: {{ $kelurahans->toJson() }},
                            normalizeWilayah(value) {
                                if (value === null || value === undefined || value === '') return '';
                                const parsed = parseInt(String(value).replace(/\D/g, ''), 10);
                                return Number.isNaN(parsed) ? '' : String(parsed);
                            },
                            init() {
                                this.selectedRw = this.normalizeWilayah('{{ old('rw', $user->rw) }}');
                                this.selectedRt = this.normalizeWilayah('{{ old('rt', $user->rt) }}');
                            },
                            get currentRws() {
                                if (!this.selectedKel) return [];
                                const kel = this.kelurahans.find(k => k.id == this.selectedKel);
                                return kel && kel.rws ? kel.rws : [];
                            },
                            get currentRts() {
                                if (!this.selectedRw || !this.currentRws.length) return [];
                                const rw = this.currentRws.find(r => this.normalizeWilayah(r.nomor_rw) == this.selectedRw);
                                return rw && rw.rts ? rw.rts : [];
                            }
                        }" x-show="roleVal === 'operator_lapangan' || roleVal === 'pelaku_umkm'" style="display: none;"
                            x-transition
                            class="p-5 bg-amber-50/50 border border-amber-200/60 rounded-xl space-y-4">
                            
                            <div class="flex items-center gap-3 mb-2 pb-3 border-b border-amber-200/50">
                                <div class="p-1.5 bg-amber-100 text-amber-600 rounded-2xl">
                                    <i class="fa-solid fa-map-location-dot"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-amber-900" x-text="roleVal === 'operator_lapangan' ? 'Wilayah Tugas Operator' : 'Wilayah Tempat Tinggal / Usaha'"></h3>
                                    <p class="text-[10px] text-amber-700/70">Pilih kelurahan dan RW terkait</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider" x-text="roleVal === 'operator_lapangan' ? 'Kelurahan Tugas' : 'Kelurahan'"></label>
                                    <select name="id_kelurahan" x-model="selectedKel" @change="if ($event.isTrusted) { selectedRw = ''; selectedRt = '' }"
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none">
                                        <option value="">-- Pilih Kelurahan --</option>
                                        <template x-for="kel in kelurahans" :key="kel.id">
                                            <option :value="String(kel.id)" x-text="kel.nama_kelurahan"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider flex items-center justify-between">
                                        <span x-text="roleVal === 'operator_lapangan' ? 'RW Tugas (Opsional)' : 'RW'"></span>
                                        <div class="flex gap-1 items-center">
                                            <span x-show="roleVal === 'pelaku_umkm'" class="text-red-500" style="display: none;">*</span>
                                            <span x-show="roleVal === 'operator_lapangan'" class="text-[9px] bg-amber-200/50 text-amber-700 px-1.5 py-0.5 rounded-xl">Bisa Semua RW</span>
                                        </div>
                                    </label>
                                    <select name="rw" x-model="selectedRw" @change="if ($event.isTrusted) { selectedRt = '' }"
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none">
                                        <option value="">-- Semua RW --</option>
                                        <template x-for="rw in currentRws" :key="rw.id">
                                            <option :value="normalizeWilayah(rw.nomor_rw)"
                                                x-text="'RW ' + String(rw.nomor_rw).padStart(3, '0')"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                        RT
                                        <span x-show="roleVal === 'pelaku_umkm'" class="text-red-500" style="display: none;">*</span>
                                    </label>
                                    <select name="rt" x-model="selectedRt"
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none">
                                        <option value="">-- Pilih RT --</option>
                                        <template x-for="rt in currentRts" :key="rt.id">
                                            <option :value="normalizeWilayah(rt.nomor_rt)"
                                                x-text="'RT ' + String(rt.nomor_rt).padStart(3, '0')"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Keamanan -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                    <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-emerald-100 text-emerald-600 rounded-2xl">
                                <i class="fa-solid fa-lock text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800">Keamanan (Password)</h3>
                                <p class="text-xs text-slate-700">Abaikan jika tidak ingin mengubah password</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Password Baru</label>
                            <div class="relative">
                                <input :type="showEditPassword ? 'text' : 'password'" name="password"
                                    class="w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none @error('password') border-red-400 @enderror"
                                    placeholder="Minimal 8 karakter...">
                                <button type="button" @click="showEditPassword = !showEditPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                    <i class="fa-solid" :class="showEditPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <input :type="showEditConfirmPassword ? 'text' : 'password'" name="password_confirmation"
                                    class="w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none"
                                    placeholder="Ulangi password...">
                                <button type="button" @click="showEditConfirmPassword = !showEditConfirmPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                    <i class="fa-solid" :class="showEditConfirmPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info & Tombol Aksi -->
                <div class="bg-slate-50/50 rounded-2xl border border-slate-100 p-5">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3 text-xs text-slate-400">
                            <i class="fa-regular fa-clock"></i>
                            <span>Terakhir diperbarui: {{ $user->updated_at ? $user->updated_at->format('d M Y, H:i') : '-' }}</span>
                        </div>
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <a href="{{ route('superadmin.users') }}"
                                class="flex-1 sm:flex-none text-center px-5 py-2.5 rounded-lg border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-100 transition-colors">Batal</a>
                            <button type="submit"
                                class="flex-1 sm:flex-none text-center px-6 py-2.5 rounded-xl bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 shadow-md shadow-blue-500/20 transition-all hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
