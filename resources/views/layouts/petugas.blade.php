<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Petugas Lapangan Dashboard') | Mandalaloka</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo_Mandalaloka.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css" rel="stylesheet">

    <style>
        [x-cloak] {
            display: none !important;
        }

        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .sidebar-transition {
            transition: width 300ms ease-in-out;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: #334155;
            border-radius: 10px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #64748b;
            border-radius: 10px;
        }

        .menu-item-active {
            background-color: #2563eb !important;
            color: white !important;
        }

        .menu-item-active svg {
            stroke: white !important;
        }

        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            background-color: #1e293b;
            color: white;
            text-align: center;
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 11px;
            white-space: nowrap;
            position: absolute;
            z-index: 50;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 12px;
            opacity: 0;
            transition: opacity 0.2s;
            pointer-events: none;
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        body.menu-open {
            overflow: hidden;
        }

        button,
        a {
            -webkit-tap-highlight-color: transparent;
        }
    </style>
    @include('partials.mobile-responsive-styles')
    @include('partials.dashboard-polish')
    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-800 selection:bg-indigo-500 selection:text-white" x-data="app()" x-init="init()" x-cloak
    :class="{ 'menu-open': mobileMenuOpen }">

    <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 z-40 md:hidden" @click="toggleMobileMenu()">
    </div>

    <x-mobile-sidebar />

    <x-sidebar />

    <main class="transition-all duration-300 min-h-screen relative animate-fade-in"
        :class="sidebarOpen ? 'md:ml-64' : 'md:ml-[72px]'">

        <x-topbar />

        <div class="p-3 sm:p-4 md:p-6 lg:p-8">
            @yield('content')
        </div>
    </main>

    <script>
        function app() {
            return {
                sidebarOpen: true,
                mobileMenuOpen: false,
                init() {
                    this.sidebarOpen = true;
                },
                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                    localStorage.setItem('sipumkm_sidebar', this.sidebarOpen);
                },
                toggleMobileMenu() {
                    this.mobileMenuOpen = !this.mobileMenuOpen;
                    document.body.style.overflow = this.mobileMenuOpen ? 'hidden' : '';
                }
            }
        }

        function serverTime() {
            return {
                serverOffset: 0,
                time: '--:--:--',
                date: '--, -- ----',
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

    <style>
        .animate-spin-slow {
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>

    @stack('scripts')

    @include('partials.toast-container')
    @include('partials.confirm-modal')

</body>

</html>
