<section class="warta-section" id="bantuan">
    <div class="stats-container">
        <h2 class="section-title">Warta Dinas & Informasi Publik</h2>
        
        @php $berita = \App\Models\Berita::where('status', 'published')->where('published_at', '<=', now())->latest('published_at')->take(4)->get(); @endphp
        
        @if(isset($berita) && $berita->count() > 0)
            <div class="news-grid">
                @foreach($berita as $b)
                <a href="{{ route('warta.show', $b->id) }}" class="news-card">
                    <div class="news-img-wrapper">
                        <div class="news-img" style="background-image: url('{{ $b->gambar_url }}');"></div>
                        <span class="news-category">{{ $b->penulis ?? 'Warta' }}</span>
                    </div>
                    <div class="news-content">
                        <h3 class="news-title">{{ $b->judul }}</h3>
                        <p class="news-excerpt">{{ Str::limit(strip_tags($b->konten), 100) }}</p>
                        <div class="news-meta">
                            <div class="news-date">
                                <i class="fas fa-calendar-alt"></i>
                                {{ date('d M Y', strtotime($b->published_at)) }}
                            </div>
                            <span class="news-readmore">Baca <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @else
            <div class="news-grid">
                <a href="#" class="news-card">
                    <div class="news-img-wrapper">
                        <div class="news-img" style="background-image: url('https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=800&auto=format&fit=crop&q=80');"></div>
                        <span class="news-category">Program Pemerintah</span>
                    </div>
                    <div class="news-content">
                        <h3 class="news-title">Penyerahan Dana Hibah Transformasi UMKM Tahap I</h3>
                        <p class="news-excerpt">Alokasi program hibah pemerintah untuk mendorong percepatan transformasi digital usaha mikro di wilayah Mandalajati.</p>
                        <div class="news-meta">
                            <div class="news-date"><i class="fas fa-calendar-alt"></i> 15 Mar 2026</div>
                            <span class="news-readmore">Baca <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
                <a href="#" class="news-card">
                    <div class="news-img-wrapper">
                        <div class="news-img" style="background-image: url('https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=800&auto=format&fit=crop&q=80');"></div>
                        <span class="news-category">Rilis Publikasi</span>
                    </div>
                    <div class="news-content">
                        <h3 class="news-title">Pendataan Sensus UMKM Triwulan Pertama 2026</h3>
                        <p class="news-excerpt">Laporan resmi peningkatan cakupan data pelaku usaha mandiri melalui portal Mandalaloka yang terintegrasi secara digital.</p>
                        <div class="news-meta">
                            <div class="news-date"><i class="fas fa-calendar-alt"></i> 10 Mar 2026</div>
                            <span class="news-readmore">Baca <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
                <a href="#" class="news-card">
                    <div class="news-img-wrapper">
                        <div class="news-img" style="background-image: url('https://images.unsplash.com/photo-1450133064473-71024230f91b?w=800&auto=format&fit=crop&q=80');"></div>
                        <span class="news-category">Bimbingan Teknis</span>
                    </div>
                    <div class="news-content">
                        <h3 class="news-title">Bimtek Standarisasi Mutu dan Penerbitan NIB</h3>
                        <p class="news-excerpt">Penyelenggaraan bimbingan teknis bagi pemilik usaha terkait legalitas, standarisasi produk, dan integrasi teknologi pasar digital.</p>
                        <div class="news-meta">
                            <div class="news-date"><i class="fas fa-calendar-alt"></i> 05 Mar 2026</div>
                            <span class="news-readmore">Baca <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
                <a href="#" class="news-card">
                    <div class="news-img-wrapper">
                        <div class="news-img" style="background-image: url('https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=800&auto=format&fit=crop&q=80');"></div>
                        <span class="news-category">Informasi Publik</span>
                    </div>
                    <div class="news-content">
                        <h3 class="news-title">Fasilitasi Sertifikasi Halal Gratis 2026</h3>
                        <p class="news-excerpt">Pemerintah memberikan fasilitas sertifikasi halal gratis untuk pelaku usaha mikro sektor kuliner dan minuman di seluruh kecamatan.</p>
                        <div class="news-meta">
                            <div class="news-date"><i class="fas fa-calendar-alt"></i> 01 Mar 2026</div>
                            <span class="news-readmore">Baca <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        <div style="text-align: center; margin-top: 40px;">
            <a href="{{ route('warta.index') }}" class="product-showcase-link" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700; color: #0f2e5c; text-decoration: none; padding: 12px 28px; background: #ffffff; border-radius: 9999px; border: 1px solid #cbd5e1; box-shadow: 0 4px 12px rgba(15, 46, 92, 0.06); transition: all 0.3s ease;">
                Lihat Semua Warta Dinas & Informasi <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<div class="why-data">
    <div class="why-container">
        <div class="why-content">
            <h2>Urgensi Registrasi UMKM Daerah</h2>
            <p>Mandalaloka bertindak sebagai sistem tata kelola administrasi UMKM tingkat kecamatan yang terstruktur. Pendaftaran unit usaha melalui portal ini merupakan tahapan esensial guna memastikan validitas data untuk pengajuan perizinan, akses permodalan, serta fasilitasi program strategis dari Pemerintah Kota maupun Pusat.</p>
            <a href="#" class="why-link">Tinjau Prosedur Pendaftaran <i class="fas fa-chevron-right" style="font-size: 10px; margin-left: 4px;"></i></a>
        </div>
        <div class="why-grid">
            <div class="why-card">
                <i class="fas fa-laptop-house"></i>
                <h4>Integrasi Digital</h4>
                <p>Pengisian formulir kelengkapan entitas usaha secara mandiri melalui portal daring resmi.</p>
            </div>
            <div class="why-card">
                <i class="fas fa-stamp"></i>
                <h4>Validasi Dokumen</h4>
                <p>Pencatatan data usaha dibuat langsung aktif setelah pelaku UMKM melengkapi informasi wajib.</p>
            </div>
            <div class="why-card">
                <i class="fas fa-database"></i>
                <h4>Pusat Informasi</h4>
                <p>Rekapitulasi data yang tersaji secara transparan, akurat, dan dapat dipertanggungjawabkan.</p>
            </div>
            <div class="why-card">
                <i class="fas fa-handshake"></i>
                <h4>Fasilitasi Program</h4>
                <p>Pemanfaatan pangkalan data sebagai acuan penyaluran bantuan serta program binaan.</p>
            </div>
        </div>
    </div>
</div>

<div class="quote-section">
    <div class="quote-icon"><i class="fas fa-quote-right"></i></div>
    <div class="quote-text">
        "Basis data yang presisi adalah fondasi mutlak dalam melahirkan kebijakan publik yang berkeadilan dan tepat sasaran. Melalui Mandalaloka, kita tingkatkan derajat UMKM lokal ke kancah yang lebih luas."
    </div>
    <div class="quote-author">Camat Mandalajati</div>
    <div class="quote-title">Pemerintah Kota Bandung</div>
</div>

