
@php
    $pendingCount = \App\Models\UMKM::where('status_verifikasi', 'terkirim')->count();
    $userRole = auth()->user()->roles->first()->name ?? 'super_admin';
@endphp

<div x-show="mobileMenuOpen" x-transition:enter="transform transition duration-300 ease-out"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transform transition duration-300 ease-in" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed top-0 left-0 bottom-0 w-[86vw] max-w-80 bg-slate-900 z-50 shadow-xl overflow-y-auto md:hidden">

    <div class="sticky top-0 bg-slate-900 p-4 border-b border-slate-700">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo" class="h-8 w-8 object-contain">
            <div>
                <h1 class="text-sm font-bold text-white">Mandalaloka</h1>
                <p class="text-xs text-slate-400">
                    {{ $userRole === 'super_admin' ? 'Super Admin Panel' : ($userRole === 'admin_kecamatan' ? 'Admin Kecamatan' : ($userRole === 'operator_lapangan' ? 'Petugas Lapangan' : 'Pelaku UMKM')) }}
                </p>
            </div>
        </div>
    </div>

    <nav class="p-3 space-y-1 pb-32">
        @php
            $dashboardRoute = route('operator.dashboard');
            if ($userRole === 'super_admin') {
                $dashboardRoute = route('superadmin.dashboard');
            } elseif ($userRole === 'admin_kecamatan') {
                $dashboardRoute = route('admin.dashboard');
            } elseif ($userRole === 'pelaku_umkm') {
                $dashboardRoute = route('pelaku.dashboard');
            }

            $isDashboardActive =
                request()->routeIs('superadmin.dashboard') ||
                request()->routeIs('admin.dashboard') ||
                request()->routeIs('operator.dashboard') ||
                request()->routeIs('pelaku.dashboard');
        @endphp
        <a href="{{ $dashboardRoute }}" @click="toggleMobileMenu()"
            class="flex min-h-11 items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ $isDashboardActive ? 'menu-item-active bg-slate-800/40 text-blue-400 font-bold' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                </path>
            </svg>
            <span>Dashboard</span>
        </a>

        <div class="my-2 border-t border-slate-700/50"></div>

        @if ($userRole === 'super_admin')
            
            <div x-data="{ open: {{ request()->routeIs('superadmin.umkm*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <span>Data UMKM</span>
                    </div>
                    <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="pl-7 mt-0.5 space-y-0.5">
                    <a href="{{ route('superadmin.umkm') }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">Daftar
                        UMKM</a>
                    <a href="{{ route('superadmin.umkm.create') }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">Tambah
                        UMKM</a>
                    {{-- 
                    <a href="{{ route('superadmin.umkm', ['status_verifikasi' => 'terverifikasi']) }}"
                        @click="toggleMobileMenu()"
                        class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">
                        <span>UMKM Pending</span>
                        <span
                            class="bg-red-500 text-white text-[9px] px-1.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
                    </a>
                    --}}
                </div>
            </div>

            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span>Data Pengajuan</span>
                    </div>
                    <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="pl-7 mt-0.5 space-y-0.5">
                    <a href="{{ route('superadmin.pengajuan', ['status' => 'menunggu']) }}"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">Daftar
                        Pengajuan</a>
                    <a href="{{ route('superadmin.pengajuan') }}"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">Riwayat
                        Pengajuan</a>
                </div>
            </div>

            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span>User Manajemen</span>
                    </div>
                    <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="pl-7 mt-0.5 space-y-0.5">
                    <a href="{{ route('superadmin.users') }}"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">Daftar
                        User</a>
                    <a href="{{ route('superadmin.users.create') }}"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">Tambah
                        User</a>
                    <a href="{{ route('superadmin.users.roles') }}"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">Role
                        Access</a>
                </div>
            </div>

            <div class="my-2 border-t border-slate-700/50"></div>
            <p class="text-[9px] text-slate-500 uppercase tracking-wider px-3 pt-1">Master & Sistem</p>

            <a href="{{ route('superadmin.laporan') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.laporan') ? 'menu-item-active bg-slate-800/40 text-blue-400 font-bold' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span>Laporan</span>
            </a>

            <a href="{{ route('admin.pelatihan.index') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('admin.pelatihan.*') ? 'menu-item-active bg-slate-800/40 text-blue-400 font-bold' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path>
                </svg>
                <span>Kelola Pelatihan</span>
            </a>

            <a href="{{ route('dss.index') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('dss.index') ? 'menu-item-active bg-slate-800/40 text-blue-400 font-bold' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                    </path>
                </svg>
                <span>Analisis AI (DSS)</span>
            </a>

            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4">
                            </path>
                        </svg>
                        <span>Data Master</span>
                    </div>
                    <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="pl-7 mt-0.5 space-y-0.5">
                    <a href="{{ route('superadmin.kelurahan.index') }}"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">Data
                        Kelurahan</a>
                    <a href="{{ route('superadmin.rw.index') }}"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">Data
                        RW</a>
                    <a href="{{ route('superadmin.sektor.index') }}"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">Data
                        Sektor</a>
                    <a href="{{ route('superadmin.kategori.index') }}"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">Kategori
                        UMKM</a>
                    <a href="{{ route('superadmin.logs') }}"
                        class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">
                        <span>Logs Sistem</span>
                    </a>
                </div>
            </div>

            <a href="{{ route('superadmin.settings') }}"
                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span>Pengaturan Sistem</span>
            </a>

            <a href="{{ route('superadmin.backup.index') }}"
                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                    </path>
                </svg>
                <span>Backup & Restore</span>
            </a>
        @elseif($userRole === 'admin_kecamatan')

            <a href="{{ route('admin.umkm.index') }}"
                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('admin.umkm.*') ? 'menu-item-active bg-slate-800/40 text-blue-400 font-bold' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
                <span>Data UMKM</span>
            </a>

            {{-- 
            <a href="{{ route('admin.verifikasi.index') }}"
                class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('admin.verifikasi.*') ? 'menu-item-active bg-slate-800' : '' }}">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Verifikasi Lapangan</span>
                </div>
                @if ($pendingCount > 0)
                    <span
                        class="bg-red-500 text-white text-[9px] px-1.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
                @endif
            </a>
            --}}

            <div class="my-2 border-t border-slate-700/50"></div>

            <a href="{{ route('admin.laporan.index') }}"
                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('admin.laporan.*') ? 'menu-item-active bg-slate-800' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span>Rekap & Laporan</span>
            </a>

            <a href="{{ route('admin.pelatihan.index') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('admin.pelatihan.*') ? 'menu-item-active bg-slate-800/40 text-blue-400 font-bold' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path>
                </svg>
                <span>Kelola Pelatihan</span>
            </a>

            <a href="{{ route('dss.index') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('dss.index') ? 'menu-item-active bg-slate-800/40 text-blue-400 font-bold' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                    </path>
                </svg>
                <span>Analisis AI (DSS)</span>
            </a>
        @elseif($userRole === 'operator_lapangan')

            <a href="{{ route('operator.umkm.create') }}"
                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('operator.umkm.create') ? 'menu-item-active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Input UMKM Baru</span>
            </a>

            <a href="{{ route('operator.umkm.index') }}"
                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('operator.umkm.index') || request()->routeIs('operator.umkm.edit') || request()->routeIs('operator.umkm.upload-foto') || request()->routeIs('operator.umkm.input-gps') ? 'menu-item-active bg-slate-800/40 text-blue-400 font-bold' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
                <span>Data UMKM</span>
            </a>

            <a href="{{ route('operator.verifikasi.index') }}"
                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('operator.verifikasi.*') ? 'menu-item-active bg-slate-800/40 text-blue-400 font-bold' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Verifikasi Lapangan</span>
            </a>

            <a href="{{ route('operator.pelatihan.scanner') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('operator.pelatihan.scanner*') ? 'menu-item-active bg-slate-800/40 text-blue-400 font-bold' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                </svg>
                <span>Scanner Pelatihan</span>
            </a>

        @elseif($userRole === 'pelaku_umkm')

            <p class="text-[9px] text-slate-500 uppercase tracking-wider px-3 pt-1 pb-1">Menu Pelaku UMKM</p>

            <a href="{{ route('pelaku.profil.edit') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('pelaku.profil.edit') ? 'menu-item-active bg-slate-800/40 text-blue-400 font-bold' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>Profil Saya</span>
            </a>

            <a href="{{ route('pelaku.pelatihan.index') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('pelaku.pelatihan.*') ? 'menu-item-active bg-slate-800/40 text-blue-400 font-bold' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path>
                </svg>
                <span class="flex-1">Pelatihan UMKM</span>
                @if($jumlah_pelatihan_tersedia > 0)
                    <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-emerald-500 text-white text-[10px] font-bold leading-none">{{ $jumlah_pelatihan_tersedia > 9 ? '9+' : $jumlah_pelatihan_tersedia }}</span>
                @endif
            </a>

            @if(\App\Models\Setting::get('pelaku_submission_active', false))
                <a href="{{ route('pelaku.pengajuan.index') }}" @click="toggleMobileMenu()"
                    class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('pelaku.pengajuan.*') ? 'menu-item-active bg-slate-800/40 text-blue-400 font-bold' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Pengajuan Bantuan</span>
                </a>
            @endif

            <a href="{{ route('pelaku.produk.index') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('pelaku.produk.*') ? 'menu-item-active bg-slate-800/40 text-blue-400 font-bold' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span>Katalog Produk</span>
            </a>
        @endif
    </nav>

    <div class="fixed bottom-0 left-0 right-0 p-2 border-t border-slate-700 bg-slate-900">
        <div class="flex items-center gap-2">
            <div
                class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-[10px]">
                {{ substr(Auth::user()->name ?? 'SA', 0, 2) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'Super Admin' }}</p>
                <p class="text-xs text-slate-400">{{ ucfirst(str_replace('_', ' ', $userRole)) }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2.5 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-red-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
