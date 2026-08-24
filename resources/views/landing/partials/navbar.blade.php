<nav class="navbar" id="navbar">
    <div class="nav-container">
        <div class="logo">
            <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo Mandalaloka">
            <div class="logo-text">
                <h4>Mandalaloka</h4>
                <p>Pendataan UMKM</p>
            </div>
        </div>
        
        <div class="nav-menu">
            <a href="#home" class="nav-link active">Beranda</a>
            <a href="#statistik" class="nav-link">Data UMKM</a>
            <a href="#produk-umkm" class="nav-link">Katalog UMKM</a>
            <a href="#peta-umkm" class="nav-link">Peta</a>
            <a href="#bantuan" class="nav-link">Warta</a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-register">Masuk ke Dasbor</a>
                <a href="{{ route('logout') }}" class="btn-login" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Keluar
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-login">Masuk</a>
                <a href="{{ route('register') }}" class="btn-register">Pendaftaran UMKM</a>
            @endif
        </div>
        
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<div class="mobile-menu-overlay" id="mobileOverlay"></div>
<div class="mobile-menu" id="mobileMenu">
    <button class="mobile-close" id="closeMenuBtn"><i class="fas fa-times"></i></button>
    <br><br>
    <a href="#home" class="mobile-nav-link">Beranda</a>
    <a href="#statistik" class="mobile-nav-link">Data UMKM</a>
    <a href="#produk-umkm" class="mobile-nav-link">Katalog UMKM</a>
    <a href="#peta-umkm" class="mobile-nav-link">Peta UMKM</a>
    <a href="#bantuan" class="mobile-nav-link">Warta</a>
    @auth
        <a href="{{ route('dashboard') }}" class="mobile-nav-link">Dasbor Admin</a>
        <a href="{{ route('logout') }}" class="mobile-nav-link" 
           onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
            Keluar
        </a>
        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @else
        <a href="{{ route('login') }}" class="mobile-nav-link">Masuk</a>
        <a href="{{ route('register') }}" class="mobile-nav-link" style="color: var(--gold-dark);">Pendaftaran UMKM</a>
    @endif
</div>

