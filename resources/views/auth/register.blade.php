<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Mandiri - Mandalaloka | Sistem Pendataan UMKM</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo_Mandalaloka.png') }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Merriweather:ital,wght@0,400;0,700;0,900;1,400&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        official: {
                            navy: '#0f2e5c',
                            navylight: '#1a498b',
                            gold: '#d4af37',
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

        @media (max-width: 640px) {
            html,
            body {
                max-width: 100%;
                overflow-x: hidden;
            }

            input,
            select,
            textarea {
                min-height: 44px;
                font-size: 16px !important;
            }

            button,
            a {
                min-height: 44px;
                touch-action: manipulation;
            }

            .brand-logo-card {
                width: 72px;
                height: 72px;
            }
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen font-sans antialiased selection:bg-official-gold selection:text-official-navy">

    <div class="top-ribbon"></div>

    <div class="min-h-screen flex flex-col lg:flex-row">
        
        <div class="relative lg:w-2/5 overflow-hidden bg-official-navy hidden lg:block">
            <div id="slideshow" class="absolute inset-0">
                <div class="slideshow-image absolute inset-0 opacity-100">
                    <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=2074"
                        class="w-full h-full object-cover" alt="Kegiatan UMKM 1">
                </div>
                <div class="slideshow-image absolute inset-0 opacity-0">
                    <img src="https://images.unsplash.com/photo-1556740738-b6a63e27c4df?auto=format&fit=crop&q=80&w=2070"
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
                        <i class="fas fa-users text-2xl text-official-gold"></i>
                    </div>
                    <h1
                        class="text-4xl sm:text-5xl font-extrabold leading-tight tracking-tight text-white font-serif mb-2">
                        Pendaftaran<br>
                        <span class="text-official-gold">UMKM Mandiri</span>
                    </h1>
                    <p class="mt-6 text-base text-slate-200 leading-relaxed">
                        Langkah awal pengembangan bisnis Anda. Daftarkan usaha Anda untuk mendapatkan fasilitas, bimbingan, serta terhubung dengan ekosistem ekonomi digital Kecamatan Mandalajati.
                    </p>
                    <div class="mt-10 text-xs text-white/50 border-t border-white/10 pt-4">
                        © {{ date('Y') }} Kecamatan Mandalajati. Hak Cipta Dilindungi Undang-Undang.
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-3/5 relative flex min-h-screen items-center justify-center p-6 lg:p-12 bg-slate-50">

            <div class="absolute inset-0 mega-mendung-light pointer-events-none"></div>

            <a href="{{ url('/') }}"
                class="absolute right-6 top-6 lg:right-10 lg:top-10 inline-flex items-center justify-center rounded-full bg-white/90 backdrop-blur-sm border border-slate-200 px-5 py-2.5 text-sm font-semibold text-official-navy shadow-sm transition hover:bg-slate-50 hover:border-official-gold hover:text-official-golddark group z-20">
                <i class="fas fa-arrow-left mr-2 transition-transform group-hover:-translate-x-1"></i> Beranda
            </a>

            <div
                class="w-full max-w-lg space-y-8 mt-12 lg:mt-0 relative z-10 bg-white/60 backdrop-blur-md p-8 rounded-[2rem] shadow-xl sm:shadow-none sm:p-0 sm:bg-transparent">
                <div class="text-center">
                    <div
                        class="inline-flex items-center justify-center rounded-[1.5rem] mb-6 overflow-hidden brand-logo-card mx-auto lg:hidden">
                        <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo Mandalaloka"
                            class="w-3/4 h-3/4 object-contain" />
                    </div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-official-golddark mb-3">Registrasi Entitas Usaha</p>
                    <h2 class="text-3xl font-bold text-official-navy font-serif">Buat Akun Baru</h2>
                    <p class="mt-3 text-sm text-slate-500 leading-relaxed">Lengkapi formulir di bawah ini dengan data yang valid dan sesuai dengan identitas resmi Anda.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{
                    nik: '{{ old('nik') }}',
                    nikMessage: '',
                    nikValid: null,
                    isChecking: false,
                    checkNik() {
                        if (this.nik.length !== 16) {
                            this.nikValid = null;
                            this.nikMessage = '';
                            return;
                        }
                        this.isChecking = true;
                        fetch('{{ route('api.check-nik-register') }}?nik=' + this.nik)
                            .then(res => res.json())
                            .then(data => {
                                this.nikValid = data.valid;
                                this.nikMessage = data.message;
                                this.isChecking = false;
                            }).catch(() => {
                                this.isChecking = false;
                            });
                    }
                }" x-init="if(nik.length === 16) checkNik(); $watch('nik', () => {
                    clearTimeout(window.nikTimeout);
                    window.nikTimeout = setTimeout(() => { checkNik(); }, 300);
                })">
                    @csrf

                    <div class="space-y-2">
                        <label for="name" class="text-xs font-bold uppercase tracking-[0.1em] text-slate-600">Nama Lengkap Pemilik</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-official-navy transition-colors">
                                <i class="fas fa-user text-lg"></i>
                            </div>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Sesuai KTP" class="w-full bg-white/90 border border-slate-200 focus:border-official-gold focus:ring-1 focus:ring-official-gold focus:outline-none rounded-lg py-3.5 pl-12 pr-4 text-sm transition-all placeholder:text-slate-300 shadow-sm">
                        </div>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="text-xs font-bold uppercase tracking-[0.1em] text-slate-600">Alamat Email</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-official-navy transition-colors">
                                <i class="fas fa-envelope text-lg"></i>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="contoh@email.com" class="w-full bg-white/90 border border-slate-200 focus:border-official-gold focus:ring-1 focus:ring-official-gold focus:outline-none rounded-lg py-3.5 pl-12 pr-4 text-sm transition-all placeholder:text-slate-300 shadow-sm">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="nik" class="text-xs font-bold uppercase tracking-[0.1em] text-slate-600">Nomor Induk Kependudukan (NIK)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-official-navy transition-colors">
                                <i class="fas fa-id-card text-lg"></i>
                            </div>
                            <input id="nik" type="text" name="nik" x-model="nik" required maxlength="16" pattern="\d{16}" title="NIK harus berupa 16 digit angka" autocomplete="off" placeholder="Masukkan 16 Digit Angka NIK" class="w-full bg-white/90 border focus:outline-none rounded-lg py-3.5 pl-12 pr-10 text-sm transition-all placeholder:text-slate-300 shadow-sm" :class="nikValid === false ? 'border-rose-500 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 bg-rose-50/30' : (nikValid === true ? 'border-emerald-500 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-emerald-50/30' : 'border-slate-200 focus:border-official-gold focus:ring-1 focus:ring-official-gold')">
                            
                            <!-- Icon Feedback inside input -->
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <div x-show="isChecking" x-transition.opacity.duration.300ms style="display: none;">
                                    <i class="fas fa-spinner fa-spin text-indigo-500"></i>
                                </div>
                                <div x-show="!isChecking && nikValid === true" x-transition.opacity.duration.300ms style="display: none;">
                                    <i class="fas fa-check-circle text-emerald-500 text-lg shadow-sm rounded-full"></i>
                                </div>
                                <div x-show="!isChecking && nikValid === false" x-transition.opacity.duration.300ms style="display: none;">
                                    <i class="fas fa-times-circle text-rose-500 text-lg shadow-sm rounded-full"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Animated Message Text -->
                        <div class="relative h-6 mt-1.5 overflow-hidden">
                            <div x-show="isChecking" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute inset-0 flex items-center" style="display: none;">
                                <p class="text-xs text-indigo-500 font-medium"><i class="fas fa-spinner fa-spin mr-1"></i> Memeriksa NIK ke server...</p>
                            </div>
                            <div x-show="!isChecking && nikValid === true" x-transition:enter="transition ease-out duration-300 delay-150" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute inset-0 flex items-center" style="display: none;">
                                <p class="text-xs text-emerald-600 font-bold"><i class="fas fa-check-circle mr-1"></i> <span x-text="nikMessage"></span></p>
                            </div>
                            <div x-show="!isChecking && nikValid === false" x-transition:enter="transition ease-out duration-300 delay-150" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute inset-0 flex items-center" style="display: none;">
                                <p class="text-xs text-rose-500 font-bold"><i class="fas fa-times-circle mr-1"></i> <span x-text="nikMessage"></span></p>
                            </div>
                            <div x-show="nikValid === null && !isChecking" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="absolute inset-0 flex items-center">
                                <p class="text-xs text-slate-500/80"><i class="fas fa-info-circle mr-1 text-official-gold"></i> NIK akan diverifikasi dan menjadi acuan utama integrasi basis data instansi.</p>
                            </div>
                        </div>
                        @error('nik')
                            <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="no_hp" class="text-xs font-bold uppercase tracking-[0.1em] text-slate-600">No. Telepon / WhatsApp</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-official-navy transition-colors">
                                <i class="fas fa-phone text-lg"></i>
                            </div>
                            <input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp') }}" required maxlength="15" autocomplete="tel" placeholder="08xxxxxxxxxx" class="w-full bg-white/90 border border-slate-200 focus:border-official-gold focus:ring-1 focus:ring-official-gold focus:outline-none rounded-lg py-3.5 pl-12 pr-4 text-sm transition-all placeholder:text-slate-300 shadow-sm">
                        </div>
                        @error('no_hp')
                            <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                        @enderror
                    </div>



                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <div class="space-y-2">
                            <label for="password" class="text-xs font-bold uppercase tracking-[0.1em] text-slate-600">Kata Sandi</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-official-navy transition-colors">
                                    <i class="fas fa-lock text-lg"></i>
                                </div>
                                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" class="w-full bg-white/90 border border-slate-200 focus:border-official-gold focus:ring-1 focus:ring-official-gold focus:outline-none rounded-lg py-3.5 pl-12 pr-12 text-sm transition-all placeholder:text-slate-300 shadow-sm">
                                
                                <button type="button" aria-label="Tampilkan kata sandi" onclick="togglePassword('password', 'toggle-icon-1')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 transition hover:text-official-navy focus:outline-none z-10">
                                    <i class="fas fa-eye text-lg" id="toggle-icon-1"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="password_confirmation" class="text-xs font-bold uppercase tracking-[0.1em] text-slate-600">Konfirmasi Sandi</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-official-navy transition-colors">
                                    <i class="fas fa-check-circle text-lg"></i>
                                </div>
                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" class="w-full bg-white/90 border border-slate-200 focus:border-official-gold focus:ring-1 focus:ring-official-gold focus:outline-none rounded-lg py-3.5 pl-12 pr-12 text-sm transition-all placeholder:text-slate-300 shadow-sm">
                                
                                <button type="button" aria-label="Tampilkan kata sandi" onclick="togglePassword('password_confirmation', 'toggle-icon-2')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 transition hover:text-official-navy focus:outline-none z-10">
                                    <i class="fas fa-eye text-lg" id="toggle-icon-2"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-official-navy text-white rounded-full py-4 font-semibold text-sm flex items-center justify-center gap-2 button-glow">
                            <span>Kirim Formulir Pendaftaran</span>
                            <i class="fas fa-paper-plane ml-1"></i>
                        </button>
                    </div>
                </form>

                <div class="text-center text-sm text-slate-600 mt-8 pt-6 border-t border-slate-200/60">
                    <p>Sudah punya akun? <a href="{{ route('login') }}" class="text-official-navy font-bold hover:text-official-golddark hover:underline ml-1">Masuk di sini</a></p>
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

        // Toggle Password Visibility
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>
