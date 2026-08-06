<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Digital ID & Stiker UMKM - {{ $umkm->nama_usaha }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Merriweather:wght@700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --bg-navy: #0b1120;
            --gold-light: #fef08a;
            --gold: #d4af37;
            --gold-dark: #854d0e;
            --gold-gradient: linear-gradient(135deg, #fcd34d, #b45309, #fef08a, #78350f, #fcd34d);
            --gold-metallic: linear-gradient(90deg, #b38728 0%, #fdf5a6 25%, #d4af37 50%, #fdf5a6 75%, #b38728 100%);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        /* The Premium Card */
        .id-card {
            width: 100%;
            max-width: 380px;
            background-color: var(--bg-navy);
            border-radius: 20px;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.6), 0 0 40px rgba(212, 175, 55, 0.15);
            position: relative;
            overflow: hidden;
            
            /* Gold border using gradient */
            border: 3px solid transparent;
            background-clip: padding-box, border-box;
            background-origin: padding-box, border-box;
            background-image: 
                linear-gradient(var(--bg-navy), var(--bg-navy)),
                var(--gold-gradient);
                
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Batik Background Texture */
        .id-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("{{ asset('images/bg_batik_premium.png') }}");
            background-size: 150px;
            background-repeat: repeat;
            opacity: 0.25;
            mix-blend-mode: luminosity;
            z-index: 1;
            pointer-events: none;
        }

        /* Sparkles/Glow effects */
        .glow-orb {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(253,245,166,0.5) 0%, rgba(212,175,55,0) 70%);
            z-index: 1;
            pointer-events: none;
        }
        .glow-1 { top: 30%; left: 0%; width: 150px; height: 150px; transform: translate(-50%, -50%); }
        .glow-2 { top: 60%; right: 0%; width: 200px; height: 200px; transform: translate(50%, -50%); opacity: 0.6; }
        .glow-3 { bottom: 0%; left: 50%; width: 250px; height: 100px; transform: translate(-50%, 50%); opacity: 0.4; }

        .card-content {
            padding: 2.5rem 1.5rem;
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Header Layout */
        .card-header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        /* Premium Logo Box */
        .logo-box {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 0 15px rgba(253,245,166,0.3);
        }
        .logo-box::after {
            content: '';
            position: absolute;
            inset: 2px;
            border: 2px dashed var(--gold);
            border-radius: 50%;
        }
        .logo-box img {
            max-width: 70%;
            max-height: 70%;
            position: relative;
            z-index: 2;
        }

        /* Metallic Badge */
        .badge-verified {
            background: var(--gold-metallic);
            color: #1a1a1a;
            padding: 6px 14px;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4), inset 0 1px 1px rgba(255,255,255,0.8);
            letter-spacing: 0.5px;
        }

        /* Typography */
        .umkm-title {
            font-family: 'Merriweather', serif;
            font-size: 1.5rem;
            font-weight: 900;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.8);
            text-align: center;
            letter-spacing: 1px;
            line-height: 1.2;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
        }

        .umkm-sector {
            color: var(--gold);
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 2rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.8);
        }

        /* Golden QR Frame */
        .qr-container {
            position: relative;
            margin: 0 auto 2rem;
            display: inline-block;
        }
        .qr-frame {
            background: white;
            padding: 16px;
            border-radius: 12px;
            
            /* Complex golden border */
            border: 8px solid transparent;
            background-clip: padding-box, border-box;
            background-origin: padding-box, border-box;
            background-image: 
                linear-gradient(white, white),
                var(--gold-gradient);
            
            box-shadow: 
                0 15px 35px rgba(0,0,0,0.5),
                0 0 30px rgba(212,175,55,0.4);
            position: relative;
            z-index: 10;
        }
        
        /* The inner intricate pattern of the frame - simplified as an inner shadow/border */
        .qr-frame::after {
            content: '';
            position: absolute;
            inset: -4px;
            border: 1px solid rgba(255,255,255,0.4);
            border-radius: 8px;
            pointer-events: none;
        }

        /* Star sparkles */
        .sparkle {
            position: absolute;
            width: 20px;
            height: 20px;
            background: radial-gradient(circle, #fff 0%, rgba(255,255,255,0) 60%);
            z-index: 20;
        }
        .sparkle::before, .sparkle::after {
            content: '';
            position: absolute;
            background: #fff;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
        }
        .sparkle::before { width: 100%; height: 1px; }
        .sparkle::after { width: 1px; height: 100%; }
        
        .s-1 { top: -10px; left: -10px; }
        .s-2 { bottom: -15px; right: 20px; transform: scale(0.7); }
        .s-3 { top: 40%; right: -25px; transform: scale(1.2); }
        .s-4 { bottom: 30%; left: -20px; transform: scale(0.8); }

        /* NIB Box */
        .nib-box {
            background: linear-gradient(180deg, rgba(30,41,59,0.9), rgba(15,23,42,0.95));
            border: 1px solid rgba(212,175,55,0.6);
            border-radius: 12px;
            padding: 1rem;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.5);
            margin-bottom: 1.5rem;
        }

        .nib-label {
            color: #94a3b8;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            margin-bottom: 0.5rem;
        }

        .nib-number {
            color: white;
            font-family: 'Outfit', monospace;
            font-size: 1.1rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(255,255,255,0.3);
        }

        .scan-text {
            color: #94a3b8;
            font-size: 0.65rem;
            text-align: center;
            line-height: 1.5;
            max-width: 80%;
            margin: 0 auto;
        }

        /* Action Buttons (Hidden when printing) */
        .action-container {
            margin-top: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            padding: 0 2rem 2rem 2rem;
            width: 100%;
            max-width: 380px;
        }

        .action-btn {
            transition: all 0.2s ease;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
        }

        /* --- PRINT STYLES --- */
        @media print {
            @page {
                size: portrait;
                margin: 0;
            }
            .no-print { display: none !important; }
            
            body {
                background: white;
                padding: 1cm;
                margin: 0;
                display: flex;
                align-items: flex-start;
                justify-content: center;
                height: 100vh;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .id-card {
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <div class="flex flex-col items-center">
        <!-- Unified Card (Screen & Print) -->
        <div class="id-card">
            <div class="glow-orb glow-1"></div>
            <div class="glow-orb glow-2"></div>
            <div class="glow-orb glow-3"></div>
            
            <div class="card-content">
                <!-- Header -->
                <div class="card-header">
                    <div class="logo-box">
                        <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Mandalaloka">
                    </div>
                    <div class="badge-verified">
                        <i class="mdi mdi-check-decagram text-lg"></i> TERVERIFIKASI
                    </div>
                </div>

                <!-- Shop Info -->
                <h1 class="umkm-title">{{ $umkm->nama_usaha }}</h1>
                <div class="umkm-sector">{{ $umkm->sektor?->nama_sektor ?? '-' }}</div>

                <!-- QR Code -->
                <div class="qr-container">
                    <div class="sparkle s-1"></div>
                    <div class="sparkle s-2"></div>
                    <div class="sparkle s-3"></div>
                    <div class="sparkle s-4"></div>
                    
                    <div class="qr-frame">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)
                            ->color(15, 23, 42)
                            ->margin(0)
                            ->style('round')
                            ->eye('circle')
                            ->errorCorrection('H')
                            ->generate(route('katalog.umkm', ['search' => $umkm->nama_usaha])) !!}
                    </div>
                </div>

                <!-- Details -->
                <div class="nib-box">
                    <div class="nib-label">NOMOR PENDAFTARAN (NIB/REG)</div>
                    <div class="nib-number">{{ $umkm->no_pendaftaran }}</div>
                </div>
                
                <p class="scan-text">
                    Scan QR Code di atas untuk memverifikasi profil digital dan informasi resmi UMKM.
                </p>
            </div>
        </div>

        <!-- Action Buttons (Hidden when printing) -->
        @php
            $backRoute = route('pelaku.dashboard');
            if (auth()->check()) {
                if (auth()->user()->hasRole('super_admin')) {
                    $backRoute = route('superadmin.umkm.show', $umkm->id_umkm);
                } elseif (auth()->user()->hasRole('admin_kecamatan')) {
                    $backRoute = route('admin.umkm.show', $umkm->id_umkm);
                } elseif (auth()->user()->hasRole('operator_lapangan')) {
                    $backRoute = route('operator.umkm.show', $umkm->id_umkm);
                }
            }
        @endphp
        <div class="action-container no-print">
            <div class="flex gap-3">
                <a href="{{ $backRoute }}" class="action-btn flex-1 py-2.5 px-4 rounded-xl text-sm font-semibold flex items-center justify-center gap-2 bg-slate-800 text-slate-300 hover:bg-slate-700 border border-slate-700 shadow-md">
                    <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
                <button onclick="shareCard()" class="action-btn flex-1 py-2.5 px-4 rounded-xl text-sm font-semibold flex items-center justify-center gap-2 bg-slate-800 text-blue-400 hover:bg-slate-700 border border-blue-900/50 shadow-md">
                    <i class="mdi mdi-share-variant"></i> Bagikan
                </button>
            </div>
            <button onclick="window.print()" class="action-btn w-full py-3 px-4 rounded-xl text-sm font-bold flex items-center justify-center gap-2 bg-amber-500 text-slate-900 hover:bg-amber-400 mt-1 shadow-lg shadow-amber-900/20">
                <i class="mdi mdi-printer text-lg"></i> Cetak QR
            </button>
        </div>
    </div>

    <script>
        function shareCard() {
            if (navigator.share) {
                navigator.share({
                    title: 'Digital ID UMKM - {{ $umkm->nama_usaha }}',
                    text: 'Lihat Identitas Digital UMKM saya dari Mandalaloka!',
                    url: window.location.href
                }).catch(console.error);
            } else {
                alert('Fitur bagikan tidak didukung di browser ini. Silakan salin URL.');
            }
        }
    </script>
</body>
</html>
