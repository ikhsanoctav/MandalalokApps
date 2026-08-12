<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $berita->judul }} | Warta Mandalaloka</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo_Mandalaloka.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --primary: #0a1930;
            --primary-light: #16325c;
            --gold: #d4af37;
            --gold-light: #f3e5ab;
            --batik-pattern: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M30 30c0-8.284 6.716-15 15-15 8.284 0 15 6.716 15 15 0 8.284-6.716 15-15 15-8.284 0-15-6.716-15-15zm-15 0c0-8.284-6.716-15-15-15C6.716 15 0 21.716 0 30c0 8.284 6.716 15 15 15 8.284 0 15-6.716 15-15zM15 15c0-8.284 6.716-15 15-15 8.284 0 15 6.716 15 15 0 8.284-6.716 15-15 15-8.284 0-15-6.716-15-15zM15 45c0-8.284 6.716-15 15-15 8.284 0 15 6.716 15 15 0 8.284-6.716 15-15 15-8.284 0-15-6.716-15-15z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8;
            color: #1e293b;
            overflow-x: hidden;
        }

        /* Glassmorphism Header */
        .glass-nav {
            background: rgba(10, 25, 48, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 50;
        }

        /* Progress Bar */
        #progress-container {
            width: 100%;
            height: 3px;
            background: rgba(255, 255, 255, 0.1);
            position: absolute;
            bottom: -1px;
            left: 0;
        }

        #progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--gold), #ffdf73);
            width: 0%;
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
            transition: width 0.1s ease;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            position: relative;
            padding-top: 120px;
            padding-bottom: 180px;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: var(--batik-pattern);
            background-size: 60px;
            z-index: 1;
        }

        .hero-glow {
            position: absolute;
            top: -20%;
            left: 50%;
            transform: translateX(-50%);
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.15) 0%, rgba(212, 175, 55, 0) 70%);
            border-radius: 50%;
            z-index: 0;
            pointer-events: none;
        }

        /* Main Content Card */
        .content-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(10, 25, 48, 0.25);
            margin-top: -120px;
            position: relative;
            z-index: 10;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        @media (min-width: 768px) {
            .content-card {
                padding: 60px;
            }
        }

        /* Featured Image */
        .featured-image-container {
            width: 100%;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            position: relative;
            margin-bottom: 3rem;
            transform: translateY(0);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .featured-image-container:hover {
            transform: translateY(-5px);
        }

        .featured-image-container img {
            width: 100%;
            height: auto;
            max-height: 550px;
            object-fit: cover;
            display: block;
        }

        .image-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0) 100%);
            color: rgba(255, 255, 255, 0.9);
            padding: 30px 20px 15px 20px;
            font-size: 0.875rem;
            font-style: italic;
            text-align: right;
        }

        /* Typography */
        .article-title {
            font-family: 'Inter', sans-serif;
            color: #ffffff;
            letter-spacing: -0.02em;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 2;
        }

        .article-content {
            font-family: 'Merriweather', serif;
            font-size: 1.125rem;
            line-height: 2;
            color: #334155;
        }

        @media (min-width: 768px) {
            .article-content {
                font-size: 1.25rem;
            }
        }

        .article-content p {
            margin-bottom: 2rem;
        }

        /* Stunning Dropcap */
        .article-content > p:first-of-type::first-letter {
            float: left;
            font-size: 5.5rem;
            line-height: 0.8;
            padding-right: 1rem;
            padding-top: 0.5rem;
            color: var(--primary);
            font-weight: 900;
            font-family: 'Inter', sans-serif;
            text-shadow: 2px 2px 0px rgba(212, 175, 55, 0.4);
        }

        .article-content h2 {
            font-family: 'Inter', sans-serif;
            color: var(--primary);
            margin-top: 3.5rem;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.3;
            letter-spacing: -0.02em;
        }

        .article-content h3 {
            font-family: 'Inter', sans-serif;
            color: var(--primary-light);
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .article-content blockquote {
            position: relative;
            border-left: 5px solid var(--gold);
            padding: 2.5rem 3rem;
            margin: 3.5rem 0;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            font-style: italic;
            font-size: 1.4rem;
            color: var(--primary);
            border-radius: 0 20px 20px 0;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }
        
        .article-content blockquote::before {
            content: '\201C';
            position: absolute;
            top: -15px;
            left: 15px;
            font-size: 6rem;
            color: rgba(212, 175, 55, 0.15);
            font-family: 'Merriweather', serif;
            line-height: 1;
        }

        .article-content img {
            border-radius: 16px;
            margin: 3.5rem auto;
            max-width: 100%;
            height: auto;
            box-shadow: 0 15px 35px rgba(10, 25, 48, 0.1);
            transition: transform 0.4s ease;
        }
        
        .article-content img:hover {
            transform: scale(1.02);
        }

        /* Buttons */
        .btn-glass {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Share Menu */
        .share-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }
        
        .share-circle:hover {
            transform: translateY(-5px) scale(1.1);
            color: white;
            border-color: transparent;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .share-circle.fb:hover { background: #1877f2; box-shadow: 0 10px 15px rgba(24, 119, 242, 0.3); }
        .share-circle.tw:hover { background: #1da1f2; box-shadow: 0 10px 15px rgba(29, 161, 242, 0.3); }
        .share-circle.wa:hover { background: #25d366; box-shadow: 0 10px 15px rgba(37, 211, 102, 0.3); }
        .share-circle.link:hover { background: var(--primary); box-shadow: 0 10px 15px rgba(10, 25, 48, 0.3); }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-up {
            animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>

<body class="antialiased">

    <!-- Premium Navigation -->
    <nav class="glass-nav transition-all duration-300">
        <div class="w-full px-4 sm:px-6 lg:px-12">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-4 sm:gap-6">
                    <a href="{{ route('welcome') }}#bantuan" class="btn-glass flex items-center gap-2 px-4 py-2 rounded-full font-medium text-sm transition-all hover:-translate-x-1" title="Kembali ke Beranda">
                        <i class="fas fa-arrow-left"></i>
                        <span class="hidden sm:inline">Kembali</span>
                    </a>
                    <div class="h-8 w-px bg-white/20"></div>
                    <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo" class="w-10 h-10 object-contain drop-shadow-[0_0_12px_rgba(255,255,255,0.6)] group-hover:scale-105 transition-transform duration-300">
                        <div class="flex flex-col">
                            <span class="text-xl font-bold text-white tracking-tight hidden sm:block leading-none">Mandalaloka</span>
                            <span class="text-[10px] text-slate-300 uppercase tracking-widest hidden sm:block mt-1">Portal Kecamatan</span>
                        </div>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="px-5 py-2 rounded-full bg-gradient-to-r from-[#fcd34d] to-[#f59e0b] text-[#0f2e5c] text-xs font-bold tracking-widest uppercase shadow-[0_0_15px_rgba(245,158,11,0.4)] hidden sm:block">
                        <i class="fas fa-newspaper mr-1.5"></i> Warta Kecamatan
                    </div>
                </div>
            </div>
        </div>
        <div id="progress-container">
            <div id="progress-bar"></div>
        </div>
    </nav>

    <!-- Hero Header -->
    <header class="hero-section">
        <div class="hero-glow"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 relative z-10 animate-fade-up">
            
            <!-- Category Badge -->
            <div class="flex justify-center mb-6">
                <span class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-gradient-to-r from-[var(--gold)] to-[#b8902d] text-white text-xs font-bold uppercase tracking-widest shadow-[0_0_20px_rgba(212,175,55,0.4)] border border-[var(--gold-light)]/30">
                    <i class="fas fa-newspaper"></i> Warta Utama
                </span>
            </div>

            <!-- Title -->
            <h1 class="article-title text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-black text-center leading-[1.1] mb-8">
                {{ $berita->judul }}
            </h1>

            <!-- Meta Data (Glass Style) -->
            <div class="flex flex-wrap justify-center items-center gap-6 text-white/80 mt-8 pt-8 border-t border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center border border-white/20 backdrop-blur-md">
                        <i class="fas fa-user-pen text-white text-lg"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-[10px] text-white/50 uppercase tracking-widest font-bold mb-0.5">Penulis</p>
                        <p class="text-white font-semibold text-sm">{{ $berita->penulis ?? 'Humas Mandalaloka' }}</p>
                    </div>
                </div>
                
                <div class="h-10 w-px bg-white/20 hidden sm:block"></div>
                
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center border border-white/20 backdrop-blur-md">
                        <i class="fas fa-calendar-alt text-[var(--gold)] text-lg"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-[10px] text-white/50 uppercase tracking-widest font-bold mb-0.5">Diterbitkan</p>
                        <p class="text-white font-semibold text-sm">{{ \Carbon\Carbon::parse($berita->published_at ?? $berita->created_at)->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </header>

    <!-- Main Content Area -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        
        <div class="content-card animate-fade-up" style="animation-delay: 0.2s;">
            
            <!-- Beautiful Featured Image -->
            @if (!empty($berita->gambar))
                @php 
                    $bgImage = ($berita->gambar == 'news_default.jpg' || empty($berita->gambar)) ? asset('images/Logo_Mandalaloka.png') : Storage::url($berita->gambar);
                @endphp
                <div class="featured-image-container">
                    <img src="{{ $bgImage }}" alt="{{ $berita->judul }}">
                    <div class="image-caption">
                        <i class="fas fa-camera mr-2"></i> Dokumentasi Warta Mandalaloka
                    </div>
                </div>
            @endif

            <!-- Article Body -->
            <article class="article-content mt-8">
                {!! strip_tags($berita->konten, '<p><br><b><i><u><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6><table><thead><tbody><tr><th><td><span><div><a><img><iframe><blockquote><hr>') !!}
            </article>

            <!-- Interactive Footer / Share -->
            <div class="mt-16 pt-10 border-t border-slate-200">
                <div class="bg-gradient-to-r from-[#f8fafc] to-[#f1f5f9] rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row justify-between items-center gap-6 border border-slate-200/60 shadow-inner">
                    
                    <div class="text-center sm:text-left">
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 sm:mb-2">Bagikan Artikel Ini</h4>
                        <div class="flex gap-3 justify-center sm:justify-start">
                            <a href="#" class="share-circle fb" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="share-circle tw" title="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="share-circle wa" title="WhatsApp"><i class="fab fa-whatsapp text-xl"></i></a>
                            <button onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan berhasil disalin!')" class="share-circle link" title="Salin Tautan"><i class="fas fa-link"></i></button>
                        </div>
                    </div>

                    <div class="h-px w-full sm:h-16 sm:w-px bg-slate-200"></div>

                    <div class="text-center sm:text-right">
                        <a href="{{ route('welcome') }}#bantuan" class="inline-flex items-center gap-3 bg-[var(--primary)] hover:bg-[var(--primary-light)] text-white px-8 py-4 rounded-xl font-bold transition-all duration-300 shadow-[0_10px_20px_rgba(10,25,48,0.2)] hover:shadow-[0_15px_30px_rgba(10,25,48,0.3)] hover:-translate-y-1 group">
                            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                            Kembali ke Warta
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <!-- Premium Footer -->
    <footer class="bg-[var(--primary)] text-slate-400 py-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-5" style="background-image: var(--batik-pattern); background-size: 60px;"></div>
        <div class="max-w-5xl mx-auto px-4 relative z-10 text-center">
            <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo" class="w-16 h-16 mx-auto mb-6 opacity-80 drop-shadow-[0_0_15px_rgba(255,255,255,0.2)]">
            <h3 class="text-white font-bold text-xl mb-2 tracking-tight">Kecamatan Mandalajati</h3>
            <p class="text-sm text-slate-400 mb-8 max-w-md mx-auto leading-relaxed">Portal informasi resmi Pemerintah Kecamatan Mandalajati, memberikan layanan informasi terbaik untuk masyarakat.</p>
            <div class="w-24 h-1 bg-[var(--gold)] mx-auto mb-8 rounded-full opacity-50"></div>
            <p class="text-xs tracking-widest uppercase">&copy; {{ date('Y') }} Mandalaloka. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script>
        // Reading Progress Indicator
        window.addEventListener('scroll', () => {
            const docElem = document.documentElement;
            const docBody = document.body;
            const scrollTop = docElem['scrollTop'] || docBody['scrollTop'];
            const scrollBottom = (docElem['scrollHeight'] || docBody['scrollHeight']) - window.innerHeight;
            const scrollPercent = (scrollTop / scrollBottom) * 100;
            document.getElementById('progress-bar').style.width = scrollPercent + '%';
        });
    </script>
</body>

</html>
