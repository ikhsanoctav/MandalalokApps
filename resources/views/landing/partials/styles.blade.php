    <style>
        :root {
            /* Elegant Government Color Palette */
            --primary: #0f2e5c;        /* Deep Navy Blue */
            --primary-light: #1a498b;
            --primary-soft: #e8f0fb;
            --gold: #d4af37;           /* Elegant Gold */
            --gold-dark: #b8902d;
            --green: #10b981;
            --coral: #d94b2b;
            --bg-light: #f8fafc;
            --bg-warm: #fffaf0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            
            /* Modern Rounded Corners */
            --radius-sm: 12px;
            --radius-md: 24px;
            --radius-lg: 32px;
            --radius-pill: 999px;

            --shadow-subtle: 0 12px 34px rgba(15, 46, 92, 0.07);
            --shadow-hover: 0 22px 46px rgba(15, 46, 92, 0.14);
            --shadow-ring: 0 0 0 1px rgba(15, 46, 92, 0.06), 0 20px 50px rgba(15, 46, 92, 0.12);

            /* Aksesn Batik Jawa Barat (Seamless SVG Pattern) */
            --batik-jabar: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23d4af37' fill-opacity='0.15'%3E%3Cpath d='M30 30c0-8.284 6.716-15 15-15 8.284 0 15 6.716 15 15 0 8.284-6.716 15-15 15-8.284 0-15-6.716-15-15zm-15 0c0-8.284-6.716-15-15-15C6.716 15 0 21.716 0 30c0 8.284 6.716 15 15 15 8.284 0 15-6.716 15-15zM15 15c0-8.284 6.716-15 15-15 8.284 0 15 6.716 15 15 0 8.284-6.716 15-15 15-8.284 0-15-6.716-15-15zM15 45c0-8.284 6.716-15 15-15 8.284 0 15 6.716 15 15 0 8.284-6.716 15-15 15-8.284 0-15-6.716-15-15z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(212, 175, 55, 0.13), transparent 30rem),
                linear-gradient(180deg, #ffffff 0%, var(--bg-light) 38%, #ffffff 100%);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        h1, h2, h3, .quote-text, .section-title {
            font-family: 'Merriweather', serif;
        }

        /* Indonesian Red & White Top Ribbon */
        .top-ribbon {
            height: 4px;
            width: 100%;
            background: linear-gradient(90deg, #ef4444 50%, #ffffff 50%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1001;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stagger > * {
            opacity: 0;
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .stagger > *:nth-child(1) { animation-delay: 0.1s; }
        .stagger > *:nth-child(2) { animation-delay: 0.2s; }
        .stagger > *:nth-child(3) { animation-delay: 0.3s; }
        .stagger > *:nth-child(4) { animation-delay: 0.4s; }
        .stagger > *:nth-child(5) { animation-delay: 0.5s; }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 24px;
            left: 24px;
            right: 24px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(18px);
            border-radius: var(--radius-pill);
            border: 1px solid rgba(255, 255, 255, 0.74);
            box-shadow: 0 18px 45px rgba(15, 46, 92, 0.1);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            max-width: 1320px;
            margin: 0 auto;
        }
        .navbar.scrolled {
            top: 4px;
            left: 0;
            right: 0;
            max-width: 100%;
            border-radius: 0 0 var(--radius-md) var(--radius-md);
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-left: none;
            border-right: none;
            border-top: none;
        }
        .nav-container {
            padding: 10px 24px 10px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .logo img {
            height: 48px;
            width: auto;
            border-radius: var(--radius-sm);
        }
        .logo-text h4 {
            font-size: 18px;
            font-weight: 900;
            color: var(--primary);
            letter-spacing: 0.5px;
            line-height: 1.1;
        }
        .logo-text p {
            font-size: 11px;
            font-weight: 500;
            color: var(--text-muted);
            letter-spacing: 0.5px;
            margin-top: 4px;
            text-transform: uppercase;
        }
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 28px;
        }
        .nav-link {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 700;
            font-size: 14px;
            transition: all 0.3s ease;
            position: relative;
            padding: 8px 0;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--gold-dark);
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            border-radius: var(--radius-pill);
            background: var(--gold);
            transition: width 0.3s ease;
        }
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
        .btn-login {
            background: rgba(255, 255, 255, 0.72);
            border: 1.5px solid rgba(15, 46, 92, 0.18);
            color: var(--primary);
            padding: 12px 28px;
            border-radius: var(--radius-pill);
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background: rgba(15, 46, 92, 0.06);
            border-color: rgba(15, 46, 92, 0.32);
            transform: translateY(-2px);
        }
        .btn-register {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 12px 28px;
            border-radius: var(--radius-pill);
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1.5px solid var(--primary);
            box-shadow: 0 12px 26px rgba(15, 46, 92, 0.22);
        }
        .btn-register:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(15, 46, 92, 0.25);
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            font-size: 20px;
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            padding: 8px;
        }
        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 300px;
            height: 100%;
            background: white;
            box-shadow: -4px 0 30px rgba(0,0,0,0.1);
            z-index: 1002;
            transition: right 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            padding: 80px 32px 32px;
            border-radius: 24px 0 0 24px;
        }
        .mobile-menu.active { right: 0; }
        .mobile-menu-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1001;
            display: none;
        }
        .mobile-menu-overlay.active { display: block; }
        .mobile-nav-link {
            display: block;
            padding: 16px 0;
            text-decoration: none;
            color: var(--primary);
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
        }
        .mobile-close {
            position: absolute;
            top: 24px;
            right: 24px;
            font-size: 24px;
            background: none;
            border: none;
            color: var(--text-dark);
            cursor: pointer;
        }
        /* --- Premium Animations --- */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Hero Specific Animations (On Load) */
        @keyframes fadeInUpHero {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInScaleHero {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes floatPremium {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
            100% { transform: translateY(0px); }
        }
        @keyframes float-img {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        @keyframes pulse-dot {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .hero-content .official-tag { animation: fadeInUpHero 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards 0.1s; opacity: 0; }
        .hero-content h1 { animation: fadeInUpHero 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards 0.2s; opacity: 0; }
        .hero-content h2 { animation: fadeInUpHero 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards 0.3s; opacity: 0; }
        .hero-content p { animation: fadeInUpHero 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards 0.4s; opacity: 0; }
        .hero-buttons { animation: fadeInUpHero 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards 0.5s; opacity: 0; }
        .logo-card { 
            opacity: 0; 
            animation: fadeInScaleHero 1s cubic-bezier(0.2, 0.8, 0.2, 1) forwards 0.6s, floatPremium 6s ease-in-out infinite 1.6s; 
        }

        /* Hero */
        .hero {
            min-height: 94vh;
            display: flex;
            align-items: center;
            padding: 148px 24px 88px;
            position: relative;
            background:
                radial-gradient(circle at 74% 22%, rgba(16, 185, 129, 0.12), transparent 22rem),
                radial-gradient(circle at 88% 72%, rgba(217, 75, 43, 0.1), transparent 18rem),
                linear-gradient(135deg, #ffffff 0%, #f7fbff 48%, #fffaf0 100%);
            overflow: hidden;
        }
        /* BATIK OVERLAY PADA HERO */
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: var(--batik-jabar);
            background-size: 80px 80px;
            opacity: 0.8;
            pointer-events: none;
        }
        /* Gradient fade agar teks tetap terbaca jelas */
        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, rgba(255,255,255,0.98) 0%, rgba(255,255,255,0.85) 45%, rgba(255,255,255,0.15) 100%),
                linear-gradient(180deg, transparent 0%, rgba(248,250,252,0.7) 100%);
            pointer-events: none;
        }

        .hero-container {
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(360px, 0.92fr);
            gap: 64px;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        .hero-content .official-tag {
            display: inline-flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 32px;
        }
        .official-tag .accent-line {
            width: 48px;
            height: 2px;
            background: var(--gold);
        }
        .official-tag span {
            font-size: 11px;
            font-weight: 800;
            color: var(--primary-color);
            letter-spacing: 4px;
            text-transform: uppercase;
        }
        .hero-content h1 {
            font-size: 64px;
            font-weight: 900;
            line-height: 1.05;
            margin-bottom: 20px;
            color: var(--primary);
            letter-spacing: 0;
        }
        .hero-content h1 span {
            color: transparent;
            background: linear-gradient(120deg, var(--gold-dark), var(--coral));
            -webkit-background-clip: text;
            background-clip: text;
        }
        .hero-content h2 {
            font-size: 24px;
            font-weight: 400;
            color: var(--text-muted);
            margin-bottom: 24px;
            font-family: 'Inter', sans-serif;
        }
        .hero-content p {
            font-size: 16px;
            color: var(--text-dark);
            line-height: 1.8;
            margin-bottom: 32px;
            max-width: 620px;
        }
        .hero-buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 28px;
        }
        .hero-buttons .btn-register,
        .hero-buttons .btn-login {
            padding: 14px 32px;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .hero-trust-row {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            max-width: 620px;
        }
        .hero-trust-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            background: rgba(255, 255, 255, 0.76);
            border: 1px solid rgba(226, 232, 240, 0.78);
            border-radius: 18px;
            box-shadow: 0 12px 26px rgba(15, 46, 92, 0.07);
        }
        .hero-trust-icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
        }
        .hero-trust-item:nth-child(2) .hero-trust-icon { background: linear-gradient(135deg, var(--green), #0f9f77); }
        .hero-trust-item:nth-child(3) .hero-trust-icon { background: linear-gradient(135deg, var(--gold-dark), var(--coral)); }
        .hero-trust-item strong {
            display: block;
            font-size: 13px;
            color: var(--primary);
            line-height: 1.2;
        }
        .hero-trust-item span {
            display: block;
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Hero Image/Logo Official Focus dengan Aksen Batik */
        .hero-image {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .logo-card {
            background-color: rgba(255, 255, 255, 0.9);
            background-image:
                linear-gradient(rgba(255,255,255,0.9), rgba(255,255,255,0.9)),
                var(--batik-jabar);
            background-size: cover;
            border-radius: var(--radius-lg);
            padding: 42px 38px 34px;
            text-align: center;
            box-shadow: var(--shadow-ring);
            border: 1px solid rgba(255, 255, 255, 0.78);
            position: relative;
            width: 100%;
            max-width: 420px;
            backdrop-filter: blur(14px);
        }
        /* Softened Corner Accents */
        .logo-card::before, .logo-card::after {
            content: '';
            position: absolute;
            width: 30px;
            height: 30px;
            border: 3px solid var(--gold);
            opacity: 0.7;
        }
        .logo-card::before { 
            top: 15px; left: 15px; 
            border-right: none; border-bottom: none; 
            border-top-left-radius: 16px; 
        }
        .logo-card::after { 
            bottom: 15px; right: 15px; 
            border-left: none; border-top: none; 
            border-bottom-right-radius: 16px; 
        }
        
        .logo-img {
            width: 180px;
            height: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
        .logo-card-metrics {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 26px;
            position: relative;
            z-index: 2;
        }
        .logo-card-metric {
            background: rgba(248, 250, 252, 0.82);
            border: 1px solid rgba(226, 232, 240, 0.88);
            border-radius: 16px;
            padding: 13px 10px;
        }
        .logo-card-metric strong {
            display: block;
            color: var(--primary);
            font-size: 20px;
            line-height: 1;
        }
        .logo-card-metric span {
            display: block;
            color: var(--text-muted);
            font-size: 11px;
            margin-top: 6px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Product Showcase Carousel */
        /* Product Showcase Carousel */
        .product-showcase {
            padding: 60px 24px 40px;
            background: linear-gradient(135deg, rgba(248,250,252,1) 0%, rgba(255,255,255,1) 100%);
            position: relative;
            overflow: hidden;
            border-top: 1px solid rgba(212, 175, 55, 0.2);
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
        }
        .product-showcase::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: var(--batik-jabar);
            opacity: 0.05; /* Very subtle */
            z-index: 0;
            pointer-events: none;
        }
        .product-showcase::after {
            content: '';
            position: absolute;
            top: -50%; left: -20%;
            width: 70%; height: 200%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.08) 0%, transparent 60%);
            z-index: 0;
            pointer-events: none;
        }
        .product-showcase-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 0 56px;
            position: relative;
            z-index: 1;
        }
        .product-showcase-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 32px;
        }
        .product-showcase-eyebrow {
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--gold-dark);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .product-showcase-eyebrow::before {
            content: '';
            display: block;
            width: 24px;
            height: 2px;
            background: var(--gold);
        }
        .product-showcase-title {
            font-size: 38px;
            line-height: 1.2;
            color: var(--primary);
            margin-bottom: 16px;
        }
        .product-showcase-copy {
            color: var(--text-muted);
            line-height: 1.7;
            max-width: 680px;
            font-size: 16px;
        }
        .product-showcase-actions {
            display: flex;
            gap: 12px;
            flex-shrink: 0;
        }
        .carousel-btn {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-pill);
            border: 1px solid var(--border-color);
            background: white;
            color: var(--primary);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 22px rgba(15, 46, 92, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .carousel-btn:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(15, 46, 92, 0.2);
        }
        .product-carousel {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: calc((100% - 80px) / 5);
            gap: 20px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            padding: 10px 40px 32px;
            margin: 0 -40px;
            scrollbar-width: thin;
            scrollbar-color: rgba(15, 46, 92, 0.2) transparent;
            -webkit-mask-image: linear-gradient(to right, transparent, black 40px, black calc(100% - 40px), transparent);
            mask-image: linear-gradient(to right, transparent, black 40px, black calc(100% - 40px), transparent);
        }
        .product-carousel::-webkit-scrollbar { height: 8px; }
        .product-carousel::-webkit-scrollbar-track { background: transparent; }
        .product-carousel::-webkit-scrollbar-thumb {
            background: rgba(15, 46, 92, 0.2);
            border-radius: var(--radius-pill);
        }
        .home-product-card {
            scroll-snap-align: start;
            background: #ffffff;
            border: 1px solid rgba(226,232,240,0.8);
            border-radius: 20px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 4px 20px rgba(15, 46, 92, 0.05), 0 1px 3px rgba(15, 46, 92, 0.02);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            min-width: 0;
            position: relative;
            display: flex;
            flex-direction: column;
        }
        .home-product-card::after {
            content: ''; position: absolute; inset: 0; border-radius: 20px;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.5); pointer-events: none; z-index: 2;
        }
        .home-product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 48px rgba(15, 46, 92, 0.12), 0 8px 16px rgba(15, 46, 92, 0.04);
            border-color: rgba(212, 175, 55, 0.3); /* gold subtle border */
        }
        .home-product-media {
            aspect-ratio: 4 / 3;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #f1f5f9, #f8fafc);
        }
        .home-product-media::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0) 40%);
            opacity: 0; transition: opacity 0.4s ease; z-index: 1;
        }
        .home-product-card:hover .home-product-media::after { opacity: 1; }
        .home-product-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 0;
        }
        .home-product-card:hover .home-product-media img { transform: scale(1.08); }
        .home-product-placeholder {
            width: 64px !important;
            height: 64px !important;
            object-fit: contain !important;
            opacity: 0.6;
            position: absolute;
            inset: 0;
            margin: auto;
            transform: none !important;
            filter: grayscale(100%);
            z-index: 0;
        }
        .home-product-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            max-width: calc(100% - 24px);
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
            color: var(--primary);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.4);
            z-index: 2;
        }
        .home-product-body { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; position: relative; z-index: 2; background: white; }
        .home-product-store {
            font-size: 11px; color: var(--gold-dark); font-weight: 800; letter-spacing: 0.5px;
            text-transform: uppercase; margin-bottom: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .home-product-name {
            font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 8px;
            font-family: 'Inter', sans-serif; line-height: 1.4;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 50px;
        }
        .home-product-price {
            font-size: 18px; font-weight: 900; color: #ef4444; margin-bottom: 16px;
            display: flex; align-items: center; gap: 4px;
        }
        .home-product-location {
            display: flex; align-items: center; gap: 6px; font-size: 13px; color: #64748b; margin-top: auto; min-width: 0; font-weight: 500;
        }
        .home-product-location i { color: var(--primary-light); font-size: 12px; }
        .home-product-location span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .product-showcase-footer {
            display: flex;
            justify-content: center;
            margin-top: 18px;
        }
        .product-showcase-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            text-decoration: none;
            padding: 16px 36px;
            border-radius: var(--radius-pill);
            font-size: 15px;
            font-weight: 800;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 20px rgba(15, 46, 92, 0.15);
        }
        .product-showcase-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(15, 46, 92, 0.25);
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
        }

        /* Stats Section */
        .stats-section {
            padding: 80px 24px;
            background:
                radial-gradient(circle at 12% 8%, rgba(16, 185, 129, 0.08), transparent 22rem),
                linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-top: 1px solid rgba(226, 232, 240, 0.7);
            border-bottom: 1px solid rgba(226, 232, 240, 0.7);
        }
        .stats-container {
            max-width: 1280px;
            margin: 0 auto;
        }
        .stats-header {
            text-align: center;
            margin-bottom: 56px;
        }
        .stats-header h3 {
            font-size: 13px;
            font-weight: 700;
            color: var(--gold-dark);
            letter-spacing: 2px;
            margin-bottom: 16px;
            text-transform: uppercase;
            font-family: 'Inter', sans-serif;
            background: rgba(212, 175, 55, 0.1);
            display: inline-block;
            padding: 6px 16px;
            border-radius: var(--radius-pill);
        }
        .stats-header h2 {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary);
        }
        .stats-header p {
            color: var(--text-muted);
            margin-top: 12px;
            font-size: 15px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
            margin-bottom: 64px;
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.84);
            padding: 40px 32px;
            border-radius: 24px; 
            text-align: center;
            border: 1px solid rgba(226, 232, 240, 0.82);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(15, 46, 92, 0.05);
        }
        /* Aksen Batik di Stat Card */
        .stat-card::after {
            content: '';
            position: absolute;
            bottom: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            background-image: var(--batik-jabar);
            background-size: 60px 60px;
            opacity: 0.1;
            border-radius: 50%;
            pointer-events: none;
        }
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-hover);
            border-color: rgba(212, 175, 55, 0.4);
        }
        .stat-card.primary {
            background:
                linear-gradient(135deg, rgba(15, 46, 92, 0.96), rgba(26, 73, 139, 0.95)),
                var(--batik-jabar);
            color: white;
            border: none;
            box-shadow: 0 16px 36px rgba(15, 46, 92, 0.2);
        }
        .stat-card.primary::after {
            opacity: 0.2;
            filter: brightness(0) invert(1); /* Membuat batik putih di card biru */
        }
        .stat-card .stat-icon {
            font-size: 28px;
            margin-bottom: 20px;
            color: var(--gold);
            background: white;
            width: 72px;
            height: 72px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            box-shadow: 0 8px 16px rgba(0,0,0,0.06);
            position: relative;
            z-index: 1;
        }
        .stat-card.primary .stat-icon {
            color: var(--primary);
            background: white;
        }
        .stat-card .stat-icon svg {
            width: 32px;
            height: 32px;
        }
        .stat-value {
            font-size: 40px;
            font-weight: 700;
            margin-bottom: 8px;
            font-family: 'Merriweather', serif;
            position: relative;
            z-index: 1;
            line-height: 1.1;
        }
        .stat-label {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            position: relative;
            z-index: 1;
        }
        .stat-card.primary .stat-label { color: rgba(255,255,255,0.8); }
        .stat-change {
            margin-top: 14px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 5px 16px 5px 6px;
            border-radius: 9999px;
            background: rgba(16, 185, 129, 0.12);
            color: #065f46;
            font-weight: 700;
            font-size: 12px;
            position: relative;
            z-index: 1;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        .stat-change .stat-change-badge {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #ffffff;
            color: #d97706;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }
        .stat-change .stat-change-badge i {
            font-size: 13px !important;
            color: inherit !important;
            margin: 0 !important;
        }
        .stat-card.primary .stat-change { 
            background: rgba(255, 255, 255, 0.18); 
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .stat-card.primary .stat-change .stat-change-badge {
            background: #ffffff;
            color: #0f2e5c;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        /* Chart Section */
        .chart-section {
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(226, 232, 240, 0.86);
            border-radius: 28px;
            padding: 48px;
            display: flex;
            flex-wrap: wrap;
            gap: 60px;
            align-items: center;
            box-shadow: var(--shadow-ring);
        }
        .chart-box {
            flex: 1;
            max-width: 300px;
            margin: 0 auto;
        }
        .legend-box { flex: 1.2; }
        .legend-box h4 {
            color: var(--primary);
            font-family: 'Merriweather', serif;
        }
        .legend-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px dashed var(--border-color);
        }
        .legend-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            margin-right: 16px;
            display: inline-block;
        }
        .legend-label { font-weight: 500; font-size: 14px; color: var(--text-dark); }
        .legend-percent { font-weight: 700; color: var(--primary); font-size: 15px; background: var(--bg-light); padding: 4px 10px; border-radius: var(--radius-sm);}

        /* Warta (News) */
        .warta-section {
            padding: 80px 24px;
            background:
                radial-gradient(circle at right top, rgba(212, 175, 55, 0.12), transparent 20rem),
                var(--bg-light);
        }
        .section-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 32px;
            padding-left: 20px;
            border-left: 4px solid var(--gold);
            border-radius: 2px;
            color: var(--primary);
        }
        .news-grid {
            display: flex;
            gap: 32px;
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 24px;
            scroll-snap-type: x mandatory;
            scroll-padding: 24px;
        }
        .news-grid::-webkit-scrollbar {
            height: 8px;
        }
        .news-grid::-webkit-scrollbar-track {
            background: var(--bg-light); 
            border-radius: var(--radius-pill);
        }
        .news-grid::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: var(--radius-pill);
        }
        .news-grid::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
        .news-card {
            flex: 1 1 calc(25% - 24px);
            min-width: 280px;
            scroll-snap-align: start;
            background: white;
            border-radius: 22px;
            border: 1px solid rgba(226, 232, 240, 0.88);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            text-decoration: none;
            box-shadow: 0 12px 28px rgba(15, 46, 92, 0.06);
        }
        .news-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 40px rgba(15, 46, 92, 0.12);
            border-color: rgba(212, 175, 55, 0.3);
        }
        .news-img-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 22px 22px 0 0;
        }
        .news-card:hover .news-img {
            transform: scale(1.08);
        }
        .news-img {
            height: 240px;
            background-size: cover;
            background-position: center;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .news-img::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(15, 46, 92, 0.8) 0%, rgba(15, 46, 92, 0) 50%);
        }
        .news-category {
            position: absolute;
            top: 16px;
            left: 16px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            color: var(--primary);
            padding: 8px 16px;
            border-radius: var(--radius-pill);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            z-index: 2;
        }
        .news-content { padding: 28px; }
        .news-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 14px;
            line-height: 1.5;
            color: var(--primary);
            font-family: 'Merriweather', serif;
        }
        .news-excerpt {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.6;
            overflow-wrap: anywhere;
            word-break: break-word;
        }
        .news-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--border-color);
            padding-top: 18px;
            margin-top: 20px;
        }
        .news-date {
            font-size: 12px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }
        .news-readmore {
            background: transparent;
            color: var(--primary);
            padding: 8px 0;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            border-bottom: 2px solid transparent;
        }
        .news-readmore i {
            transition: transform 0.3s ease;
            font-size: 11px;
            color: var(--gold);
        }
        .news-card:hover .news-readmore {
            color: var(--gold-dark);
            border-bottom-color: var(--gold);
        }
        .news-card:hover .news-readmore i {
            transform: translateX(6px);
        }

        /* Why Data - Ditambahkan Motif Batik Jabar Emas */
        .why-data {
            background:
                radial-gradient(circle at 15% 25%, rgba(212, 175, 55, 0.22), transparent 18rem),
                linear-gradient(135deg, #0b1f42 0%, var(--primary) 58%, #123b72 100%);
            position: relative;
            margin: 40px 24px;
            border-radius: 30px;
            padding: 64px 48px;
            box-shadow: 0 20px 40px rgba(15, 46, 92, 0.15);
            overflow: hidden;
        }
        .why-data::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: var(--batik-jabar);
            background-size: 120px 120px;
            opacity: 0.25;
            pointer-events: none;
        }
        .why-container {
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 64px;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        .why-content h2 {
            font-size: 32px;
            font-weight: 700;
            color: white;
            margin-bottom: 24px;
        }
        .why-content p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.8;
            margin-bottom: 32px;
            font-size: 15px;
        }
        .why-link {
            color: var(--gold);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            border: 1px solid var(--gold);
            padding: 12px 28px;
            border-radius: var(--radius-pill);
            transition: all 0.3s;
            background: rgba(212, 175, 55, 0.1);
            backdrop-filter: blur(4px);
        }
        .why-link:hover {
            background: var(--gold);
            color: var(--primary);
            transform: translateY(-2px);
        }
        .why-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        .why-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 32px;
            border-radius: var(--radius-md);
            transition: all 0.3s ease;
            backdrop-filter: blur(8px);
        }
        .why-card:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-4px);
            border-color: rgba(212, 175, 55, 0.4);
        }
        .why-card i {
            font-size: 28px;
            color: var(--gold);
            margin-bottom: 16px;
            background: rgba(212, 175, 55, 0.1);
            width: 56px;
            height: 56px;
            line-height: 56px;
            text-align: center;
            border-radius: 50%;
        }
        .why-card h4 {
            color: white;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .why-card p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
        }

        /* Quote */
        .quote-section {
            background:
                linear-gradient(rgba(255,255,255,0.92), rgba(255,255,255,0.92)),
                var(--batik-jabar);
            margin: 40px 24px;
            border-radius: 30px;
            padding: 80px 24px;
            text-align: center;
            box-shadow: var(--shadow-subtle);
            border: 1px solid var(--border-color);
        }
        .quote-icon {
            font-size: 40px;
            color: var(--gold);
            margin-bottom: 24px;
            opacity: 0.8;
        }
        .quote-text {
            font-size: 26px;
            font-weight: 400;
            font-style: italic;
            max-width: 800px;
            margin: 0 auto 32px;
            color: var(--primary);
            line-height: 1.6;
        }
        .quote-author {
            font-weight: 700;
            font-size: 16px;
            color: var(--text-dark);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .quote-title {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 8px;
        }

        /* Footer - Ditambahkan Motif Batik Jabar */
        .footer {
            background:
                radial-gradient(circle at top right, rgba(212, 175, 55, 0.14), transparent 24rem),
                #0b1f42; 
            color: rgba(255, 255, 255, 0.7);
            padding: 80px 24px 32px;
            border-top: 1px solid rgba(255,255,255,0.1);
            border-radius: 40px 40px 0 0; 
            margin-top: 60px;
            position: relative;
            overflow: hidden;
        }
        .footer::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: var(--batik-jabar);
            background-size: 100px 100px;
            opacity: 0.08;
            pointer-events: none;
            z-index: 0;
        }
        .footer-container {
            max-width: 1280px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 64px;
        }
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }
        .footer-logo-img {
            height: 54px;
            width: auto;
            background: white;
            padding: 4px;
            border-radius: var(--radius-sm);
        }
        .footer-logo-text {
            font-size: 20px;
            font-weight: 700;
            color: white;
            font-family: 'Merriweather', serif;
        }
        .footer-about {
            font-size: 14px;
            line-height: 1.8;
            margin-top: 12px;
        }
        .footer h4 {
            color: white;
            font-weight: 600;
            margin-bottom: 24px;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .footer-links { list-style: none; }
        .footer-links li {
            margin-bottom: 14px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
        }
        .footer-links li i {
            flex-shrink: 0;
            margin-top: 4px;
        }
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }
        .footer-links a:hover { color: var(--gold); }
        .social-links {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }
        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s;
            font-size: 15px;
        }
        .social-links a:hover {
            background: var(--gold);
            border-color: var(--gold);
            color: var(--primary);
            transform: translateY(-3px);
        }
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .nav-menu { gap: 14px; }
            .nav-link { font-size: 13px; }
            .btn-login, .btn-register { padding: 10px 20px; font-size: 12px; }
            .logo-text h4 { font-size: 16px; }
        }
        
        @media (max-width: 1024px) {
            .nav-menu { display: none; }
            .mobile-menu-btn { display: block; }
            .hero-container { grid-template-columns: 1fr; text-align: center; gap: 48px; }
            .hero-content h1 { font-size: 52px; }
            .hero-content p { margin: 0 auto 40px; }
            .hero-buttons { justify-content: center; }
            .hero-trust-row { margin: 0 auto; text-align: left; }
            .product-carousel { grid-auto-columns: calc((100% - 48px) / 4); }
            .stats-grid { grid-template-columns: repeat(3, 1fr); }
            .news-card { flex: 0 0 calc(50% - 16px); }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 40px; }
            .why-container { grid-template-columns: 1fr; }
            .why-grid { text-align: left; }
            .why-data, .quote-section { margin: 24px; padding: 60px 32px; }
        }
        @media (max-width: 768px) {
            .product-showcase { padding: 48px 16px; }
            .product-showcase-header { flex-direction: column; align-items: flex-start; gap: 20px; }
            .product-showcase-actions { width: 100%; justify-content: flex-end; }
            .product-showcase-title { font-size: 24px; }
            .product-carousel { grid-auto-columns: minmax(220px, 78vw); }
            .stats-grid, .footer-grid, .why-grid { grid-template-columns: 1fr; gap: 16px; }
            .news-card { flex: 0 0 85%; }
            .news-img { height: 180px; }
            .news-content { padding: 20px; }
            .hero { padding: 100px 16px 40px; }
            .hero-content h1 { font-size: 34px; line-height: 1.12; }
            .hero-content h2 { font-size: 18px; }
            .hero-content p { font-size: 14px; margin-bottom: 24px; }
            .hero-buttons { flex-direction: column; width: 100%; gap: 12px; }
            .hero-buttons .btn-register, .hero-buttons .btn-login { width: 100%; justify-content: center; padding: 12px; }
            .hero-trust-row { grid-template-columns: 1fr; width: 100%; }
            .hero-trust-item { padding: 10px 12px; }
            .logo-card { padding: 24px 18px; max-width: 100%; }
            .logo-img { width: 112px; }
            .logo-card-metrics { margin-top: 20px; }
            .logo img { height: 36px; }
            .logo-text h4 { font-size: 15px; }
            .logo-text p { font-size: 9px; }
            .stat-card { padding: 20px 16px; }
            .stat-card i { width: 56px; height: 56px; line-height: 56px; font-size: 24px; margin-bottom: 16px; }
            .stat-value { font-size: 28px; }
            .stats-header h2 { font-size: 22px; }
            .section-title { font-size: 20px; margin-bottom: 24px; }
            .quote-text { font-size: 16px; }
            .quote-section { padding: 32px 16px; margin: 16px; }
            .why-data { padding: 32px 16px; margin: 16px; }
            .why-card { padding: 20px; }
            .why-card i { width: 44px; height: 44px; line-height: 44px; font-size: 22px; margin-bottom: 12px; }
            .chart-section { flex-direction: column; padding: 24px 16px; text-align: center; gap: 32px; }
            .footer { padding: 48px 16px 24px; border-radius: 20px 20px 0 0; }
            .footer-bottom { flex-direction: column; gap: 16px; text-align: center; padding-top: 24px; }
            .navbar { left: 16px; right: 16px; top: 16px; }
            .chatbot-btn { width: 64px; height: 64px; right: 8px; bottom: 8px; }
            .chatbot-btn.active { width: 54px; height: 54px; }
            .chatbot-window { bottom: 80px; right: 16px; width: calc(100vw - 32px); max-width: none; height: calc(100vh - 100px); }
        }
        
        /* ==========================================================================
           AI CHATBOT WIDGET STYLES (PREMIUM GLASSMORPHISM)
           ========================================================================== */
        .chatbot-btn {
            position: fixed;
            bottom: 0px;
            right: 10px;
            width: 250px;
            height: 250px;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 9999;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: none;
        }
        .chatbot-btn.active {
            width: 150px;
            height: 150px;
            bottom: -5px;
            right: -10px;
            z-index: 10000;
        }
        .chatbot-btn img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
            filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.25));
            transform: scale(1.2);
            transition: all 0.5s ease;
        }
        .chatbot-btn.active img {
            transform: scale(1.1);
        }
        .chatbot-btn:hover {
            transform: scale(1.1) translateY(-8px);
        }
        .chatbot-btn .pulse-ring {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid var(--gold);
            animation: pulse-ring 2s infinite;
            opacity: 0;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.9); opacity: 0.8; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        .chatbot-window {
            position: fixed;
            bottom: 105px;
            right: 30px;
            width: 380px;
            max-width: calc(100vw - 60px);
            height: 580px;
            max-height: calc(100vh - 140px);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(15, 46, 92, 0.15);
            border: 1px solid rgba(226, 232, 240, 0.8);
            z-index: 9998;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transform-origin: bottom right;
            transform: scale(0.85);
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .chatbot-window.active {
            transform: scale(1);
            opacity: 1;
            visibility: visible;
        }

        .chatbot-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            padding: 20px 24px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            position: relative;
        }
        .chatbot-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: var(--batik-jabar);
            background-size: 60px;
            opacity: 0.12;
            pointer-events: none;
        }
        .chatbot-header-info {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }
        .chatbot-avatar {
            width: 38px;
            height: 38px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            border: 1px solid rgba(212, 175, 55, 0.4);
        }
        .chatbot-title {
            font-weight: 700;
            font-size: 15px;
            line-height: 1.2;
            font-family: 'Merriweather', serif;
        }
        .chatbot-status {
            font-size: 11px;
            color: #10b981;
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 2px;
        }
        .chatbot-status-dot {
            width: 6px;
            height: 6px;
            background: #10b981;
            border-radius: 50%;
            box-shadow: 0 0 6px #10b981;
        }
        .chatbot-close {
            background: none;
            border: none;
            color: rgba(255,255,255,0.7);
            font-size: 18px;
            cursor: pointer;
            transition: color 0.2s;
            position: relative;
            z-index: 1;
        }
        .chatbot-close:hover {
            color: white;
        }

        .chatbot-messages {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
            background: rgba(248, 250, 252, 0.6);
        }
        .chatbot-messages::-webkit-scrollbar { width: 6px; }
        .chatbot-messages::-webkit-scrollbar-track { background: transparent; }
        .chatbot-messages::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .chatbot-messages::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .chat-bubble {
            max-width: 85%;
            padding: 12px 16px;
            border-radius: 16px;
            font-size: 13.5px;
            line-height: 1.5;
            position: relative;
            animation: fadeInBubble 0.3s ease;
        }
        @keyframes fadeInBubble {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .chat-bubble.bot {
            background: white;
            color: var(--text-dark);
            align-self: flex-start;
            border: 1px solid var(--border-color);
            border-bottom-left-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.01);
        }
        .chat-bubble.user {
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
            box-shadow: 0 4px 12px rgba(15, 46, 92, 0.15);
        }
        
        .chat-time {
            font-size: 9px;
            margin-top: 5px;
            opacity: 0.6;
            text-align: right;
            display: block;
        }

        .chatbot-input-area {
            padding: 16px 20px;
            background: white;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 12px;
            align-items: flex-end;
        }
        .chatbot-input-wrapper {
            flex: 1;
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 4px 16px;
            transition: all 0.2s;
        }
        .chatbot-input-wrapper:focus-within {
            border-color: var(--primary-light);
            background: white;
            box-shadow: 0 0 0 3px rgba(26, 73, 139, 0.1);
        }
        .chatbot-input {
            width: 100%;
            border: none;
            background: transparent;
            padding: 10px 0;
            font-family: 'Inter', sans-serif;
            font-size: 13.5px;
            color: var(--text-dark);
            resize: none;
            max-height: 120px;
            min-height: 38px;
            outline: none;
        }
        .chatbot-input::placeholder {
            color: #94a3b8;
        }
        .chatbot-send {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gold);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(212, 175, 55, 0.3);
        }
        .chatbot-send:hover {
            background: var(--gold-dark);
            transform: scale(1.05);
        }
        .chatbot-send:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }
        
        .typing-indicator {
            display: flex;
            gap: 4px;
            padding: 8px 12px;
            background: white;
            border-radius: 16px;
            align-self: flex-start;
            border: 1px solid var(--border-color);
            border-bottom-left-radius: 4px;
            animation: fadeInBubble 0.3s ease;
            display: none;
        }
        .typing-indicator.active {
            display: flex;
        }
        .typing-dot {
            width: 6px;
            height: 6px;
            background: #94a3b8;
            border-radius: 50%;
            animation: typing 1.4s infinite ease-in-out both;
        }
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        @keyframes typing {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
        
        /* Chatbot Onboarding CSS */
        .chatbot-onboarding {
            flex: 1;
            padding: 30px 24px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .chatbot-onboarding h4 {
            font-size: 16px;
            margin-bottom: 20px;
            color: var(--primary);
            font-weight: 700;
            font-family: 'Merriweather', serif;
            text-align: center;
            line-height: 1.4;
        }
        .onboarding-field {
            margin-bottom: 16px;
        }
        .onboarding-field label {
            display: block;
            font-size: 11px;
            color: var(--text-dark);
            margin-bottom: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .onboarding-input {
            width: 100%;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            font-size: 13.5px;
            outline: none;
            font-family: 'Inter', sans-serif;
            background: var(--bg-light);
            transition: all 0.2s;
        }
        .onboarding-input:focus {
            border-color: var(--primary-light);
            background: white;
            box-shadow: 0 0 0 3px rgba(26, 73, 139, 0.15);
        }
        .onboarding-btn {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(15, 46, 92, 0.2);
            text-align: center;
            margin-top: 10px;
        }
        .onboarding-btn:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(15, 46, 92, 0.3);
        }

        /* Unified Chatbot FAQ & AI Section Styles */
        .kelurahan-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            margin-top: 40px;
        }
        .kelurahan-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(15, 46, 92, 0.04);
            border: 1px solid rgba(15, 46, 92, 0.05);
            text-align: center;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .kelurahan-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(15, 46, 92, 0.1);
            border-color: rgba(16, 185, 129, 0.2);
        }
        .kelurahan-icon {
            width: 56px;
            height: 56px;
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 16px;
            transition: all 0.3s ease;
        }
        .kelurahan-card:hover .kelurahan-icon {
            background: #10b981;
            color: white;
            transform: scale(1.1);
        }
        .kelurahan-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }
        .kelurahan-count {
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 6px;
        }
        .kelurahan-count-number {
            font-size: 32px;
            font-weight: 800;
            color: var(--primary);
        }
        .kelurahan-count-label {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .faq-selection-container {
            background: white;
            border-radius: 16px;
            padding: 16px;
            border: 1px solid var(--border-color);
            margin-bottom: 8px;
            box-shadow: 0 4px 12px rgba(15, 46, 92, 0.02);
            animation: fadeInBubble 0.3s ease;
            align-self: stretch;
        }
        .faq-selection-title {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .faq-buttons-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .faq-btn {
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--text-dark);
            cursor: pointer;
            text-align: left;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .faq-btn:hover {
            background: rgba(15, 46, 92, 0.05);
            border-color: rgba(15, 46, 92, 0.2);
            transform: translateY(-1px);
        }
        .chat-section-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 16px 0 8px;
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            align-self: stretch;
        }
        .chat-section-divider::before,
        .chat-section-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px dashed var(--border-color);
        }
        .chat-section-divider:not(:empty)::before {
            margin-right: .5em;
        }
        .chat-section-divider:not(:empty)::after {
            margin-left: .5em;
        }

        /* ==========================================================================
           PETA INTERAKTIF UMKM STYLES
           ========================================================================== */
        .map-section {
            padding: 80px 24px;
            background: white;
            border-top: 1px solid var(--border-color);
        }
        .map-container {
            max-width: 1280px;
            margin: 0 auto;
        }
        .map-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .map-header h3 {
            font-size: 13px;
            font-weight: 700;
            color: var(--gold-dark);
            letter-spacing: 2px;
            margin-bottom: 16px;
            text-transform: uppercase;
            font-family: 'Inter', sans-serif;
            background: rgba(212, 175, 55, 0.1);
            display: inline-block;
            padding: 6px 16px;
            border-radius: var(--radius-pill);
        }
        .map-header h2 {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary);
        }
        .map-header p {
            color: var(--text-muted);
            margin-top: 12px;
            font-size: 15px;
        }
        .map-wrapper {
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-subtle);
        }
        .map-toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 24px;
            background: white;
            border-bottom: 1px solid var(--border-color);
            flex-wrap: wrap;
        }
        .map-toolbar-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .map-toolbar-label i {
            color: var(--gold);
        }
        .map-filter-select {
            padding: 8px 14px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            font-size: 13px;
            outline: none;
            background: var(--bg-light);
            cursor: pointer;
            font-weight: 500;
            color: var(--text-dark);
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
        }
        .map-filter-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(26, 73, 139, 0.1);
        }
        .map-search-container {
            position: relative;
            flex: 1;
            min-width: 200px;
            max-width: 300px;
        }
        .map-search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 13px;
            pointer-events: none;
        }
        .map-search-input {
            width: 100%;
            padding: 8px 14px 8px 36px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            font-size: 13px;
            outline: none;
            background: var(--bg-light);
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
        }
        .map-search-input:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(26, 73, 139, 0.1);
            background: white;
        }
        .map-counter {
            margin-left: auto;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
            background: rgba(15, 46, 92, 0.06);
            padding: 8px 16px;
            border-radius: var(--radius-pill);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .map-counter .dot-live {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            animation: pulse-dot 2s infinite;
        }
        #umkmMap {
            height: 520px;
            width: 100%;
            background: #e2e8f0;
        }
        .map-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 24px;
            background: white;
            border-top: 1px solid var(--border-color);
            flex-wrap: wrap;
            gap: 12px;
        }
        .map-legend {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }
        .map-legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 500;
            color: var(--text-muted);
        }
        .map-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .map-info-note {
            font-size: 11px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .map-info-note i {
            color: var(--gold);
        }

        /* Custom Leaflet Popup Styles */
        .umkm-popup {
            font-family: 'Inter', sans-serif;
            min-width: 240px;
        }
        .umkm-popup-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .umkm-popup-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid #e2e8f0;
            flex-shrink: 0;
        }
        .umkm-popup-avatar-placeholder {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            font-weight: 700;
            flex-shrink: 0;
        }
        .umkm-popup-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--primary);
            line-height: 1.3;
        }
        .umkm-popup-badges {
            display: flex;
            gap: 6px;
            margin-top: 4px;
            flex-wrap: wrap;
        }
        .umkm-popup-badge {
            font-size: 10px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 999px;
            background: rgba(212, 175, 55, 0.12);
            color: var(--gold-dark);
            white-space: nowrap;
        }
        .umkm-popup-badge.sektor {
            background: rgba(15, 46, 92, 0.08);
            color: var(--primary);
        }
        .umkm-popup-detail {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.6;
        }
        .umkm-popup-detail i {
            width: 14px;
            text-align: center;
            color: var(--gold);
            margin-right: 6px;
        }
        .umkm-popup-detail div {
            margin-bottom: 4px;
        }

        /* Leaflet Override Styles */
        .leaflet-popup-content-wrapper {
            border-radius: 16px !important;
            padding: 0 !important;
            box-shadow: 0 12px 40px rgba(15, 46, 92, 0.15) !important;
            border: 1px solid #e2e8f0 !important;
        }
        .leaflet-popup-content {
            margin: 16px !important;
            line-height: 1.5 !important;
        }
        .leaflet-popup-tip {
            box-shadow: 0 3px 14px rgba(15, 46, 92, 0.1) !important;
        }
        .marker-cluster-small {
            background-color: rgba(15, 46, 92, 0.15) !important;
        }
        .marker-cluster-small div {
            background-color: var(--primary) !important;
            color: white !important;
            font-family: 'Inter', sans-serif !important;
            font-weight: 700 !important;
        }
        .marker-cluster-medium {
            background-color: rgba(212, 175, 55, 0.2) !important;
        }
        .marker-cluster-medium div {
            background-color: var(--gold-dark) !important;
            color: white !important;
            font-family: 'Inter', sans-serif !important;
            font-weight: 700 !important;
        }
        .marker-cluster-large {
            background-color: rgba(16, 185, 129, 0.2) !important;
        }
        .marker-cluster-large div {
            background-color: #10b981 !important;
            color: white !important;
            font-family: 'Inter', sans-serif !important;
            font-weight: 700 !important;
        }

        @media (max-width: 768px) {
            #umkmMap { height: 380px; }
            .map-header h2 { font-size: 22px; }
            .map-toolbar { padding: 12px 16px; }
            .map-counter { margin-left: 0; width: 100%; justify-content: center; }
            .map-footer { justify-content: center; text-align: center; }
            .map-legend { justify-content: center; }
        }

        /* --- Ensure Mobile Overrides Apply Last --- */
        @media (max-width: 768px) {
            .chatbot-btn { width: 64px !important; height: 64px !important; right: 8px !important; bottom: 8px !important; }
            .chatbot-btn.active { width: 54px !important; height: 54px !important; bottom: 4px !important; right: 8px !important; }
        }

        @media (max-width: 640px) {
            html,
            body {
                max-width: 100%;
                overflow-x: hidden;
            }

            a,
            button,
            input,
            select,
            textarea {
                touch-action: manipulation;
            }

            a,
            button {
                min-height: 44px;
            }

            input,
            select,
            textarea {
                min-height: 44px;
                font-size: 16px;
            }

            .navbar {
                left: 8px;
                right: 8px;
                top: 12px;
            }

            .nav-container {
                padding: 8px 12px;
                gap: 8px;
            }

            .mobile-menu {
                width: min(86vw, 320px);
                max-width: calc(100vw - 24px);
            }

            .mobile-menu a {
                display: flex;
                align-items: center;
                min-height: 44px;
            }

            .hero,
            .stats-section,
            .warta-section,
            .map-section,
            .footer {
                width: 100%;
                max-width: 100vw;
            }

            .hero-content .official-tag {
                font-size: 10px;
                letter-spacing: 0.6px;
                padding: 8px 12px;
            }

            .hero-buttons .btn-login {
                margin-left: 0 !important;
            }

            .stats-header h2,
            .map-header h2,
            .product-showcase-title {
                font-size: 24px;
                line-height: 1.25;
            }
        }
    </style>
</head>
<body>

<div class="top-ribbon"></div>

