@extends('errors.layout')

@section('title', '419 - Sesi Kadaluarsa')
@section('accent', '#d97706')
@section('accent_bg', '#fffbeb')
@section('accent_border', '#fde68a')

@section('content')
    <div class="status-card">
        <div class="status-header">
            <span class="status-label">Status Sistem</span>
            <span class="status-time" id="clock"></span>
        </div>
        <div class="status-body">
            <div class="status-left">
                <div class="error-code">419</div>
                <div class="error-badge">
                    <i class="fas fa-clock"></i>
                    Sesi Kadaluarsa
                </div>

                <div class="meta-list">
                    <div class="meta-item">
                        <span class="meta-label">Domain Diakses</span>
                        <span class="meta-value mono">{{ request()->getHost() }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">IP Publik Anda</span>
                        <span class="meta-value mono">{{ request()->ip() }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Path Rute Diakses (URL)</span>
                        <span class="meta-value"><code>{{ request()->getPathInfo() }}</code></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Status Akses</span>
                        <span class="meta-value">SESSION EXPIRED</span>
                    </div>
                </div>
            </div>

            <div class="status-divider"></div>

            <div class="status-right">
                <h1 class="status-title">Sesi Anda Telah Berakhir</h1>
                <p class="status-desc">
                    Sesi login Anda telah kadaluarsa karena tidak ada aktivitas dalam waktu tertentu, atau token keamanan (CSRF) tidak valid.
                    Muat ulang halaman untuk memulai sesi baru.
                </p>

                <div class="action-hint">
                    <i class="fas fa-arrow-right"></i> Muat ulang halaman untuk melanjutkan
                </div>
                <div class="status-actions">
                    <button onclick="location.reload()" class="btn btn-primary">
                        <i class="fas fa-redo"></i> Muat Ulang Halaman
                    </button>
                    <a href="{{ url('/') }}" class="btn btn-ghost">
                        <i class="fas fa-home"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="advisory-card">
        <div class="advisory-header">
            <div class="advisory-icon blue"><i class="fas fa-info"></i></div>
            <span class="advisory-title">Pemberitahuan Sistem</span>
        </div>
        <div class="advisory-body">
            Untuk keamanan data, sistem secara otomatis mengakhiri sesi yang tidak aktif dalam jangka waktu tertentu.
            <div class="advisory-highlight">
                <strong>Tips:</strong> Jika Anda sering mengalami sesi kadaluarsa, pastikan koneksi internet Anda stabil dan hindari membiarkan halaman terbuka terlalu lama tanpa aktivitas.
            </div>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const opts = { day:'2-digit', month:'long', year:'numeric', hour:'2-digit', minute:'2-digit', second:'2-digit', hour12:false, timeZoneName:'short' };
            document.getElementById('clock').textContent = now.toLocaleDateString('id-ID', opts).replace(',', ' -');
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
@endsection
