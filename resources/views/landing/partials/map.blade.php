<section class="map-section" id="peta-umkm">
    <div class="map-container">
        <div class="map-header">
            <h3>Peta Interaktif</h3>
            <h2>Sebaran Lokasi UMKM Kecamatan Mandalajati</h2>
            <p>Temukan UMKM terdaftar di sekitar Anda. Klik pin untuk melihat detail usaha.</p>
        </div>

        <div class="map-wrapper">
            <div class="map-toolbar">
                <span class="map-toolbar-label"><i class="fas fa-filter"></i> Filter:</span>
                
                <div class="map-search-container">
                    <i class="fas fa-search map-search-icon"></i>
                    <input type="text" id="mapSearchInput" class="map-search-input" placeholder="Cari nama UMKM..." oninput="filterMapMarkers()">
                </div>

                <select id="mapFilterSektor" class="map-filter-select" onchange="filterMapMarkers()">
                    <option value="">Semua Sektor</option>
                </select>
                <select id="mapFilterKelurahan" class="map-filter-select" onchange="filterMapMarkers()">
                    <option value="">Semua Kelurahan</option>
                </select>
                <div class="map-counter">
                    <span class="dot-live"></span>
                    <span id="mapMarkerCount">0</span> UMKM Ditemukan
                </div>
            </div>

            <div id="umkmMap"></div>

            <div class="map-footer">
                <div class="map-legend">
                    <div class="map-legend-item">
                        <span class="map-legend-dot" style="background: #0f2e5c;"></span> UMKM Aktif
                    </div>
                    <div class="map-legend-item">
                        <span class="map-legend-dot" style="background: #d4af37;"></span> Cluster Area
                    </div>
                </div>
                <div class="map-info-note">
                    <i class="fas fa-info-circle"></i> Data diperbarui otomatis dari database UMKM terdaftar
                </div>
            </div>
        </div>
    </div>
</section>

