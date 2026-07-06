<style>
    :root {
        --ml-primary: #0f2e5c;
        --ml-primary-2: #1a498b;
        --ml-gold: #d4af37;
        --ml-green: #10b981;
        --ml-coral: #d94b2b;
        --ml-surface: rgba(255, 255, 255, 0.86);
        --ml-line: rgba(226, 232, 240, 0.82);
        --ml-shadow: 0 18px 45px rgba(15, 46, 92, 0.08);
        --ml-shadow-soft: 0 10px 28px rgba(15, 46, 92, 0.06);
        --ml-batik: url("{{ asset('images/batik-jabar-tile.svg') }}");
    }

    html {
        background: #f8fafc;
    }

    body {
        background:
            radial-gradient(circle at top left, rgba(212, 175, 55, 0.13), transparent 28rem),
            radial-gradient(circle at 90% 8%, rgba(16, 185, 129, 0.09), transparent 24rem),
            linear-gradient(180deg, #f8fbff 0%, #f8fafc 42%, #ffffff 100%) !important;
    }

    body::before {
        content: "";
        position: fixed;
        inset: 0;
        pointer-events: none;
        background-image: var(--ml-batik);
        background-size: 120px 120px;
        opacity: 0.035;
        z-index: -1;
    }

    aside {
        background:
            radial-gradient(circle at 30% 0%, rgba(212, 175, 55, 0.16), transparent 16rem),
            linear-gradient(180deg, #081a36 0%, #0b1021 100%) !important;
        border-right: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 18px 0 44px rgba(15, 23, 42, 0.16) !important;
    }

    .sidebar-scroll::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.06) !important;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb {
        background: rgba(212, 175, 55, 0.45) !important;
    }

    .menu-item {
        min-height: 44px;
        border: 1px solid transparent;
        color: rgba(226, 232, 240, 0.88);
    }

    .menu-item:hover {
        background: rgba(255, 255, 255, 0.08) !important;
        border-color: rgba(255, 255, 255, 0.08);
    }

    .menu-item-active {
        background: linear-gradient(135deg, var(--ml-primary-2), var(--ml-primary)) !important;
        box-shadow: 0 12px 24px rgba(26, 73, 139, 0.26);
        border-color: rgba(255, 255, 255, 0.14);
    }

    main > div:first-of-type,
    header,
    .topbar {
        backdrop-filter: blur(16px);
    }

    main > .p-3,
    main > .sm\:p-4,
    main > .md\:p-6,
    main > .lg\:p-8 {
        width: 100%;
        max-width: 100%;
    }

    .bg-white,
    .bg-white\/80,
    .bg-white\/90 {
        border-color: var(--ml-line);
    }

    .rounded-3xl {
        border-radius: 22px !important;
    }

    .shadow-sm {
        box-shadow: var(--ml-shadow-soft) !important;
    }

    .hover\:shadow-\[0_8px_30px_rgb\(0\,0\,0\,0\.04\)\]:hover,
    .hover\:shadow-\[0_10px_40px_-15px_rgba\(0\,0\,0\,0\.1\)\]:hover {
        box-shadow: var(--ml-shadow) !important;
    }

    table {
        border-collapse: separate;
        border-spacing: 0;
    }

    thead {
        background: linear-gradient(135deg, var(--ml-primary-2), var(--ml-primary));
    }

    th {
        color: #ffffff !important;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        border-color: rgba(255, 255, 255, 0.15) !important;
    }
    
    thead tr:first-child th:first-child {
        border-top-left-radius: 14px;
    }
    
    thead tr:first-child th:last-child {
        border-top-right-radius: 14px;
    }

    tbody tr {
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    tbody tr:hover {
        background: rgba(15, 46, 92, 0.035);
    }

    input,
    select,
    textarea {
        border-color: #dbe3ef !important;
        border-radius: 14px !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }

    input:focus,
    select:focus,
    textarea:focus {
        border-color: var(--ml-primary-2) !important;
        box-shadow: 0 0 0 4px rgba(26, 73, 139, 0.12) !important;
    }

    .btn-primary,
    button[type="submit"],
    a[href*="create"].bg-blue-600,
    .bg-blue-600 {
        background: linear-gradient(135deg, var(--ml-primary-2), var(--ml-primary)) !important;
        box-shadow: 0 12px 24px rgba(15, 46, 92, 0.18);
    }

    .bg-indigo-600,
    .bg-purple-600 {
        background: linear-gradient(135deg, #5b5bd6, var(--ml-primary-2)) !important;
    }

    .bg-emerald-600,
    .bg-green-600 {
        background: linear-gradient(135deg, var(--ml-green), #0f9f77) !important;
    }

    .bg-amber-500,
    .bg-yellow-500 {
        background: linear-gradient(135deg, var(--ml-gold), #c88a17) !important;
    }

    .bg-red-600 {
        background: linear-gradient(135deg, var(--ml-coral), #b91c1c) !important;
    }

    .text-blue-600 {
        color: var(--ml-primary-2) !important;
    }

    .ring-blue-500,
    .focus\:ring-blue-500:focus {
        --tw-ring-color: rgba(26, 73, 139, 0.32) !important;
    }

    [class*="from-blue-"],
    [class*="to-blue-"] {
        --tw-gradient-from: var(--ml-primary-2) var(--tw-gradient-from-position);
        --tw-gradient-to: var(--ml-primary) var(--tw-gradient-to-position);
    }

    @media (max-width: 768px) {
        body {
            background: linear-gradient(180deg, #f8fbff 0%, #f8fafc 100%) !important;
        }

        .rounded-3xl {
            border-radius: 18px !important;
        }

        main > div[class*="p-"] {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        table {
            min-width: 720px;
        }
    }
</style>
