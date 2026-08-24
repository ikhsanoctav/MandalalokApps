<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warta Dinas & Informasi Publik | Mandalaloka Kecamatan Mandalajati</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo_Mandalaloka.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">
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
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            overflow-x: hidden;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #07152b 0%, #0f2e5c 60%, #17427d 100%);
            position: relative;
            padding-top: 130px;
            padding-bottom: 90px;
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
            background: radial-gradient(circle, rgba(212, 175, 55, 0.18) 0%, rgba(212, 175, 55, 0) 70%);
            border-radius: 50%;
            z-index: 0;
            pointer-events: none;
        }

        /* News Card */
        .news-card {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(15, 46, 92, 0.05);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .news-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(15, 46, 92, 0.12);
            border-color: rgba(212, 175, 55, 0.4);
        }

        .news-img-wrapper {
            position: relative;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            background: #e2e8f0;
        }

        .news-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .news-card:hover .news-img {
            transform: scale(1.08);
        }

        .news-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            background: rgba(15, 46, 92, 0.85);
            backdrop-filter: blur(8px);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 9999px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            letter-spacing: 0.05em;
            text-transform: uppercase;
            z-index: 2;
        }

        .category-pill {
            transition: all 0.25s ease;
        }
        .category-pill.active {
            background: #0f2e5c;
            color: #ffffff;
            border-color: #0f2e5c;
            box-shadow: 0 4px 12px rgba(15, 46, 92, 0.2);
        }
        .category-pill:not(.active):hover {
            background: #e2e8f0;
            color: #0f2e5c;
        }
    </style>
</head>

<body class="antialiased">

    <!-- Premium Navigation Pill -->
    <div class="fixed top-4 left-4 right-4 sm:top-6 sm:left-6 sm:right-6 z-50 flex justify-center pointer-events-none">
        <nav class="pointer-events-auto bg-white/95 backdrop-blur-md rounded-full border border-white/75 shadow-[0_18px_45px_rgba(15,46,92,0.1)] w-full max-w-7xl flex justify-between items-center transition-all duration-300" style="padding: 8px 16px 8px 24px;">
            <div class="flex items-center gap-4">
                <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo Mandalaloka" class="h-10 w-auto rounded-md group-hover:scale-105 transition-transform">
                    <div class="flex flex-col">
                        <span class="text-[16px] sm:text-[18px] font-black text-[#0f2e5c] leading-[1.1] tracking-wide">Mandalaloka</span>
                        <span class="text-[9px] sm:text-[10px] text-slate-500 font-semibold tracking-widest uppercase mt-0.5">Pendataan UMKM</span>
                    </div>
                </a>
            </div>
            
            <div class="hidden lg:flex items-center gap-6 xl:gap-8">
                <a href="{{ route('welcome') }}#home" class="text-[14px] font-bold text-[#475569] hover:text-[#d97706] transition-colors py-2">Beranda</a>
                <a href="{{ route('welcome') }}#statistik" class="text-[14px] font-bold text-[#475569] hover:text-[#d97706] transition-colors py-2">Data UMKM</a>
                <a href="{{ route('katalog.umkm') }}" class="text-[14px] font-bold text-[#475569] hover:text-[#d97706] transition-colors py-2">Katalog UMKM</a>
                <a href="{{ route('welcome') }}#peta-umkm" class="text-[14px] font-bold text-[#475569] hover:text-[#d97706] transition-colors py-2">Peta</a>
                <a href="{{ route('warta.index') }}" class="text-[14px] font-bold text-[#d97706] transition-colors py-2 border-b-2 border-[#d97706]">Warta Publik</a>
            </div>
            
            <div class="hidden lg:flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-[#1e3a8a] text-white px-5 py-2.5 rounded-full text-[13px] font-bold shadow-md hover:bg-[#1e40af] transition-colors whitespace-nowrap">Masuk ke Dasbor</a>
                @else
                    <a href="{{ route('login') }}" class="bg-white text-[#1e3a8a] border border-slate-300 px-5 py-2.5 rounded-full text-[13px] font-bold shadow-sm hover:bg-slate-50 transition-colors whitespace-nowrap">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-[#1e3a8a] text-white px-5 py-2.5 rounded-full text-[13px] font-bold shadow-md hover:bg-[#1e40af] transition-colors whitespace-nowrap">Daftar UMKM</a>
                @endif
            </div>
            
            <!-- Mobile Menu Button -->
            <a href="{{ route('welcome') }}" class="lg:hidden bg-[#f1f5f9] text-[#0f2e5c] px-4 py-2 rounded-full text-sm font-bold border border-slate-200">
                <i class="fas fa-home mr-1.5"></i> Beranda
            </a>
        </nav>
    </div>

    <!-- Hero Header -->
    <header class="hero-section">
        <div class="hero-glow"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 relative z-10 text-center">
            
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 text-[#fcd34d] text-xs font-bold uppercase tracking-wider mb-5 border border-white/20 backdrop-blur-md">
                <i class="fas fa-bullhorn text-sm"></i> Portal Informasi Resmi
            </div>

            <h1 class="hero-title text-3xl sm:text-5xl lg:text-6xl font-black text-white leading-tight mb-5">
                Warta Dinas & Informasi Publik
            </h1>
            <p class="text-slate-200 text-sm sm:text-base lg:text-lg max-w-2xl mx-auto mb-8 font-medium">
                Pemberitaan resmi, program pemberdayaan, agenda pelatihan, dan regulasi terkini seputar ekosistem UMKM Kecamatan Mandalajati.
            </p>

            <!-- Search Form -->
            <form action="{{ route('warta.index') }}" method="GET" class="max-w-2xl mx-auto">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                <div class="relative flex items-center bg-white/95 rounded-full p-2 shadow-2xl border border-white/40 focus-within:ring-4 focus-within:ring-amber-400/30 transition-all">
                    <i class="fas fa-search text-slate-400 pl-4 text-lg"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari judul berita, pelatihan, NIB, atau topik..." 
                        class="w-full bg-transparent pl-3 pr-4 py-2.5 text-sm sm:text-base text-slate-800 placeholder-slate-400 focus:outline-none font-medium">
                    <button type="submit" class="bg-[#0f2e5c] hover:bg-[#164380] text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-md transition-all whitespace-nowrap">
                        Cari Warta
                    </button>
                </div>
            </form>

        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Category Filters & Active Status -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-10 pb-6 border-b border-slate-200">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('warta.index', array_filter(['search' => request('search')])) }}" 
                   class="category-pill px-4 py-2 rounded-full text-xs font-bold border border-slate-300 {{ !request('kategori') ? 'active' : 'bg-white text-slate-600' }}">
                    Semua Topik
                </a>
                @foreach($kategoris as $kat)
                    <a href="{{ route('warta.index', array_filter(['kategori' => $kat, 'search' => request('search')])) }}" 
                       class="category-pill px-4 py-2 rounded-full text-xs font-bold border border-slate-300 {{ request('kategori') == $kat ? 'active' : 'bg-white text-slate-600' }}">
                        {{ $kat }}
                    </a>
                @endforeach
            </div>

            @if(request('search') || request('kategori'))
                <div class="flex items-center gap-2 text-xs text-slate-500 font-semibold">
                    <span>Filter aktif: 
                        @if(request('search')) "<strong>{{ request('search') }}</strong>" @endif
                        @if(request('kategori')) [<strong>{{ request('kategori') }}</strong>] @endif
                    </span>
                    <a href="{{ route('warta.index') }}" class="text-rose-600 hover:underline font-bold ml-2">
                        <i class="fas fa-times-circle"></i> Reset Filter
                    </a>
                </div>
            @endif
        </div>

        <!-- News Cards Grid -->
        @if($beritaList->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach($beritaList as $b)
                <article class="news-card group">
                    <a href="{{ route('warta.show', $b->id) }}" class="news-img-wrapper block">
                        <img src="{{ $b->gambar_url }}" alt="{{ $b->judul }}" class="news-img" loading="lazy" onerror="this.onerror=null;this.src='{{ $b->dummy_gambar }}';">
                        <span class="news-badge">{{ $b->kategori ?? $b->penulis ?? 'Warta' }}</span>
                    </a>
                    
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-3 text-xs text-slate-500 mb-3 font-semibold">
                                <span><i class="fas fa-user-edit text-amber-500 mr-1.5"></i>{{ $b->penulis ?? 'Humas' }}</span>
                                <span>&bull;</span>
                                <span><i class="fas fa-calendar-alt text-blue-500 mr-1.5"></i>{{ date('d M Y', strtotime($b->published_at ?? $b->created_at)) }}</span>
                            </div>

                            <h2 class="text-xl font-bold text-slate-900 group-hover:text-[#0f2e5c] transition-colors leading-snug mb-3 line-clamp-2">
                                <a href="{{ route('warta.show', $b->id) }}">{{ $b->judul }}</a>
                            </h2>

                            <p class="text-slate-600 text-sm leading-relaxed line-clamp-3 mb-4">
                                {{ Str::limit(strip_tags($b->konten), 140) }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-400 font-medium">
                                <i class="far fa-clock mr-1"></i> {{ max(1, ceil(str_word_count(strip_tags($b->konten)) / 200)) }} mnt baca
                            </span>
                            <a href="{{ route('warta.show', $b->id) }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-[#0f2e5c] hover:text-amber-600 transition-colors">
                                Baca Lengkap <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $beritaList->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20 bg-white rounded-3xl border border-slate-200 shadow-sm my-8">
                <div class="w-20 h-20 mx-auto bg-amber-50 rounded-full flex items-center justify-center text-amber-500 text-3xl mb-4 border border-amber-200">
                    <i class="fas fa-newspaper"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Tidak Ada Warta yang Ditemukan</h3>
                <p class="text-slate-500 max-w-md mx-auto text-sm mb-6">
                    Maaf, tidak ada berita atau warta publik yang cocok dengan kata kunci atau filter pencarian Anda.
                </p>
                <a href="{{ route('warta.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#0f2e5c] text-white rounded-full text-sm font-bold hover:bg-[#164380] transition-colors shadow-md">
                    <i class="fas fa-redo text-xs"></i> Tampilkan Semua Warta
                </a>
            </div>
        @endif

    </main>

    <!-- Footer -->
    <footer class="bg-[#07152b] text-white pt-14 pb-8 border-t border-white/10 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 pb-8 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Mandalaloka" class="h-10 w-auto rounded-md">
                    <div>
                        <div class="font-black text-lg text-white">Mandalaloka</div>
                        <div class="text-xs text-slate-400">Portal Resmi Pemberdayaan & Pendataan UMKM Kecamatan Mandalajati</div>
                    </div>
                </div>
                <div class="flex gap-6 text-sm font-semibold text-slate-300">
                    <a href="{{ route('welcome') }}" class="hover:text-amber-400 transition-colors">Beranda</a>
                    <a href="{{ route('katalog.umkm') }}" class="hover:text-amber-400 transition-colors">Katalog Produk</a>
                    <a href="{{ route('warta.index') }}" class="text-amber-400 font-bold">Warta Publik</a>
                    <a href="{{ route('login') }}" class="hover:text-amber-400 transition-colors">Masuk</a>
                </div>
            </div>
            <div class="text-center text-xs text-slate-500 pt-6">
                &copy; {{ date('Y') }} Pemerintah Kecamatan Mandalajati - Kota Bandung. Seluruh hak cipta dilindungi.
            </div>
        </div>
    </footer>

</body>
</html>
