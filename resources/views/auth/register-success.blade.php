<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - Mandalaloka | Sistem Pendataan UMKM</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo_Mandalaloka.png') }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Merriweather:ital,wght@0,400;0,700;0,900;1,400&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        official: {
                            navy: '#0f2e5c',
                            /* Deep Navy Blue */
                            navylight: '#1a498b',
                            gold: '#d4af37',
                            /* Elegant Gold */
                            golddark: '#b8902d',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Merriweather', 'serif'],
                    }
                }
            }
        }
    </script>

    <style>
        /* Indonesian Red & White Top Ribbon */
        .top-ribbon {
            height: 4px;
            width: 100%;
            background: linear-gradient(90deg, #ef4444 50%, #ffffff 50%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1001;
        }

        .glass-card {
            background: rgba(15, 46, 92, 0.4);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(212, 175, 55, 0.2);
            box-shadow: 0 24px 40px rgba(0, 0, 0, 0.2);
        }

        .slideshow-image {
            transition: opacity 1.5s ease-in-out;
        }

        .glow-surface {
            box-shadow: 0 24px 96px rgba(212, 175, 55, 0.08), 0 0 46px rgba(212, 175, 55, 0.05);
        }

        .button-glow {
            transition: box-shadow 0.3s ease, transform 0.2s ease, background-color 0.3s ease;
        }

        .button-glow:hover {
            box-shadow: 0 8px 25px rgba(15, 46, 92, 0.25);
            transform: translateY(-2px);
        }

        .brand-logo-card {
            width: 96px;
            height: 96px;
            box-shadow: 0 10px 30px rgba(15, 46, 92, 0.08);
            border: 1px solid rgba(212, 175, 55, 0.3);
            background: white;
        }

        .modal-fade-in {
            animation: modalFadeIn 260ms ease-out forwards;
        }

        .modal-fade-out {
            animation: modalFadeOut 220ms ease-in forwards;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-18px) scale(0.96);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes modalFadeOut {
            from {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

            to {
                opacity: 0;
                transform: translateY(-18px) scale(0.96);
            }
        }

        /* Mega Mendung Custom Pattern Overlay untuk Sisi Gelap (Kiri) */
        .mega-mendung-dark {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='72' viewBox='0 0 120 72'%3E%3Cpath d='M0 36 C10 20, 30 20, 40 36 C50 52, 70 52, 80 36 C90 20, 110 20, 120 36 M0 12 C15 0, 35 0, 45 12 C55 24, 75 24, 85 12 C95 0, 115 0, 120 12 M0 60 C10 48, 25 48, 35 60 C45 72, 65 72, 75 60 C85 48, 105 48, 120 60' fill='none' stroke='%23d4af37' stroke-width='1.5' stroke-opacity='0.15'/%3E%3Cpath d='M20 24 C25 15, 35 15, 40 24 C45 33, 55 33, 60 24 C65 15, 75 15, 80 24' fill='none' stroke='%23ffffff' stroke-width='1' stroke-opacity='0.08'/%3E%3C/svg%3E");
            background-repeat: repeat;
        }

        /* Mega Mendung Custom Pattern Overlay untuk Sisi Terang (Kanan) */
        .mega-mendung-light {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='72' viewBox='0 0 120 72'%3E%3Cpath d='M0 36 C10 20, 30 20, 40 36 C50 52, 70 52, 80 36 C90 20, 110 20, 120 36 M0 12 C15 0, 35 0, 45 12 C55 24, 75 24, 85 12 C95 0, 115 0, 120 12 M0 60 C10 48, 25 48, 35 60 C45 72, 65 72, 75 60 C85 48, 105 48, 120 60' fill='none' stroke='%230f2e5c' stroke-width='1.2' stroke-opacity='0.03'/%3E%3Cpath d='M20 24 C25 15, 35 15, 40 24 C45 33, 55 33, 60 24 C65 15, 75 15, 80 24' fill='none' stroke='%23d4af37' stroke-width='1' stroke-opacity='0.04'/%3E%3C/svg%3E");
            background-repeat: repeat;
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen font-sans antialiased selection:bg-official-gold selection:text-official-navy">

    <div class="top-ribbon"></div>

    @php
        $loginErrorMessage = session('error') ?? (session('status') ?? ($errors->any() ? $errors->first() : null));
        if ($loginErrorMessage && str_contains($loginErrorMessage, 'credentials do not match')) {
            $loginErrorMessage = 'Email atau kata sandi tidak sesuai dengan basis data kami.';
        }
    @endphp

    @if ($loginErrorMessage)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof window.showToast !== 'undefined') {
                    window.showToast('error', 'Otentikasi Gagal', @json($loginErrorMessage));
                }
            });
        </script>
    @endif
    @include('partials.toast-container')

    <div class="min-h-screen flex flex-col lg:flex-row">
        
        <div class="relative lg:w-3/5 overflow-hidden bg-official-navy">
            <div id="slideshow" class="absolute inset-0">
                <div class="slideshow-image absolute inset-0 opacity-100">
                    <img src="https://images.unsplash.com/photo-1556740738-b6a63e27c4df?auto=format&fit=crop&q=80&w=2070"
                        class="w-full h-full object-cover" alt="Kegiatan UMKM 1">
                </div>
                <div class="slideshow-image absolute inset-0 opacity-0">
                    <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=2074"
                        class="w-full h-full object-cover" alt="Kegiatan UMKM 2">
                </div>
                <div class="slideshow-image absolute inset-0 opacity-0">
                    <img src="https://images.unsplash.com/photo-1511317551248-2b100022cf94?auto=format&fit=crop&q=80&w=2076"
                        class="w-full h-full object-cover" alt="Kegiatan UMKM 3">
                </div>
            </div>

            <div class="absolute inset-0 bg-official-navy/60 mix-blend-multiply"></div>

            <div class="absolute inset-0 mega-mendung-dark opacity-80 mix-blend-screen"></div>

            <div class="absolute inset-0 bg-gradient-to-t from-official-navy via-official-navy/40 to-transparent"></div>

            <div class="absolute inset-0">
                <div class="absolute -left-24 top-20 w-72 h-72 rounded-full bg-official-gold/10 blur-3xl"></div>
                <div class="absolute right-0 bottom-10 w-96 h-96 rounded-full bg-official-navylight/30 blur-3xl"></div>
            </div>

            <div class="relative z-10 flex min-h-screen items-center justify-center p-6 sm:p-12">
                <div class="glass-card w-full max-w-lg rounded-[2rem] p-10">
                    <div
                        class="inline-flex items-center justify-center rounded-2xl bg-white/10 border border-official-gold/30 p-4 mb-8">
                        <i class="fas fa-shield-alt text-2xl text-official-gold"></i>
                    </div>
                    <h1
                        class="text-4xl sm:text-5xl font-extrabold leading-tight tracking-tight text-white font-serif mb-2">
                        Portal Resmi<br>
                        <span class="text-official-gold">Mandalaloka</span>
                    </h1>
                    <p class="mt-6 text-base text-slate-200 leading-relaxed">
                        Sistem basis data dan informasi kelola UMKM terintegrasi di lingkungan Kecamatan Mandalajati.
                        Fasilitas pelayanan satu pintu untuk administrasi dan pembinaan usaha berskala mikro hingga
                        menengah.
                    </p>
                    <p class="mt-8 text-xs uppercase tracking-[0.2em] text-official-gold font-semibold">
                        Akses Terbatas • Kecamatan Mandalajati
                    </p>
                    <div class="mt-10 text-xs text-white/50 border-t border-white/10 pt-4">
                        © 2026 Kecamatan Mandalajati. Hak Cipta Dilindungi Undang-Undang.
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-2/5 relative flex min-h-screen items-center justify-center p-6 lg:p-12 bg-slate-50">

            <div class="absolute inset-0 mega-mendung-light pointer-events-none"></div>

            <a href="{{ url('/') }}"
                class="absolute right-6 top-6 lg:right-10 lg:top-10 inline-flex items-center justify-center rounded-full bg-white/90 backdrop-blur-sm border border-slate-200 px-5 py-2.5 text-sm font-semibold text-official-navy shadow-sm transition hover:bg-slate-50 hover:border-official-gold hover:text-official-golddark group z-20">
                <i class="fas fa-arrow-left mr-2 transition-transform group-hover:-translate-x-1"></i> Kembali
            </a>

            <div
                class="w-full max-w-md space-y-10 mt-12 lg:mt-0 relative z-10 bg-white/60 backdrop-blur-md p-8 rounded-[2rem] shadow-xl sm:shadow-none sm:p-0 sm:bg-transparent">
                <div class="text-center">
                    <div
                        class="inline-flex items-center justify-center rounded-[1.5rem] mb-6 overflow-hidden brand-logo-card mx-auto">
                        <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo Mandalaloka"
                            class="w-3/4 h-3/4 object-contain" />
                    </div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-green-600 mb-3">Pendaftaran Berhasil</p>
                    <h2 class="text-3xl font-bold text-official-navy font-serif">Selamat Bergabung!</h2>
                    
                    <div class="mt-8 mb-8">
                        <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-check text-4xl text-green-500"></i>
                        </div>
                        <p class="text-base text-slate-600 leading-relaxed">
                            Terima kasih telah mendaftar di <strong>Portal Mandalaloka</strong>.
                            <br><br>
                            Silakan lakukan login untuk masuk ke Dasbor dan melengkapi data profil pribadi maupun data UMKM Anda.
                        </p>
                    </div>

                    <a href="{{ route('login') }}"
                        class="w-full bg-official-navy text-white rounded-full py-4 font-semibold text-sm flex items-center justify-center gap-2 button-glow">
                        <span>Menuju Halaman Login</span>
                        <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="text-center text-xs text-slate-400 mt-8">
                    <p>Mandalaloka - Sistem Pendataan UMKM Terpadu</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('.slideshow-image');
            let currentSlide = 0;

            const nextSlide = () => {
                slides[currentSlide].style.opacity = '0';
                currentSlide = (currentSlide + 1) % slides.length;
                slides[currentSlide].style.opacity = '1';
            };



            if (slides.length > 1) {
                setInterval(nextSlide, 5000);
            }
        });
    </script>
</body>

</html>
