<style>
    html,
    body {
        max-width: 100%;
    }

    button,
    a,
    input,
    select,
    textarea {
        touch-action: manipulation;
    }

    @media (max-width: 640px) {
        body {
            background: #f8fafc !important;
        }

        .mobile-topbar {
            width: 100%;
            max-width: 100vw;
            overflow: visible;
            isolation: isolate;
        }

        .mobile-topbar-inner {
            display: grid !important;
            grid-template-columns: 42px minmax(0, 1fr) 88px;
            align-items: center !important;
            min-height: 58px;
            flex-wrap: nowrap !important;
            gap: 0.5rem !important;
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }

        .mobile-topbar-left {
            display: contents !important;
        }

        .mobile-topbar-menu,
        .mobile-account-button,
        .mobile-topbar-actions > .relative > button {
            flex: 0 0 42px !important;
            width: 42px !important;
            min-width: 42px !important;
            min-height: 42px !important;
            border-radius: 0.875rem !important;
        }

        .mobile-topbar-menu {
            grid-column: 1;
            margin-left: 0 !important;
        }

        .mobile-topbar-brand {
            grid-column: 2;
            display: flex !important;
            align-items: center !important;
            flex: none !important;
            min-width: 0;
            max-width: 100% !important;
            overflow: hidden;
        }

        .mobile-topbar-logo {
            width: 30px !important;
            height: 30px !important;
        }

        .mobile-topbar-title,
        .mobile-topbar-role {
            display: block;
            max-width: 100% !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .mobile-topbar-title {
            font-size: 0.875rem !important;
            line-height: 1.1 !important;
        }

        .mobile-topbar-role {
            font-size: 0.625rem !important;
            line-height: 1rem !important;
        }

        .mobile-topbar-actions {
            grid-column: 3;
            flex: 0 0 auto !important;
            width: 88px !important;
            max-width: 88px !important;
            flex-wrap: nowrap !important;
            justify-content: flex-end !important;
            align-items: center !important;
            gap: 0.25rem !important;
            min-width: 0;
        }

        .mobile-topbar-actions > .relative {
            flex: 0 0 42px !important;
            width: 42px !important;
            min-width: 42px !important;
        }

        .mobile-topbar-actions img {
            width: 34px !important;
            height: 34px !important;
            border-radius: 0.75rem !important;
        }

        .mobile-notification-panel {
            position: absolute !important;
            top: 100% !important;
            right: 0 !important;
            left: auto !important;
            width: min(24rem, calc(100vw - 1.5rem)) !important;
            max-width: calc(100vw - 1.5rem) !important;
            margin-top: 0.5rem !important;
            border-radius: 1rem !important;
            transform-origin: top right !important;
            z-index: 9999 !important;
        }

        .mobile-account-panel {
            position: absolute !important;
            top: 100% !important;
            right: 0 !important;
            left: auto !important;
            width: min(18rem, calc(100vw - 1.5rem)) !important;
            max-width: calc(100vw - 1.5rem) !important;
            margin-top: 0.5rem !important;
            border-radius: 1rem !important;
            transform-origin: top right !important;
            z-index: 9999 !important;
        }

        main {
            width: 100%;
            max-width: 100vw;
        }

        main > div {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
            padding-top: 0.875rem !important;
            padding-bottom: 1.25rem !important;
        }

        main :where(.space-y-6) {
            gap: 0 !important;
        }

        main :where(.space-y-6 > :not([hidden]) ~ :not([hidden])) {
            margin-top: 0.875rem !important;
        }

        main :where(.space-y-5 > :not([hidden]) ~ :not([hidden])) {
            margin-top: 0.875rem !important;
        }

        main :where(.bg-white.rounded-2xl, .bg-white.rounded-3xl) {
            border-radius: 1rem !important;
            box-shadow: 0 1px 2px rgb(15 23 42 / 0.06) !important;
        }

        main :where(.bg-white.rounded-2xl *, .bg-white.rounded-3xl *) {
            min-width: 0;
        }

        main :where(.bg-white.rounded-2xl > div:first-child[class*="border-b"],
            .bg-white.rounded-3xl > div:first-child[class*="border-b"]) {
            padding: 0.875rem 1rem !important;
        }

        main :where(.bg-white.rounded-2xl > div:first-child[class*="border-b"] h3,
            .bg-white.rounded-3xl > div:first-child[class*="border-b"] h3) {
            align-items: flex-start !important;
            font-size: 0.95rem !important;
            line-height: 1.25rem !important;
        }

        main :where(.bg-white.rounded-2xl > div:first-child[class*="border-b"] p,
            .bg-white.rounded-3xl > div:first-child[class*="border-b"] p) {
            margin-top: 0.25rem !important;
            line-height: 1.25 !important;
        }

        main :where(.p-8, .p-10, .p-12, .p-16) {
            padding: 1rem !important;
        }

        main :where(.p-5, .p-6, .p-7) {
            padding: 1rem !important;
        }

        main :where(.px-5, .px-6, .px-7) {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        main :where(.py-4, .py-5, .py-6) {
            padding-top: 0.875rem !important;
            padding-bottom: 0.875rem !important;
        }

        main :where(.px-8, .px-10, .px-12, .px-16) {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        main :where(.py-8, .py-10, .py-12, .py-16) {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }

        main :where(.gap-8, .gap-10, .gap-12) {
            gap: 1rem !important;
        }

        main :where(.gap-4, .gap-5, .gap-6) {
            gap: 0.875rem !important;
        }

        main :where(.rounded-3xl, .rounded-\[2rem\], .rounded-\[2\.5rem\]) {
            border-radius: 1rem !important;
        }

        main :where(.tracking-tight, .tracking-wide, .tracking-wider, .tracking-widest) {
            letter-spacing: 0 !important;
        }

        main :where(.uppercase) {
            line-height: 1.2rem !important;
        }

        main :where(.blur-3xl, .blur-xl.mix-blend-overlay, .mix-blend-multiply.filter.blur-3xl) {
            display: none !important;
        }

        main :where(.hover\:-translate-y-1\.5, .hover\:-translate-y-1, .hover\:scale-110) {
            transform: none !important;
        }

        main :where(.bg-gradient-to-br.rounded-3xl, .bg-gradient-to-br.rounded-2xl) {
            padding: 1rem !important;
            min-height: auto !important;
        }

        main :where(.bg-gradient-to-br.rounded-3xl > .flex,
            .bg-gradient-to-br.rounded-2xl > .flex) {
            align-items: flex-start !important;
            gap: 0.75rem !important;
            flex-wrap: nowrap !important;
        }

        main :where(.bg-gradient-to-br.rounded-3xl > .flex > div:first-child,
            .bg-gradient-to-br.rounded-2xl > .flex > div:first-child) {
            flex: 1 1 auto !important;
            min-width: 0 !important;
            max-width: calc(100% - 3.25rem) !important;
        }

        main :where(.bg-gradient-to-br.rounded-3xl > .flex > div:last-child,
            .bg-gradient-to-br.rounded-2xl > .flex > div:last-child) {
            flex: 0 0 auto !important;
        }

        main :where(.bg-gradient-to-br.rounded-3xl .w-14.h-14,
            .bg-gradient-to-br.rounded-2xl .w-14.h-14) {
            width: 2.75rem !important;
            height: 2.75rem !important;
            border-radius: 0.875rem !important;
        }

        main :where(.bg-gradient-to-br.rounded-3xl .text-4xl,
            .bg-gradient-to-br.rounded-2xl .text-4xl) {
            font-size: 1.875rem !important;
            line-height: 2.125rem !important;
        }

        main :where(.bg-gradient-to-br.rounded-3xl .mt-3.flex,
            .bg-gradient-to-br.rounded-2xl .mt-3.flex,
            .bg-gradient-to-br.rounded-3xl .flex.justify-between,
            .bg-gradient-to-br.rounded-2xl .flex.justify-between) {
            flex-wrap: wrap !important;
            gap: 0.375rem 0.5rem !important;
        }

        main :where(.relative.overflow-hidden.rounded-3xl > .relative.z-10) {
            padding: 1rem !important;
            gap: 0.875rem !important;
        }

        main :where(.relative.overflow-hidden.rounded-3xl > .relative.z-10 > .flex-shrink-0) {
            width: 100% !important;
            text-align: left !important;
        }

        main :where(.relative.overflow-hidden.rounded-3xl > .relative.z-10 > .flex-shrink-0 p.flex) {
            justify-content: flex-start !important;
        }

        main :where(.inline-flex) {
            max-width: 100%;
            min-width: 0;
            align-items: center;
            line-height: 1.15rem !important;
        }

        main :where(.inline-flex > span) {
            min-width: 0;
        }

        main :where(.text-2xl, .text-3xl, .text-4xl, .text-5xl) {
            line-height: 1.15 !important;
        }

        main :where(.text-3xl, .text-4xl, .text-5xl) {
            font-size: 1.75rem !important;
        }

        main :where([class*="line-clamp"]) {
            line-height: 1.35 !important;
        }

        main :where(h1) {
            font-size: 1.5rem !important;
            line-height: 1.25 !important;
            overflow-wrap: break-word;
            word-break: normal;
        }

        main :where(h2) {
            font-size: 1.25rem !important;
            line-height: 1.3 !important;
            overflow-wrap: break-word;
            word-break: normal;
        }

        main :where(h3, h4, p, span, label, td, th, a, button) {
            overflow-wrap: break-word;
            word-break: normal;
            line-height: 1.35;
        }

        main :where(.text-\[9px\], .text-\[10px\], .text-\[11px\], .text-xs) {
            line-height: 1rem !important;
        }

        main :where(.text-sm) {
            line-height: 1.35rem !important;
        }

        main :where(.text-base) {
            line-height: 1.45rem !important;
        }

        main :where(.leading-none) {
            line-height: 1.15 !important;
        }

        main :where(.leading-tight) {
            line-height: 1.25 !important;
        }

        main :where(.truncate) {
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }

        main :where(label) {
            line-height: 1.25rem !important;
        }

        main :where(label.flex) {
            align-items: flex-start !important;
            flex-wrap: wrap;
            gap: 0.375rem;
        }

        main :where(label.flex) > :where(span[id$="-loading"]) {
            margin-left: 0 !important;
            width: 100%;
        }

        main :where(input, select, textarea) {
            min-height: 44px;
            font-size: 16px !important;
            border-radius: 0.875rem !important;
            padding-left: 0.875rem !important;
            padding-right: 0.875rem !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        main :where(button, a[href], input[type="submit"], input[type="button"]) {
            min-height: 44px;
            line-height: 1.25rem !important;
            white-space: normal;
        }

        main :where(button.flex, a[href].flex) {
            align-items: center;
        }

        main :where(button svg, a[href] svg) {
            flex: 0 0 auto;
        }

        main :where(.flex > div, .flex > span, .flex > p, .flex > h1, .flex > h2, .flex > h3) {
            min-width: 0;
        }

        main :where(.flex > [class*="w-8"],
            .flex > [class*="w-9"],
            .flex > [class*="w-10"],
            .flex > [class*="w-12"],
            .flex > [class*="w-14"],
            .flex > [class*="w-16"]) {
            flex-shrink: 0 !important;
        }

        main :where(.group.block .flex.items-center,
            a.group .flex.items-center,
            .bg-white .flex.items-center.gap-3,
            .bg-white .flex.items-center.gap-4) {
            align-items: flex-start !important;
        }

        main :where(.group.block .flex.items-center > div:last-child,
            a.group .flex.items-center > div:last-child,
            .bg-white .flex.items-center.gap-3 > div:last-child,
            .bg-white .flex.items-center.gap-4 > div:last-child) {
            min-width: 0 !important;
            flex: 1 1 auto !important;
        }

        main > div > :where(.flex.justify-between,
            .flex.items-center.justify-between,
            .flex.items-start.justify-between) {
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        main :where(.flex.justify-end, .flex.items-center.justify-end) {
            flex-wrap: wrap;
            justify-content: stretch;
        }

        main :where(.flex.justify-end, .flex.items-center.justify-end) > :where(a[class*="px-"], button[class*="px-"], form) {
            flex: 1 1 100%;
            width: 100%;
            min-width: 0;
        }

        main :where(.flex.justify-end, .flex.items-center.justify-end) > :where(a[class*="px-"], button[class*="px-"]) {
            justify-content: center;
        }

        main :where(.flex.justify-end, .flex.items-center.justify-end) > form > button {
            width: 100%;
            justify-content: center;
        }

        main :where(.grid) {
            min-width: 0;
        }

        main :where(.grid.grid-cols-2, .grid.grid-cols-3, .grid.grid-cols-4) {
            grid-template-columns: minmax(0, 1fr) !important;
        }

        main :where(.sm\:grid-cols-2, .sm\:grid-cols-3, .md\:grid-cols-2, .md\:grid-cols-3, .md\:grid-cols-4, .lg\:grid-cols-2, .lg\:grid-cols-3, .xl\:grid-cols-3) {
            grid-template-columns: minmax(0, 1fr) !important;
        }

        main :where(.md\:col-span-2, .md\:col-span-3, .sm\:col-span-2) {
            grid-column: auto !important;
        }

        main :where(#map) {
            height: 16rem !important;
            border-radius: 1rem !important;
        }

        main :where(.overflow-x-auto) {
            margin-left: -0.25rem;
            margin-right: -0.25rem;
            padding-left: 0.25rem;
            padding-right: 0.25rem;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            max-width: calc(100vw - 1rem);
            border-radius: 0.875rem;
        }

        main :where(.overflow-x-auto) > table {
            min-width: 680px;
        }

        main :where(table) {
            font-size: 0.8125rem !important;
            line-height: 1.25rem !important;
        }

        main :where(table) :where(a, button) {
            min-height: 40px;
            min-width: 40px;
            line-height: 1.1rem !important;
        }

        main :where(td, th) {
            white-space: normal;
            vertical-align: top;
        }

        main :where(.overflow-x-auto) :where(td, th) {
            white-space: nowrap;
        }

        main :where(.sticky.bottom-0, .sticky.top-0) {
            max-width: 100vw;
        }

        main :where(.fixed.inset-0) {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        main :where(.max-w-md, .max-w-lg, .max-w-xl, .max-w-2xl, .max-w-3xl, .max-w-4xl) {
            width: 100% !important;
            max-width: calc(100vw - 1.5rem) !important;
        }

        main :where(.max-h-\[70vh\], .max-h-\[80vh\]) {
            max-height: calc(100dvh - 10rem) !important;
        }

        main :where(.fixed.inset-0 > div, .fixed.inset-0 .inline-block, .fixed.inset-0 [id$="Content"]) {
            max-width: calc(100vw - 1.5rem) !important;
        }

        main :where(.fixed.inset-0) :where(.p-6, .p-8, .px-6, .px-8) {
            padding: 1rem !important;
        }

        main :where(.fixed.inset-0) :where(.flex.justify-end, .flex.items-center.justify-end) {
            flex-direction: column;
            align-items: stretch;
        }

        main :where(.fixed.inset-0) :where(.flex.justify-end, .flex.items-center.justify-end) > :where(a, button, form) {
            width: 100%;
        }

        main :where(.absolute.-top-2.-right-2, .absolute.top-2.right-2) {
            opacity: 1 !important;
        }

        main :where(.h-36) {
            height: 8.5rem !important;
        }

        main img,
        main svg,
        main canvas,
        main iframe {
            max-width: 100%;
        }

        main :where(.w-48, .w-56, .w-64, .w-72, .w-80, .w-96) {
            max-width: 100%;
        }

        main :where(.h-48, .h-56, .h-64, .h-72, .h-80, .h-96) {
            max-height: 18rem;
        }

        body > div.fixed.z-50.md\:hidden :where(a, button) {
            min-height: 44px;
        }

        body > div.fixed.z-50.md\:hidden svg {
            flex-shrink: 0;
        }
    }
</style>
