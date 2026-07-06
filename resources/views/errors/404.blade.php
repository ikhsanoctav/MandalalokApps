@extends('errors.layout')

@section('title', '404 - Halaman Tidak Ditemukan')
@section('accent', '#d97706')
@section('accent_bg', '#fffbeb')
@section('accent_border', '#fde68a')

@section('content')
    {{-- Status Card --}}
    <div class="status-card">
        <div class="status-header">
            <span class="status-label">Status Sistem</span>
            <span class="status-time" id="clock"></span>
        </div>
        <div class="status-body">
            {{-- Left: Error Code --}}
            <div class="status-left">
                <div class="error-code">404</div>
                <div class="error-badge">
                    <i class="fas fa-exclamation-triangle"></i>
                    Halaman Tidak Ditemukan
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
                        <span class="meta-value">NOT FOUND</span>
                    </div>
                </div>
            </div>

            <div class="status-divider"></div>

            {{-- Right: Description + Actions --}}
            <div class="status-right">
                <h1 class="status-title">Halaman yang Anda Cari Tidak Ditemukan</h1>
                <p class="status-desc">
                    URL yang Anda akses tidak terdaftar dalam sistem atau halaman tersebut telah dipindahkan.
                    Periksa kembali alamat URL atau gunakan navigasi di bawah untuk kembali ke halaman yang sesuai.
                </p>

                <div class="action-hint">
                    <i class="fas fa-arrow-right"></i> Arahkan kembali ke halaman utama sistem
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

    {{-- Advisory Card --}}
    <div class="advisory-card">
        <div class="advisory-header">
            <div class="advisory-icon amber"><i class="fas fa-info"></i></div>
            <span class="advisory-title">Pemberitahuan Sistem</span>
        </div>
        <div class="advisory-body">
            Jika Anda yakin halaman ini seharusnya tersedia, silakan hubungi <strong>administrator sistem</strong> atau tim pengelola aplikasi untuk meninjau konfigurasi rute pada sistem.
            <div class="advisory-highlight">
                <strong>Tips:</strong> Pastikan Anda mengakses URL yang benar. Jika Anda diarahkan dari tautan eksternal, tautan tersebut mungkin sudah tidak berlaku.
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
