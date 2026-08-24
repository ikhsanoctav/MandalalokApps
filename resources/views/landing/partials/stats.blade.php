<section class="stats-section" id="statistik">
    <div class="stats-container">
        <div class="stats-header">
            <h3>Pusat Data Terpadu</h3>
            <h2>Visualisasi Pertumbuhan UMKM <span style="font-size: 14px; font-weight: 600; background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 6px 12px; border-radius: var(--radius-pill); vertical-align: middle; margin-left: 12px; display: inline-flex; align-items: center; gap: 8px;"><span style="display: inline-block; width: 8px; height: 8px; background: #10b981; border-radius: 50%; animation: pulse-dot 2s infinite;"></span> Live</span></h2>
            <p>Transparansi basis data pelaku usaha mikro, kecil, dan menengah di Kecamatan Mandalajati.</p>
        </div>
        
        @php
            $initialTotalUmkm = \App\Models\UMKM::where('status_verifikasi', 'terverifikasi')->count();
            $initialGrowth = \App\Models\UMKM::where('status_verifikasi', 'terverifikasi')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
            $initialSektor = \App\Models\SektorUmkm::count();
        @endphp
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="stat-value" id="growthValue">+{{ $initialGrowth }} Baru</div>
                <div class="stat-label">Bina Usaha Baru</div>
                <div class="stat-change">
                    <span class="stat-change-badge"><i class="fas fa-arrow-up"></i></span>
                    <span class="stat-change-text">Data Bulan Terakhir</span>
                </div>
            </div>
            <div class="stat-card primary">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="stat-value" id="totalUmkmValue">{{ number_format($initialTotalUmkm, 0, ',', '.') }}</div>
                <div class="stat-label">Total UMKM Terdaftar</div>
                <div class="stat-change">
                    <span class="stat-change-badge"><i class="fas fa-check"></i></span>
                    <span class="stat-change-text">Data Tervalidasi</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.5 14.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm5.622-10.439l6.32 6.32a1.5 1.5 0 010 2.122l-8.485 8.485a1.5 1.5 0 01-2.122 0l-6.32-6.32a1.5 1.5 0 010-2.122l8.485-8.485a1.5 1.5 0 012.122 0z"></path>
                    </svg>
                </div>
                <div class="stat-value" id="totalSektorValue">{{ $initialSektor }} Sektor</div>
                <div class="stat-label">Sektor Usaha</div>
                <div class="stat-change">
                    <span class="stat-change-badge"><i class="fas fa-list-ul"></i></span>
                    <span class="stat-change-text">Klasifikasi Aktif</span>
                </div>
            </div>
        </div>

        <div class="chart-section">
            <div class="chart-box">
                <canvas id="sectorChart" width="300" height="300"></canvas>
            </div>
            <div class="legend-box">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <h4 style="font-size: 20px;">Distribusi UMKM</h4>
                    <select id="chartFilter" onchange="loadDataFromDatabase()" style="padding: 6px 12px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 13px; outline: none; background: var(--bg-light); cursor: pointer; font-weight: 600; color: var(--primary);">
                        <option value="kategori">Berdasarkan Kategori</option>
                        <option value="sektor">Berdasarkan Sektor</option>
                    </select>
                </div>
                <div id="legendList">
                    <div class="legend-item">
                        <div><span class="legend-dot" style="background: #e2e8f0;"></span><span class="legend-label">Menyinkronkan data...</span></div>
                    </div>
                </div>
                <div style="margin-top: 24px; padding-top: 20px; border-top: 1px dashed var(--border-color);">
                    <p style="font-size: 13px; color: var(--text-muted); background: var(--bg-light); padding: 12px 16px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                        <i class="fas fa-info-circle" style="color: var(--primary); margin-right: 6px;"></i> 
                        Kategori dominan: <strong id="topKategori" style="color: var(--text-dark);">-</strong> 
                        (<span id="topKategoriTotal">0</span> entitas terdaftar)
                    </p>
                </div>
            </div>
        </div>
        
        <div class="kelurahan-grid">
            @foreach($kelurahans as $kel)
            <div class="kelurahan-card">
                <div class="kelurahan-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4 class="kelurahan-name">Kelurahan {{ $kel->nama_kelurahan }}</h4>
                <div class="kelurahan-count">
                    <span class="kelurahan-count-number">{{ $kel->umkm_count }}</span>
                    <span class="kelurahan-count-label">UMKM</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

