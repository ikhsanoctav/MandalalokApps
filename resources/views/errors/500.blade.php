@extends('errors.layout')

@section('title', '500 - Kesalahan Server Internal')
@section('accent', '#dc2626')
@section('accent_bg', '#fef2f2')
@section('accent_border', '#fecaca')

@section('content')
    {{-- Status Card --}}
    <div class="status-card">
        <div class="status-header">
            <span class="status-label">Status Sistem</span>
            <span class="status-time" id="clock"></span>
        </div>
        <div class="status-body">
            <div class="status-left">
                <div class="error-code">500</div>
                <div class="error-badge">
                    <i class="fas fa-times-circle"></i>
                    Server Error
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
                        <span class="meta-value">INTERNAL SERVER ERROR</span>
                    </div>
                </div>
            </div>

            <div class="status-divider"></div>

            <div class="status-right">
                <h1 class="status-title">Format Respon Server Tidak Sesuai</h1>
                <p class="status-desc">
                    Server utama mengalami kesalahan saat memproses permintaan Anda.
                    Tim teknis telah menerima laporan otomatis dan sedang melakukan investigasi untuk memulihkan layanan.
                </p>

                <div class="action-hint">
                    <i class="fas fa-arrow-right"></i> Muat ulang halaman atau kembali ke beranda
                </div>
                <div class="status-actions">
                    <button onclick="location.reload()" class="btn btn-accent">
                        <i class="fas fa-redo"></i> Muat Ulang Halaman
                    </button>
                    <a href="{{ url('/') }}" class="btn btn-ghost">
                        <i class="fas fa-home"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Advisory Card --}}
    <div class="advisory-card">
        <div class="advisory-header">
            <div class="advisory-icon amber"><i class="fas fa-info"></i></div>
            <span class="advisory-title">Pemberitahuan Sistem</span>
        </div>
        <div class="advisory-body">
            Tim pengelola aplikasi dapat meninjau <strong>log server web</strong> (Nginx/Apache) untuk menganalisis potensi kegagalan runtime pada kode aplikasi.
            <div class="advisory-highlight">
                <strong>Mengapa error ini terjadi?</strong> Kesalahan 500 biasanya disebabkan oleh bug pada kode aplikasi, konfigurasi server yang salah, atau sumber daya server yang tidak mencukupi. Silakan coba lagi dalam beberapa saat.
            </div>
        </div>
    </div>

    @if(isset($exception) && $exception->getMessage() && app()->hasDebugModeEnabled())
        <div class="debug-card">
            <div class="debug-label">Detail Teknis (Debug Mode)</div>
            <div class="debug-text">{{ $exception->getMessage() }}</div>
        </div>
        @if(method_exists($exception, 'getFile'))
            <div class="debug-card" style="margin-top: -8px;">
                <div class="debug-label">Lokasi File</div>
                <div class="debug-text">{{ $exception->getFile() }}:{{ $exception->getLine() }}</div>
            </div>
        @endif
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
