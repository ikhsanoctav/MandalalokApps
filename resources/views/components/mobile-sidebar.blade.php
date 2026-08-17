@php
    $pendingCount = \App\Models\UMKM::where('status_verifikasi', 'menunggu_verifikasi')->count();
    $pendingAkunCount = \App\Models\Pemilik::where('status_verifikasi_ktp', 'pending')->count();
    $userRole = auth()->user()->roles->first()->name ?? 'super_admin';
@endphp

<!-- Dark Backdrop Overlay -->
<div x-show="mobileMenuOpen" 
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100" 
     x-transition:leave-end="opacity-0"
     @click="toggleMobileMenu()"
     class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-[999990] md:hidden"></div>

<!-- Mobile Sidebar Panel -->
<div x-show="mobileMenuOpen" 
     x-cloak
     x-transition:enter="transform transition duration-300 ease-out"
     x-transition:enter-start="-translate-x-full" 
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transform transition duration-300 ease-in" 
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="fixed top-0 left-0 bottom-0 w-[86vw] max-w-80 bg-slate-900 z-[999999] shadow-2xl overflow-y-auto flex flex-col md:hidden">

    <!-- Header Panel -->
    <div class="sticky top-0 bg-slate-900/95 backdrop-blur-md p-4 border-b border-slate-800 flex items-center justify-between z-10">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo" class="h-8 w-8 object-contain">
            <div>
                <h1 class="text-sm font-bold text-white leading-tight">Mandalaloka</h1>
                <p class="text-[11px] text-slate-400 font-medium">
                    {{ $userRole === 'super_admin' ? 'Super Admin Panel' : ($userRole === 'admin_kecamatan' ? 'Admin Kecamatan' : ($userRole === 'operator_lapangan' ? 'Petugas Lapangan' : 'Pelaku UMKM')) }}
                </p>
            </div>
        </div>
        <button type="button" @click="toggleMobileMenu()" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-colors focus:outline-none" title="Tutup Menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="flex-1 p-3 space-y-1 pb-32">
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
            class="flex min-h-11 items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ $isDashboardActive ? 'menu-item-active bg-slate-800/60 text-blue-400 font-bold border-l-4 border-blue-500' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                </path>
            </svg>
            <span>Dashboard</span>
        </a>

        <div class="my-2 border-t border-slate-800"></div>

        @if ($userRole === 'super_admin')
            
            <!-- Super Admin: Data UMKM Dropdown -->
            <div x-data="{ open: {{ request()->routeIs('superadmin.umkm*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.umkm*') ? 'bg-slate-800/40 text-blue-400 font-semibold' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <span>Data UMKM</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="pl-8 mt-1 space-y-1">
                    <a href="{{ route('superadmin.umkm') }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.umkm') && (!request()->has('status_verifikasi') || request('status_verifikasi') == '') ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">Daftar UMKM</a>
                    <a href="{{ route('superadmin.umkm.create') }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.umkm.create') ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">Tambah UMKM</a>
                </div>
            </div>

            <!-- Super Admin: Verifikasi Akun -->
            <a href="{{ route('superadmin.verifikasi_akun.index') }}" @click="toggleMobileMenu()"
                class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.verifikasi_akun.*') ? 'menu-item-active bg-slate-800/60 text-blue-400 font-bold' : '' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                    </svg>
                    <span>Verifikasi Akun</span>
                </div>
                @if ($pendingAkunCount > 0)
                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingAkunCount }}</span>
                @endif
            </a>

            <!-- Super Admin: Data Pengajuan Dropdown -->
            <div x-data="{ open: {{ request()->routeIs('superadmin.pengajuan*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.pengajuan*') ? 'bg-slate-800/40 text-blue-400 font-semibold' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span>Data Pengajuan</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="pl-8 mt-1 space-y-1">
                    <a href="{{ route('superadmin.pengajuan', ['status' => 'menunggu']) }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.pengajuan') && request('status') == 'menunggu' ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">Daftar Pengajuan</a>
                    <a href="{{ route('superadmin.pengajuan') }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.pengajuan') && !request('status') ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">Riwayat Pengajuan</a>
                </div>
            </div>

            <!-- Super Admin: User Manajemen Dropdown -->
            <div x-data="{ open: {{ request()->routeIs('superadmin.users*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.users*') ? 'bg-slate-800/40 text-blue-400 font-semibold' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span>User Manajemen</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="pl-8 mt-1 space-y-1">
                    <a href="{{ route('superadmin.users') }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.users') && !request()->routeIs('superadmin.users.create') && !request()->routeIs('superadmin.users.roles') ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">Daftar User</a>
                    <a href="{{ route('superadmin.users.create') }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.users.create') ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">Tambah User</a>
                    <a href="{{ route('superadmin.users.roles') }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.users.roles') ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">Role Access</a>
                </div>
            </div>

            <div class="my-2 border-t border-slate-800"></div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 pt-1">Master & Sistem</p>

            <a href="{{ route('superadmin.laporan') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.laporan') ? 'menu-item-active bg-slate-800/60 text-blue-400 font-bold' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span>Laporan</span>
            </a>

            <!-- Super Admin: Data Master Dropdown -->
            <div x-data="{ open: {{ request()->routeIs('superadmin.kategori*') || request()->routeIs('superadmin.sektor*') || request()->routeIs('superadmin.kelurahan*') || request()->routeIs('superadmin.rw*') || request()->routeIs('superadmin.berita*') || request()->routeIs('superadmin.flyer*') || request()->routeIs('superadmin.logs') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.kategori*') || request()->routeIs('superadmin.sektor*') || request()->routeIs('superadmin.kelurahan*') || request()->routeIs('superadmin.rw*') || request()->routeIs('superadmin.berita*') || request()->routeIs('superadmin.flyer*') || request()->routeIs('superadmin.logs') ? 'bg-slate-800/40 text-blue-400 font-semibold' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4">
                            </path>
                        </svg>
                        <span>Data Master</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="pl-8 mt-1 space-y-1">
                    <a href="{{ route('superadmin.kategori.index') }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.kategori*') ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">Kategori UMKM</a>
                    <a href="{{ route('superadmin.sektor.index') }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.sektor*') ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">Data Sektor</a>
                    <a href="{{ route('superadmin.kelurahan.index') }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.kelurahan*') ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">Data Kelurahan</a>
                    <a href="{{ route('superadmin.rw.index') }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.rw*') ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">Data RW</a>
                    <a href="{{ route('superadmin.berita.index') }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.berita*') ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">Manajemen Warta</a>
                    <a href="{{ route('superadmin.flyer.index') }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.flyer*') ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">Flyer Program</a>
                    <a href="{{ route('superadmin.logs') }}" @click="toggleMobileMenu()"
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.logs') ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">
                        <span>Logs Sistem</span>
                    </a>
                    <a href="{{ route('log-viewer.index') }}" target="_blank" @click="toggleMobileMenu()"
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all">
                        <span>Logs Error Sistem</span>
                    </a>
                </div>
            </div>

            <a href="{{ route('superadmin.settings') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.settings*') ? 'menu-item-active bg-slate-800/60 text-blue-400 font-bold' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span>Pengaturan Sistem</span>
            </a>

            <a href="{{ route('superadmin.backup.index') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('superadmin.backup*') ? 'menu-item-active bg-slate-800/60 text-blue-400 font-bold' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                    </path>
                </svg>
                <span>Backup & Restore</span>
            </a>

        @elseif($userRole === 'admin_kecamatan')

            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 pt-1">Manajemen</p>

            <!-- Admin Kecamatan: Data UMKM Dropdown -->
            <div x-data="{ open: {{ request()->routeIs('admin.umkm*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('admin.umkm*') ? 'bg-slate-800/40 text-blue-400 font-semibold' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <span>Data UMKM</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="pl-8 mt-1 space-y-1">
                    <a href="{{ route('admin.umkm.index') }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('admin.umkm.index') ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">Daftar UMKM</a>
                    <a href="{{ route('admin.umkm.create') }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('admin.umkm.create') ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">Tambah UMKM</a>
                </div>
            </div>

            <!-- Admin Kecamatan: Verifikasi Lapangan -->
            <a href="{{ route('admin.verifikasi.index') }}" @click="toggleMobileMenu()"
                class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('admin.verifikasi.*') ? 'menu-item-active bg-slate-800/60 text-blue-400 font-bold' : '' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Verifikasi Lapangan</span>
                </div>
                @if ($pendingCount > 0)
                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                @endif
            </a>

            <!-- Admin Kecamatan: Verifikasi Akun -->
            <a href="{{ route('admin.verifikasi_akun.index') }}" @click="toggleMobileMenu()"
                class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('admin.verifikasi_akun.*') ? 'menu-item-active bg-slate-800/60 text-blue-400 font-bold' : '' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                    </svg>
                    <span>Verifikasi Akun</span>
                </div>
                @if ($pendingAkunCount > 0)
                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingAkunCount }}</span>
                @endif
            </a>

            <!-- Admin Kecamatan: Data Pengajuan Dropdown -->
            <div x-data="{ open: {{ request()->routeIs('admin.pengajuan*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('admin.pengajuan*') ? 'bg-slate-800/40 text-blue-400 font-semibold' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span>Data Pengajuan</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="pl-8 mt-1 space-y-1">
                    <a href="{{ route('admin.pengajuan', ['status' => 'menunggu']) }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('admin.pengajuan') && request('status') == 'menunggu' ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">Daftar Pengajuan</a>
                    <a href="{{ route('admin.pengajuan') }}" @click="toggleMobileMenu()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-all {{ request()->routeIs('admin.pengajuan') && !request('status') ? 'text-blue-400 font-bold bg-slate-800/60' : '' }}">Riwayat Pengajuan</a>
                </div>
            </div>

            <div class="my-2 border-t border-slate-800"></div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 pt-1">Laporan & Ekspor</p>

            <!-- Admin Kecamatan: Rekap & Laporan -->
            <a href="{{ route('admin.laporan.index') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('admin.laporan.*') ? 'menu-item-active bg-slate-800/60 text-blue-400 font-bold' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span>Rekap & Laporan</span>
            </a>

        @elseif($userRole === 'operator_lapangan')

            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 pt-1">Menu Petugas</p>

            <a href="{{ route('operator.umkm.create') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('operator.umkm.create') ? 'menu-item-active bg-slate-800/60 text-blue-400 font-bold' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Input UMKM Baru</span>
            </a>

            <a href="{{ route('operator.umkm.index') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('operator.umkm.index') || request()->routeIs('operator.umkm.edit') || request()->routeIs('operator.umkm.upload-foto') || request()->routeIs('operator.umkm.input-gps') ? 'menu-item-active bg-slate-800/60 text-blue-400 font-bold' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
                <span>Data UMKM</span>
            </a>

            <a href="{{ route('operator.verifikasi.index') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('operator.verifikasi.*') ? 'menu-item-active bg-slate-800/60 text-blue-400 font-bold' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Verifikasi Lapangan</span>
            </a>

        @elseif($userRole === 'pelaku_umkm')

            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 pt-1">Menu Pelaku UMKM</p>

            <a href="{{ route('pelaku.profil.edit') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('pelaku.profil.edit') ? 'menu-item-active bg-slate-800/60 text-blue-400 font-bold' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>Profil Saya</span>
            </a>

            @if(\App\Models\Setting::get('pelaku_submission_active', false))
                <a href="{{ route('pelaku.pengajuan.index') }}" @click="toggleMobileMenu()"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('pelaku.pengajuan.*') ? 'menu-item-active bg-slate-800/60 text-blue-400 font-bold' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Pengajuan Bantuan</span>
                </a>
            @endif

            <a href="{{ route('pelaku.produk.index') }}" @click="toggleMobileMenu()"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 transition-all {{ request()->routeIs('pelaku.produk.*') ? 'menu-item-active bg-slate-800/60 text-blue-400 font-bold' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span>Katalog Produk</span>
            </a>
        @endif
    </nav>

    <!-- Footer Profile Info -->
    <div class="p-3 border-t border-slate-800 bg-slate-900/95 sticky bottom-0 z-10">
        <div class="flex items-center gap-3">
            <div
                class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0 shadow-md">
                {{ strtoupper(substr(Auth::user()->name ?? 'SA', 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate leading-tight">{{ Auth::user()->name ?? 'Super Admin' }}</p>
                <p class="text-xs text-slate-400 truncate mt-0.5">{{ ucwords(str_replace('_', ' ', $userRole)) }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-red-400 transition-colors" title="Keluar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
