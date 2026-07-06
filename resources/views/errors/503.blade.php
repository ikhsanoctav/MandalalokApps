@extends('errors.layout')

@section('title', '503 - Layanan Tidak Tersedia')
@section('accent', '#475569')
@section('accent_bg', '#f1f5f9')
@section('accent_border', '#cbd5e1')

@section('content')
    <div class="status-card">
        <div class="status-header">
            <span class="status-label">Status Sistem</span>
            <span class="status-time" id="clock"></span>
        </div>
        <div class="status-body">
            <div class="status-left">
                <div class="error-code">503</div>
                <div class="error-badge">
                    <i class="fas fa-tools"></i>
                    Maintenance
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
                        <span class="meta-value">SERVICE UNAVAILABLE</span>
                    </div>
                </div>
            </div>

            <div class="status-divider"></div>

            <div class="status-right">
                <h1 class="status-title">Layanan Sedang Dalam Pemeliharaan</h1>
                <p class="status-desc">
                    Sistem sedang dalam proses pemeliharaan atau perbaikan untuk meningkatkan kualitas layanan.
                    Kami akan segera kembali. Mohon bersabar dan coba lagi dalam beberapa saat.
                </p>

                <div class="action-hint">
                    <i class="fas fa-arrow-right"></i> Coba muat ulang halaman secara berkala
                </div>
                <div class="status-actions">
                    <button onclick="setTimeout(() => location.reload(), 500)" class="btn btn-primary">
                        <i class="fas fa-redo"></i> Coba Lagi
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
            Pemeliharaan sistem dilakukan secara berkala untuk memastikan <strong>keamanan dan performa optimal</strong> bagi seluruh pengguna.
            <div class="advisory-highlight">
                <strong>Estimasi:</strong> Proses pemeliharaan biasanya memakan waktu 5-30 menit. Data Anda aman dan tidak akan terpengaruh oleh proses ini.
            </div>
        </div>
    </div>

    @if(isset($exception) && $exception->getMessage() && app()->hasDebugModeEnabled())
        <div class="debug-card">
            <div class="debug-label">Detail Teknis (Debug Mode)</div>
            <div class="debug-text">{{ $exception->getMessage() }}</div>
        </div>
    @endif

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
