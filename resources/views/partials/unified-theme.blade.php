<style>
    :root {
        --ml-primary: #0f2e5c;
        --ml-primary-2: #1f5f9f;
        --ml-accent: #c79a2f;
        --ml-success: #0f8f69;
        --ml-bg: #f5f7fb;
        --ml-surface: rgba(255, 255, 255, 0.96);
        --ml-border: #dbe3ee;
        --ml-text: #102033;
        --ml-muted: #64748b;
        --ml-radius: 10px;
        --ml-radius-sm: 8px;
        --ml-shadow-sm: 0 4px 14px rgba(15, 46, 92, 0.05);
    }

    html {
        background: var(--ml-bg);
    }

    .{
        width: 100%;
        max-width: 1560px;
        margin-left: auto;
        margin-right: auto;
    }

    /*
     * Theme ini sengaja di-scope ke area konten.
     * Header/topbar/sidebar/navbar publik tidak disentuh agar tampilan header tetap original.
     */
    .:is(.rounded-2xl, .rounded-3xl, .rounded-\[2rem\], .rounded-\[28px\]),
    .status-card,
    .auth-card {
        border-radius: var(--ml-radius) !important;
    }

    ..rounded-xl {
        border-radius: var(--ml-radius-sm) !important;
    }

    .:is(.shadow, .shadow-sm, .shadow-md, .shadow-lg, .shadow-xl, .shadow-2xl),
    .status-card,
    .auth-card {
        box-shadow: var(--ml-shadow-sm) !important;
    }

    .:is(.bg-white, .bg-white\/80, .bg-white\/90, .bg-white\/95, .bg-white\/70),
    .auth-card {
        background-color: var(--ml-surface) !important;
    }

    .:is(.border, .border-slate-100, .border-slate-200, .border-gray-100, .border-gray-200),
    .auth-card {
        border-color: var(--ml-border) !important;
    }

    .:is(input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="date"], input[type="file"], input[type="search"], input[type="tel"], input[type="url"], select, textarea),
    .auth-card :is(input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="date"], input[type="file"], input[type="search"], input[type="tel"], input[type="url"], select, textarea) {
        border-color: var(--ml-border) !important;
        border-radius: var(--ml-radius-sm) !important;
        background-color: #ffffff !important;
        color: var(--ml-text) !important;
        box-shadow: none !important;
    }

    .:is(input, select, textarea):focus,
    .auth-card :is(input, select, textarea):focus {
        border-color: var(--ml-primary-2) !important;
        box-shadow: 0 0 0 3px rgba(31, 95, 159, 0.14) !important;
        outline: none !important;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .:is(thead, thead tr, thead th) {
        background-color: #f8fafc !important;
        color: #475569 !important;
    }

    .tbody tr {
        transition: background-color 160ms ease, transform 160ms ease;
    }

    .tbody tr:hover {
        background-color: rgba(15, 46, 92, 0.035) !important;
    }

    .:is(.bg-blue-600, .hover\:bg-blue-700:hover, .bg-indigo-600, .hover\:bg-indigo-700:hover),
    .auth-card :is(.bg-blue-600, .hover\:bg-blue-700:hover, .bg-indigo-600, .hover\:bg-indigo-700:hover) {
        background-color: var(--ml-primary-2) !important;
    }

    .:is(.text-blue-600, .text-indigo-600),
    .auth-card :is(.text-blue-600, .text-indigo-600) {
        color: var(--ml-primary-2) !important;
    }

    .:is(.bg-emerald-600, .hover\:bg-emerald-700:hover) {
        background-color: var(--ml-success) !important;
    }

    .:is(.text-slate-800, .text-gray-900) {
        color: var(--ml-text) !important;
    }

    .:is(.text-slate-500, .text-gray-500) {
        color: var(--ml-muted) !important;
    }

    .:is(.animate-fade-in, .animate-fade-in-up, .animate-fade-in-left, .animate-fade-in-right) {
        animation-duration: 260ms !important;
    }

    @media (max-width: 640px) {
        .{
            padding-left: 0.875rem !important;
            padding-right: 0.875rem !important;
        }

        .:is(h1, .text-4xl, .text-5xl) {
            font-size: 1.875rem !important;
            line-height: 2.25rem !important;
        }

        ..overflow-x-auto {
            border-radius: var(--ml-radius);
        }
    }
</style>
