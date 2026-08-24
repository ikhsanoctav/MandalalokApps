<section class="hero" id="home">
    <div class="hero-container">
        <div class="hero-content stagger">
            <div class="official-tag">
                <div class="accent-line"></div>
                <span>Portal Resmi Kecamatan Mandalajati</span>
            </div>
            <h1>Mandalaloka:<br><span>Portal Pendataan UMKM</span></h1>
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

