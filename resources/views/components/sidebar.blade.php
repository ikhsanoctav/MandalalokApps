
@props(['role' => 'super_admin'])

@php
    $pendingCount = \App\Models\UMKM::where('status_verifikasi', 'menunggu_verifikasi')->count();
    $pendingAkunCount = \App\Models\Pemilik::where('status_verifikasi_ktp', 'pending')->count();
    $userRole = auth()->user()->roles->first()->name ?? 'super_admin';
@endphp

<aside
    class="hidden md:block fixed inset-y-0 left-0 h-screen bg-gradient-to-b from-slate-950 to-[#0B1021] text-slate-300 z-[10000] shadow-xl transition-all duration-300 flex flex-col"
    :class="sidebarOpen ? 'w-64' : 'w-[72px]'">

    <div class="relative px-4 py-5 border-b border-white/5 flex items-center justify-between">
        <div class="flex items-center gap-3 overflow-hidden">
            
            <div class="relative group cursor-pointer">
                
                <div
                    class="absolute inset-0 bg-gradient-to-br from-amber-400/40 via-orange-500/40 to-red-500/40 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                </div>

                <div
                    class="relative bg-gradient-to-br from-slate-800 to-slate-700 rounded-full p-2 shadow-lg border border-white/10 group-hover:scale-105 transition-all duration-300">
                    <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo Mandalaloka"
                        class="w-8 h-8 md:w-10 md:h-10 object-contain rounded-full">
                </div>
            </div>

            <div x-cloak x-show="sidebarOpen" class="overflow-hidden whitespace-nowrap">
                <h1
                    class="text-lg font-bold bg-gradient-to-r from-amber-400 to-orange-500 bg-clip-text text-transparent">
                    Mandalaloka
                </h1>
                <p class="text-[10px] text-slate-400">
                    {{ $userRole === 'super_admin' ? 'Sistem Pendataan UMKM' : ($userRole === 'admin_kecamatan' ? 'Admin Kecamatan' : 'Petugas Lapangan') }}
                </p>
            </div>
        </div>

        <button @click="toggleSidebar()"
            class="absolute -right-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full p-[1.5px] bg-gradient-to-tr from-blue-500 via-indigo-500 to-cyan-400 transition-all duration-300 hover:scale-115 hover:shadow-[0_0_25px_rgba(59,130,246,0.7)] focus:outline-none z-[10001] group">
            <div
                class="w-full h-full bg-gradient-to-b from-slate-950 to-[#0B1021] rounded-full flex items-center justify-center transition-colors group-hover:bg-black/40">
                <svg class="w-4 h-4 text-blue-400 group-hover:text-cyan-300 transition-transform duration-500 ease-out"
                    :class="sidebarOpen ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 19l-7-7 7-7"></path>
                </svg>
            </div>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4 px-3 space-y-1 sidebar-scroll" style="min-height: 0;">

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

        <a href="{{ $dashboardRoute }}" @click="if(!sidebarOpen) { $event.preventDefault(); sidebarOpen = true; }"
            class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ $isDashboardActive ? 'menu-item-active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                </path>
            </svg>
            <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Dashboard</span>
            <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Dashboard</div>
        </a>

        <div class="my-3 border-t border-white/5"></div>

        @if ($userRole === 'super_admin')

            <div x-data="{ open: {{ request()->routeIs('superadmin.umkm*') ? 'true' : 'false' }} }" class="relative">
                <button @click="if(!sidebarOpen) { sidebarOpen = true; open = true; } else { open = !open; }"
                    class="menu-item w-full flex items-center justify-between px-3 py-2.5 rounded-full transition-all hover:bg-white/5 {{ request()->routeIs('superadmin.umkm*') ? 'menu-item-active' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Data
                            UMKM</span>
                    </div>
                    <svg x-cloak x-show="sidebarOpen" class="w-4 h-4 transition-transform"
                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-cloak x-show="sidebarOpen && open" class="pl-10 mt-1 space-y-1">
                    <a href="{{ route('superadmin.umkm') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('superadmin.umkm') && (!request()->has('status_verifikasi') || request('status_verifikasi') == '') ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">Daftar
                        UMKM</a>
                    <a href="{{ route('superadmin.umkm.create') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('superadmin.umkm.create') ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">Tambah
                        UMKM</a>
                    {{--
                    <a href="{{ route('superadmin.umkm', ['status_verifikasi' => 'terverifikasi']) }}"
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('superadmin.umkm') && request('status_verifikasi') == 'menunggu_verifikasi' ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">
                        <span>UMKM Pending</span>
                        <span
                            class="bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
                    </a>
                    --}}
                </div>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Data UMKM</div>
            </div>
            <a href="{{ route('superadmin.verifikasi_akun.index') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('superadmin.verifikasi_akun.*') ? 'menu-item-active' : '' }}">
                @if ($pendingAkunCount > 0)
                    <span class="absolute right-2 top-1/2 -translate-y-1/2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendingAkunCount }}</span>
                @endif
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Verifikasi Akun</span>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Verifikasi Akun</div>
            </a>


            <div x-data="{ open: {{ request()->routeIs('superadmin.pengajuan*') ? 'true' : 'false' }} }" class="relative">
                <button @click="if(!sidebarOpen) { sidebarOpen = true; open = true; } else { open = !open; }"
                    class="menu-item w-full flex items-center justify-between px-3 py-2.5 rounded-full transition-all hover:bg-white/5 {{ request()->routeIs('superadmin.pengajuan*') ? 'menu-item-active' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Data
                            Pengajuan</span>
                    </div>
                    <svg x-cloak x-show="sidebarOpen" class="w-4 h-4 transition-transform"
                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-cloak x-show="sidebarOpen && open" class="pl-10 mt-1 space-y-1">
                    <a href="{{ route('superadmin.pengajuan', ['status' => 'menunggu']) }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('superadmin.pengajuan') && request('status') == 'menunggu' ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">Daftar
                        Pengajuan</a>
                    <a href="{{ route('superadmin.pengajuan') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('superadmin.pengajuan') && !request('status') ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">Riwayat
                        Pengajuan</a>
                </div>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Data Pengajuan</div>
            </div>

            @if(false)
            <a href="{{ route('superadmin.pelatihan.index') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('superadmin.pelatihan.*') ? 'menu-item-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Kelola Pelatihan</span>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Kelola Pelatihan</div>
            </a>
            @endif

            <div x-data="{ open: {{ request()->routeIs('superadmin.users*') ? 'true' : 'false' }} }" class="relative">
                <button @click="if(!sidebarOpen) { sidebarOpen = true; open = true; } else { open = !open; }"
                    class="menu-item w-full flex items-center justify-between px-3 py-2.5 rounded-full transition-all hover:bg-white/5 {{ request()->routeIs('superadmin.users*') ? 'menu-item-active' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">User
                            Manajemen</span>
                    </div>
                    <svg x-cloak x-show="sidebarOpen" class="w-4 h-4 transition-transform"
                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
                <div x-cloak x-show="sidebarOpen && open" class="pl-10 mt-1 space-y-1">
                    <a href="{{ route('superadmin.users') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('superadmin.users') && !request()->routeIs('superadmin.users.create') && !request()->routeIs('superadmin.users.roles') ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">Daftar
                        User</a>
                    <a href="{{ route('superadmin.users.create') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('superadmin.users.create') ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">Tambah
                        User</a>
                    <a href="{{ route('superadmin.users.roles') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('superadmin.users.roles') ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">Role
                        Access</a>
                </div>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">User Manajemen</div>
            </div>

            <div class="my-3">
                <div class="border-t border-white/5"></div>
                <p x-cloak x-show="sidebarOpen" class="text-[10px] text-slate-500 uppercase tracking-wider px-2 pt-3">
                    Master & Sistem</p>
            </div>

            <a href="{{ route('superadmin.laporan') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('superadmin.laporan') ? 'menu-item-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Laporan</span>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Laporan</div>
            </a>

            @if(false)
            <a href="{{ route('dss.index') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('dss.index') ? 'menu-item-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                    </path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Analisis AI (DSS)</span>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Analisis AI (DSS)</div>
            </a>
            @endif

            <div x-data="{ open: {{ request()->routeIs('superadmin.kategori*') || request()->routeIs('superadmin.sektor*') || request()->routeIs('superadmin.kelurahan*') || request()->routeIs('superadmin.rw*') || request()->routeIs('superadmin.berita*') || request()->routeIs('superadmin.flyer*') || request()->routeIs('superadmin.logs') ? 'true' : 'false' }} }" class="relative">
                <button @click="if(!sidebarOpen) { sidebarOpen = true; open = true; } else { open = !open; }"
                    class="menu-item w-full flex items-center justify-between px-3 py-2.5 rounded-full transition-all hover:bg-white/5 {{ request()->routeIs('superadmin.kategori*') || request()->routeIs('superadmin.sektor*') || request()->routeIs('superadmin.kelurahan*') || request()->routeIs('superadmin.rw*') || request()->routeIs('superadmin.berita*') || request()->routeIs('superadmin.flyer*') || request()->routeIs('superadmin.logs') ? 'menu-item-active' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4">
                            </path>
                        </svg>
                        <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Data
                            Master</span>
                    </div>
                    <svg x-cloak x-show="sidebarOpen" class="w-4 h-4 transition-transform"
                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
                <div x-cloak x-show="sidebarOpen && open" class="pl-10 mt-1 space-y-1">
                    <a href="{{ route('superadmin.kategori.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('superadmin.kategori*') ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">Kategori
                        UMKM</a>
                    <a href="{{ route('superadmin.sektor.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('superadmin.sektor*') ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">Data
                        Sektor UMKM</a>
                    <a href="{{ route('superadmin.kelurahan.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('superadmin.kelurahan*') ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">Data
                        Kelurahan</a>
                    <a href="{{ route('superadmin.rw.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('superadmin.rw*') ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">Data
                        RW</a>
                    <a href="{{ route('superadmin.berita.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('superadmin.berita*') ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">Manajemen
                        Warta</a>
                    <a href="{{ route('superadmin.flyer.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('superadmin.flyer*') ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">Flyer Program</a>
                    <a href="{{ route('superadmin.logs') }}"
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('superadmin.logs') ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">
                        <span>Audit Logs</span>
                    </a>
                    <a href="{{ route('log-viewer.index') }}" target="_blank"
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('log-viewer.index') ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">
                        <span>Logs Error Sistem</span>
                    </a>
                </div>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Data Master</div>
            </div>

            <div class="my-3 border-t border-white/5"></div>

            <a href="{{ route('superadmin.settings') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('superadmin.settings*') ? 'menu-item-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Pengaturan
                    Sistem</span>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Pengaturan Sistem</div>
            </a>

            <a href="{{ route('superadmin.backup.index') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('superadmin.backup*') ? 'menu-item-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                    </path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Backup &
                    Restore</span>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Backup & Restore</div>
            </a>
        @elseif($userRole === 'admin_kecamatan')

            <p x-cloak x-show="sidebarOpen"
                class="text-[10px] text-slate-500 uppercase tracking-wider px-2 pt-1 pb-1">Manajemen</p>

            <div x-data="{ open: {{ request()->routeIs('admin.umkm*') ? 'true' : 'false' }} }" class="relative">
                <button @click="if(!sidebarOpen) { sidebarOpen = true; open = true; } else { open = !open; }"
                    class="menu-item w-full flex items-center justify-between px-3 py-2.5 rounded-full transition-all hover:bg-white/5 {{ request()->routeIs('admin.umkm*') ? 'menu-item-active' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Data UMKM</span>
                    </div>
                    <svg x-cloak x-show="sidebarOpen" class="w-4 h-4 transition-transform"
                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-cloak x-show="sidebarOpen && open" class="pl-10 mt-1 space-y-1">
                    <a href="{{ route('admin.umkm.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('admin.umkm.index') ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">Daftar UMKM</a>
                    <a href="{{ route('admin.umkm.create') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('admin.umkm.create') ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">Tambah UMKM</a>
                </div>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Data UMKM</div>
            </div>

            <a href="{{ route('admin.verifikasi.index') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('admin.verifikasi.*') ? 'menu-item-active' : '' }}">
                @if ($pendingCount > 0)
                    <span class="absolute right-2 top-1/2 -translate-y-1/2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
                @endif
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Verifikasi Lapangan</span>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Verifikasi Lapangan</div>
            </a>

            <a href="{{ route('admin.verifikasi_akun.index') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('admin.verifikasi_akun.*') ? 'menu-item-active' : '' }}">
                @if ($pendingAkunCount > 0)
                    <span class="absolute right-2 top-1/2 -translate-y-1/2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendingAkunCount }}</span>
                @endif
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Verifikasi Akun</span>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Verifikasi Akun</div>
            </a>

            <div x-data="{ open: {{ request()->routeIs('admin.pengajuan*') ? 'true' : 'false' }} }" class="relative">
                <button @click="if(!sidebarOpen) { sidebarOpen = true; open = true; } else { open = !open; }"
                    class="menu-item w-full flex items-center justify-between px-3 py-2.5 rounded-full transition-all hover:bg-white/5 {{ request()->routeIs('admin.pengajuan*') ? 'menu-item-active' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Data Pengajuan</span>
                    </div>
                    <svg x-cloak x-show="sidebarOpen" class="w-4 h-4 transition-transform"
                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-cloak x-show="sidebarOpen && open" class="pl-10 mt-1 space-y-1">
                    <a href="{{ route('admin.pengajuan', ['status' => 'menunggu']) }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('admin.pengajuan') && request('status') == 'menunggu' ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">Daftar Pengajuan</a>
                    <a href="{{ route('admin.pengajuan') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-white/5 {{ request()->routeIs('admin.pengajuan') && !request('status') ? 'text-blue-400 font-bold bg-white/5/30' : '' }}">Riwayat Pengajuan</a>
                </div>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Data Pengajuan</div>
            </div>

            @if(false)
            <a href="{{ route('admin.pelatihan.index') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('admin.pelatihan.*') ? 'menu-item-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Kelola Pelatihan</span>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Kelola Pelatihan</div>
            </a>
            @endif

            <div class="my-3">
                <div class="border-t border-white/5"></div>
                <p x-cloak x-show="sidebarOpen" class="text-[10px] text-slate-500 uppercase tracking-wider px-2 pt-3">
                    Laporan & Ekspor</p>
            </div>

            <a href="{{ route('admin.laporan.index') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('admin.laporan.*') ? 'menu-item-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Rekap &
                    Laporan</span>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Rekap & Laporan</div>
            </a>

            @if(false)
            <a href="{{ route('dss.index') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('dss.index') ? 'menu-item-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                    </path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Analisis AI (DSS)</span>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Analisis AI (DSS)</div>
            </a>
            @endif
        @elseif($userRole === 'operator_lapangan')

            <p x-cloak x-show="sidebarOpen"
                class="text-[10px] text-slate-500 uppercase tracking-wider px-2 pt-1 pb-1">Menu Petugas</p>

            <a href="{{ route('operator.umkm.create') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('operator.umkm.create') ? 'menu-item-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Input UMKM
                    Baru</span>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Input UMKM Baru</div>
            </a>

            <a href="{{ route('operator.umkm.index') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('operator.umkm.index') || request()->routeIs('operator.umkm.edit') || request()->routeIs('operator.umkm.upload-foto') || request()->routeIs('operator.umkm.input-gps') ? 'menu-item-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Data UMKM</span>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Data UMKM</div>
            </a>

            <a href="{{ route('operator.verifikasi.index') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('operator.verifikasi.*') ? 'menu-item-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Verifikasi Lapangan</span>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Verifikasi Lapangan</div>
            </a>

            @if(false)
            <a href="{{ route('operator.pelatihan.scanner') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('operator.pelatihan.scanner*') ? 'menu-item-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Scanner Pelatihan</span>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Scanner Pelatihan</div>
            </a>
            @endif

        @elseif($userRole === 'pelaku_umkm')
            
            <p x-cloak x-show="sidebarOpen"
                class="text-[10px] text-slate-500 uppercase tracking-wider px-2 pt-1 pb-1">Menu Pelaku UMKM</p>

            <a href="{{ route('pelaku.profil.edit') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('pelaku.profil.edit') ? 'menu-item-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Profil Saya</span>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Profil Saya</div>
            </a>

            @if(false)
            <a href="{{ route('pelaku.pelatihan.index') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('pelaku.pelatihan.*') ? 'menu-item-active' : '' }}">
                <div class="relative flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    @if($jumlah_pelatihan_tersedia > 0)
                        <span x-cloak x-show="!sidebarOpen" class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-emerald-500 text-white text-[9px] font-bold leading-none ring-1 ring-slate-900">{{ $jumlah_pelatihan_tersedia > 9 ? '9+' : $jumlah_pelatihan_tersedia }}</span>
                    @endif
                </div>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap flex-1">Pelatihan UMKM</span>
                @if($jumlah_pelatihan_tersedia > 0)
                    <span x-cloak x-show="sidebarOpen" class="ml-auto inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-emerald-500 text-white text-[10px] font-bold leading-none">{{ $jumlah_pelatihan_tersedia > 9 ? '9+' : $jumlah_pelatihan_tersedia }}</span>
                @endif
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Pelatihan UMKM ({{ $jumlah_pelatihan_tersedia }} tersedia)</div>
            </a>
            @endif

            @if(\App\Models\Setting::get('pelaku_submission_active', false))
                <a href="{{ route('pelaku.pengajuan.index') }}"
                    class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('pelaku.pengajuan.*') ? 'menu-item-active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Pengajuan Bantuan</span>
                    <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Pengajuan Bantuan</div>
                </a>
            @endif

            <a href="{{ route('pelaku.produk.index') }}"
                class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-full transition-all group relative hover:bg-white/5 {{ request()->routeIs('pelaku.produk.*') ? 'menu-item-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span x-cloak x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Katalog Produk</span>
                <div x-cloak x-show="!sidebarOpen" class="tooltip-text">Katalog Produk</div>
            </a>
        @endif
    </nav>

    @if ($userRole !== 'pelaku_umkm')
    <div class="mt-auto p-3 border-t border-white/5" x-data="serverTime()" x-init="initTime()">
        <div class="bg-white/5 backdrop-blur-md rounded-3xl transition-all duration-300"
            :class="sidebarOpen ? 'p-3' : 'p-2 flex justify-center'">
            <div class="flex items-center" :class="sidebarOpen ? 'justify-between w-full' : 'justify-center'">
                <div x-show="sidebarOpen" class="flex items-center gap-2 text-slate-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-xs font-medium">Server Time</span>
                </div>
                <div x-cloak x-show="!sidebarOpen" class="relative group flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-300 animate-spin-slow" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div
                        class="tooltip-text flex flex-col items-start bg-black/40 p-2.5 rounded-2xl border border-slate-800 shadow-2xl">
                        <span class="text-xs font-semibold text-slate-400">Server Time</span>
                        <span class="text-lg font-mono font-bold text-white mt-0.5" x-text="time"></span>
                        <span class="text-[10px] text-slate-400 mt-1" x-text="date"></span>
                    </div>
                </div>
            </div>
            <div x-cloak x-show="sidebarOpen" class="mt-2">
                <p class="text-xl font-mono font-bold text-white" x-text="time"></p>
                <p class="text-xs text-slate-400" x-text="date"></p>
            </div>
        </div>
    </div>
    @endif

    <div class="pt-3 px-3 pb-1 border-t border-white/5">
        <div class="flex items-center" :class="sidebarOpen ? 'gap-3' : 'justify-center'">
            <div
                class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                {{ substr(Auth::user()->name ?? 'SA', 0, 2) }}
            </div>
            <div x-cloak x-show="sidebarOpen" class="whitespace-nowrap overflow-hidden flex-1">
                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'Super Admin' }}</p>
                <p class="text-[10px] text-slate-400">{{ ucfirst(str_replace('_', ' ', $userRole)) }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0" x-cloak x-show="sidebarOpen">
                @csrf
                <button type="submit"
                    class="p-2 rounded-2xl hover:bg-white/5 transition-all text-slate-400 hover:text-red-400" title="Logout">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<style>
    /* Tooltip styling */
    .tooltip-text {
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-60%);
        margin-left: 12px;
        padding: 6px 12px;
        background-color: #1e293b;
        color: white;
        font-size: 0.75rem;
        white-space: nowrap;
        border-radius: 6px;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s;
        z-index: 50;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .group:hover .tooltip-text,
    .relative.group:hover .tooltip-text {
        opacity: 1;
    }

    /* Menu item active state */
    .menu-item-active {
        background: linear-gradient(90deg, rgba(59, 130, 246, 0.15) 0%, rgba(59, 130, 246, 0.05) 100%);
        color: #60a5fa;
        border-left: 3px solid #3b82f6;
        box-shadow: inset 0 0 15px rgba(59, 130, 246, 0.1);
    }

    .menu-item-active:hover {
        background: linear-gradient(90deg, rgba(59, 130, 246, 0.2) 0%, rgba(59, 130, 246, 0.1) 100%);
    }

    /* Custom scrollbar - Hide scrollbar entirely for clean UI */
    .sidebar-scroll {
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }

    .sidebar-scroll::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari and Opera */
    }

    /* Slow spin animation */
    @keyframes spin-slow {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .animate-spin-slow {
        animation: spin-slow 4s linear infinite;
    }

    /* Ensure x-cloak works */
    [x-cloak] {
        display: none !important;
    }
</style>

<script>
    function serverTime() {
        return {
            serverOffset: 0,
            time: '',
            date: '',
            initTime() {
                const serverTimeMs = {{ now()->timestamp * 1000 }};
                const clientTimeMs = Date.now();
                this.serverOffset = serverTimeMs - clientTimeMs;

                this.updateTime();
                setInterval(() => this.updateTime(), 1000);
            },
            updateTime() {
                const currentServerTime = new Date(Date.now() + this.serverOffset);
                this.time = currentServerTime.toLocaleTimeString('id-ID', {
                    timeZone: 'Asia/Jakarta',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                this.date = currentServerTime.toLocaleDateString('id-ID', {
                    timeZone: 'Asia/Jakarta',
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }
        }
    }
</script>
