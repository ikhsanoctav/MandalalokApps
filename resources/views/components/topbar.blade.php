
@php
    $userRole = auth()->user()->roles->first()->name ?? 'super_admin';

    $dbNotifications = auth()->user()->notifications()->latest()->take(10)->get();
    $dbCount = auth()->user()->unreadNotifications()->count();

    $totalUnread = $dbCount;

    $patternClass = '';

    $textRoleClass = 'text-blue-600';
    $accentGradient = 'from-blue-500 via-indigo-500 to-cyan-400';

    if ($userRole === 'super_admin') {
        $patternClass = 'pattern-batik-blue';
        $textRoleClass = 'text-blue-600';
        $accentGradient = 'from-blue-500 via-indigo-500 to-cyan-400';
    } elseif ($userRole === 'admin_kecamatan') {
        $patternClass = 'pattern-batik-emerald';
        $textRoleClass = 'text-emerald-600';
        $accentGradient = 'from-emerald-500 via-teal-500 to-cyan-400';
    } elseif ($userRole === 'operator_lapangan') {
        $patternClass = 'pattern-batik-amber';
        $textRoleClass = 'text-amber-600';
        $accentGradient = 'from-amber-500 via-orange-500 to-yellow-400';
    } elseif ($userRole === 'pelaku_umkm') {
        $patternClass = 'pattern-batik-purple';
        $textRoleClass = 'text-purple-600';
        $accentGradient = 'from-purple-500 via-indigo-500 to-pink-400';
    }

    // Dynamic nested breadcrumbs trail generation
    $routeName = request()->route()?->getName();
    $breadcrumbs = [];
    
    // Add Dashboard as the first segment
    $routePrefix = 'pelaku';
    if ($userRole === 'super_admin') {
        $dashboardRoute = 'superadmin.dashboard';
        $routePrefix = 'superadmin';
    } elseif ($userRole === 'admin_kecamatan') {
        $dashboardRoute = 'admin.dashboard';
        $routePrefix = 'admin';
    } elseif ($userRole === 'operator_lapangan') {
        $dashboardRoute = 'operator.dashboard';
        $routePrefix = 'operator';
    } elseif ($userRole === 'pelaku_umkm') {
        $dashboardRoute = 'pelaku.dashboard';
    }
    
    $breadcrumbs[] = [
        'label' => 'Dashboard',
        'url' => route($dashboardRoute),
        'active' => false
    ];

    if ($routeName && $routeName !== $dashboardRoute) {
        // Super Admin Users
        if (str_starts_with($routeName, 'superadmin.users.')) {
            $breadcrumbs[] = [
                'label' => 'Kelola Pengguna',
                'url' => route('superadmin.users'),
                'active' => false
            ];
            if ($routeName === 'superadmin.users.create') {
                $breadcrumbs[] = ['label' => 'Tambah Pengguna', 'url' => '#', 'active' => true];
            } elseif ($routeName === 'superadmin.users.edit') {
                $breadcrumbs[] = ['label' => 'Edit Pengguna', 'url' => '#', 'active' => true];
            }
        }
        // Super Admin UMKM
        elseif (str_starts_with($routeName, 'superadmin.umkm.')) {
            $breadcrumbs[] = [
                'label' => 'Daftar UMKM',
                'url' => route('superadmin.umkm'),
                'active' => false
            ];
            if ($routeName === 'superadmin.umkm.create') {
                $breadcrumbs[] = ['label' => 'Tambah UMKM', 'url' => '#', 'active' => true];
            } elseif ($routeName === 'superadmin.umkm.edit') {
                $breadcrumbs[] = ['label' => 'Edit UMKM', 'url' => '#', 'active' => true];
            } elseif ($routeName === 'superadmin.umkm.show') {
                $breadcrumbs[] = ['label' => 'Detail UMKM', 'url' => '#', 'active' => true];
            }
        }
        // Super Admin Kategori
        elseif (str_starts_with($routeName, 'superadmin.kategori.')) {
            $breadcrumbs[] = [
                'label' => 'Kategori UMKM',
                'url' => route('superadmin.kategori.index'),
                'active' => false
            ];
            if ($routeName === 'superadmin.kategori.create') {
                $breadcrumbs[] = ['label' => 'Tambah Kategori', 'url' => '#', 'active' => true];
            } elseif ($routeName === 'superadmin.kategori.edit') {
                $breadcrumbs[] = ['label' => 'Edit Kategori', 'url' => '#', 'active' => true];
            }
        }
        // Super Admin Kelurahan
        elseif (str_starts_with($routeName, 'superadmin.kelurahan.')) {
            $breadcrumbs[] = [
                'label' => 'Data Kelurahan',
                'url' => route('superadmin.kelurahan.index'),
                'active' => false
            ];
            if ($routeName === 'superadmin.kelurahan.create') {
                $breadcrumbs[] = ['label' => 'Tambah Kelurahan', 'url' => '#', 'active' => true];
            } elseif ($routeName === 'superadmin.kelurahan.edit') {
                $breadcrumbs[] = ['label' => 'Edit Kelurahan', 'url' => '#', 'active' => true];
            }
        }
        // Super Admin RW
        elseif (str_starts_with($routeName, 'superadmin.rw.')) {
            $breadcrumbs[] = [
                'label' => 'Data RW',
                'url' => route('superadmin.rw.index'),
                'active' => false
            ];
            if ($routeName === 'superadmin.rw.create') {
                $breadcrumbs[] = ['label' => 'Tambah RW', 'url' => '#', 'active' => true];
            } elseif ($routeName === 'superadmin.rw.edit') {
                $breadcrumbs[] = ['label' => 'Edit RW', 'url' => '#', 'active' => true];
            }
        }
        // Super Admin Sektor
        elseif (str_starts_with($routeName, 'superadmin.sektor.')) {
            $breadcrumbs[] = [
                'label' => 'Sektor Usaha',
                'url' => route('superadmin.sektor.index'),
                'active' => false
            ];
            if ($routeName === 'superadmin.sektor.create') {
                $breadcrumbs[] = ['label' => 'Tambah Sektor', 'url' => '#', 'active' => true];
            } elseif ($routeName === 'superadmin.sektor.edit') {
                $breadcrumbs[] = ['label' => 'Edit Sektor', 'url' => '#', 'active' => true];
            }
        }
        // Super Admin Berita/Warta
        elseif (str_starts_with($routeName, 'superadmin.berita.')) {
            $breadcrumbs[] = [
                'label' => 'Informasi & Warta',
                'url' => route('superadmin.berita.index'),
                'active' => false
            ];
            if ($routeName === 'superadmin.berita.create') {
                $breadcrumbs[] = ['label' => 'Tambah Warta', 'url' => '#', 'active' => true];
            } elseif ($routeName === 'superadmin.berita.edit') {
                $breadcrumbs[] = ['label' => 'Edit Warta', 'url' => '#', 'active' => true];
            }
        }
        // Super Admin Backup
        elseif (str_starts_with($routeName, 'superadmin.backup.')) {
            $breadcrumbs[] = [
                'label' => 'Backup Sistem',
                'url' => route('superadmin.backup.index'),
                'active' => true
            ];
        }
        // Admin Kecamatan Verifikasi Data UMKM
        elseif (str_starts_with($routeName, 'admin.verifikasi.')) {
            $breadcrumbs[] = [
                'label' => 'Verifikasi Data UMKM',
                'url' => route('admin.verifikasi.index'),
                'active' => false
            ];
            if (str_ends_with($routeName, '.show')) {
                $breadcrumbs[] = ['label' => 'Detail UMKM', 'url' => '#', 'active' => true];
            }
        }
        // Operator Lapangan UMKM
        elseif (str_starts_with($routeName, 'operator.umkm.')) {
            $breadcrumbs[] = [
                'label' => 'Pendataan UMKM',
                'url' => route('operator.umkm.index'),
                'active' => false
            ];
            if ($routeName === 'operator.umkm.create') {
                $breadcrumbs[] = ['label' => 'Tambah Pendataan', 'url' => '#', 'active' => true];
            } elseif ($routeName === 'operator.umkm.edit') {
                $breadcrumbs[] = ['label' => 'Edit Pendataan', 'url' => '#', 'active' => true];
            } elseif ($routeName === 'operator.umkm.show') {
                $breadcrumbs[] = ['label' => 'Detail Pendataan', 'url' => '#', 'active' => true];
            }
        }
        // Pelaku UMKM UMKM
        elseif (str_starts_with($routeName, 'pelaku.umkm.')) {
            $breadcrumbs[] = [
                'label' => 'UMKM Saya',
                'url' => '#',
                'active' => false
            ];
            if ($routeName === 'pelaku.umkm.create') {
                $breadcrumbs[] = ['label' => 'Daftarkan UMKM', 'url' => '#', 'active' => true];
            } elseif ($routeName === 'pelaku.umkm.edit') {
                $breadcrumbs[] = ['label' => 'Ubah Data UMKM', 'url' => '#', 'active' => true];
            }
        }
        // General fallback if route is explicitly defined but doesn't have child routes
        else {
            $routeMap = [
                'superadmin.users' => 'Kelola Pengguna',
                'superadmin.umkm' => 'Daftar UMKM',
                'superadmin.pengajuan' => 'Kelola Pengajuan',
                'superadmin.kategori' => 'Kategori UMKM',
                'superadmin.kelurahan' => 'Data Kelurahan',
                'superadmin.rw' => 'Data RW',
                'superadmin.sektor' => 'Sektor Usaha',
                'superadmin.berita' => 'Informasi & Warta',
                'superadmin.logs' => 'Log Aktivitas',
                'superadmin.laporan' => 'Laporan & Statistik',
                'superadmin.settings' => 'Pengaturan Aplikasi',
                'admin.verifikasi.index' => 'Verifikasi Data UMKM',
                'operator.umkm.index' => 'Pendataan UMKM',
                'pelaku.profil.edit' => 'Edit Profil UMKM',
                'pelaku.pengajuan.index' => 'Pengajuan Bantuan',
                'profile.edit' => 'Profil Pengguna',
            ];
            
            if (isset($routeMap[$routeName])) {
                $breadcrumbs[] = [
                    'label' => $routeMap[$routeName],
                    'url' => '#',
                    'active' => true
                ];
            } else {
                // Segment fallback
                $segments = request()->segments();
                if (!empty($segments)) {
                    $filtered = array_filter($segments, function($segment) {
                        return !is_numeric($segment) && strlen($segment) > 2 && !in_array($segment, ['edit', 'show', 'create', 'update', 'destroy']);
                    });
                    
                    $dictionary = [
                        'umkm' => 'UMKM',
                        'rw' => 'RW',
                        'logs' => 'Log Aktivitas',
                        'users' => 'Pengguna',
                        'dashboard' => 'Dashboard',
                        'profile' => 'Profil Saya',
                        'kategori' => 'Kategori',
                        'kelurahan' => 'Kelurahan',
                        'sektor' => 'Sektor Usaha',
                        'berita' => 'Warta & Berita',
                        'pengajuan' => 'Pengajuan Bantuan',
                        'verifikasi' => 'Verifikasi Data UMKM',
                        'backup' => 'Backup Sistem',
                        'settings' => 'Pengaturan',
                        'laporan' => 'Laporan & Statistik'
                    ];

                    foreach ($filtered as $seg) {
                        $label = isset($dictionary[strtolower($seg)]) ? $dictionary[strtolower($seg)] : ucwords(str_replace(['-', '_'], ' ', $seg));
                        if (strtolower($seg) !== 'superadmin' && strtolower($seg) !== 'admin' && strtolower($seg) !== 'operator' && strtolower($seg) !== 'pelaku') {
                            $resolvedUrl = '#';
                            if (Route::has("{$routePrefix}.{$seg}.index")) {
                                $resolvedUrl = route("{$routePrefix}.{$seg}.index");
                            } elseif (Route::has("{$routePrefix}.{$seg}")) {
                                $resolvedUrl = route("{$routePrefix}.{$seg}");
                            }
                            
                            $breadcrumbs[] = [
                                'label' => $label,
                                'url' => $resolvedUrl,
                                'active' => false
                            ];
                        }
                    }
                    
                    // Mark the last segment as active
                    if (!empty($breadcrumbs)) {
                        $breadcrumbs[count($breadcrumbs) - 1]['active'] = true;
                    }
                }
            }
        }
    } else {
        // Mark Dashboard as active if no routeName or it is dashboard
        $breadcrumbs[0]['active'] = true;
    }
    
    // Safety check: Ensure the last breadcrumb is always active
    if (!empty($breadcrumbs)) {
        $lastIndex = count($breadcrumbs) - 1;
        foreach ($breadcrumbs as $i => &$item) {
            $item['active'] = ($i === $lastIndex);
        }
    }
@endphp

<style>
    /* Original Elegant Diamond Batik Motif - Soft Watermark */
    .pattern-batik-blue {
        background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 0 L40 20 L20 40 L0 20 Z M20 10 L30 20 L20 30 L10 20 Z' fill='none' stroke='%233b82f6' stroke-width='1' stroke-opacity='0.4'/%3E%3C/svg%3E");
        background-size: 40px 40px;
    }

    .pattern-batik-emerald {
        background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 0 L40 20 L20 40 L0 20 Z M20 10 L30 20 L20 30 L10 20 Z' fill='none' stroke='%2310b981' stroke-width='1' stroke-opacity='0.4'/%3E%3C/svg%3E");
        background-size: 40px 40px;
    }

    .pattern-batik-amber {
        background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 0 L40 20 L20 40 L0 20 Z M20 10 L30 20 L20 30 L10 20 Z' fill='none' stroke='%23f59e0b' stroke-width='1' stroke-opacity='0.4'/%3E%3C/svg%3E");
        background-size: 40px 40px;
    }

    .pattern-batik-purple {
        background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 0 L40 20 L20 40 L0 20 Z M20 10 L30 20 L20 30 L10 20 Z' fill='none' stroke='%238b5cf6' stroke-width='1' stroke-opacity='0.4'/%3E%3C/svg%3E");
        background-size: 40px 40px;
    }
</style>

<div class="mobile-topbar sticky top-0 z-[999] relative bg-white/95 backdrop-blur-md shadow-sm border-b border-slate-200/80 transition-all duration-300">
    <div class="h-[2.5px] w-full bg-gradient-to-r {{ $accentGradient }}"></div>
    <div class="absolute inset-0 pointer-events-none opacity-[0.06] {{ $patternClass }}"></div>
    <div class="mobile-topbar-inner relative py-2 px-3 md:py-3 md:px-6 flex items-center justify-between gap-2 md:gap-4">
        
        <div class="mobile-topbar-left flex items-center gap-2 md:gap-3 min-w-0">
            
            <button @click="toggleMobileMenu()"
                class="mobile-topbar-menu md:hidden h-11 w-11 -ml-1 rounded-xl hover:bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
                <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    x-cloak>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <a href="{{ route($dashboardRoute) }}" class="mobile-topbar-brand flex md:hidden items-center gap-2 min-w-0">
                <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo" class="mobile-topbar-logo h-8 w-8 object-contain flex-shrink-0">
                <div class="min-w-0">
                    <h1 class="mobile-topbar-title text-sm font-bold text-blue-600 leading-tight">Mandalaloka</h1>
                    <p class="mobile-topbar-role text-[9px] text-slate-400 truncate max-w-[8rem]">{{ ucwords(str_replace('_', ' ', $userRole)) }}</p>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-1.5 text-xs text-slate-500 flex-wrap pl-2 md:pl-6 lg:pl-2">
                @foreach ($breadcrumbs as $index => $item)
                    @if ($index > 0)
                        <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    @endif

                    @if ($item['active'])
                        <a href="{{ $item['url'] !== '#' ? $item['url'] : url()->current() }}" class="inline-flex items-center gap-1 text-blue-600 font-bold bg-blue-50 border border-blue-100/60 px-2.5 py-0.5 rounded-xl hover:bg-blue-100 transition-colors">
                            @if ($index === count($breadcrumbs) - 1)
                                @hasSection('breadcrumb')
                                    @yield('breadcrumb')
                                @else
                                    {{ $item['label'] }}
                                @endif
                            @else
                                {{ $item['label'] }}
                            @endif
                        </a>
                    @else
                        <a href="{{ $item['url'] }}" class="flex items-center gap-1 hover:text-blue-600 transition-colors font-medium">
                            @if ($item['label'] === 'Dashboard')
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                            @endif
                            {{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
            </div>

            <div class="hidden lg:block w-px h-4 bg-slate-200"></div>

            <div class="hidden lg:flex items-center gap-2" x-data="topbarClock('{{ Auth::user()->name }}')" x-init="initTime()">
                <div
                    class="flex items-center gap-1.5 px-2.5 py-0.5 bg-slate-100/80 rounded-full border border-slate-200/60 text-[10px] font-bold text-slate-650 shadow-sm">
                    <span class="w-1 h-1 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span x-text="greeting">Halo</span>
                </div>
            </div>
        </div>

        <div class="mobile-topbar-actions flex items-center justify-end gap-1 md:gap-4 flex-shrink-0">

            <div class="relative" x-data="notificationDropdown()" @click.away="open = false">
                <button @click="open = !open"
                    aria-label="Buka notifikasi"
                    class="relative h-11 w-11 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                    <template x-if="totalUnread > 0">
                        <span class="absolute -top-1 -right-1 flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[9px] font-bold text-white bg-red-500 border-2 border-white rounded-full" x-text="totalUnread">
                        </span>
                    </template>
                </button>

                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                    class="mobile-notification-panel absolute right-0 mt-2 w-80 sm:w-96 max-w-[calc(100vw-1.5rem)] bg-white rounded-2xl shadow-2xl shadow-slate-900/10 border border-slate-200/80 overflow-hidden z-50">
                    <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-sm font-bold text-slate-800">Notifikasi</h3>
                        <template x-if="totalUnread > 0">
                            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-0.5 rounded-full"
                                x-text="totalUnread + ' Baru'"></span>
                        </template>
                    </div>

                    <div class="max-h-[22rem] overflow-y-auto custom-scrollbar">
                        <template x-if="notifications.length === 0">
                            <div class="flex flex-col items-center justify-center py-10 px-4 text-center">
                                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                        </path>
                                    </svg>
                                </div>
                                <p class="text-sm text-slate-500 font-medium">Belum ada notifikasi</p>
                                <p class="text-xs text-slate-400 mt-1">Anda sudah melihat semua pembaruan</p>
                            </div>
                        </template>

                        <div x-show="notifications.length > 0" class="bg-slate-50/80 px-4 py-2 border-b border-slate-100 border-t border-transparent">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Aktivitas Terbaru</p>
                        </div>

                        <div id="db-notifications-list">
                            <template x-for="notif in notifications" :key="notif.id">
                                <div class="relative group hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0"
                                     :class="notif.is_read ? 'opacity-60 bg-white' : 'bg-blue-50/40'">
                                    <a :href="notif.url"
                                        @click.prevent="markAsRead(notif.id, notif.url)"
                                        class="block px-5 py-3 pr-10">
                                        <div class="flex gap-3">
                                            <div class="flex-shrink-0 mt-1">
                                                <div class="w-9 h-9 rounded-full flex items-center justify-center border"
                                                    :class="getTheme(notif.type).bg + ' ' + getTheme(notif.type).border">
                                                    <svg class="w-4 h-4" :class="getTheme(notif.type).text" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" :d="getTheme(notif.type).icon"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-slate-800 line-clamp-1" x-text="notif.title"></p>
                                                <p class="text-xs text-slate-500 mt-0.5 line-clamp-2" x-text="notif.message"></p>
                                                <p class="text-[10px] text-slate-400 mt-1.5 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span x-text="notif.created_at_human"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                    <button @click.stop.prevent="deleteNotification(notif.id)"
                                        class="absolute right-3 top-2.5 text-slate-400 hover:text-red-500 hover:bg-red-50 p-1 rounded-2xl transition-all duration-200 z-10 opacity-100 md:opacity-0 md:group-hover:opacity-100 focus:opacity-100 focus:outline-none"
                                        title="Hapus notifikasi">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                    <template x-if="!notif.is_read">
                                        <div class="unread-dot absolute right-4 top-1/2 -translate-y-1/2 w-2 h-2 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)] pointer-events-none"></div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="p-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-2 text-xs">
                        <template x-if="totalUnread > 0">
                            <button @click="markAllAsRead"
                                class="font-semibold text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1 py-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Tandai Dibaca
                            </button>
                        </template>
                        <a href="{{ route('notifications.index') }}"
                            class="font-semibold text-slate-700 hover:text-blue-600 transition-colors flex items-center gap-1 ml-auto py-1">
                            Lihat Semua Notifikasi
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="hidden md:block w-px h-8 bg-slate-200"></div>

            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open"
                    aria-label="Buka menu akun"
                    class="mobile-account-button flex items-center justify-center md:justify-start gap-2 md:gap-3 h-11 w-11 md:w-auto md:px-3 rounded-xl hover:bg-slate-100 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-100 border border-transparent hover:border-slate-200/60">
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                        class="w-9 h-9 rounded-xl object-cover ring-2 ring-slate-100 shadow-md">
                    <div class="text-left hidden md:block">
                        <p class="text-sm font-bold text-slate-800 leading-tight">
                            {{ Auth::user()->name ?? 'Super Admin' }}</p>
                        <p class="text-[11px] {{ $textRoleClass }} font-medium">
                            {{ ucfirst(str_replace('_', ' ', auth()->user()->roles->first()->name ?? 'Super Admin')) }}
                        </p>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 hidden md:block transition-transform duration-300"
                        :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>

                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                    class="mobile-account-panel absolute right-0 -mr-2 md:mr-0 mt-3 w-64 max-w-[calc(100vw-2rem)] bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden z-50">

                    <!-- Premium Header -->
                    <div class="px-5 py-5 bg-slate-50/80 border-b border-slate-100 flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl overflow-hidden shadow-md flex-shrink-0 border-2 border-white ring-2 ring-slate-100">
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name ?? 'Pengguna' }}</p>
                            <p class="text-[11px] font-medium text-slate-500 truncate">{{ Auth::user()->email ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="p-2 space-y-1">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-slate-700 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-all duration-200 group">
                            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-sm border border-slate-200 group-hover:border-slate-300 transition-colors">
                                <svg class="w-4 h-4 text-slate-500 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 text-left">Profil Saya</div>
                        </a>
                    </div>

                    <div class="p-2 border-t border-slate-100 bg-slate-50/50">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="bg-transparent flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-rose-600 rounded-xl hover:bg-rose-50 hover:text-rose-700 w-full transition-all duration-200 group">
                                <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-sm border border-rose-100 group-hover:border-rose-200 transition-colors">
                                    <svg class="w-4 h-4 text-rose-500 group-hover:text-rose-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1 text-left">Keluar dari Akun</div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    @php
        $jsNotifications = $dbNotifications->map(function($n) {
            return [
                'id' => $n->id,
                'title' => $n->data['title'] ?? 'Pemberitahuan',
                'message' => $n->data['message'] ?? '',
                'type' => $n->data['type'] ?? 'info',
                'url' => $n->data['url'] ?? '#',
                'is_read' => $n->read_at !== null,
                'created_at_human' => $n->created_at->diffForHumans()
            ];
        });
    @endphp
    document.addEventListener('alpine:init', () => {
        Alpine.data('notificationDropdown', () => ({
            open: false,
            notifications: @json($jsNotifications),
            dbCount: {{ $dbCount }},
            totalUnread: {{ $totalUnread }},

            init() {
                const self = this;
                setTimeout(function poll() {
                    fetch('/notifications/unread', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(r => r.ok ? r.json() : null)
                        .then(data => {
                            if (!data) return;

                            // Find new notifications that are not in our list
                            const newNotifs = data.notifications.filter(n => {
                                return !self.notifications.some(existing => existing.id === n.id);
                            });

                            if (newNotifs.length > 0) {
                                newNotifs.forEach(n => {
                                    if (window.showToast) {
                                        window.showToast(n.type, n.title, n.message);
                                    }
                                    self.notifications.unshift({
                                        id: n.id,
                                        title: n.title,
                                        message: n.message,
                                        type: n.type,
                                        url: n.url,
                                        is_read: false,
                                        created_at_human: n.created_at
                                    });
                                });
                            }

                            const diff = data.count - self.dbCount;
                            self.dbCount = data.count;
                            self.totalUnread = Math.max(0, self.totalUnread + diff);
                        })
                        .catch(() => {})
                        .finally(() => setTimeout(poll, 15000));
                }, 8000);
            },

            getTheme(type) {
                const colors = {
                    success: {
                        bg: 'bg-emerald-100',
                        text: 'text-emerald-600',
                        border: 'border-emerald-200',
                        icon: 'M5 13l4 4L19 7'
                    },
                    danger: {
                        bg: 'bg-red-100',
                        text: 'text-red-600',
                        border: 'border-red-200',
                        icon: 'M6 18L18 6M6 6l12 12'
                    },
                    warning: {
                        bg: 'bg-amber-100',
                        text: 'text-amber-600',
                        border: 'border-amber-200',
                        icon: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
                    },
                    info: {
                        bg: 'bg-blue-100',
                        text: 'text-blue-600',
                        border: 'border-blue-200',
                        icon: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                    }
                };
                return colors[type] || colors['info'];
            },

            markAsRead(id, redirectUrl) {
                fetch('/notifications/' + id + '/read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        const notif = this.notifications.find(n => n.id === id);
                        if (notif && !notif.is_read) {
                            notif.is_read = true;
                            this.dbCount = Math.max(0, this.dbCount - 1);
                            this.totalUnread = Math.max(0, this.totalUnread - 1);
                        }

                        // Parse redirect url to handle cross-origin or local port issues
                        let path = redirectUrl;
                        try {
                            const urlObj = new URL(redirectUrl);
                            path = urlObj.pathname + urlObj.search + urlObj.hash;
                        } catch (e) {
                            // Already relative or invalid URL
                        }

                        if (path && path !== '#') {
                            setTimeout(() => {
                                window.location.href = path;
                            }, 150);
                        }
                    }
                });
            },

            markAllAsRead() {
                fetch('/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        this.notifications.forEach(n => {
                            n.is_read = true;
                        });
                        this.totalUnread = 0;
                        this.dbCount = 0;
                    }
                });
            },

            deleteNotification(id) {
                fetch('/notifications/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        const notifIndex = this.notifications.findIndex(n => n.id === id);
                        if (notifIndex !== -1) {
                            const notif = this.notifications[notifIndex];
                            if (!notif.is_read) {
                                this.dbCount = Math.max(0, this.dbCount - 1);
                                this.totalUnread = Math.max(0, this.totalUnread - 1);
                            }
                            this.notifications.splice(notifIndex, 1);
                        }
                    }
                });
            }
        }));

        Alpine.data('topbarClock', (userName) => ({
            serverOffset: 0,
            time: '--:--:--',
            greeting: 'Selamat Datang',
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

                const hour = currentServerTime.getHours();
                const firstName = userName ? (userName.toLowerCase() === 'super admin' ?
                    'Super Admin' : userName.split(' ')[0]) : 'Admin';
                if (hour >= 5 && hour < 11) {
                    this.greeting = `Selamat Pagi, ${firstName} 🌅`;
                } else if (hour >= 11 && hour < 15) {
                    this.greeting = `Selamat Siang, ${firstName} ☀️`;
                } else if (hour >= 15 && hour < 18) {
                    this.greeting = `Selamat Sore, ${firstName} 🌇`;
                } else {
                    this.greeting = `Selamat Malam, ${firstName} 🌙`;
                }
            }
        }));
    });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .custom-scrollbar:hover::-webkit-scrollbar-thumb {
        background: #94a3b8;
    }
</style>
