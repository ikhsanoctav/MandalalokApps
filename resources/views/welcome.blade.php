<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mandalaloka - Sistem Pendataan UMKM | Kecamatan Mandalajati</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo_Mandalaloka.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Merriweather:ital,wght@0,400;0,700;0,900;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    
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

        .hero-content .badge { animation: fadeInUpHero 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards 0.1s; opacity: 0; }
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
            opacity: 0.34;
            pointer-events: none;
        }
        /* Gradient fade agar teks tetap terbaca jelas */
        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, rgba(255,255,255,0.96) 0%, rgba(255,255,255,0.74) 48%, rgba(255,255,255,0.42) 100%),
                linear-gradient(180deg, transparent 0%, rgba(248,250,252,0.88) 100%);
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
        .hero-content .badge {
            background: rgba(212, 175, 55, 0.1);
            color: var(--gold-dark);
            padding: 9px 18px;
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: var(--radius-pill);
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 10px 24px rgba(212, 175, 55, 0.12);
            background-image: linear-gradient(to right, rgba(255,255,255,0.94), rgba(255,255,255,0.9)), var(--batik-jabar);
            background-size: 40px 40px;
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
        .product-showcase {
            padding: 40px 24px 24px;
            background:
                linear-gradient(180deg, rgba(248,250,252,0.35) 0%, var(--bg-light) 100%);
        }
        .product-showcase-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 40px 0 56px;
        }
        .product-showcase-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 24px;
        }
        .product-showcase-eyebrow {
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gold-dark);
            margin-bottom: 10px;
        }
        .product-showcase-title {
            font-size: 34px;
            line-height: 1.2;
            color: var(--primary);
            margin-bottom: 10px;
        }
        .product-showcase-copy {
            color: var(--text-muted);
            line-height: 1.7;
            max-width: 680px;
            font-size: 15px;
        }
        .product-showcase-actions {
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }
        .carousel-btn {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-pill);
            border: 1px solid var(--border-color);
            background: white;
            color: var(--primary);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 22px rgba(15, 46, 92, 0.08);
            transition: all 0.25s ease;
        }
        .carousel-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }
        .product-carousel {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: calc((100% - 64px) / 5);
            gap: 16px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            padding: 4px 2px 18px;
            scrollbar-width: thin;
            scrollbar-color: rgba(15, 46, 92, 0.28) transparent;
        }
        .product-carousel::-webkit-scrollbar { height: 8px; }
        .product-carousel::-webkit-scrollbar-track { background: transparent; }
        .product-carousel::-webkit-scrollbar-thumb {
            background: rgba(15, 46, 92, 0.24);
            border-radius: var(--radius-pill);
        }
        .home-product-card {
            scroll-snap-align: start;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            box-shadow: var(--shadow-subtle);
            transition: all 0.3s ease;
            min-width: 0;
        }
        .home-product-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-hover);
            border-color: rgba(26, 73, 139, 0.28);
        }
        .home-product-media {
            aspect-ratio: 4 / 3;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(15, 46, 92, 0.08), rgba(212, 175, 55, 0.14));
        }
        .home-product-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.45s ease;
        }
        .home-product-card:hover .home-product-media img { transform: scale(1.05); }
        .home-product-placeholder {
            width: 76px !important;
            height: 76px !important;
            object-fit: contain !important;
            opacity: 0.78;
            position: absolute;
            inset: 0;
            margin: auto;
            transform: none !important;
        }
        .home-product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            max-width: calc(100% - 20px);
            padding: 6px 10px;
            border-radius: var(--radius-pill);
            background: rgba(255, 255, 255, 0.94);
            color: var(--primary);
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            box-shadow: 0 8px 18px rgba(15, 46, 92, 0.12);
        }
        .home-product-body { padding: 14px; }
        .home-product-store {
            color: var(--gold-dark);
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 5px;
        }
        .home-product-name {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            font-size: 16px;
            line-height: 1.35;
            min-height: 43px;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .home-product-price {
            color: #d94b2b;
            font-size: 15px;
            font-weight: 900;
            margin-bottom: 10px;
        }
        .home-product-location {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-muted);
            font-size: 12px;
            min-width: 0;
        }
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
            background: var(--primary);
            color: white;
            text-decoration: none;
            padding: 13px 22px;
            border-radius: var(--radius-pill);
            font-size: 14px;
            font-weight: 800;
            transition: all 0.25s ease;
        }
        .product-showcase-link:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
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
        .stat-card i {
            font-size: 36px;
            margin-bottom: 20px;
            color: var(--gold);
            background: white;
            width: 72px;
            height: 72px;
            line-height: 72px;
            border-radius: 50%;
            box-shadow: 0 8px 16px rgba(0,0,0,0.06);
            position: relative;
            z-index: 1;
        }
        .stat-card.primary i {
            color: var(--primary);
            background: white;
        }
        .stat-value {
            font-size: 40px;
            font-weight: 700;
            margin-bottom: 8px;
            font-family: 'Merriweather', serif;
            position: relative;
            z-index: 1;
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
            font-size: 13px;
            margin-top: 12px;
            color: #10b981;
            font-weight: 500;
            background: rgba(16, 185, 129, 0.1);
            display: inline-block;
            padding: 4px 12px;
            border-radius: var(--radius-pill);
            position: relative;
            z-index: 1;
        }
        .stat-card.primary .stat-change { 
            color: #fff; 
            background: rgba(255,255,255,0.2); 
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
            flex: 0 0 calc(33.333% - 22px);
            min-width: 300px;
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
        @media (max-width: 1024px) {
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
            .nav-menu { display: none; }
            .mobile-menu-btn { display: block; }
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

            .hero-content .badge {
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

<nav class="navbar" id="navbar">
    <div class="nav-container">
        <div class="logo">
            <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo Mandalaloka">
            <div class="logo-text">
                <h4>Mandalaloka</h4>
                <p>Pendataan UMKM</p>
            </div>
        </div>
        
        <div class="nav-menu">
            <a href="#home" class="nav-link active">Beranda</a>
            <a href="#statistik" class="nav-link">Data UMKM</a>
            <a href="#produk-umkm" class="nav-link">Katalog UMKM</a>
            <a href="#peta-umkm" class="nav-link">Peta</a>
            <a href="#bantuan" class="nav-link">Warta</a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-register">Masuk ke Dasbor</a>
                <a href="{{ route('logout') }}" class="btn-login" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Keluar
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-login">Masuk</a>
                <a href="{{ route('register') }}" class="btn-register">Pendaftaran UMKM</a>
            @endif
        </div>
        
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<div class="mobile-menu-overlay" id="mobileOverlay"></div>
<div class="mobile-menu" id="mobileMenu">
    <button class="mobile-close" id="closeMenuBtn"><i class="fas fa-times"></i></button>
    <br><br>
    <a href="#home" class="mobile-nav-link">Beranda</a>
    <a href="#statistik" class="mobile-nav-link">Data UMKM</a>
    <a href="#produk-umkm" class="mobile-nav-link">Katalog UMKM</a>
    <a href="#peta-umkm" class="mobile-nav-link">Peta UMKM</a>
    <a href="#bantuan" class="mobile-nav-link">Warta</a>
    @auth
        <a href="{{ route('dashboard') }}" class="mobile-nav-link">Dasbor Admin</a>
        <a href="{{ route('logout') }}" class="mobile-nav-link" 
           onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
            Keluar
        </a>
        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @else
        <a href="{{ route('login') }}" class="mobile-nav-link">Masuk</a>
        <a href="{{ route('register') }}" class="mobile-nav-link" style="color: var(--gold-dark);">Pendaftaran UMKM</a>
    @endif
</div>

<section class="hero" id="home">
    <div class="hero-container">
        <div class="hero-content stagger">
            <div class="badge">
                <i class="fas fa-shield-alt"></i> Portal Resmi Kecamatan Mandalajati
            </div>
            <h1>Mandalaloka:<br><span>Portal Pendataan UMKM</span></h1>
            <h2>Kecamatan Mandalajati</h2>
            <p>Sistem basis data dan informasi kelola UMKM terintegrasi di lingkungan Kecamatan Mandalajati. Daftarkan usaha Anda untuk mendapatkan fasilitas dan pembinaan program pemerintah secara tepat sasaran.</p>
            <div class="hero-buttons">
                <a href="{{ route('register') }}" class="btn-register">
                    Mulai Pendaftaran <i class="fas fa-arrow-right" style="margin-left: 6px;"></i>
                </a>
                <a href="#statistik" class="btn-login">
                    Tinjau Basis Data
                </a>
                <a href="{{ route('katalog.umkm') }}" class="btn-login" style="margin-left: 8px;">
                    Lihat Katalog UMKM
                </a>
            </div>
            <div class="hero-trust-row">
                <div class="hero-trust-item">
                    <span class="hero-trust-icon"><i class="fas fa-database"></i></span>
                    <div>
                        <strong>Data Terpadu</strong>
                        <span>RT/RW sampai kecamatan</span>
                    </div>
                </div>
                <div class="hero-trust-item">
                    <span class="hero-trust-icon"><i class="fas fa-location-dot"></i></span>
                    <div>
                        <strong>Peta UMKM</strong>
                        <span>Sebaran usaha aktif</span>
                    </div>
                </div>
                <div class="hero-trust-item">
                    <span class="hero-trust-icon"><i class="fas fa-store"></i></span>
                    <div>
                        <strong>Katalog Lokal</strong>
                        <span>Produk warga Mandalajati</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="hero-image">
            <div class="logo-card">
                <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo Mandalaloka" class="logo-img">
                <p style="margin-top: 24px; font-weight: 600; color: var(--primary); font-family: 'Merriweather', serif;">Kecamatan Mandalajati</p>
                <p style="font-size: 12px; color: var(--text-muted); margin-top: 4px; background: var(--bg-light); display: inline-block; padding: 4px 12px; border-radius: var(--radius-pill); position: relative; z-index: 2;">Pemerintah Kota Bandung</p>
                <div class="logo-card-metrics">
                    <div class="logo-card-metric">
                        <strong>{{ isset($kelurahans) ? $kelurahans->sum('umkm_count') : '--' }}</strong>
                        <span>UMKM</span>
                    </div>
                    <div class="logo-card-metric">
                        <strong>{{ isset($kelurahans) ? $kelurahans->count() : '--' }}</strong>
                        <span>Kelurahan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="stats-section" id="statistik">
    <div class="stats-container">
        <div class="stats-header">
            <h3>Pusat Data Terpadu</h3>
            <h2>Visualisasi Pertumbuhan UMKM <span style="font-size: 14px; font-weight: 600; background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 6px 12px; border-radius: var(--radius-pill); vertical-align: middle; margin-left: 12px; display: inline-flex; align-items: center; gap: 8px;"><span style="display: inline-block; width: 8px; height: 8px; background: #10b981; border-radius: 50%; animation: pulse-dot 2s infinite;"></span> Live</span></h2>
            <p>Transparansi basis data pelaku usaha mikro, kecil, dan menengah di Kecamatan Mandalajati.</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-chart-line"></i>
                <div class="stat-value" id="growthValue">--</div>
                <div class="stat-label">Bina Usaha Baru</div>
                <div class="stat-change"><i class="fas fa-arrow-up"></i> Data Bulan Terakhir</div>
            </div>
            <div class="stat-card primary">
                <i class="fas fa-building"></i>
                <div class="stat-value" id="totalUmkmValue">--</div>
                <div class="stat-label">Total UMKM Terdaftar</div>
                <div class="stat-change"><i class="fas fa-check-circle"></i> Data Tervalidasi</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-tags"></i>
                <div class="stat-value" id="totalSektorValue">--</div>
                <div class="stat-label">Sektor Usaha</div>
                <div class="stat-change"><i class="fas fa-list-ul"></i> Klasifikasi Aktif</div>
            </div>
        </div>

        <div class="chart-section">
            <div class="chart-box">
                <canvas id="sectorChart" width="300" height="300"></canvas>
            </div>
            <div class="legend-box">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <h4 style="font-size: 20px;">Distribusi UMKM</h4>
                    <select id="chartFilter" onchange="loadDataFromDatabase()" style="padding: 6px 12px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 13px; outline: none; background: var(--bg-light); cursor: pointer; font-weight: 600; color: var(--primary);">
                        <option value="kategori">Berdasarkan Kategori</option>
                        <option value="sektor">Berdasarkan Sektor</option>
                    </select>
                </div>
                <div id="legendList">
                    <div class="legend-item">
                        <div><span class="legend-dot" style="background: #e2e8f0;"></span><span class="legend-label">Menyinkronkan data...</span></div>
                    </div>
                </div>
                <div style="margin-top: 24px; padding-top: 20px; border-top: 1px dashed var(--border-color);">
                    <p style="font-size: 13px; color: var(--text-muted); background: var(--bg-light); padding: 12px 16px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                        <i class="fas fa-info-circle" style="color: var(--primary); margin-right: 6px;"></i> 
                        Sektor dominan: <strong id="topKategori" style="color: var(--text-dark);">-</strong> 
                        (<span id="topKategoriTotal">0</span> entitas terdaftar)
                    </p>
                </div>
            </div>
        </div>
        
        <div class="kelurahan-grid">
            @foreach($kelurahans as $kel)
            <div class="kelurahan-card">
                <div class="kelurahan-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4 class="kelurahan-name">Kelurahan {{ $kel->nama_kelurahan }}</h4>
                <div class="kelurahan-count">
                    <span class="kelurahan-count-number">{{ $kel->umkm_count }}</span>
                    <span class="kelurahan-count-label">UMKM</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@if(isset($produkUnggulan) && $produkUnggulan->count() > 0)
<section class="product-showcase" id="produk-umkm">
    <div class="product-showcase-container">
        <div class="product-showcase-header">
            <div>
                <div class="product-showcase-eyebrow">Katalog Pilihan</div>
                <h2 class="product-showcase-title">Produk Lokal Mandalajati</h2>
                <p class="product-showcase-copy">Beberapa produk dari UMKM Mandalajati yang dapat langsung Anda jelajahi melalui katalog Mandalaloka.</p>
            </div>
            <div class="product-showcase-actions">
                <button type="button" class="carousel-btn" onclick="scrollProductCarousel(-1)" aria-label="Produk sebelumnya">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button type="button" class="carousel-btn" onclick="scrollProductCarousel(1)" aria-label="Produk berikutnya">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <div class="product-carousel" id="homeProductCarousel">
            @foreach($produkUnggulan as $produk)
                <a href="{{ route('katalog.umkm', ['search' => $produk->nama_produk]) }}" class="home-product-card">
                    <div class="home-product-media">
                        @if($produk->foto_produk && count($produk->foto_produk) > 0)
                            <img src="{{ Storage::url($produk->foto_produk[0]) }}" alt="{{ $produk->nama_produk }}" loading="lazy">
                        @else
                            <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Produk Mandalaloka" class="home-product-placeholder" loading="lazy">
                        @endif
                        <div class="home-product-badge">{{ $produk->umkm->sektor->nama_sektor ?? 'UMKM' }}</div>
                    </div>
                    <div class="home-product-body">
                        <div class="home-product-store">{{ $produk->umkm->nama_usaha ?? 'UMKM Mandalajati' }}</div>
                        <h3 class="home-product-name">{{ $produk->nama_produk }}</h3>
                        <div class="home-product-price">
                            {{ $produk->harga ? 'Rp ' . number_format($produk->harga, 0, ',', '.') : 'Harga hubungi penjual' }}
                        </div>
                        <div class="home-product-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ optional(optional($produk->umkm->pemilik)->kelurahanRel)->nama_kelurahan ?? optional($produk->umkm->pemilik)->kelurahan ?? 'Mandalajati' }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="product-showcase-footer">
            <a href="{{ route('katalog.umkm') }}" class="product-showcase-link">
                Lihat Semua Produk <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

<section class="map-section" id="peta-umkm">
    <div class="map-container">
        <div class="map-header">
            <h3>Peta Interaktif</h3>
            <h2>Sebaran Lokasi UMKM Kecamatan Mandalajati</h2>
            <p>Temukan UMKM terdaftar di sekitar Anda. Klik pin untuk melihat detail usaha.</p>
        </div>

        <div class="map-wrapper">
            <div class="map-toolbar">
                <span class="map-toolbar-label"><i class="fas fa-filter"></i> Filter:</span>
                
                <div class="map-search-container">
                    <i class="fas fa-search map-search-icon"></i>
                    <input type="text" id="mapSearchInput" class="map-search-input" placeholder="Cari nama UMKM..." oninput="filterMapMarkers()">
                </div>

                <select id="mapFilterSektor" class="map-filter-select" onchange="filterMapMarkers()">
                    <option value="">Semua Sektor</option>
                </select>
                <select id="mapFilterKelurahan" class="map-filter-select" onchange="filterMapMarkers()">
                    <option value="">Semua Kelurahan</option>
                </select>
                <div class="map-counter">
                    <span class="dot-live"></span>
                    <span id="mapMarkerCount">0</span> UMKM Ditemukan
                </div>
            </div>

            <div id="umkmMap"></div>

            <div class="map-footer">
                <div class="map-legend">
                    <div class="map-legend-item">
                        <span class="map-legend-dot" style="background: #0f2e5c;"></span> UMKM Aktif
                    </div>
                    <div class="map-legend-item">
                        <span class="map-legend-dot" style="background: #d4af37;"></span> Cluster Area
                    </div>
                </div>
                <div class="map-info-note">
                    <i class="fas fa-info-circle"></i> Data diperbarui otomatis dari database UMKM terdaftar
                </div>
            </div>
        </div>
    </div>
</section>

<section class="warta-section" id="bantuan">
    <div class="stats-container">
        <h2 class="section-title">Warta Dinas & Informasi Publik</h2>
        
        @php $berita = \App\Models\Berita::where('status', 'published')->where('published_at', '<=', now())->latest('published_at')->take(10)->get(); @endphp
        
        @if(isset($berita) && $berita->count() > 0)
            <div class="news-grid">
                @foreach($berita as $b)
                <a href="{{ route('warta.show', $b->id) }}" class="news-card">
                    <div class="news-img-wrapper">
                        <div class="news-img" style="background-image: url('{{ $b->gambar == 'news_default.jpg' ? 'https://picsum.photos/seed/'.$b->id.'/600/400' : Storage::url($b->gambar) }}');"></div>
                        <span class="news-category">{{ $b->penulis }}</span>
                    </div>
                    <div class="news-content">
                        <h3 class="news-title">{{ $b->judul }}</h3>
                        <p class="news-excerpt">{{ Str::limit(strip_tags($b->konten), 100) }}</p>
                        <div class="news-meta">
                            <div class="news-date">
                                <i class="fas fa-calendar-alt"></i>
                                {{ date('d M Y', strtotime($b->published_at)) }}
                            </div>
                            <span class="news-readmore">Baca <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @else
            <div class="news-grid">
                <a href="#" class="news-card">
                    <div class="news-img-wrapper">
                        <div class="news-img" style="background-image: url('https://picsum.photos/seed/default1/600/400');"></div>
                        <span class="news-category">Program Pemerintah</span>
                    </div>
                    <div class="news-content">
                        <h3 class="news-title">Penyerahan Dana Hibah Transformasi UMKM Tahap I</h3>
                        <p class="news-excerpt">Alokasi program hibah pemerintah untuk mendorong percepatan transformasi digital usaha mikro di wilayah Mandalajati.</p>
                        <div class="news-meta">
                            <div class="news-date"><i class="fas fa-calendar-alt"></i> 15 Mar 2026</div>
                            <span class="news-readmore">Baca <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
                <a href="#" class="news-card">
                    <div class="news-img-wrapper">
                        <div class="news-img" style="background-image: url('https://picsum.photos/seed/default2/600/400');"></div>
                        <span class="news-category">Rilis Publikasi</span>
                    </div>
                    <div class="news-content">
                        <h3 class="news-title">Pendataan Sensus UMKM Triwulan Pertama 2026</h3>
                        <p class="news-excerpt">Laporan resmi peningkatan cakupan data pelaku usaha mandiri melalui portal Mandalaloka yang terintegrasi secara digital.</p>
                        <div class="news-meta">
                            <div class="news-date"><i class="fas fa-calendar-alt"></i> 10 Mar 2026</div>
                            <span class="news-readmore">Baca <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
                <a href="#" class="news-card">
                    <div class="news-img-wrapper">
                        <div class="news-img" style="background-image: url('https://picsum.photos/seed/default3/600/400');"></div>
                        <span class="news-category">Bimbingan Teknis</span>
                    </div>
                    <div class="news-content">
                        <h3 class="news-title">Bimtek Standarisasi Mutu dan Penerbitan NIB</h3>
                        <p class="news-excerpt">Penyelenggaraan bimbingan teknis bagi pemilik usaha terkait legalitas, standarisasi produk, dan integrasi teknologi pasar digital.</p>
                        <div class="news-meta">
                            <div class="news-date"><i class="fas fa-calendar-alt"></i> 05 Mar 2026</div>
                            <span class="news-readmore">Baca <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
            </div>
        @endif
    </div>
</section>

<div class="why-data">
    <div class="why-container">
        <div class="why-content">
            <h2>Urgensi Registrasi UMKM Daerah</h2>
            <p>Mandalaloka bertindak sebagai sistem tata kelola administrasi UMKM tingkat kecamatan yang terstruktur. Pendaftaran unit usaha melalui portal ini merupakan tahapan esensial guna memastikan validitas data untuk pengajuan perizinan, akses permodalan, serta fasilitasi program strategis dari Pemerintah Kota maupun Pusat.</p>
            <a href="#" class="why-link">Tinjau Prosedur Pendaftaran <i class="fas fa-chevron-right" style="font-size: 10px; margin-left: 4px;"></i></a>
        </div>
        <div class="why-grid">
            <div class="why-card">
                <i class="fas fa-laptop-house"></i>
                <h4>Integrasi Digital</h4>
                <p>Pengisian formulir kelengkapan entitas usaha secara mandiri melalui portal daring resmi.</p>
            </div>
            <div class="why-card">
                <i class="fas fa-stamp"></i>
                <h4>Validasi Dokumen</h4>
                <p>Pencatatan data usaha dibuat langsung aktif setelah pelaku UMKM melengkapi informasi wajib.</p>
            </div>
            <div class="why-card">
                <i class="fas fa-database"></i>
                <h4>Pusat Informasi</h4>
                <p>Rekapitulasi data yang tersaji secara transparan, akurat, dan dapat dipertanggungjawabkan.</p>
            </div>
            <div class="why-card">
                <i class="fas fa-handshake"></i>
                <h4>Fasilitasi Program</h4>
                <p>Pemanfaatan pangkalan data sebagai acuan penyaluran bantuan serta program binaan.</p>
            </div>
        </div>
    </div>
</div>

<div class="quote-section">
    <div class="quote-icon"><i class="fas fa-quote-right"></i></div>
    <div class="quote-text">
        "Basis data yang presisi adalah fondasi mutlak dalam melahirkan kebijakan publik yang berkeadilan dan tepat sasaran. Melalui Mandalaloka, kita tingkatkan derajat UMKM lokal ke kancah yang lebih luas."
    </div>
    <div class="quote-author">Camat Mandalajati</div>
    <div class="quote-title">Pemerintah Kota Bandung</div>
</div>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-grid">
            <div>
                <div class="footer-logo">
                    <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo Mandalaloka" class="footer-logo-img">
                    <div>
                        <span class="footer-logo-text">Mandalaloka</span>
                        <p style="font-size: 10px; color: var(--gold); margin-top: 2px; text-transform: uppercase; letter-spacing: 1px;">Sistem Informasi Resmi</p>
                    </div>
                </div>
                <p class="footer-about">Portal Layanan dan Sistem Informasi Pengelolaan Data UMKM Terpadu di Lingkungan Kecamatan Mandalajati, Pemerintah Kota Bandung.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div>
                <h4>Tautan Lintas</h4>
                <ul class="footer-links">
                    <li><a href="#home">Beranda Utama</a></li>
                    <li><a href="#statistik">Basis Data UMKM</a></li>
                    <li><a href="#statistik">Statistik Sektoral</a></li>
                    <li><a href="#bantuan">Publikasi & Warta</a></li>
                </ul>
            </div>
            <div>
                <h4>Layanan Publik</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('register') }}">Registrasi Entitas Baru</a></li>
                    <li><a href="javascript:void(0)" onclick="askChatbot('daftar')">Panduan Penggunaan</a></li>
                    <li><a href="javascript:void(0)" onclick="askChatbot('regulasi')">Regulasi & Surat Edaran</a></li>
                    <li><a href="javascript:void(0)" onclick="askChatbot('pengaduan')">Pengaduan Masyarakat</a></li>
                </ul>
            </div>
            <div>
                <h4>Sekretariat</h4>
                <ul class="footer-links">
                    <li><i class="fas fa-map-marker-alt" style="width: 16px; color: var(--gold);"></i> Kantor Kecamatan Mandalajati, Jl. Pasir Impun No.33, Karang Pamulang, Kec. Mandalajati, Kota Bandung, Jawa Barat 40194</li>
                    <li><i class="fas fa-phone" style="width: 16px; color: var(--gold);"></i> (022) 1234567</li>
                    <li><i class="fas fa-envelope" style="width: 16px; color: var(--gold);"></i> humas@mandalajati.bandung.go.id</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Pemerintah Kecamatan Mandalajati. Hak Cipta Dilindungi Undang-Undang.</p>
            <p style="color: var(--gold); font-weight: 600;">Sistem Terintegrasi Mandalaloka v1.0</p>
        </div>
    </div>
</footer>


<script>
    let sectorChart = null;

    function setLegend(html) {
        const legendList = document.getElementById('legendList');
        if (legendList) legendList.innerHTML = html;
    }

    function legendItem(color, label) {
        return `<div class="legend-item">
            <div><span class="legend-dot" style="background:${color}"></span><span class="legend-label">${label}</span></div>
        </div>`;
    }

    async function loadDataFromDatabase() {
        try {
            const filterValue = document.getElementById('chartFilter') ? document.getElementById('chartFilter').value : 'kategori';
            const chartResponse = await fetch('/api/chart-data?filter=' + filterValue);
            const chartData     = await chartResponse.json();

            if (chartData.success && chartData.labels && chartData.labels.length > 0) {
                const legendList = document.getElementById('legendList');
                const elegantColors = ['#0f2e5c', '#1a498b', '#2b65b6', '#d4af37', '#b8902d', '#708090', '#475569'];

                if (legendList) {
                    legendList.innerHTML = '';
                    chartData.labels.forEach((label, i) => {
                        let renderColor = chartData.colors[i] || elegantColors[i % elegantColors.length];
                        legendList.innerHTML += `
                            <div class="legend-item">
                                <div>
                                    <span class="legend-dot" style="background:${renderColor}"></span>
                                    <span class="legend-label">${label}</span>
                                    <span style="font-size:12px;color:var(--text-muted);margin-left:8px;">(${chartData.totals[i]} unit)</span>
                                </div>
                                <span class="legend-percent">${chartData.percentages[i]}%</span>
                            </div>`;
                    });
                }

                const canvas = document.getElementById('sectorChart');
                if (canvas) {
                    const ctx = canvas.getContext('2d');
                    if (sectorChart) sectorChart.destroy();

                    let chartColors = chartData.colors && chartData.colors.length > 0 ? chartData.colors : elegantColors;

                    sectorChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels  : chartData.labels,
                            datasets: [{
                                data           : chartData.percentages,
                                backgroundColor: chartColors,
                                borderWidth    : 3,
                                borderColor    : '#ffffff',
                                hoverOffset    : 6,
                            }]
                        },
                        options: {
                            responsive        : true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend : { display: false },
                                tooltip: {
                                    backgroundColor: '#0f2e5c',
                                    titleFont: { family: 'Inter', size: 13 },
                                    bodyFont: { family: 'Inter', size: 13 },
                                    padding: 12,
                                    cornerRadius: 8,
                                    callbacks: {
                                        label(context) {
                                            const label = context.label || '';
                                            const value = context.raw  || 0;
                                            const total = chartData.totals[context.dataIndex];
                                            return ` ${label}: ${value}% (${total} Unit)`;
                                        }
                                    }
                                }
                            },
                            cutout: '65%'
                        }
                    });
                }
            } else {
                setLegend(legendItem('#d4af37', 'Basis data sedang diperbarui...'));
            }
        } catch (error) {
            console.error('Error:', error);
            setLegend(legendItem('#ef4444', 'Terjadi kendala memuat data server'));
        }

        try {
            const statsResponse = await fetch('/api/statistics');
            const statsData     = await statsResponse.json();

            if (statsData.success) {
                document.getElementById('totalUmkmValue').innerText    = statsData.total_umkm.toLocaleString('id-ID');
                document.getElementById('growthValue').innerText        = statsData.growth;
                document.getElementById('totalSektorValue').innerText   = statsData.total_sektor;
                document.getElementById('topKategori').innerText        = statsData.top_kategori;
                document.getElementById('topKategoriTotal').innerText   = statsData.top_kategori_total.toLocaleString('id-ID');
            }
        } catch (error) {
            console.error('Stats error:', error);
        }
    }

    // Set realtime polling interval (every 30 seconds)
    // Server caches the API responses for 30s to ensure no overload
    setInterval(() => {
        loadDataFromDatabase();
    }, 30000);
    
    // Navbar Scroll
    const navbar   = document.getElementById('navbar');
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.nav-link');

    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 40);

        let current = '';
        sections.forEach(section => {
            if (window.scrollY >= (section.offsetTop - 250)) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    });
    
    // Mobile Menu
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const closeBtn = document.getElementById('closeMenuBtn');
    const mobileLinks = document.querySelectorAll('.mobile-nav-link');
    
    mobileLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            const target = link.getAttribute('href');
            if (target && target.startsWith('#')) {
                e.preventDefault();
                closeMenu();
                const element = document.querySelector(target);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });
    
    function openMenu() {
        mobileMenu.classList.add('active');
        mobileOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMenu() {
        mobileMenu.classList.remove('active');
        mobileOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    if (mobileBtn) mobileBtn.addEventListener('click', openMenu);
    if (closeBtn) closeBtn.addEventListener('click', closeMenu);
    if (mobileOverlay) mobileOverlay.addEventListener('click', closeMenu);

    // Premium Scroll Reveal Animation
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15
    };
    
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Auto-inject reveal class to elements to make the site look premium
    const elementsToReveal = document.querySelectorAll('.stats-header, .stat-card, .data-header, .chart-box, .legend-box, .section-title, .news-card, .why-content, .why-card, .quote-icon, .quote-text, .quote-author, .footer-grid > div, .map-header, .map-wrapper');
    
    elementsToReveal.forEach((el, index) => {
        el.classList.add('reveal');
        // Add staggered delay for grid items
        if(el.classList.contains('stat-card') || el.classList.contains('news-card') || el.classList.contains('why-card') || el.parentElement.classList.contains('footer-grid')) {
             el.style.transitionDelay = `${(index % 4) * 0.15}s`;
        }
        observer.observe(el);
    });
    
    document.addEventListener('DOMContentLoaded', loadDataFromDatabase);

    // ==========================================================================
    // PETA INTERAKTIF UMKM — Leaflet.js
    // ==========================================================================
    let umkmMap = null;
    let umkmMarkers = null;
    let allMapData = [];
    let mapInitialized = false;

    // Custom Icon for UMKM Markers
    function createUmkmIcon(umkm) {
        return L.divIcon({
            className: 'custom-umkm-marker',
            html: `<div style="
                width: 44px; height: 44px;
                background: white;
                border-radius: 50% 50% 50% 0;
                transform: rotate(-45deg);
                border: 3px solid #0f2e5c;
                box-shadow: 0 6px 16px rgba(15, 46, 92, 0.4);
                display: flex; align-items: center; justify-content: center;
                overflow: hidden;
                position: relative;
            ">
                <img src="${umkm.foto}" style="
                    width: 100%; height: 100%;
                    object-fit: cover;
                    transform: rotate(45deg) scale(1.42);
                    border-radius: 50%;
                " onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(umkm.nama_usaha)}&background=0f2e5c&color=fff'">
            </div>`,
            iconSize: [44, 44],
            iconAnchor: [22, 44],
            popupAnchor: [0, -44],
        });
    }

    function buildPopupContent(umkm) {
        const avatarHtml = umkm.foto
            ? `<img src="${umkm.foto}" alt="${umkm.nama_usaha}" class="umkm-popup-avatar" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
               <div class="umkm-popup-avatar-placeholder" style="display:none;">${umkm.nama_usaha.charAt(0)}</div>`
            : `<div class="umkm-popup-avatar-placeholder">${umkm.nama_usaha.charAt(0)}</div>`;

        return `
            <div class="umkm-popup">
                <div class="umkm-popup-header">
                    ${avatarHtml}
                    <div>
                        <div class="umkm-popup-name">${umkm.nama_usaha}</div>
                        <div class="umkm-popup-badges">
                            <span class="umkm-popup-badge">${umkm.kategori}</span>
                            <span class="umkm-popup-badge sektor">${umkm.sektor}</span>
                        </div>
                    </div>
                </div>
                <div class="umkm-popup-detail">
                    <div><i class="fas fa-user"></i> ${umkm.pemilik}</div>
                    <div><i class="fas fa-map-pin"></i> ${umkm.kelurahan}</div>
                    <div><i class="fas fa-location-dot"></i> ${umkm.alamat}</div>
                    ${umkm.telp && umkm.telp !== '-' ? `<div><i class="fas fa-phone"></i> ${umkm.telp}</div>` : ''}
                </div>
                <div style="margin-top: 12px; border-top: 1px solid #f1f5f9; padding-top: 12px; text-align: center;">
                    <a href="https://www.google.com/maps/search/?api=1&query=${umkm.latitude},${umkm.longitude}" target="_blank" style="display: inline-block; background: #0f2e5c; color: white; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; width: 100%;">
                        <i class="fas fa-map-marked-alt" style="margin-right: 4px;"></i> Buka di Google Maps
                    </a>
                </div>
            </div>
        `;
    }

    function initUmkmMap() {
        if (mapInitialized) return;
        mapInitialized = true;

        // Pusat Kecamatan Mandalajati, Bandung
        umkmMap = L.map('umkmMap', {
            center: [-6.8868, 107.6545],
            zoom: 14,
            scrollWheelZoom: true,
            zoomControl: true,
        });

        // OpenStreetMap Tile Layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19,
        }).addTo(umkmMap);

        // Marker Cluster Group
        umkmMarkers = L.markerClusterGroup({
            maxClusterRadius: 50,
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true,
            iconCreateFunction: function(cluster) {
                const count = cluster.getChildCount();
                let size = 'small';
                if (count >= 20) size = 'large';
                else if (count >= 10) size = 'medium';

                return L.divIcon({
                    html: `<div><span>${count}</span></div>`,
                    className: `marker-cluster marker-cluster-${size}`,
                    iconSize: L.point(40, 40),
                });
            }
        });

        umkmMap.addLayer(umkmMarkers);

        // Load Data
        loadMapData();
    }

    async function loadMapData() {
        try {
            const response = await fetch('/api/map-data');
            const result = await response.json();

            if (result.success) {
                allMapData = result.data || [];
                populateMapFilters(result.filters || {});
                renderMapMarkers(allMapData);
            }
        } catch (error) {
            console.error('Error loading map data:', error);
        }
    }

    function populateMapFilters(filters) {
        const sektorSelect = document.getElementById('mapFilterSektor');
        const kelurahanSelect = document.getElementById('mapFilterKelurahan');

        // Ambil dari tabel master (bukan dari data marker)
        const sektors = filters.sektor || [];
        const kelurahans = filters.kelurahan || [];

        sektors.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s;
            opt.textContent = s;
            sektorSelect.appendChild(opt);
        });

        kelurahans.forEach(k => {
            const opt = document.createElement('option');
            opt.value = k;
            opt.textContent = k;
            kelurahanSelect.appendChild(opt);
        });
    }

    function renderMapMarkers(data) {
        umkmMarkers.clearLayers();

        data.forEach(umkm => {
            const icon = createUmkmIcon(umkm);
            const marker = L.marker([umkm.latitude, umkm.longitude], { icon: icon });
            marker.bindPopup(buildPopupContent(umkm), {
                maxWidth: 300,
                minWidth: 260,
            });
            umkmMarkers.addLayer(marker);
        });

        // Update counter
        document.getElementById('mapMarkerCount').textContent = data.length;

        // Fit bounds if there's data
        if (data.length > 0) {
            const bounds = L.latLngBounds(data.map(d => [d.latitude, d.longitude]));
            umkmMap.fitBounds(bounds, { padding: [40, 40], maxZoom: 16 });
        }
    }

    function filterMapMarkers() {
        const searchQuery = document.getElementById('mapSearchInput') ? document.getElementById('mapSearchInput').value.toLowerCase() : '';
        const sektorFilter = document.getElementById('mapFilterSektor').value;
        const kelurahanFilter = document.getElementById('mapFilterKelurahan').value;

        let filtered = allMapData;

        if (searchQuery) {
            filtered = filtered.filter(d => 
                (d.nama_usaha && d.nama_usaha.toLowerCase().includes(searchQuery)) ||
                (d.pemilik && d.pemilik.toLowerCase().includes(searchQuery)) ||
                (d.kategori && d.kategori.toLowerCase().includes(searchQuery))
            );
        }
        if (sektorFilter) {
            filtered = filtered.filter(d => d.sektor === sektorFilter);
        }
        if (kelurahanFilter) {
            filtered = filtered.filter(d => d.kelurahan === kelurahanFilter);
        }

        renderMapMarkers(filtered);
    }

    // Lazy-initialize map when section scrolls into view
    const mapObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !mapInitialized) {
                initUmkmMap();
                mapObserver.unobserve(entry.target);
            }
        });
    }, { rootMargin: '200px' });

    const mapSection = document.getElementById('peta-umkm');
    if (mapSection) {
        mapObserver.observe(mapSection);
    }
</script>

<!-- AI Chatbot Widget (Floating Pop-up) -->
<div class="chatbot-btn" id="chatbotToggleBtn" onclick="toggleChatbot()">
    <img src="{{ asset('images/chatbot-avatar.png') }}" alt="AI">
</div>

<div class="chatbot-window" id="chatbotWindow">
    <div class="chatbot-header">
        <div class="chatbot-header-info">
            <div class="chatbot-avatar" style="overflow: hidden;">
                <img src="{{ asset('images/chatbot-avatar.png') }}" alt="Mandalaloka AI" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div>
                <h3 class="chatbot-title">Tanya Mang Loka</h3>
                <div class="chatbot-status">
                    <span class="chatbot-status-dot"></span> Online & Siap Membantu
                </div>
            </div>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="chatbot-close" onclick="clearChatHistory()" title="Hapus Riwayat Obrolan" style="background: transparent; border: none; color: white; opacity: 0.8; cursor: pointer; font-size: 1.1rem; padding: 4px;">
                <i class="fas fa-trash-alt"></i>
            </button>
            <button class="chatbot-close" onclick="toggleChatbot()" title="Tutup Chat" style="background: transparent; border: none; color: white; opacity: 0.8; cursor: pointer; font-size: 1.2rem; padding: 4px;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    
    <!-- Onboarding Form for Chatbot -->
    <div class="chatbot-onboarding" id="chatbotOnboarding" style="display: none;">
        <h4>Lengkapi Data Diri Anda</h4>
        <div class="onboarding-field">
            <label for="obName">Nama Lengkap</label>
            <input type="text" id="obName" class="onboarding-input" placeholder="Contoh: Budi Santoso" required>
        </div>
        <div class="onboarding-field">
            <label for="obKelurahan">Kelurahan</label>
            <select id="obKelurahan" class="onboarding-input" required>
                <option value="" disabled selected>Pilih Kelurahan Anda</option>
                @if(isset($kelurahans) && $kelurahans->count() > 0)
                    @foreach($kelurahans as $kel)
                        <option value="{{ $kel->nama_kelurahan }}">{{ $kel->nama_kelurahan }}</option>
                    @endforeach
                @else
                    <option value="Jatihandap">Jatihandap</option>
                    <option value="Karangpamulang">Karangpamulang</option>
                    <option value="Pasir Impun">Pasir Impun</option>
                    <option value="Pasirlayung">Pasirlayung</option>
                @endif
            </select>
        </div>
        <div class="onboarding-field">
            <label for="obPhone">Nomor WhatsApp / HP</label>
            <input type="tel" id="obPhone" class="onboarding-input" placeholder="Contoh: 08123456789" required>
        </div>
        <button onclick="submitOnboarding()" class="onboarding-btn">Mulai Percakapan</button>
    </div>
    
    <!-- Unified Chat Window -->
    <div class="chatbot-messages" id="chatbotMessages" style="display: none;">
        <!-- Welcome Message -->
        <div class="chat-bubble bot" id="chatbotWelcomeBubble">
            Halo! Saya adalah <strong>Mang Loka</strong>. 👋<br><br>
            Saya siap membantu Anda mencari informasi seputar pendataan UMKM, perizinan, atau statistik di Kecamatan Mandalajati. Ada yang bisa saya bantu hari ini?
            <span class="chat-time" id="welcomeTime"></span>
        </div>
        
        <!-- FAQ / Pertanyaan Dasar Grid (Tinggal Pilih) -->
        <div class="faq-selection-container">
            <div class="faq-selection-title">
                <i class="fas fa-question-circle"></i> Pertanyaan Dasar (Tinggal Pilih):
            </div>
            <div class="faq-buttons-grid">
                <button class="faq-btn" onclick="selectFaqQuestion('daftar')">📝 Cara Daftar Akun</button>
                <button class="faq-btn" onclick="selectFaqQuestion('ktp')">👤 Kelengkapan KTP</button>
                <button class="faq-btn" onclick="selectFaqQuestion('lama')">⏳ Proses Pendaftaran</button>
                <button class="faq-btn" onclick="selectFaqQuestion('usaha')">🏬 Daftar Usaha Baru</button>
                <button class="faq-btn" onclick="selectFaqQuestion('tolak')">✏️ Perbaiki Data</button>
                <button class="faq-btn" onclick="selectFaqQuestion('biaya')">💳 Biaya Pendaftaran</button>
            </div>
        </div>
        
        <!-- Tanya Mang Loka Section Divider -->
        <div class="chat-section-divider">
            <span>Tanya Mang Loka (AI)</span>
        </div>
        
        <!-- Typing Indicator -->
        <div class="typing-indicator" id="chatbotTyping">
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
        </div>
    </div>
    
    <div class="chatbot-input-area" id="chatbotInputArea" style="display: none;">
        <div class="chatbot-input-wrapper">
            <textarea id="chatbotInput" class="chatbot-input" placeholder="Tulis pertanyaan Anda di sini..." rows="1" oninput="autoResize(this)" onkeypress="handleEnter(event)"></textarea>
        </div>
        <button id="chatbotSendBtn" class="chatbot-send" onclick="sendChatMessage()" disabled>
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<!-- Chatbot Visual Demo Scripts with Local Intelligent Mock replies -->
<script>
    function toggleChatbot() {
        const windowEl = document.getElementById('chatbotWindow');
        const btnEl = document.getElementById('chatbotToggleBtn');
        const isOpening = !windowEl.classList.contains('active');
        windowEl.classList.toggle('active');
        btnEl.classList.toggle('active');
        
        if (isOpening) {
            checkChatbotOnboarding();
        }
    }

    const faqData = {
        daftar: {
            q: "Bagaimana cara mendaftar akun pelaku UMKM?",
            a: "Klik tombol <strong>\"Pendaftaran UMKM\"</strong> di pojok kanan atas halaman utama (/register). Isi data: Nama Lengkap Pemilik, Alamat Email, NIK (16 digit), dan Kata Sandi Anda. Setelah terdaftar, silakan lengkapi profil Anda."
        },
        ktp: {
            q: "Apa saja kelengkapan KTP yang dibutuhkan?",
            a: "Masuk ke menu <strong>\"Profil\"</strong> di dashboard pelaku (/pelaku/profil), lengkapi biodata Anda, dan <strong>unggah foto KTP</strong> yang jelas. <br><br>🛡️ <strong>Keamanan Tinggi:</strong> Sistem kami secara otomatis menempelkan watermark keamanan permanen di atas foto KTP Anda dan menyimpannya di storage tertutup (tidak dapat diakses publik) demi melindungi privasi Anda dari penyalahgunaan."
        },
        lama: {
            q: "Berapa lama proses pendaftaran?",
            a: "Akun pelaku UMKM dan data usaha langsung dibuat aktif setelah data wajib dilengkapi. Anda tidak perlu menunggu verifikasi admin untuk mulai mengelola UMKM dan produk."
        },
        usaha: {
            q: "Bagaimana cara mendaftarkan usaha baru?",
            a: "Masuk ke dashboard Pelaku, pilih menu <strong>\"Daftar UMKM Baru\"</strong> (/pelaku/umkm/create), isi seluruh informasi detail usaha Anda dengan lengkap, unggah foto utama dan galeri produk, lalu simpan. Data UMKM akan langsung aktif di sistem."
        },
        tolak: {
            q: "Mengapa data UMKM saya perlu direvisi?",
            a: "Data usaha/KTP dapat perlu diperbaiki jika informasi tidak lengkap atau foto tidak jelas. Anda dapat mengedit data langsung dari dashboard pelaku agar informasi UMKM tetap rapi dan akurat."
        },
        biaya: {
            q: "Apakah ada biaya pendaftaran?",
            a: "Seluruh proses pendaftaran akun, pelengkapan KTP, pendaftaran usaha, hingga publikasi katalog UMKM di platform MandalalokaApps adalah <strong>100% Gratis</strong> tanpa dipungut biaya apa pun."
        },
        regulasi: {
            q: "Bagaimana regulasi dan surat edaran legalitas UMKM?",
            a: "Berdasarkan Peraturan Pemerintah, Pelaku UMKM wajib memiliki Nomor Induk Berusaha (NIB) sebagai identitas resmi usaha. NIB dapat diperoleh secara online melalui sistem OSS (Online Single Submission) di oss.go.id. Aplikasi platform MandalalokaApps membantu mencatat data legalitas tersebut di tingkat kecamatan."
        },
        pengaduan: {
            q: "Bagaimana cara menyampaikan pengaduan masyarakat?",
            a: "Untuk pengaduan masyarakat terkait pelayanan UMKM, kendala pendataan, atau laporan penyimpangan di lapangan, Anda dapat menghubungi Seksi Ekbang Kecamatan Mandalajati melalui email <strong>humas@mandalajati.bandung.go.id</strong> atau mengirim pesan langsung via WhatsApp/HP ke kantor sekretariat kami di (022) 1234567."
        }
    };

    function selectFaqQuestion(key) {
        const item = faqData[key];
        if (!item) return;

        // Tampilkan pesan user
        appendMessage('user', item.q);
        showTyping(true);

        // Simulasi respon bot instan
        setTimeout(() => {
            showTyping(false);
            appendMessage('bot', item.a);
        }, 500);
    }

    function askChatbot(key) {
        const windowEl = document.getElementById('chatbotWindow');
        const btnEl = document.getElementById('chatbotToggleBtn');
        
        // Buka chatbot jika belum aktif
        if (!windowEl.classList.contains('active')) {
            windowEl.classList.add('active');
            btnEl.classList.add('active');
            checkChatbotOnboarding();
        }

        const user = window.chatbotUser;
        if (!user) {
            alert('Harap isi data diri Anda terlebih dahulu pada kotak chat untuk menggunakan asisten AI!');
            return;
        }

        selectFaqQuestion(key);
    }

    const USER_SESSION_KEY = 'mandalaloka_chatbot_user';
    const SESSION_EXPIRY_MS = 3 * 60 * 60 * 1000; // 3 Jam

    function saveChatbotUser(name, kelurahan, phone) {
        const data = {
            name: name,
            kelurahan: kelurahan,
            phone: phone,
            expiresAt: Date.now() + SESSION_EXPIRY_MS
        };
        localStorage.setItem(USER_SESSION_KEY, JSON.stringify(data));
        window.chatbotUser = data;
    }

    function loadChatbotUser() {
        const stored = localStorage.getItem(USER_SESSION_KEY);
        if (!stored) return null;
        try {
            const data = JSON.parse(stored);
            if (Date.now() > data.expiresAt) {
                localStorage.removeItem(USER_SESSION_KEY);
                localStorage.removeItem('mandalaloka_chat_history');
                return null;
            }
            window.chatbotUser = data;
            return data;
        } catch (e) {
            localStorage.removeItem(USER_SESSION_KEY);
            localStorage.removeItem('mandalaloka_chat_history');
            return null;
        }
    }

    function clearChatHistory() {
        if(confirm('Apakah Anda yakin ingin menghapus seluruh riwayat percakapan?')) {
            localStorage.removeItem('mandalaloka_chat_history');
            
            // Hapus isi DOM chat except the intro and divider
            const msgs = document.getElementById('chatbotMessages');
            const intro = msgs.querySelector('.chatbot-message.bot');
            const divider = msgs.querySelector('.chat-section-divider');
            const typing = document.getElementById('chatbotTyping');
            
            msgs.innerHTML = '';
            if (intro) msgs.appendChild(intro);
            if (divider) msgs.appendChild(divider);
            if (typing) msgs.appendChild(typing);
            
            window.chatHistoryMemory = []; // clear in-memory array if defined globally
            
            alert('Riwayat obrolan berhasil dihapus. AI telah melupakan percakapan sebelumnya.');
        }
    }

    function checkChatbotOnboarding() {
        if (!window.chatbotUser) {
            loadChatbotUser();
        }

        const user = window.chatbotUser;
        const onboardingEl = document.getElementById('chatbotOnboarding');
        const messagesEl = document.getElementById('chatbotMessages');
        const inputAreaEl = document.getElementById('chatbotInputArea');
        
        if (user) {
            onboardingEl.style.display = 'none';
            messagesEl.style.display = 'flex';
            inputAreaEl.style.display = 'flex';
            
            updateWelcomeMessage(user.name, user.kelurahan);
            loadChatHistory();
            
            setTimeout(() => {
                const input = document.getElementById('chatbotInput');
                if (input) input.focus();
            }, 300);
        } else {
            onboardingEl.style.display = 'flex';
            messagesEl.style.display = 'none';
            inputAreaEl.style.display = 'none';
        }
    }

    function submitOnboarding() {
        const name = document.getElementById('obName').value.trim();
        const kelurahan = document.getElementById('obKelurahan').value;
        const phone = document.getElementById('obPhone').value.trim();
        
        if (!name || !kelurahan || !phone) {
            alert('Harap lengkapi seluruh kolom data diri Anda!');
            return;
        }
        
        saveChatbotUser(name, kelurahan, phone);
        checkChatbotOnboarding();
    }

    function updateWelcomeMessage(name, kelurahan) {
        const messagesEl = document.getElementById('chatbotMessages');
        const firstBubble = messagesEl.querySelector('.chat-bubble.bot');
        if (firstBubble && !firstBubble.hasAttribute('data-initialized')) {
            firstBubble.innerHTML = `Halo <strong>${name}</strong> dari Kelurahan <strong>${kelurahan}</strong>! Saya adalah <strong>Mang Loka</strong>. 👋<br><br>Saya dapat membantu Anda mencari informasi seputar pendataan UMKM, perizinan, atau statistik di Kecamatan Mandalajati. Ada yang bisa saya bantu hari ini?<span class="chat-time" id="welcomeTime">${getCurrentTimeStr()}</span>`;
            firstBubble.setAttribute('data-initialized', 'true');
        }
    }

    function getCurrentTimeStr() {
        const now = new Date();
        return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
    }

    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
        
        const btn = document.getElementById('chatbotSendBtn');
        btn.disabled = textarea.value.trim().length === 0;
    }

    function handleEnter(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendChatMessage();
        }
    }

    async function sendChatMessage() {
        const inputEl = document.getElementById('chatbotInput');
        const message = inputEl.value.trim();
        const sendBtn = document.getElementById('chatbotSendBtn');
        
        if (!message) return;
        
        inputEl.value = '';
        inputEl.disabled = true;
        sendBtn.disabled = true;
        inputEl.style.height = 'auto';
        
        appendMessage('user', message);
        showTyping(true);
        
        let replyText = '';
        
        try {
            const userObj = window.chatbotUser || null;

            // Coba panggil backend API terlebih dahulu
            const response = await fetch('/api/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    message: message,
                    name: userObj ? userObj.name : 'Tamu',
                    kelurahan: userObj ? userObj.kelurahan : 'Umum',
                    phone: userObj ? userObj.phone : '',
                    history: JSON.parse(localStorage.getItem(CHAT_HISTORY_KEY) || '[]')
                })
            });
            
            const data = await response.json();
            if(data.success) {
                replyText = data.reply;
            } else {
                throw new Error("API Route Disabled / n8n Off");
            }
            
        } catch (error) {
            // MOCK LOCAL RESPONSE: Jika backend dinonaktifkan, AI menjawab secara lokal dengan cerdas!
            const msgLower = message.toLowerCase();
            await new Promise(resolve => setTimeout(resolve, 1000)); // Simulasi mengetik
            
            if(msgLower.includes('daftar') || msgLower.includes('registrasi') || msgLower.includes('buat akun')) {
                replyText = '<strong>Langkah Pendaftaran Pelaku UMKM Mandalaloka:</strong><br><br>' +
                            '1. 📝 <strong>Registrasi Akun</strong>: Klik tombol <strong>"Pendaftaran UMKM"</strong> di pojok kanan atas halaman utama (atau akses /register). Isi data: Nama Lengkap Pemilik, Alamat Email, NIK (16 digit), Kata Sandi, dan Konfirmasi Sandi.<br>' +
                            '2. 👤 <strong>Lengkapi Profil</strong>: Setelah terdaftar, masuk ke menu <strong>"Profil"</strong> (/pelaku/profil). Isi data diri lengkap dan wajib <strong>mengunggah Foto KTP</strong> Anda (sistem akan otomatis memberi watermark keamanan & memproteksi file secara privat).<br>' +
                            '3. 🏬 <strong>Daftarkan Usaha</strong>: Buka menu <strong>"Daftar UMKM Baru"</strong> (/pelaku/umkm/create). Isi data detail usaha Anda dengan lengkap (Nama Usaha, Kategori, Sektor, Alamat, Foto Utama, dll.) lalu simpan.<br>' +
                            '4. ✅ <strong>Langsung Aktif</strong>: Akun dan UMKM Anda langsung aktif/terdaftar di sistem tanpa perlu menunggu verifikasi admin.';
            } else if(msgLower.includes('sistem') || msgLower.includes('mandalaloka') || msgLower.includes('apa ini') || msgLower.includes('aplikasi')) {
                replyText = '<strong>MandalalokaApps</strong> adalah platform resmi Sistem Informasi UMKM Kecamatan Mandalajati.<br><br>' +
                            'Sistem ini dirancang untuk mengintegrasikan basis data UMKM dari tingkat RT/RW dan kelurahan secara satu pintu guna membantu digitalisasi usaha lokal, kemudahan legalitas perizinan, dan pembuatan keputusan program bantuan tepat sasaran.';
            } else if(msgLower.includes('fitur') || msgLower.includes('keunggulan') || msgLower.includes('bisa apa')) {
                replyText = 'Fitur-fitur utama di <strong>MandalalokaApps</strong> meliputi:<br><br>' +
                            '- 🛡️ <strong>RBAC Login</strong>: Dashboard khusus untuk Super Admin, Admin Kecamatan, Operator Pendata, dan Pelaku UMKM.<br>' +
                            '- 📝 <strong>Pendataan Usaha</strong>: Pelaku UMKM dapat mendaftarkan usaha dan produk secara mandiri.<br>' +
                            '- 💳 <strong>Dokumen NIK & KTP Aman</strong>: Melindungi identitas pelaku usaha dengan watermarking otomatis & private storage.<br>' +
                            '- 📊 <strong>Analitik Statistik Sektoral</strong>: Pemetaan jumlah UMKM per-Kelurahan untuk pimpinan kecamatan.<br>' +
                            '- 📰 <strong>Warta Publikasi Berita</strong>: Portal info program pembinaan UMKM.';
            } else if(msgLower.includes('status') || msgLower.includes('verifikasi')) {
                replyText = 'Saat ini pelaku UMKM tidak perlu menunggu verifikasi admin. Setelah registrasi, lengkapi profil dan daftarkan UMKM Anda. Data usaha akan langsung aktif/terdaftar di sistem Mandalaloka.';
            } else {
                replyText = 'Halo! Saya adalah asisten pintar <strong>Mandalaloka AI</strong>. 🤖<br><br>' +
                            'Saat ini saya berjalan dalam <strong>Mode Demo Cerdas</strong>. Saya bisa menjawab seputar tata cara pendaftaran, fitur sistem, atau pengelolaan data usaha di MandalalokaApps.<br><br>' +
                            '<em>(Catatan: Anda dapat mengaktifkan kembali backend API / n8n workflow kapan saja untuk menghubungkan saya ke model LLM Gemini secara langsung!)</em>';
            }
        } finally {
            showTyping(false);
            appendMessage('bot', replyText);
            inputEl.disabled = false;
            inputEl.focus();
        }
    }

    // --- FITUR CHAT HISTORY ---
    const CHAT_HISTORY_KEY = 'mandalaloka_chat_history';

    function saveToHistory(sender, text, timeStr) {
        let history = JSON.parse(localStorage.getItem(CHAT_HISTORY_KEY) || '[]');
        history.push({ sender, text, timeStr });
        localStorage.setItem(CHAT_HISTORY_KEY, JSON.stringify(history));
    }

    function loadChatHistory() {
        if (window.chatbotHistoryLoaded) return;
        let history = JSON.parse(localStorage.getItem(CHAT_HISTORY_KEY) || '[]');
        history.forEach(msg => {
            appendMessage(msg.sender, msg.text, msg.timeStr, false);
        });
        window.chatbotHistoryLoaded = true;
    }

    function appendMessage(sender, text, timeStr = null, save = true) {
        const messagesDiv = document.getElementById('chatbotMessages');
        const typingIndicator = document.getElementById('chatbotTyping');
        
        if (!timeStr) {
            const now = new Date();
            timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
        }
        
        const bubble = document.createElement('div');
        bubble.className = `chat-bubble ${sender}`;
        const formattedText = text.replace(/\n/g, '<br>');
        bubble.innerHTML = `${formattedText}<span class="chat-time">${timeStr}</span>`;
        
        messagesDiv.insertBefore(bubble, typingIndicator);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
        
        if (save) {
            saveToHistory(sender, text, timeStr);
        }
    }

    function showTyping(show) {
        const indicator = document.getElementById('chatbotTyping');
        const messagesDiv = document.getElementById('chatbotMessages');
        
        if (show) {
            indicator.classList.add('active');
            messagesDiv.appendChild(indicator);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        } else {
            indicator.classList.remove('active');
        }
    }

    function scrollProductCarousel(direction) {
        const carousel = document.getElementById('homeProductCarousel');
        if (!carousel) return;

        const firstCard = carousel.querySelector('.home-product-card');
        const cardWidth = firstCard ? firstCard.getBoundingClientRect().width : 260;
        carousel.scrollBy({
            left: direction * (cardWidth + 16) * 2,
            behavior: 'smooth'
        });
    }
</script>
@include('partials.program-flyer-popup')
</body>
</html>
