@if(isset($produkUnggulan) && $produkUnggulan->count() > 0)
<section class="product-showcase" id="produk-umkm">
    <div class="product-showcase-container">
        <div class="product-showcase-header">
            <div>
                <div class="product-showcase-eyebrow">Katalog Pilihan</div>
                <h2 class="product-showcase-title">Produk Lokal Mandalajati</h2>
                <p class="product-showcase-copy">Beberapa produk dari UMKM Mandalajati yang dapat langsung Anda jelajahi melalui katalog Mandalaloka.</p>
            </div>
            <div class="product-showcase-actions">
                <button type="button" class="carousel-btn" onclick="scrollProductCarousel(-1)" aria-label="Produk sebelumnya">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button type="button" class="carousel-btn" onclick="scrollProductCarousel(1)" aria-label="Produk berikutnya">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <div class="product-carousel" id="homeProductCarousel">
            @foreach($produkUnggulan as $produk)
                <a href="{{ route('katalog.umkm', ['search' => $produk->nama_produk]) }}" class="home-product-card">
                    <div class="home-product-media">
                        <img src="{{ $produk->foto_utama }}" alt="{{ $produk->nama_produk }}" loading="lazy" onerror="this.onerror=null;this.src='{{ $produk->dummy_foto }}';">
                        <div class="home-product-badge">{{ $produk->umkm->sektor?->nama_sektor ?? 'UMKM' }}</div>
                    </div>
                    <div class="home-product-body">
                        <div class="home-product-store">{{ $produk->umkm->nama_usaha ?? 'UMKM Mandalajati' }}</div>
                        <h3 class="home-product-name">{{ $produk->nama_produk }}</h3>
                        <div class="home-product-price">
                            {{ $produk->harga ? 'Rp ' . number_format($produk->harga, 0, ',', '.') : 'Harga hubungi penjual' }}
                        </div>
                        @php
                            $namaKel = optional(optional($produk->umkm->pemilik)->kelurahanRel)->nama_kelurahan ?? optional($produk->umkm->pemilik)->kelurahan ?? 'Mandalajati';
                        @endphp
                        <div class="home-product-location" style="display: flex; align-items: center; justify-content: space-between; gap: 6px; margin-top: auto; padding: 4px 6px; background: #f8fafc; border-radius: 6px; border: 1px solid #f1f5f9;">
                            <div style="display: flex; align-items: center; gap: 5px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; min-width: 0;">
                                <i class="fas fa-map-marker-alt" style="color: #ef4444; font-size: 11px;"></i>
                                <span style="font-size: 12px; font-weight: 600; color: #64748b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $namaKel }}</span>
                            </div>
                            <span style="font-size: 10px; font-weight: 700; color: #2563eb; background: #eff6ff; border: 1px solid #bfdbfe; padding: 2px 6px; border-radius: 4px; display: inline-flex; align-items: center; gap: 3px; flex-shrink: 0;">
                                <i class="fas fa-diamond-turn-right" style="font-size: 9px;"></i> Arah
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="product-showcase-footer">
            <a href="{{ route('katalog.umkm') }}" class="product-showcase-link">
                Lihat Semua Produk <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

