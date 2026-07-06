<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Digital ID - {{ $umkm->nama_usaha }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0f172a;
            color: white;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        /* Animated Mesh Background */
        .mesh-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
            background: #0f172a;
        }
        
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            animation: move 15s infinite alternate ease-in-out;
        }

        .blob-1 {
            top: -10%; left: -10%;
            width: 50vw; height: 50vw;
            background: rgba(56, 189, 248, 0.4);
            animation-delay: 0s;
        }

        .blob-2 {
            bottom: -20%; right: -10%;
            width: 60vw; height: 60vw;
            background: rgba(139, 92, 246, 0.4);
            animation-delay: -5s;
        }
        
        .blob-3 {
            top: 30%; left: 40%;
            width: 40vw; height: 40vw;
            background: rgba(45, 212, 191, 0.3);
            animation-delay: -10s;
        }

        @keyframes move {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(10%, 15%) scale(1.1); }
            100% { transform: translate(-15%, -10%) scale(0.9); }
        }

        /* Glassmorphism Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), inset 0 0 0 1px rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            perspective: 1000px;
            animation: card-entrance 1s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        @keyframes card-entrance {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Highlight overlay for glass reflection */
        .glass-card::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 50%; height: 100%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.1) 50%, rgba(255,255,255,0) 100%);
            transform: skewX(-20deg);
            animation: shine 6s infinite;
        }

        @keyframes shine {
            0% { left: -100%; }
            20% { left: 200%; }
            100% { left: 200%; }
        }

        /* QR Frame */
        .qr-container {
            background: white;
            padding: 12px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            display: inline-block;
            margin: 1.5rem 0;
            position: relative;
        }

        .qr-container::after {
            content: '';
            position: absolute;
            inset: -4px;
            background: linear-gradient(135deg, #38bdf8, #8b5cf6);
            border-radius: 24px;
            z-index: -1;
            opacity: 0.8;
            filter: blur(8px);
            animation: pulse-glow 3s infinite alternate;
        }

        @keyframes pulse-glow {
            0% { opacity: 0.6; filter: blur(8px); }
            100% { opacity: 1; filter: blur(12px); }
        }

        .action-btn {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            position: relative;
            z-index: 10;
        }
        
        .action-btn:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <!-- Animated Background -->
    <div class="mesh-bg">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <!-- Main Card -->
    <div class="glass-card text-center" id="digital-card">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Mandalaloka" class="h-10 filter drop-shadow-md">
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold tracking-widest text-emerald-400 uppercase">Terverifikasi</p>
                <p class="text-xs text-slate-300 font-medium opacity-80">Mandalaloka ID</p>
            </div>
        </div>

        <!-- Body / Info -->
        <div class="space-y-1">
            <h1 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-300">
                {{ $umkm->nama_usaha }}
            </h1>
            <p class="text-sm font-medium text-slate-300">{{ $umkm->pemilik->nama_lengkap ?? '-' }}</p>
        </div>

        <!-- QR Code -->
        <div class="qr-container">
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)
                ->color(15, 23, 42)
                ->margin(1)
                ->style('round')
                ->eye('circle')
                ->errorCorrection('H')
                ->merge(public_path('images/Logo_Mandalaloka.png'), 0.2, true)
                ->generate($umkm->no_pendaftaran) !!}
        </div>

        <!-- Details -->
        <div class="grid grid-cols-2 gap-3 mt-2 text-left bg-black/20 rounded-2xl p-4 border border-white/5">
            <div>
                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mb-0.5">NIB / No. Reg</p>
                <p class="text-sm font-semibold text-white">{{ $umkm->no_pendaftaran }}</p>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mb-0.5">Sektor Usaha</p>
                <p class="text-sm font-semibold text-white">{{ $umkm->sektor->nama_sektor ?? '-' }}</p>
            </div>
            <div class="col-span-2 pt-2 border-t border-white/10">
                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mb-0.5">Alamat Lengkap</p>
                <p class="text-xs font-medium text-slate-200 leading-relaxed">{{ $umkm->alamat_usaha ?? '-' }}</p>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="mt-6 flex gap-3">
            <a href="{{ route('pelaku.dashboard') }}" class="action-btn flex-1 py-3 px-4 rounded-xl text-sm font-semibold flex items-center justify-center gap-2 text-white">
                <i class="mdi mdi-arrow-left"></i> Kembali
            </a>
            <button onclick="shareCard()" class="action-btn flex-1 py-3 px-4 rounded-xl text-sm font-semibold flex items-center justify-center gap-2 bg-gradient-to-r from-blue-500/20 to-indigo-500/20 border-blue-400/30 text-blue-100">
                <i class="mdi mdi-share-variant"></i> Bagikan
            </button>
        </div>

    </div>

    <!-- Simple interaction script -->
    <script>
        // 3D Tilt Effect on Desktop
        const card = document.getElementById('digital-card');
        
        document.addEventListener('mousemove', (e) => {
            if(window.innerWidth < 768) return; // Only on desktop
            
            const xAxis = (window.innerWidth / 2 - e.pageX) / 25;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 25;
            
            card.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
        });

        // Reset tilt on mouse leave
        document.addEventListener('mouseleave', () => {
            card.style.transform = `rotateY(0deg) rotateX(0deg)`;
            card.style.transition = `transform 0.5s ease`;
        });
        
        document.addEventListener('mouseenter', () => {
            card.style.transition = `none`;
        });

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
