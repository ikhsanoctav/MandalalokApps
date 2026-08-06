@extends('layouts.superadmin')
@section('title', 'Daftar User')
@section('breadcrumb', 'User Manajemen / Daftar User')

@section('content')
    <div class="space-y-6" x-data="{
        search: '',
        activeTab: sessionStorage.getItem('users_active_tab') || 'staff',
        init() {
            this.$watch('activeTab', (value) => {
                sessionStorage.setItem('users_active_tab', value);
            });
        },
        isModalOpen: false,
        selectedUser: null,
        confirmModal: {
            title: '',
            message: '',
            confirmText: 'Ya, Lanjutkan',
            type: 'danger',
            action: null
        },
        openConfirm(title, message, confirmText, type, action) {
            this.confirmModal.title = title;
            this.confirmModal.message = message;
            this.confirmModal.confirmText = confirmText;
            this.confirmModal.type = type;
            this.confirmModal.action = action;
            this.$dispatch('open-modal', 'confirmModal');
        },
        viewUser(user) {
            this.selectedUser = user;
            this.isModalOpen = true;
        },
        deleteUser(id, name) {
            this.openConfirm(
                'Hapus User',
                'Apakah Anda yakin ingin menghapus user ' + name + '?',
                'Ya, Hapus',
                'danger',
                () => {
                    fetch('/superadmin/users/' + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.showToast('success', 'Berhasil', 'Berhasil menghapus user ' + name);
                            this.$dispatch('close-modal', 'confirmModal');
                            
                            // Smoothly remove row without reloading
                            const row = document.getElementById('row-user-' + id);
                            if (row) {
                                row.style.transition = 'all 0.3s ease';
                                row.style.opacity = '0';
                                row.style.transform = 'translateX(-20px)';
                                setTimeout(() => row.remove(), 300);
                            }
                        } else {
                            window.showToast('error', 'Gagal', data.message || 'Gagal menghapus user');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.showToast('error', 'Gagal', 'Terjadi kesalahan koneksi.');
                    });
                }
            );
        },
        resetPassword(id, name) {
            this.openConfirm(
                'Reset Password',
                'Apakah Anda yakin ingin mereset password user ' + name + ' menjadi default (password)?',
                'Ya, Reset',
                'warning',
                () => {
                    fetch('/superadmin/users/' + id + '/reset-password', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.showToast('success', 'Berhasil', data.message);
                            this.$dispatch('close-modal', 'confirmModal');
                        } else {
                            window.showToast('error', 'Gagal', data.message || 'Gagal mereset password');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.showToast('error', 'Gagal', 'Terjadi kesalahan koneksi.');
                    });
                }
            );
        },
        toggleActive(id, name, actionName) {
            this.openConfirm(
                actionName === 'aktifkan' ? 'Aktifkan User' : 'Nonaktifkan User',
                'Apakah Anda yakin ingin ' + actionName + ' user ' + name + '?',
                'Ya, Lanjutkan',
                actionName === 'aktifkan' ? 'success' : 'warning',
                () => {
                    fetch('/superadmin/users/' + id + '/toggle-active', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.showToast('success', 'Berhasil', data.message);
                            this.$dispatch('close-modal', 'confirmModal');
                            setTimeout(() => window.location.reload(), 1000); // Reload to reflect status
                        } else {
                            window.showToast('error', 'Gagal', data.message || 'Gagal mengubah status user');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.showToast('error', 'Gagal', 'Terjadi kesalahan koneksi.');
                    });
                }
            );
        },
    }">
        
        <div
            class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/60 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Daftar Pengguna Sistem</h1>
                <p class="text-xs text-slate-700 mt-0.5">Kelola seluruh pengguna, tipe hak akses, dan status profil mereka
                </p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('superadmin.users.roles') }}"
                    class="flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 font-semibold text-xs text-slate-700 shadow-sm transition-all w-full sm:w-auto">
                    <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z">
                        </path>
                    </svg>
                    Role Access Matrix
                </a>
                <a href="{{ route('superadmin.users.create') }}"
                    class="flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 font-semibold text-xs text-white shadow-md shadow-blue-500/20 transition-all w-full sm:w-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Tambah User
                </a>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/60 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="relative w-full md:w-96">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input x-model="search" type="text" placeholder="Cari nama, email, NIK, atau jabatan..."
                    class="pl-10 pr-4 py-2.5 w-full bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <div class="flex items-center gap-4 text-xs font-medium text-slate-700 min-h-[1.5rem]">
                <!-- Staff Legend -->
                <div x-show="activeTab === 'staff'" class="flex items-center gap-4">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-blue-500 rounded-full"></span> Super Admin</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-purple-500 rounded-full"></span> Admin Kecamatan</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span> Operator Lapangan</span>
                </div>
                <!-- Pelaku Legend -->
                <div x-show="activeTab === 'pelaku'" class="flex items-center gap-1.5" x-cloak>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-blue-500 rounded-full"></span> Pelaku UMKM</span>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/60">
            <div class="border-b border-slate-200/60">
                <nav class="flex gap-6 -mb-px">
                    <button @click="activeTab = 'staff'" 
                        :class="activeTab === 'staff' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-700 hover:text-slate-700 hover:border-slate-300'"
                        class="pb-4 px-1 border-b-2 font-semibold text-sm transition-all flex items-center gap-2 focus:outline-none">
                        Pengelola & Staf Sistem
                        <span :class="activeTab === 'staff' ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-600'" 
                            class="px-2.5 py-0.5 rounded-full text-xs font-bold transition-colors">{{ $staffCount ?? $nonPelakuUmkm->total() }}</span>
                    </button>
                    <button @click="activeTab = 'pelaku'" 
                        :class="activeTab === 'pelaku' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-700 hover:text-slate-700 hover:border-slate-300'"
                        class="pb-4 px-1 border-b-2 font-semibold text-sm transition-all flex items-center gap-2 focus:outline-none">
                        Pelaku UMKM
                        <span :class="activeTab === 'pelaku' ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-600'" 
                            class="px-2.5 py-0.5 rounded-full text-xs font-bold transition-colors">{{ $pelakuCount ?? $pelakuUmkm->total() }}</span>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tabel Staff / Pengelola Sistem -->
        <div x-show="activeTab === 'staff'" 
            class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/60 text-xs font-semibold text-slate-700 uppercase tracking-wider">
                            <th class="py-4 px-6">User</th>
                            <th class="py-4 px-6">Kontak</th>
                            <th class="py-4 px-6">NIK</th>
                            <th class="py-4 px-6">Role / Jabatan</th>
                            <th class="py-4 px-6">Alamat</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                        @forelse ($nonPelakuUmkm as $user)
                            @php
                                $roleName = $user->roles->first()->name ?? 'No Role';
                                $userPhoto = $user->profile_photo_url;
                                $userInitials = collect(explode(' ', $user->name))
                                    ->map(fn($n) => Str::upper(Str::substr($n, 0, 1)))
                                    ->take(2)
                                    ->implode('');

                                $roleColors = [
                                    'super_admin' => 'bg-blue-50 text-blue-600 border border-blue-200/60',
                                    'admin_kecamatan' => 'bg-purple-50 text-purple-600 border border-purple-200/60',
                                    'operator_lapangan' => 'bg-emerald-50 text-emerald-600 border border-emerald-200/60',
                                ];
                                $roleClass = $roleColors[$roleName] ?? 'bg-slate-50 text-slate-600 border border-slate-200/60';
                                
                                $pemilikData = [];
                                if ($user->nik) {
                                    $pemilik = \App\Models\Pemilik::where('nik_hash', hash('sha256', $user->nik))->first() 
                                            ?? \App\Models\Pemilik::where('nik', $user->nik)->first();
                                    if ($pemilik) {
                                        $pemilikData = [
                                            'tempat_lahir' => $pemilik->tempat_lahir,
                                            'tanggal_lahir' => $pemilik->tanggal_lahir ? $pemilik->tanggal_lahir->format('d M Y') : null,
                                            'jenis_kelamin' => $pemilik->jenis_kelamin == 'L' ? 'Laki-laki' : ($pemilik->jenis_kelamin == 'P' ? 'Perempuan' : null),
                                            'kelurahan' => $pemilik->kelurahan,
                                            'rw' => $pemilik->rw,
                                            'rt' => $pemilik->rt,
                                            'status_verifikasi_ktp' => $pemilik->status_verifikasi_ktp,
                                            'foto_ktp' => $pemilik->foto_ktp ? asset('storage/' . $pemilik->foto_ktp) : null,
                                        ];
                                    }
                                }
                            @endphp
                            <tr id="row-user-{{ $user->id }}" 
                                x-show="search === '' || $el.dataset.search.includes(search.toLowerCase())"
                                data-search="{{ e(strtolower($user->name . ' ' . $user->email . ' ' . ($user->jabatan ?? ''))) }}"
                                class="hover:bg-slate-50/50 transition-colors">
                                
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        @if ($userPhoto)
                                            <img src="{{ $userPhoto }}" alt="{{ $user->name }}"
                                                class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-100">
                                        @else
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs bg-gradient-to-tr from-slate-200 to-slate-300 text-slate-600 border border-slate-400/20">
                                                {{ $userInitials }}
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="font-bold text-slate-800">{{ $user->name }}</h3>
                                            <span class="text-xs text-slate-400 font-mono font-semibold">{{ $user->kode_user ?? 'ID: #' . $user->id }}</span>
                                            @if($user->password_reset_status === 'pending')
                                                <div class="mt-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-red-100 text-red-600 border border-red-200">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i> Meminta Reset Sandi
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-6">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-800">{{ $user->email }}</span>
                                        <span class="text-xs text-slate-400">{{ $user->no_hp ?? '-' }}</span>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-6 font-mono text-xs text-slate-600">
                                    {{ $user->nik_masked ?? '-' }}
                                </td>
                                
                                <td class="py-4 px-6">
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $roleClass }}">
                                            {{ str_replace('_', ' ', $roleName) }}
                                        </span>
                                        <span class="text-xs text-slate-400">{{ $user->jabatan ?? '-' }}</span>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-6 text-xs text-slate-700 max-w-[200px] truncate" title="{{ $user->alamat }}">
                                    {{ $user->alamat ?? '-' }}
                                </td>
                                
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="viewUser({{ json_encode(array_merge([
                                            'id' => $user->id,
                                            'name' => $user->name,
                                            'email' => $user->email,
                                            'nik' => $user->nik,
                                            'role' => str_replace('_', ' ', $roleName),
                                            'jabatan' => $user->jabatan,
                                            'kelurahan' => $user->kelurahan,
                                            'rw' => $user->rw,
                                            'rt' => $user->rt,
                                            'no_hp' => $user->no_hp,
                                            'alamat' => $user->alamat,
                                            'foto' => $userPhoto,
                                            'kode_user' => $user->kode_user,
                                            'created_at' => $user->created_at ? $user->created_at->format('d M Y, H:i') : null,
                                        ], $pemilikData)) }})"
                                            class="p-2 text-blue-600 hover:text-blue-700 rounded-2xl hover:bg-blue-50 transition-colors"
                                            title="Lihat Detail User">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </button>
                                        <a href="{{ route('superadmin.users.edit', $user->id) }}"
                                            class="p-2 text-amber-500 hover:text-amber-600 rounded-2xl hover:bg-amber-50 transition-colors"
                                            title="Edit User">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </a>
                                        <button @click="resetPassword('{{ $user->id }}', $el.dataset.name)"
                                            data-name="{{ e($user->name) }}"
                                            class="p-2 text-emerald-500 hover:text-emerald-600 rounded-2xl hover:bg-emerald-50 transition-colors"
                                            title="Reset Password">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"></path></svg>
                                        </button>
                                        @if ($user->id !== auth()->id())
                                            <button @click="toggleActive('{{ $user->id }}', $el.dataset.name, '{{ $user->is_active ? 'nonaktifkan' : 'aktifkan' }}')"
                                                data-name="{{ e($user->name) }}"
                                                class="p-2 {{ $user->is_active ? 'text-slate-500 hover:text-slate-600 hover:bg-slate-100' : 'text-emerald-500 hover:text-emerald-600 hover:bg-emerald-50' }} rounded-2xl transition-colors"
                                                title="{{ $user->is_active ? 'Nonaktifkan User' : 'Aktifkan User' }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if ($user->is_active)
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    @endif
                                                </svg>
                                            </button>
                                            <button @click="deleteUser('{{ $user->id }}', $el.dataset.name)"
                                                data-name="{{ e($user->name) }}"
                                                class="p-2 text-red-500 hover:text-red-600 rounded-2xl hover:bg-red-50 transition-colors"
                                                title="Hapus User">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Belum ada data pengelola sistem.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($nonPelakuUmkm->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $nonPelakuUmkm->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Tabel Pelaku UMKM -->
        <div x-show="activeTab === 'pelaku'" 
            class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/60 text-xs font-semibold text-slate-700 uppercase tracking-wider">
                            <th class="py-4 px-6">User Pelaku</th>
                            <th class="py-4 px-6">Kontak</th>
                            <th class="py-4 px-6">NIK</th>
                            <th class="py-4 px-6">Role</th>
                            <th class="py-4 px-6">Alamat</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                        @forelse ($pelakuUmkm as $user)
                            @php
                                $userPhoto = $user->profile_photo_url;
                                $userInitials = collect(explode(' ', $user->name))
                                    ->map(fn($n) => Str::upper(Str::substr($n, 0, 1)))
                                    ->take(2)
                                    ->implode('');
                                    
                                $pemilikData = [];
                                if ($user->nik) {
                                    $pemilik = \App\Models\Pemilik::where('nik_hash', hash('sha256', $user->nik))->first() 
                                            ?? \App\Models\Pemilik::where('nik', $user->nik)->first();
                                    if ($pemilik) {
                                        $pemilikData = [
                                            'tempat_lahir' => $pemilik->tempat_lahir,
                                            'tanggal_lahir' => $pemilik->tanggal_lahir ? $pemilik->tanggal_lahir->format('d M Y') : null,
                                            'jenis_kelamin' => $pemilik->jenis_kelamin == 'L' ? 'Laki-laki' : ($pemilik->jenis_kelamin == 'P' ? 'Perempuan' : null),
                                            'kelurahan' => $pemilik->kelurahan,
                                            'rw' => $pemilik->rw,
                                            'rt' => $pemilik->rt,
                                            'status_verifikasi_ktp' => $pemilik->status_verifikasi_ktp,
                                            'foto_ktp' => $pemilik->foto_ktp ? asset('storage/' . $pemilik->foto_ktp) : null,
                                        ];
                                    }
                                }
                            @endphp
                            <tr id="row-user-{{ $user->id }}" 
                                x-show="search === '' || $el.dataset.search.includes(search.toLowerCase())"
                                data-search="{{ e(strtolower($user->name . ' ' . $user->email)) }}"
                                class="hover:bg-slate-50/50 transition-colors">
                                
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        @if ($userPhoto)
                                            <img src="{{ $userPhoto }}" alt="{{ $user->name }}"
                                                class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-100">
                                        @else
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs bg-gradient-to-tr from-slate-200 to-slate-300 text-slate-600 border border-slate-400/20">
                                                {{ $userInitials }}
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="font-bold text-slate-800">{{ $user->name }}</h3>
                                            <span class="text-xs text-slate-400 font-mono font-semibold">{{ $user->kode_user ?? 'ID: #' . $user->id }}</span>
                                            @if($user->password_reset_status === 'pending')
                                                <div class="mt-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-red-100 text-red-600 border border-red-200">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i> Meminta Reset Sandi
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-6">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-800">{{ $user->email }}</span>
                                        <span class="text-xs text-slate-400">{{ $user->no_hp ?? '-' }}</span>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-6 font-mono text-xs text-slate-600">
                                    {{ $user->nik_masked ?? '-' }}
                                </td>
                                
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-200/60">
                                        Pelaku UMKM
                                    </span>
                                </td>
                                
                                <td class="py-4 px-6 text-xs text-slate-700 max-w-[200px] truncate" title="{{ $user->alamat }}">
                                    {{ $user->alamat ?? '-' }}
                                </td>
                                
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="viewUser({{ json_encode(array_merge([
                                            'id' => $user->id,
                                            'name' => $user->name,
                                            'email' => $user->email,
                                            'nik' => $user->nik,
                                            'role' => 'Pelaku UMKM',
                                            'jabatan' => $user->jabatan,
                                            'kelurahan' => $user->kelurahan,
                                            'rw' => $user->rw,
                                            'rt' => $user->rt,
                                            'no_hp' => $user->no_hp,
                                            'alamat' => $user->alamat,
                                            'foto' => $userPhoto,
                                            'kode_user' => $user->kode_user,
                                            'created_at' => $user->created_at ? $user->created_at->format('d M Y, H:i') : null,
                                        ], $pemilikData)) }})"
                                            class="p-2 text-blue-600 hover:text-blue-700 rounded-2xl hover:bg-blue-50 transition-colors"
                                            title="Lihat Detail User">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </button>
                                        <a href="{{ route('superadmin.users.edit', $user->id) }}"
                                            class="p-2 text-amber-500 hover:text-amber-600 rounded-2xl hover:bg-amber-50 transition-colors"
                                            title="Edit User">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </a>
                                        <button @click="resetPassword('{{ $user->id }}', $el.dataset.name)"
                                            data-name="{{ e($user->name) }}"
                                            class="p-2 text-emerald-500 hover:text-emerald-600 rounded-2xl hover:bg-emerald-50 transition-colors"
                                            title="Reset Password">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"></path></svg>
                                        </button>
                                        @if ($user->id !== auth()->id())
                                            <button @click="toggleActive('{{ $user->id }}', $el.dataset.name, '{{ $user->is_active ? 'nonaktifkan' : 'aktifkan' }}')"
                                                data-name="{{ e($user->name) }}"
                                                class="p-2 {{ $user->is_active ? 'text-slate-500 hover:text-slate-600 hover:bg-slate-100' : 'text-emerald-500 hover:text-emerald-600 hover:bg-emerald-50' }} rounded-2xl transition-colors"
                                                title="{{ $user->is_active ? 'Nonaktifkan User' : 'Aktifkan User' }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if ($user->is_active)
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    @endif
                                                </svg>
                                            </button>
                                            <button @click="deleteUser('{{ $user->id }}', $el.dataset.name)"
                                                data-name="{{ e($user->name) }}"
                                                class="p-2 text-red-500 hover:text-red-600 rounded-2xl hover:bg-red-50 transition-colors"
                                                title="Hapus User">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Belum ada data Pelaku UMKM.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($pelakuUmkm->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $pelakuUmkm->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Detail User -->
        <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="isModalOpen" @click="isModalOpen = false"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="isModalOpen"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full border border-slate-200">
                    
                    <div class="bg-white">
                        <!-- Header Banner -->
                        <div class="relative bg-gradient-to-r from-slate-800 to-slate-900 rounded-t-lg px-6 pt-6 pb-20 overflow-hidden">
                            <!-- Background Pattern -->
                            <div class="absolute inset-0 opacity-10" style="background-image: var(--batik-jabar); background-size: 60px;"></div>
                            <div class="relative flex justify-between items-start">
                                <h3 class="text-xl font-black text-white tracking-tight flex items-center gap-2" id="modal-title">
                                    <i class="fa-solid fa-id-card text-emerald-400"></i>
                                    Profil Pengguna
                                </h3>
                                <button type="button" @click="isModalOpen = false" class="text-slate-300 hover:text-white transition-colors bg-white/10 hover:bg-white/20 p-2 rounded-xl backdrop-blur-sm">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Profile Info (Overlapping) -->
                        <template x-if="selectedUser">
                            <div class="px-6 pb-6 relative -mt-14">
                                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-5 flex flex-col sm:flex-row items-center sm:items-start gap-5">
                                    <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-md bg-slate-50 flex items-center justify-center text-slate-300 shrink-0 relative z-10">
                                        <template x-if="selectedUser.foto">
                                            <img :src="selectedUser.foto" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!selectedUser.foto">
                                            <i class="fa-solid fa-user text-4xl"></i>
                                        </template>
                                    </div>
                                    <div class="flex-1 text-center sm:text-left mt-2 sm:mt-0">
                                        <h4 class="text-2xl font-black text-slate-800" x-text="selectedUser.name"></h4>
                                        <p class="text-sm font-medium text-slate-700 mb-2" x-text="selectedUser.email"></p>
                                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-2xl text-[10px] font-bold bg-blue-50 text-blue-700 uppercase tracking-widest border border-blue-200/60" x-text="selectedUser.role"></span>
                                            <span class="px-2 py-1 rounded-md text-xs font-mono font-bold bg-slate-100 text-slate-600 border border-slate-200" x-text="selectedUser.kode_user || '-'"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detail Sections -->
                                <div class="mt-6 space-y-4">
                                    <!-- Identitas -->
                                    <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                                        <h5 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                            <i class="fa-solid fa-address-card"></i> Identitas & Kependudukan
                                        </h5>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6">
                                            <div>
                                                <p class="text-xs font-medium text-slate-700 mb-1">Nomor Induk Kependudukan</p>
                                                <p class="text-sm font-bold text-slate-800 font-mono" x-text="selectedUser.nik || '-'"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-medium text-slate-700 mb-1">Status Verifikasi KTP</p>
                                                <template x-if="selectedUser.status_verifikasi_ktp">
                                                    <span class="inline-flex px-2.5 py-1 rounded-2xl text-xs font-bold uppercase" 
                                                          :class="selectedUser.status_verifikasi_ktp == 'terverifikasi' ? 'bg-emerald-100 text-emerald-700' : (selectedUser.status_verifikasi_ktp == 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700')"
                                                          x-text="selectedUser.status_verifikasi_ktp"></span>
                                                </template>
                                                <template x-if="!selectedUser.status_verifikasi_ktp">
                                                    <span class="text-sm font-medium text-slate-800">-</span>
                                                </template>
                                            </div>
                                            <div>
                                                <p class="text-xs font-medium text-slate-700 mb-1">Jenis Kelamin</p>
                                                <p class="text-sm font-medium text-slate-800" x-text="selectedUser.jenis_kelamin || '-'"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-medium text-slate-700 mb-1">Tempat, Tanggal Lahir</p>
                                                <p class="text-sm font-medium text-slate-800" x-text="(selectedUser.tempat_lahir || '-') + ', ' + (selectedUser.tanggal_lahir || '-')"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kontak & Wilayah -->
                                    <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                                        <h5 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                            <i class="fa-solid fa-location-dot"></i> Kontak & Wilayah
                                        </h5>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6">
                                            <div>
                                                <p class="text-xs font-medium text-slate-700 mb-1">No. HP / WhatsApp</p>
                                                <p class="text-sm font-medium text-slate-800" x-text="selectedUser.no_hp || '-'"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-medium text-slate-700 mb-1">Jabatan Kerja</p>
                                                <p class="text-sm font-medium text-slate-800" x-text="selectedUser.jabatan || '-'"></p>
                                            </div>
                                            <div class="sm:col-span-2">
                                                <p class="text-xs font-medium text-slate-700 mb-1">Alamat Lengkap</p>
                                                <p class="text-sm font-medium text-slate-800 leading-relaxed" x-text="selectedUser.alamat || '-'"></p>
                                            </div>
                                            <div class="sm:col-span-2">
                                                <p class="text-xs font-medium text-slate-700 mb-1">Wilayah / Kelurahan</p>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-sm font-bold text-slate-700 shadow-sm" x-text="selectedUser.kelurahan || '-'"></span>
                                                    <template x-if="selectedUser.rw">
                                                        <span class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-sm font-bold text-slate-700 shadow-sm" x-text="'RW ' + String(selectedUser.rw).padStart(3, '0')"></span>
                                                    </template>
                                                    <template x-if="selectedUser.rt">
                                                        <span class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-sm font-bold text-slate-700 shadow-sm" x-text="'RT ' + String(selectedUser.rt).padStart(3, '0')"></span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Foto KTP (Jika Ada) -->
                                    <template x-if="selectedUser.foto_ktp">
                                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                                            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                                <i class="fa-solid fa-image"></i> Dokumen KTP
                                            </h5>
                                            <img :src="selectedUser.foto_ktp" class="w-full max-w-sm rounded-xl border border-slate-200 shadow-sm mx-auto sm:mx-0">
                                        </div>
                                    </template>

                                    <!-- Tanggal Bergabung -->
                                    <div class="flex items-center justify-between text-xs text-slate-400 pt-2">
                                        <span class="flex items-center gap-1.5">
                                            <i class="fa-regular fa-calendar"></i>
                                            Terdaftar sejak: <span class="font-semibold text-slate-700" x-text="selectedUser.created_at || '-'"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-between border-t border-slate-100">
                        <template x-if="selectedUser">
                            <a :href="'/superadmin/users/' + selectedUser.id + '/edit'" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 text-sm font-semibold transition-colors border border-blue-200/60">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                Edit User Ini
                            </a>
                            <button type="button" @click="resetPassword(selectedUser.id, selectedUser.name)" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 text-sm font-semibold transition-colors border border-amber-200/60 ml-2">
                                <i class="fa-solid fa-key text-xs"></i>
                                Reset Password
                            </button>
                        </template>
                        <button type="button" @click="isModalOpen = false" class="inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-6 py-2 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none transition-colors ml-auto">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Standardized Confirmation Modal -->
        <x-modal name="confirmModal" maxWidth="md">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-t-2xl">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                         :class="confirmModal.type === 'danger' ? 'bg-red-100' : (confirmModal.type === 'success' ? 'bg-emerald-100' : 'bg-amber-100')">
                         
                        <!-- Trash Icon for Danger -->
                        <svg x-show="confirmModal.type === 'danger'" class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>

                        <!-- Key Icon for Warning/Reset -->
                        <svg x-show="confirmModal.type === 'warning'" style="display:none;" class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z">
                            </path>
                        </svg>

                        <!-- Check Icon for Success -->
                        <svg x-show="confirmModal.type === 'success'" style="display:none;" class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-bold text-slate-900" id="modal-title" x-text="confirmModal.title"></h3>
                        <div class="mt-2">
                            <p class="text-sm text-slate-500 font-medium" x-text="confirmModal.message"></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100 rounded-b-2xl">
                <button type="button" @click="$dispatch('close'); if(confirmModal.action) confirmModal.action();" 
                    class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 text-base font-semibold text-white focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors"
                    :class="confirmModal.type === 'danger' ? 'bg-red-600 hover:bg-red-700' : (confirmModal.type === 'success' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-amber-500 hover:bg-amber-600')"
                    x-text="confirmModal.confirmText">
                </button>
                <button type="button" @click="$dispatch('close')" 
                    class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                    Batal
                </button>
            </div>
        </x-modal>
    </div>
@endsection
