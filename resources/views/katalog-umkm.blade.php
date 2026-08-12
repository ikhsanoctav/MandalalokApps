<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog UMKM - Mandalaloka</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo_Mandalaloka.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Merriweather:ital,wght@0,400;0,700;0,900;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* Import the exact CSS from welcome.blade.php for consistency */
        @import url('{{ asset("css/welcome-theme.css") }}');
        
        :root {
            /* Elegant Government Color Palette */
            --primary: #0f2e5c;        /* Deep Navy Blue */
            --primary-light: #1a498b;
            --gold: #d4af37;           /* Elegant Gold */
            --gold-dark: #b8902d;
            --bg-light: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            
            /* Modern Rounded Corners */
            --radius-sm: 12px;
            --radius-md: 24px;
            --radius-lg: 32px;
            --radius-pill: 999px;

            --shadow-subtle: 0 8px 30px rgba(15, 46, 92, 0.06);
            --shadow-hover: 0 16px 40px rgba(15, 46, 92, 0.12);

            /* Aksesn Batik Jawa Barat (Seamless SVG Pattern) */
            --batik-jabar: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23d4af37' fill-opacity='0.15'%3E%3Cpath d='M30 30c0-8.284 6.716-15 15-15 8.284 0 15 6.716 15 15 0 8.284-6.716 15-15 15-8.284 0-15-6.716-15-15zm-15 0c0-8.284-6.716-15-15-15C6.716 15 0 21.716 0 30c0 8.284 6.716 15 15 15 8.284 0 15-6.716 15-15zM15 15c0-8.284 6.716-15 15-15 8.284 0 15 6.716 15 15 0 8.284-6.716 15-15 15-8.284 0-15-6.716-15-15zM15 45c0-8.284 6.716-15 15-15 8.284 0 15 6.716 15 15 0 8.284-6.716 15-15 15-8.284 0-15-6.716-15-15z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            background:
                linear-gradient(180deg, #f8fafc 0%, #eef4fb 52%, #f8fafc 100%);
            color: var(--text-dark);
            overflow-x: hidden;
        }
        h1, h2, h3 { font-family: 'Merriweather', serif; }
        
        .top-ribbon {
            height: 4px; width: 100%; background: linear-gradient(90deg, #ef4444 50%, #ffffff 50%);
            position: fixed; top: 0; left: 0; z-index: 1001; box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Navbar */
        .navbar {
            position: fixed; top: 24px; left: 24px; right: 24px; z-index: 1000;
            background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(12px);
            border-radius: var(--radius-pill); border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04); transition: all 0.4s ease;
            max-width: 1320px; margin: 0 auto;
        }
        .navbar.scrolled {
            top: 4px; left: 0; right: 0; max-width: 100%;
            border-radius: 0 0 var(--radius-md) var(--radius-md);
            background: rgba(255, 255, 255, 0.98); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-left: none; border-right: none; border-top: none;
        }
        .nav-container { padding: 10px 24px 10px 32px; display: flex; justify-content: space-between; align-items: center; gap: 24px; }
        .logo { display: flex; align-items: center; gap: 16px; }
        .logo img { height: 48px; width: auto; border-radius: var(--radius-sm); }
        .logo-text h4 { font-size: 18px; font-weight: 900; color: var(--primary); letter-spacing: 0.5px; line-height: 1.1; }
        .logo-text p { font-size: 11px; font-weight: 500; color: var(--text-muted); letter-spacing: 0.5px; margin-top: 4px; text-transform: uppercase; }
        .nav-menu { display: flex; align-items: center; gap: 28px; }
        .nav-link { text-decoration: none; color: var(--text-dark); font-weight: 500; font-size: 14px; position: relative; padding: 8px 0; transition: all 0.3s; }
        .nav-link:hover, .nav-link.active { color: var(--gold-dark); }
        .nav-link::after {
            content: ''; position: absolute; bottom: -4px; left: 50%; transform: translateX(-50%);
            width: 0; height: 3px; border-radius: var(--radius-pill); background: var(--gold); transition: width 0.3s;
        }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        .btn-login {
            background: transparent; border: 1.5px solid var(--primary); color: var(--primary);
            padding: 12px 28px; border-radius: var(--radius-pill); font-weight: 600; font-size: 13px; text-decoration: none; transition: all 0.3s;
        }
        .btn-login:hover { background: rgba(15, 46, 92, 0.05); transform: translateY(-2px); }
        .btn-register {
            background: var(--primary); color: white; padding: 12px 28px; border-radius: var(--radius-pill);
            font-weight: 600; font-size: 13px; text-decoration: none; border: 1.5px solid var(--primary); transition: all 0.3s;
        }
        .btn-register:hover { background: var(--primary-light); transform: translateY(-2px); }

        /* Mobile Menu */
        .mobile-menu-btn { display: none; font-size: 20px; background: none; border: none; color: var(--primary); cursor: pointer; padding: 8px; }
        .mobile-menu { position: fixed; top: 0; right: -100%; width: 300px; height: 100%; background: white; box-shadow: -4px 0 30px rgba(0,0,0,0.1); z-index: 1002; transition: right 0.4s cubic-bezier(0.16, 1, 0.3, 1); padding: 80px 32px 32px; border-radius: 24px 0 0 24px; }
        .mobile-menu.active { right: 0; }
        .mobile-menu-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1001; display: none; }
        .mobile-menu-overlay.active { display: block; }
        .mobile-nav-link { display: block; padding: 16px 0; text-decoration: none; color: var(--primary); font-weight: 600; border-bottom: 1px solid var(--border-color); }
        .mobile-close { position: absolute; top: 24px; right: 24px; font-size: 24px; background: none; border: none; color: var(--text-dark); cursor: pointer; }

        /* Premium Animations */
        .reveal { opacity: 0; transform: translateY(40px); transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1); }
        .reveal.active { opacity: 1; transform: translateY(0); }
        
        @keyframes fadeInUpHero { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        .hero-content h1 { animation: fadeInUpHero 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards 0.2s; opacity: 0; }
        .hero-content p { animation: fadeInUpHero 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards 0.4s; opacity: 0; }

        /* Hero */
        .hero {
            min-height: 420px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 156px 24px 108px;
            position: relative;
            background:
                linear-gradient(135deg, rgba(15, 46, 92, 0.96) 0%, rgba(26, 73, 139, 0.94) 100%),
                url('{{ asset('images/batik-jabar-full.svg') }}');
            background-size: cover;
            overflow: hidden;
            border-radius: 0 0 28px 28px;
            margin-bottom: 40px;
            text-align: center;
        }
        .hero::before {
            content: ''; position: absolute; inset: 0; background-image: var(--batik-jabar); background-size: 80px 80px; opacity: 0.15; pointer-events: none;
        }
        
        .hero-content {
            position: relative; z-index: 10; max-width: 800px; margin: 0 auto; color: white;
        }
        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: var(--radius-pill);
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: rgba(255, 255, 255, 0.9);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 18px;
        }
        .hero-content h1 { font-size: clamp(36px, 5vw, 58px); font-weight: 900; line-height: 1.12; margin-bottom: 18px; color: white; letter-spacing: -0.02em; }
        .hero-content h1 span { color: var(--gold); }
        .hero-content p { font-size: 17px; color: rgba(255,255,255,0.88); line-height: 1.8; margin: 0 auto; max-width: 760px; }
        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 28px;
        }
        .hero-stat {
            min-width: 132px;
            padding: 12px 16px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: white;
            backdrop-filter: blur(10px);
        }
        .hero-stat strong { display: block; font-size: 20px; line-height: 1; }
        .hero-stat span { display: block; margin-top: 5px; font-size: 11px; color: rgba(255,255,255,0.72); font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }
        
        /* Filter Section */
        .filter-section {
            max-width: 1180px; margin: -72px auto 36px; background: rgba(255,255,255,0.96);
            padding: 18px; border-radius: 18px;
            box-shadow: 0 18px 46px rgba(15, 46, 92, 0.14); position: relative; z-index: 20;
            border: 1px solid rgba(226,232,240,0.9);
            backdrop-filter: blur(18px);
        }
        .filter-form { display: grid; grid-template-columns: minmax(0, 1.5fr) minmax(190px, .7fr) auto auto; gap: 12px; align-items: center; }
        .filter-field { position: relative; }
        .filter-field i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; pointer-events: none; }
        .filter-input {
            width: 100%; padding: 14px 18px 14px 42px;
            border: 1px solid #dbe3ee; border-radius: 12px;
            font-family: 'Inter', sans-serif; font-size: 15px; outline: none; transition: all 0.3s;
            background: #f8fafc;
        }
        select.filter-input { appearance: none; padding-right: 42px; }
        .filter-input:focus { border-color: var(--primary-light); background: white; box-shadow: 0 0 0 3px rgba(26, 73, 139, 0.12); }
        .filter-btn {
            background: var(--primary); color: white; border: none; padding: 14px 24px;
            border-radius: 12px; font-weight: 800; cursor: pointer; transition: all 0.3s;
            white-space: nowrap;
        }
        .filter-btn:hover { background: var(--primary-light); transform: translateY(-2px); }
        .filter-reset {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 13px 18px; border-radius: 12px; border: 1px solid #dbe3ee;
            color: #475569; text-decoration: none; font-size: 14px; font-weight: 800; background: white;
            transition: all .25s ease; white-space: nowrap;
        }
        .filter-reset:hover { color: var(--primary); border-color: #b7c7da; transform: translateY(-2px); }
        
        /* Grid */
        .catalog-container { max-width: 1320px; margin: 0 auto 80px; padding: 0 24px; }
        .catalog-heading {
            display: flex; align-items: end; justify-content: space-between; gap: 18px;
            margin-bottom: 22px;
        }
        .catalog-heading h2 {
            font-size: clamp(24px, 3vw, 34px);
            color: var(--primary);
            line-height: 1.15;
            margin-bottom: 8px;
        }
        .catalog-heading p { color: var(--text-muted); line-height: 1.6; font-size: 14px; }
        .catalog-count {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 14px; border-radius: 12px;
            background: white; border: 1px solid #e2e8f0; color: var(--primary);
            font-size: 13px; font-weight: 800; white-space: nowrap;
            box-shadow: var(--shadow-subtle);
        }
        .catalog-grid { 
            display: grid; 
            grid-template-columns: repeat(1, 1fr); 
            gap: 18px; 
        }
        @media (min-width: 640px) { .catalog-grid { grid-template-columns: repeat(2, 1fr); gap: 20px; } }
        @media (min-width: 768px) { .catalog-grid { grid-template-columns: repeat(2, 1fr); gap: 24px; } }
        @media (min-width: 1024px) { .catalog-grid { grid-template-columns: repeat(3, 1fr); gap: 24px; } }
        @media (min-width: 1280px) { .catalog-grid { grid-template-columns: repeat(4, 1fr); gap: 24px; } }
        /* Premium UMKM Card Design */
        .umkm-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(15, 46, 92, 0.05), 0 1px 3px rgba(15, 46, 92, 0.02);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(226,232,240,0.8);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .umkm-card::after {
            content: ''; position: absolute; inset: 0; border-radius: 20px;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.5); pointer-events: none; z-index: 2;
        }
        .umkm-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 48px rgba(15, 46, 92, 0.12), 0 8px 16px rgba(15, 46, 92, 0.04);
            border-color: rgba(26,73,139,0.15);
        }
        .umkm-img-wrap {
            position: relative;
            aspect-ratio: 4 / 3;
            overflow: hidden;
            background: linear-gradient(135deg, #f1f5f9, #f8fafc);
        }
        .umkm-img-wrap::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0) 40%);
            opacity: 0; transition: opacity 0.4s ease; z-index: 1;
        }
        .umkm-card:hover .umkm-img-wrap::after { opacity: 1; }
        .umkm-img-wrap img {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover; transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .umkm-card:hover .umkm-img-wrap img { transform: scale(1.08); }
        .umkm-placeholder {
            position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, rgba(15,46,92,.05), rgba(199,154,47,.08));
        }
        .umkm-placeholder img { position: static; width: 64px; height: 64px; object-fit: contain; opacity: 0.6; transform: none !important; filter: grayscale(100%); }
        .umkm-badge {
            position: absolute; top: 12px; right: 12px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
            padding: 6px 12px; border-radius: 999px;
            font-size: 11px; font-weight: 800; letter-spacing: 0.3px;
            color: var(--primary); box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            max-width: calc(100% - 24px); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            z-index: 2; border: 1px solid rgba(255,255,255,0.4);
        }
        .umkm-content { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; position: relative; z-index: 2; background: white; }
        .umkm-store {
            font-size: 11px; color: var(--gold-dark); font-weight: 800; letter-spacing: 0.5px;
            text-transform: uppercase; margin-bottom: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .umkm-title {
            font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 8px;
            font-family: 'Inter', sans-serif; line-height: 1.4;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 50px;
        }
        .umkm-price {
            font-size: 18px; font-weight: 900; color: #ef4444; margin-bottom: 16px;
            display: flex; align-items: center; gap: 4px;
        }
        
        .umkm-meta { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #64748b; margin-bottom: 16px; margin-top: auto; min-width: 0; font-weight: 500; }
        .umkm-meta i { color: var(--primary-light); font-size: 12px; }
        .card-actions { display: grid; grid-template-columns: 1fr auto; gap: 10px; align-items: center; margin-bottom: 16px; padding-top: 16px; border-top: 1px solid #f1f5f9; }
        .review-trigger {
            display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: #475569;
            cursor: pointer; padding: 8px 12px; border-radius: 999px; transition: all .25s ease; min-width: 0;
            border: 1px solid #e2e8f0; background: #f8fafc;
        }
        .review-trigger:hover { background: #e0e7ff; color: #4338ca; border-color: #c7d2fe; transform: scale(1.02); }
        .review-button {
            background: white; border: 1px solid #cbd5e1; border-radius: 999px; padding: 8px 14px; font-size: 12px; font-weight: 800; color: #334155; cursor: pointer; transition: all .2s ease;
        }
        .review-button:hover { background: #f8fafc; border-color: #b7c7da; }
        
        /* Premium Custom Pagination Styling */
        .pagination-wrap { margin-top: 50px; display: flex; justify-content: center; }
        .katalog-pagination { display: flex; flex-direction: column; align-items: center; gap: 16px; }
        .katalog-pagination nav { display: flex; align-items: center; gap: 6px; background: white; padding: 6px; border-radius: 999px; box-shadow: 0 4px 20px rgba(15, 46, 92, 0.08); border: 1px solid rgba(226,232,240,0.8); }
        .katalog-pagination .page-numbers { display: flex; align-items: center; gap: 4px; }
        .katalog-pagination .page-link { display: inline-flex; align-items: center; justify-content: center; min-width: 42px; height: 42px; padding: 0 14px; border-radius: 999px; background: transparent; color: var(--text-muted); font-weight: 700; font-size: 0.95rem; text-decoration: none; border: none; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
        .katalog-pagination .page-link i { font-size: 13px; }
        .katalog-pagination .page-link:hover:not(.disabled):not(.active) { background: #f1f5f9; color: var(--primary); transform: translateY(-1px); }
        .katalog-pagination .page-link.active { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); color: white; box-shadow: 0 4px 12px rgba(15, 46, 92, 0.25); }
        .katalog-pagination .page-link.disabled { opacity: 0.4; cursor: not-allowed; }
        .katalog-pagination .pagination-info { font-size: 0.85rem; color: var(--text-muted); letter-spacing: 0.3px; }
        .katalog-pagination .pagination-info strong { color: var(--primary); font-weight: 800; }

        /* Loader for Smooth Pagination */
        .catalog-container { position: relative; }
        .catalog-loader { position: absolute; inset: 0; background: rgba(255,255,255,0.6); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; z-index: 50; opacity: 0; pointer-events: none; transition: opacity 0.3s ease; border-radius: 20px; }
        .catalog-loader.active { opacity: 1; pointer-events: auto; }
        .catalog-spinner { width: 50px; height: 50px; border: 4px solid #e2e8f0; border-top-color: var(--primary); border-radius: 50%; animation: spin 1s cubic-bezier(0.4, 0, 0.2, 1) infinite; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        .wa-button {
            display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%;
            background: linear-gradient(135deg, #1da851 0%, #168840 100%); color: white; padding: 12px; border-radius: 12px; text-decoration: none;
            font-size: 14px; font-weight: 800; transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(29, 168, 81, 0.2); border: 1px solid rgba(255,255,255,0.1);
        }
        .wa-button:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(29, 168, 81, 0.3); background: linear-gradient(135deg, #1fae54 0%, #179344 100%); }
        /* Pagination */
        .pagination-wrap { margin-top: 48px; display: flex; justify-content: center; }
        .pagination { display: flex; gap: 8px; list-style: none; }
        .page-item .page-link {
            padding: 8px 16px; border: 1px solid var(--border-color); border-radius: var(--radius-sm);
            color: var(--text-dark); text-decoration: none; transition: all 0.3s;
        }
        .page-item.active .page-link, .page-item .page-link:hover { background: var(--primary); color: white; border-color: var(--primary); }

        /* Footer */
        .footer {
            background: var(--primary); color: white; padding: 80px 24px 24px;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0; position: relative; margin-top: 60px;
        }
        .footer-container { max-width: 1200px; margin: 0 auto; }
        .footer-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; margin-bottom: 60px; }
        .footer-logo { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
        .footer-logo-img { height: 48px; width: auto; background: white; padding: 4px; border-radius: 8px; }
        .footer-logo-text { font-size: 24px; font-family: 'Merriweather', serif; font-weight: 700; color: white; }
        .footer-about { color: rgba(255, 255, 255, 0.8); line-height: 1.8; font-size: 15px; max-width: 400px; }
        .footer-bottom { border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 24px; text-align: center; color: rgba(255, 255, 255, 0.6); font-size: 14px; }

        /* Responsive */
        @media (max-width: 1200px) {
            .nav-menu { gap: 14px; }
            .nav-link { font-size: 13px; }
            .btn-login, .btn-register { padding: 10px 20px; font-size: 12px; }
            .logo-text h4 { font-size: 16px; }
        }
        
        @media (max-width: 1024px) {
            .nav-menu { display: none; }
            .mobile-menu-btn { display: block; }
        }
        
        /* Mobile */
        @media (max-width: 768px) {
            .hero h1 { font-size: 32px; }
            .filter-section { margin-left: 16px; margin-right: 16px; padding: 14px; }
            .filter-form { grid-template-columns: 1fr; }
            .filter-btn, .filter-reset { width: 100%; }
            .catalog-heading { align-items: flex-start; flex-direction: column; }
            .catalog-count { width: 100%; justify-content: center; }
            .footer-grid { grid-template-columns: 1fr; gap: 40px; }
        }
        /* Shopee-like Product Modal */
        .product-modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 46, 92, 0.7);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .product-modal-content {
            background: white;
            border-radius: var(--radius-md);
            width: 100%;
            max-width: 900px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            animation: modalFadeIn 0.3s ease-out;
        }
        @media (min-width: 768px) {
            .product-modal-content {
                flex-direction: row;
            }
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .pm-close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            color: var(--text-dark);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.2s;
        }
        .pm-close-btn:hover {
            background: #f1f5f9;
            transform: scale(1.05);
        }
        .pm-left {
            width: 100%;
            background: #f8fafc;
            padding: 24px;
            border-right: 1px solid var(--border-color);
        }
        @media (min-width: 768px) {
            .pm-left { width: 45%; }
        }
        .pm-main-image {
            width: 100%;
            aspect-ratio: 1/1;
            border-radius: var(--radius-sm);
            background: white;
            border: 1px solid var(--border-color);
            margin-bottom: 16px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pm-main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .pm-thumbnails {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 8px;
            scroll-snap-type: x mandatory;
        }
        .pm-thumbnails::-webkit-scrollbar { height: 6px; }
        .pm-thumbnails::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .pm-thumb {
            flex-shrink: 0;
            width: 70px;
            height: 70px;
            border-radius: 8px;
            border: 2px solid transparent;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.2s;
            scroll-snap-align: start;
            padding: 0;
            background: white;
        }
        .pm-thumb.active { border-color: var(--primary); }
        .pm-thumb:hover { border-color: var(--primary-light); }
        .pm-thumb img { width: 100%; height: 100%; object-fit: cover; }
        
        .pm-right {
            width: 100%;
            padding: 24px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        @media (min-width: 768px) {
            .pm-right { width: 55%; }
        }
        .pm-store-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e0e7ff;
            color: #4338ca;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            width: max-content;
        }
        .pm-title {
            font-size: 24px;
            color: var(--text-dark);
            margin-bottom: 16px;
            line-height: 1.3;
        }
        .pm-price-box {
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            padding: 16px;
            margin-bottom: 20px;
        }
        .pm-price {
            font-size: 32px;
            font-weight: 700;
            color: #ef4444; 
        }
        .pm-meta-row {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        .pm-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            color: var(--text-muted);
        }
        .pm-meta-item i.fa-star { color: #fbbf24; }
        .pm-meta-item strong { color: var(--text-dark); }
        .pm-desc-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 12px;
        }
        .pm-desc {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.6;
            white-space: pre-wrap;
            margin-bottom: 24px;
            flex-grow: 1;
        }
        .pm-actions {
            display: flex;
            gap: 12px;
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid var(--border-color);
        }
        .pm-btn-wa {
            flex: 1;
            background: #10b981;
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            padding: 14px;
            font-size: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }
        .pm-btn-wa:hover {
            background: #059669;
            transform: translateY(-2px);
            color: white;
        }
        .pm-btn-disabled {
            flex: 1;
            background: #cbd5e1;
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            padding: 14px;
            font-size: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

    <div class="top-ribbon"></div>

    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="{{ route('welcome') }}" style="text-decoration: none;">
                <div class="logo">
                    <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo Mandalaloka">
                    <div class="logo-text">
                        <h4>Mandalaloka</h4>
                        <p>Pendataan UMKM</p>
                    </div>
                </div>
            </a>
            
            <div class="nav-menu">
                <a href="{{ route('welcome') }}" class="nav-link">Beranda</a>
                <a href="{{ route('katalog.umkm') }}" class="nav-link active">Katalog UMKM</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-register">Dasbor</a>
                @else
                    <a href="{{ route('login') }}" class="btn-login">Masuk</a>
                @endif
            </div>

            <button class="mobile-menu-btn" id="mobileMenuBtn" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobileOverlay" onclick="toggleMenu()"></div>
    <div class="mobile-menu" id="mobileMenu">
        <button class="mobile-close" onclick="toggleMenu()">
            <i class="fas fa-times"></i>
        </button>
        <div style="margin-bottom: 32px;">
            <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo" style="height: 48px; margin-bottom: 16px;">
            <h4 style="font-size: 18px; font-weight: 800; color: var(--primary);">Mandalaloka</h4>
        </div>
        <a href="{{ route('welcome') }}" class="mobile-nav-link">Beranda</a>
        <a href="{{ route('katalog.umkm') }}" class="mobile-nav-link" style="color: var(--gold-dark);">Katalog UMKM</a>
        <div style="margin-top: 32px; display: flex; flex-direction: column; gap: 16px;">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-register" style="text-align: center;">Masuk ke Dasbor</a>
            @else
                <a href="{{ route('login') }}" class="btn-login" style="text-align: center;">Masuk Akun</a>
            @endif
        </div>
    </div>

    <header class="hero">
        <div class="hero-content">
            <div class="hero-kicker"><i class="fas fa-store"></i> Produk Lokal Mandalajati</div>
            <h1>Katalog UMKM <span>Mandalajati</span></h1>
            <p>Temukan berbagai produk unggulan, kerajinan, dan jasa dari pelaku Usaha Mikro, Kecil, dan Menengah (UMKM) terbaik di Kecamatan Mandalajati.</p>
            <div class="hero-stats">
                <div class="hero-stat">
                    <strong>{{ number_format($produks->total(), 0, ',', '.') }}</strong>
                    <span>Produk tampil</span>
                </div>
                <div class="hero-stat">
                    <strong>{{ number_format($sektors->count(), 0, ',', '.') }}</strong>
                    <span>Sektor usaha</span>
                </div>
            </div>
        </div>
    </header>

    <div class="filter-section reveal">
        <form action="{{ route('katalog.umkm') }}" method="GET" class="filter-form">
            <div class="filter-field">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="filter-input" placeholder="Cari produk atau nama usaha..." value="{{ request('search') }}">
            </div>
            
            <div class="filter-field">
                <i class="fas fa-layer-group"></i>
                <select name="sektor" class="filter-input">
                    <option value="">Semua Sektor</option>
                    @foreach($sektors as $sektor)
                        <option value="{{ $sektor->id }}" {{ request('sektor') == $sektor->id ? 'selected' : '' }}>
                            {{ $sektor->nama_sektor }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="filter-btn"><i class="fas fa-search"></i> Cari</button>
            @if(request()->filled('search') || request()->filled('sektor'))
                <a href="{{ route('katalog.umkm') }}" class="filter-reset"><i class="fas fa-rotate-left"></i> Reset</a>
            @endif
        </form>
    </div>

    <div class="catalog-container">
        <div class="catalog-heading reveal">
            <div>
                <p>
                    Jelajahi produk lokal yang tersedia di Mandalaloka.
                    @if(request('search'))
                        Hasil pencarian untuk <strong>"{{ request('search') }}"</strong>.
                    @endif
                </p>
            </div>
            <div class="catalog-count">
                <i class="fas fa-boxes-stacked"></i>
                {{ number_format($produks->total(), 0, ',', '.') }} produk
            </div>
        </div>

        @if($produks->count() > 0)
            <div class="catalog-grid">
                @foreach($produks as $produk)
                    @php
                        $pesan = "Halo " . $produk->umkm->nama_usaha . ", saya tertarik dengan produk *" . $produk->nama_produk . "* yang ada di Katalog Mandalaloka. Boleh minta info lebih lanjut?";
                        $noWa = preg_replace('/\D/', '', $produk->umkm->telp_usaha ?? '');
                        if($noWa && substr($noWa, 0, 1) == '0'){
                            $noWa = '62' . substr($noWa, 1);
                        }
                        
                        $productData = [
                            'nama' => $produk->nama_produk,
                            'usaha' => $produk->umkm->nama_usaha,
                            'harga' => $produk->harga ? 'Rp ' . number_format($produk->harga, 0, ',', '.') : 'Harga hubungi penjual',
                            'deskripsi' => $produk->deskripsi ?? 'Belum ada deskripsi',
                            'lokasi' => optional(optional($produk->umkm->pemilik)->kelurahanRel)->nama_kelurahan ?? optional($produk->umkm->pemilik)->kelurahan ?? 'Mandalajati',
                            'images' => $produk->foto_produk ? array_map(fn($f) => Storage::url($f), $produk->foto_produk) : [],
                            'placeholder' => 'https://placehold.co/600x400/e8f0fb/0f2e5c?text=' . urlencode($produk->nama_produk),
                            'rating' => $produk->umkm->average_rating > 0 ? $produk->umkm->average_rating : 'Baru',
                            'ulasan_count' => $produk->umkm->ratings->count()
                        ];
                    @endphp
                    <div class="umkm-card reveal" 
                         x-data 
                         @click="$dispatch('open-product-modal', { product: {{ json_encode($productData) }}, waLink: '{{ $noWa ? "https://wa.me/".$noWa."?text=".urlencode($pesan) : "" }}' })" 
                         style="cursor: pointer;">
                        <div class="umkm-img-wrap">
                            @if($produk->foto_produk && count($produk->foto_produk) > 0)
                                <img src="{{ Storage::url($produk->foto_produk[0]) }}" alt="{{ $produk->nama_produk }}" loading="lazy" onerror="this.onerror=null;this.src='https://placehold.co/600x400/e8f0fb/0f2e5c?text={{ urlencode($produk->nama_produk) }}';">
                            @else
                                <div class="umkm-placeholder">
                                    <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Produk Mandalaloka" loading="lazy">
                                </div>
                            @endif
                            <div class="umkm-badge">{{ $produk->umkm->sektor?->nama_sektor ?? 'Umum' }}</div>
                        </div>
                        <div class="umkm-content">
                            <div class="umkm-store">{{ $produk->umkm->nama_usaha }}</div>
                            <h3 class="umkm-title">{{ $produk->nama_produk }}</h3>
                            
                            <div class="umkm-price">
                                {{ $produk->harga ? 'Rp ' . number_format($produk->harga, 0, ',', '.') : 'Harga hubungi penjual' }}
                            </div>
                            
                            <div class="umkm-meta" style="margin-bottom: 6px;">
                                <i class="fas fa-map-marker-alt"></i>
                                <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ optional(optional($produk->umkm->pemilik)->kelurahanRel)->nama_kelurahan ?? optional($produk->umkm->pemilik)->kelurahan ?? 'Mandalajati' }}</span>
                            </div>

                            <div class="card-actions">
                                <div class="review-trigger" x-data @click.stop="$dispatch('view-reviews', { name: '{{ addslashes($produk->umkm->nama_usaha) }}', html: document.getElementById('reviews-{{ $produk->umkm->id_umkm }}').innerHTML })" title="Lihat Ulasan">
                                    <i class="fas fa-star" style="color: #fbbf24;"></i>
                                    <span>{{ $produk->umkm->average_rating > 0 ? $produk->umkm->average_rating : 'Baru' }}</span>
                                    <span style="color: #94a3b8; font-weight: 700; font-size: 11px;">{{ $produk->umkm->ratings->count() }} ulasan</span>
                                </div>
                                <div x-data>
                                    <button @click.stop="$dispatch('open-review-form', { id: '{{ $produk->umkm->id_umkm }}', name: '{{ addslashes($produk->umkm->nama_usaha) }}' })" class="review-button">
                                        <i class="far fa-comment-dots"></i> Ulas
                                    </button>
                                </div>
                            </div>

                            <div style="margin-top: 8px;">
                                @if($noWa)
                                    <div x-data>
                                        <a @click.stop href="https://wa.me/{{ $noWa }}?text={{ urlencode($pesan) }}" target="_blank" class="wa-button">
                                            <i class="fab fa-whatsapp" style="font-size: 14px; color: white !important;"></i> Chat Penjual
                                        </a>
                                    </div>
                                @else
                                    <div x-data>
                                        <button @click.stop class="wa-button" style="background: #94a3b8; cursor: not-allowed; border: none; width: 100%;">
                                            <i class="fas fa-phone-slash" style="font-size: 13px; color: white !important;"></i> Kontak Belum Tersedia
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <!-- Template untuk list ulasan -->
                            <template id="reviews-{{ $produk->umkm->id_umkm }}">
                                @php $ulasans = $produk->umkm->ratings->where('is_hidden', false)->sortByDesc('created_at'); @endphp
                                @if($ulasans->count() > 0)
                                    @foreach($ulasans as $ulasan)
                                        <div style="padding: 16px; border-bottom: 1px solid #f1f5f9;">
                                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                                                <div>
                                                    <h5 style="font-size: 13px; font-weight: 700; color: #1e293b; margin: 0 0 2px 0;">{{ $ulasan->nama_reviewer }}</h5>
                                                    <span style="font-size: 10px; color: #94a3b8;">{{ $ulasan->created_at ? $ulasan->created_at->diffForHumans() : 'Baru saja' }}</span>
                                                </div>
                                                <div style="display: flex; gap: 2px;">
                                                    @for($i=1; $i<=5; $i++)
                                                        <i class="fa-star {{ $i <= $ulasan->rating ? 'fas' : 'far' }}" style="color: #fbbf24; font-size: 10px;"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            @if($ulasan->ulasan)
                                                <p style="font-size: 12px; color: #475569; line-height: 1.5; margin: 0; background: #f8fafc; padding: 10px; border-radius: 8px; border-left: 2px solid #e2e8f0;">{{ $ulasan->ulasan }}</p>
                                            @endif
                                            <div style="margin-top: 8px; display: flex; align-items: center; gap: 8px;">
                                                <button onclick="likeReview({{ $ulasan->id }}, this)" style="background: none; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 11px; font-weight: 600; color: {{ session()->has('liked_rating_' . $ulasan->id) ? '#3b82f6' : '#64748b' }}; cursor: pointer; display: flex; align-items: center; gap: 4px; padding: 4px 10px; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='none'">
                                                    <i class="{{ session()->has('liked_rating_' . $ulasan->id) ? 'fas' : 'far' }} fa-thumbs-up"></i>
                                                    <span class="like-count">{{ $ulasan->likes > 0 ? $ulasan->likes : 'Suka' }}</span>
                                                </button>
                                                
                                                <button onclick="dislikeReview({{ $ulasan->id }}, this)" style="background: none; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 11px; font-weight: 600; color: {{ session()->has('disliked_rating_' . $ulasan->id) ? '#ef4444' : '#64748b' }}; cursor: pointer; display: flex; align-items: center; gap: 4px; padding: 4px 10px; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='none'">
                                                    <i class="{{ session()->has('disliked_rating_' . $ulasan->id) ? 'fas' : 'far' }} fa-thumbs-down"></i>
                                                    <span class="dislike-count">{{ $ulasan->dislikes > 0 ? $ulasan->dislikes : 'Tidak Suka' }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div style="padding: 40px 20px; text-align: center;">
                                        <i class="far fa-comments" style="font-size: 32px; color: #cbd5e1; margin-bottom: 12px;"></i>
                                        <p style="font-size: 13px; color: #64748b; margin: 0;">Belum ada ulasan untuk UMKM ini.</p>
                                    </div>
                                @endif
                            </template>

                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="pagination-wrap reveal">
                {{ $produks->links('pagination.katalog') }}
            </div>
        @else
            <div class="reveal" style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-box-open" style="font-size: 48px; color: var(--text-muted); margin-bottom: 16px;"></i>
                <h3 style="color: var(--text-dark); margin-bottom: 8px;">Produk Tidak Ditemukan</h3>
                <p style="color: var(--text-muted);">Belum ada produk yang sesuai dengan kriteria pencarian Anda.</p>
            </div>
        @endif
    </div>

    <!-- Side Panel Component for Viewing Reviews -->
    <div x-data="{
        showReviews: false,
        umkmName: '',
        reviewsHtml: '',
        closeReviews() {
            this.showReviews = false;
            document.body.style.overflow = '';
        }
    }" @view-reviews.window="umkmName = $event.detail.name; reviewsHtml = $event.detail.html; showReviews = true; document.body.style.overflow = 'hidden';">
        <div x-show="showReviews" style="position: fixed; inset: 0; z-index: 9999; display: flex; justify-content: flex-end;" x-cloak>
            <!-- Overlay -->
            <div x-show="showReviews" x-transition.opacity style="position: absolute; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);" @click="closeReviews()"></div>
            
            <!-- Side Drawer (Pinggir Kanan) -->
            <div x-show="showReviews" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="transform translate-x-full"
                 x-transition:enter-end="transform translate-x-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="transform translate-x-0"
                 x-transition:leave-end="transform translate-x-full"
                 style="background: white; width: 100%; max-width: 400px; height: 100%; position: relative; z-index: 10000; box-shadow: -10px 0 25px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column;">
                
                <div style="padding: 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; background: white;">
                    <div>
                        <h3 style="font-family: 'Inter', sans-serif; font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">Ulasan Masyarakat</h3>
                        <p x-text="umkmName" style="font-size: 12px; color: #64748b; margin: 0;"></p>
                    </div>
                    <button @click="closeReviews()" style="background: #f1f5f9; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 16px; color: #64748b; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div style="flex-grow: 1; overflow-y: auto; background: white;" x-html="reviewsHtml">
                    <!-- Reviews will be injected here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Global Product Detail Modal -->
    <div x-data="{ 
            showProductModal: false, 
            activeProduct: null, 
            activeImage: null,
            waLink: ''
        }" 
        @open-product-modal.window="
            activeProduct = $event.detail.product; 
            activeImage = activeProduct.images.length > 0 ? activeProduct.images[0] : activeProduct.placeholder;
            waLink = $event.detail.waLink;
            showProductModal = true;
            document.body.style.overflow = 'hidden';
        "
        @close-product-modal.window="
            showProductModal = false;
            document.body.style.overflow = 'auto';
        "
        x-show="showProductModal" 
        class="product-modal-overlay" 
        style="display: none;"
    >
        <div class="product-modal-content" @click.stop>
            <button @click="$dispatch('close-product-modal')" class="pm-close-btn">
                <i class="fas fa-times"></i>
            </button>

            <div class="pm-left">
                <div class="pm-main-image">
                    <img :src="activeImage" :alt="activeProduct?.nama">
                </div>
                
                <template x-if="activeProduct?.images && activeProduct.images.length > 1">
                    <div class="pm-thumbnails">
                        <template x-for="(img, idx) in activeProduct.images" :key="idx">
                            <button @click="activeImage = img" 
                                    class="pm-thumb"
                                    :class="activeImage === img ? 'active' : ''">
                                <img :src="img">
                            </button>
                        </template>
                    </div>
                </template>
            </div>

            <div class="pm-right">
                <div class="pm-store-badge">
                    <i class="fas fa-store"></i> <span x-text="activeProduct?.usaha"></span>
                </div>
                
                <h2 class="pm-title" x-text="activeProduct?.nama"></h2>
                
                <div class="pm-price-box">
                    <div class="pm-price" x-text="activeProduct?.harga"></div>
                </div>
                
                <div class="pm-meta-row">
                    <div class="pm-meta-item">
                        <i class="fas fa-star"></i>
                        <strong x-text="activeProduct?.rating"></strong>
                        <span>(<span x-text="activeProduct?.ulasan_count"></span> Penilaian)</span>
                    </div>
                    <div class="pm-meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span x-text="activeProduct?.lokasi"></span>
                    </div>
                </div>

                <div class="pm-desc-title">Deskripsi Produk</div>
                <div class="pm-desc" x-text="activeProduct?.deskripsi"></div>

                <div class="pm-actions">
                    <template x-if="waLink">
                        <a :href="waLink" target="_blank" class="pm-btn-wa">
                            <i class="fab fa-whatsapp" style="font-size: 18px;"></i> Chat Penjual Sekarang
                        </a>
                    </template>
                    <template x-if="!waLink">
                        <button disabled class="pm-btn-disabled">
                            <i class="fas fa-phone-slash" style="font-size: 18px;"></i> Kontak Belum Tersedia
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Review Form Modal -->
    <div x-data="{
        showForm: false,
        umkmId: '',
        umkmName: '',
        rating: 5,
        hoverRating: 0,
        closeForm() {
            this.showForm = false;
            document.body.style.overflow = '';
        }
    }" @open-review-form.window="umkmId = $event.detail.id; umkmName = $event.detail.name; rating = 5; showForm = true; document.body.style.overflow = 'hidden';">
        
        <!-- Overlay -->
        <div x-show="showForm" 
             x-transition.opacity.duration.300ms
             style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(6px); z-index: 10000;" 
             @click="closeForm()" x-cloak></div>
        
        <!-- Modal Content -->
        <div x-show="showForm" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; width: calc(100vw - 32px); max-width: 400px; border-radius: 24px; padding: 32px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); z-index: 10001; border: 1px solid rgba(226, 232, 240, 0.8); margin: 0;" x-cloak>
            
            <button type="button" @click="closeForm()" style="position: absolute; top: 16px; right: 16px; width: 32px; height: 32px; border-radius: 50%; background: #f1f5f9; border: none; font-size: 16px; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'; this.style.color='#0f2e5c'" onmouseout="this.style.background='#f1f5f9'; this.style.color='#64748b'">
                <i class="fas fa-times"></i>
            </button>
            
            <div style="text-align: center; margin-bottom: 24px;">
                <div style="width: 56px; height: 56px; background: #eff6ff; color: #3b82f6; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 16px;">
                    <i class="far fa-star"></i>
                </div>
                <h3 style="font-family: 'Inter', sans-serif; font-size: 20px; font-weight: 800; color: #1e293b; margin-bottom: 6px;">Berikan Ulasan</h3>
                <p style="font-size: 13px; color: #64748b; margin: 0; line-height: 1.5;">Bagaimana pengalaman Anda dengan <br><strong x-text="umkmName" style="color: #0f2e5c;"></strong>?</p>
            </div>

            <form :action="'{{ url('katalog-umkm') }}/' + umkmId + '/rate'" method="POST">
                @csrf
                <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 24px;">
                    <template x-for="i in 5">
                        <i class="fa-star" 
                           :class="(hoverRating ? hoverRating >= i : rating >= i) ? 'fas text-yellow-400' : 'far text-gray-300'"
                           style="font-size: 32px; cursor: pointer; transition: all 0.2s;"
                           :style="(hoverRating ? hoverRating >= i : rating >= i) ? 'color: #fbbf24; transform: scale(1.15);' : 'color: #cbd5e1; transform: scale(1);'"
                           @mouseenter="hoverRating = i"
                           @mouseleave="hoverRating = 0"
                           @click="rating = i"
                        ></i>
                    </template>
                </div>
                <input type="hidden" name="rating" x-model="rating">

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Nama Anda</label>
                    @auth
                        <input type="text" name="nama_reviewer" value="{{ auth()->user()->name }}" readonly style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-family: 'Inter', sans-serif; font-size: 14px; outline: none; background: #f1f5f9; color: #64748b; cursor: not-allowed;">
                    @else
                        <input type="text" name="nama_reviewer" required placeholder="Tuliskan nama Anda..." style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-family: 'Inter', sans-serif; font-size: 14px; outline: none; transition: all 0.3s; background: #f8fafc;" onfocus="this.style.borderColor='#0f2e5c'; this.style.background='white';" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
                    @endauth
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Komentar <span style="color: #94a3b8; font-weight: 500;">(Opsional)</span></label>
                    <textarea name="ulasan" rows="3" placeholder="Ceritakan pengalaman Anda..." style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-family: 'Inter', sans-serif; font-size: 14px; outline: none; resize: none; transition: all 0.3s; background: #f8fafc;" onfocus="this.style.borderColor='#0f2e5c'; this.style.background='white';" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';"></textarea>
                </div>

                <button type="submit" style="width: 100%; background: linear-gradient(135deg, #0f2e5c 0%, #1a498b 100%); color: white; border: none; padding: 16px; border-radius: 12px; font-weight: 800; font-size: 15px; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 12px rgba(15, 46, 92, 0.2);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(15, 46, 92, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(15, 46, 92, 0.2)';">
                    Kirim Ulasan Sekarang
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" style="position: fixed; bottom: 24px; right: 24px; background: #10b981; color: white; padding: 16px 24px; border-radius: 16px; font-weight: 600; font-size: 14px; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3); z-index: 10000; display: flex; align-items: center; gap: 12px;">
        <i class="fas fa-check-circle" style="font-size: 18px;"></i>
        {{ session('success') }}
    </div>
    @endif

    <footer class="footer reveal">
        <div class="footer-container">
            <div class="footer-grid">
                <div>
                    <div class="footer-logo">
                        <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo Mandalaloka" class="footer-logo-img">
                        <span class="footer-logo-text">Mandalaloka</span>
                    </div>
                    <p class="footer-about">Portal Layanan dan Sistem Informasi Pengelolaan Data UMKM Terpadu di Lingkungan Kecamatan Mandalajati, Pemerintah Kota Bandung.</p>
                </div>
                <div>
                    <h4 style="margin-bottom: 20px; font-family: 'Merriweather', serif;">Tautan Cepat</h4>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <a href="{{ route('welcome') }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">Beranda</a>
                        <a href="{{ route('katalog.umkm') }}" style="color: var(--gold); text-decoration: none;">Katalog UMKM</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} Kecamatan Mandalajati. Seluruh Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        function toggleMenu() {
            const menu = document.getElementById('mobileMenu');
            const overlay = document.getElementById('mobileOverlay');
            menu.classList.toggle('active');
            overlay.classList.toggle('active');
            document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
        }

        // Navbar Scroll Effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 20) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Scroll Animation Observer (Premium Smooth Reveal)
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        const elementsToReveal = document.querySelectorAll('.reveal');
        elementsToReveal.forEach((el, index) => {
            if(el.classList.contains('umkm-card')) {
                 el.style.transitionDelay = `${(index % 4) * 0.15}s`;
            }
            observer.observe(el);
        });

        // Like Review Function
        function likeReview(id, btn) {
            fetch(`{{ url('katalog-umkm/ulasan') }}/${id}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btn.style.color = '#3b82f6';
                    btn.querySelector('i').classList.remove('far');
                    btn.querySelector('i').classList.add('fas');
                    btn.querySelector('.like-count').innerText = data.likes;
                } else if (data.message) {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Dislike Review Function
        function dislikeReview(id, btn) {
            fetch(`{{ url('katalog-umkm/ulasan') }}/${id}/dislike`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btn.style.color = '#ef4444';
                    btn.querySelector('i').classList.remove('far');
                    btn.querySelector('i').classList.add('fas');
                    btn.querySelector('.dislike-count').innerText = data.dislikes;
                } else if (data.message) {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
        // Unified AJAX Fetch & Render Function
        function fetchCatalog(url, scrollToTop = false) {
            const container = document.querySelector('.catalog-container');
            if (!container) return;

            // Buat loader overlay jika belum ada
            let loader = document.querySelector('.catalog-loader');
            if (!loader) {
                loader = document.createElement('div');
                loader.className = 'catalog-loader';
                loader.innerHTML = '<div class="catalog-spinner"></div>';
                container.appendChild(loader);
            }
            
            // Tampilkan loader
            loader.classList.add('active');
            container.style.pointerEvents = 'none';
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContainer = doc.querySelector('.catalog-container');
                const newFilterSection = doc.querySelector('.filter-section');
                
                if (newContainer) {
                    // Ganti konten container
                    container.innerHTML = newContainer.innerHTML;
                    
                    // Inisialisasi ulang IntersectionObserver untuk elemen baru
                    const newElements = container.querySelectorAll('.reveal');
                    newElements.forEach((el, index) => {
                        if(el.classList.contains('umkm-card')) {
                             el.style.transitionDelay = `${(index % 4) * 0.15}s`;
                        }
                        observer.observe(el);
                    });
                    
                    if (scrollToTop) {
                        window.scrollTo({
                            top: container.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                }

                // Update tombol reset filter secara dinamis
                const filterSection = document.querySelector('.filter-section');
                if (filterSection && newFilterSection) {
                    const oldReset = filterSection.querySelector('.filter-reset');
                    const newReset = newFilterSection.querySelector('.filter-reset');
                    
                    if (oldReset) oldReset.remove();
                    if (newReset) {
                        const form = filterSection.querySelector('.filter-form');
                        if (form) {
                            form.appendChild(newReset);
                            // Bind klik pada tombol reset baru
                            newReset.addEventListener('click', function(e) {
                                e.preventDefault();
                                const searchInput = form.querySelector('input[name="search"]');
                                const sektorSelect = form.querySelector('select[name="sektor"]');
                                if (searchInput) searchInput.value = '';
                                if (sektorSelect) sektorSelect.value = '';
                                triggerFilter();
                            });
                        }
                    }
                }
                
                // Perbarui URL browser tanpa reload
                window.history.pushState({}, '', url);
            })
            .catch(err => {
                console.error('Error fetching catalog:', err);
                if (!scrollToTop) {
                    window.location.href = url; // Fallback
                }
            })
            .finally(() => {
                const activeLoader = document.querySelector('.catalog-loader');
                if (activeLoader) activeLoader.classList.remove('active');
                container.style.pointerEvents = 'auto';
            });
        }

        // AJAX Filtering Handlers untuk input & select
        const filterForm = document.querySelector('.filter-form');
        if (filterForm) {
            const searchInput = filterForm.querySelector('input[name="search"]');
            const sektorSelect = filterForm.querySelector('select[name="sektor"]');
            const resetButton = filterForm.querySelector('.filter-reset');

            let debounceTimer;

            function triggerFilter() {
                const searchVal = searchInput ? searchInput.value : '';
                const sektorVal = sektorSelect ? sektorSelect.value : '';
                
                const params = new URLSearchParams();
                if (searchVal) params.set('search', searchVal);
                if (sektorVal) params.set('sektor', sektorVal);
                
                const url = `{{ route('katalog.umkm') }}?${params.toString()}`;
                fetchCatalog(url, false);
            }

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(triggerFilter, 400); // 400ms debounce
                });
            }

            if (sektorSelect) {
                sektorSelect.addEventListener('change', triggerFilter);
            }

            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                clearTimeout(debounceTimer);
                triggerFilter();
            });

            if (resetButton) {
                resetButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (searchInput) searchInput.value = '';
                    if (sektorSelect) sektorSelect.value = '';
                    triggerFilter();
                });
            }
        }

        // Smooth Pagination Event Listener
        document.addEventListener('click', function(e) {
            const link = e.target.closest('.katalog-pagination .page-link:not(.disabled)');
            if (link) {
                e.preventDefault();
                const url = link.href;
                if (url) {
                    fetchCatalog(url, true);
                }
            }
        });
    </script>
</body>
</html>
