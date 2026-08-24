<footer class="footer">
    <div class="footer-container">
        <div class="footer-grid">
            <div>
                <div class="footer-logo">
                    <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo Mandalaloka" class="footer-logo-img">
                    <div>
                        <span class="footer-logo-text">Mandalaloka</span>
                        <p style="font-size: 10px; color: var(--gold); margin-top: 2px; text-transform: uppercase; letter-spacing: 1px;">Sistem Informasi Resmi</p>
                    </div>
                </div>
                <p class="footer-about">Portal Layanan dan Sistem Informasi Pengelolaan Data UMKM Terpadu di Lingkungan Kecamatan Mandalajati, Pemerintah Kota Bandung.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div>
                <h4>Tautan Lintas</h4>
                <ul class="footer-links">
                    <li><a href="#home">Beranda Utama</a></li>
                    <li><a href="#statistik">Basis Data UMKM</a></li>
                    <li><a href="#statistik">Statistik Sektoral</a></li>
                    <li><a href="#bantuan">Publikasi & Warta</a></li>
                </ul>
            </div>
            <div>
                <h4>Layanan Publik</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('register') }}">Registrasi Entitas Baru</a></li>
                    <li><a href="javascript:void(0)" onclick="askChatbot('daftar')">Panduan Penggunaan</a></li>
                    <li><a href="javascript:void(0)" onclick="askChatbot('regulasi')">Regulasi & Surat Edaran</a></li>
                    <li><a href="javascript:void(0)" onclick="askChatbot('pengaduan')">Pengaduan Masyarakat</a></li>
                </ul>
            </div>
            <div>
                <h4>Sekretariat</h4>
                <ul class="footer-links">
                    <li><i class="fas fa-map-marker-alt" style="width: 16px; color: var(--gold);"></i> Kantor Kecamatan Mandalajati, Jl. Pasir Impun No.33, Karang Pamulang, Kec. Mandalajati, Kota Bandung, Jawa Barat 40194</li>
                    <li><i class="fas fa-phone" style="width: 16px; color: var(--gold);"></i> (022) 1234567</li>
                    <li><i class="fas fa-envelope" style="width: 16px; color: var(--gold);"></i> humas@mandalajati.bandung.go.id</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Pemerintah Kecamatan Mandalajati. Hak Cipta Dilindungi Undang-Undang.</p>
            <p style="color: var(--gold); font-weight: 600;">Sistem Terintegrasi Mandalaloka v1.0</p>
        </div>
    </div>
</footer>


