@extends('errors.layout')

@section('title', '403 - Akses Ditolak')
@section('accent', '#dc2626')
@section('accent_bg', '#fef2f2')
@section('accent_border', '#fecaca')

@section('content')
    <div class="status-card">
        <div class="status-header">
            <span class="status-label">Status Sistem</span>
            <span class="status-time" id="clock"></span>
        </div>
        <div class="status-body">
            <div class="status-left">
                <div class="error-code">403</div>
                <div class="error-badge">
                    <i class="fas fa-shield-alt"></i>
                    Akses Ditolak
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
                        <span class="meta-value">FORBIDDEN</span>
                    </div>
                </div>
            </div>

            <div class="status-divider"></div>

            <div class="status-right">
                <h1 class="status-title">Anda Tidak Memiliki Izin Akses</h1>
                <p class="status-desc">
                    Akun Anda tidak memiliki role atau hak akses yang diperlukan untuk membuka halaman ini.
                    Silakan hubungi administrator jika Anda merasa seharusnya memiliki akses ke area ini.
                </p>

                <div class="action-hint">
                    <i class="fas fa-arrow-right"></i> Kembali ke halaman yang dapat Anda akses
                </div>
                <div class="status-actions">
                    <a href="{{ url('/') }}" class="btn btn-primary">
                        <i class="fas fa-home"></i> Kembali ke Beranda
                    </a>
                    <button onclick="history.back()" class="btn btn-ghost">
                        <i class="fas fa-arrow-left"></i> Halaman Sebelumnya
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="advisory-card">
        <div class="advisory-header">
            <div class="advisory-icon amber"><i class="fas fa-info"></i></div>
            <span class="advisory-title">Pemberitahuan Sistem</span>
        </div>
        <div class="advisory-body">
            Setiap halaman dilindungi berdasarkan <strong>role pengguna</strong> (Super Admin, Admin Kecamatan, Operator Lapangan, atau Pelaku UMKM).
            <div class="advisory-highlight">
                <strong>Butuh akses?</strong> Hubungi administrator sistem untuk meminta perubahan role atau izin akses ke halaman yang Anda perlukan.
            </div>
        </div>
    </div>

    @if(isset($exception) && $exception->getMessage() && $exception->getMessage() !== '' && app()->hasDebugModeEnabled())
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
