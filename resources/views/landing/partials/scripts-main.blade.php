<script>
    let sectorChart = null;

    function setLegend(html) {
        const legendList = document.getElementById('legendList');
        if (legendList) legendList.innerHTML = html;
    }

    function legendItem(color, label) {
        return `<div class="legend-item">
            <div><span class="legend-dot" style="background:${color}"></span><span class="legend-label">${label}</span></div>
        </div>`;
    }

    async function loadDataFromDatabase() {
        try {
            const filterValue = document.getElementById('chartFilter') ? document.getElementById('chartFilter').value : 'kategori';
            const chartResponse = await fetch('/api/chart-data?filter=' + filterValue);
            const chartData     = await chartResponse.json();

            if (chartData.success && chartData.labels && chartData.labels.length > 0) {
                const legendList = document.getElementById('legendList');
                const elegantColors = ['#0f2e5c', '#1a498b', '#2b65b6', '#d4af37', '#b8902d', '#708090', '#475569'];

                if (legendList) {
                    legendList.innerHTML = '';
                    chartData.labels.forEach((label, i) => {
                        let renderColor = chartData.colors[i] || elegantColors[i % elegantColors.length];
                        legendList.innerHTML += `
                            <div class="legend-item">
                                <div>
                                    <span class="legend-dot" style="background:${renderColor}"></span>
                                    <span class="legend-label">${label}</span>
                                    <span style="font-size:12px;color:var(--text-muted);margin-left:8px;">(${chartData.totals[i]} unit)</span>
                                </div>
                                <span class="legend-percent">${chartData.percentages[i]}%</span>
                            </div>`;
                    });
                }

                const canvas = document.getElementById('sectorChart');
                if (canvas) {
                    const ctx = canvas.getContext('2d');
                    if (sectorChart) sectorChart.destroy();

                    let chartColors = chartData.colors && chartData.colors.length > 0 ? chartData.colors : elegantColors;

                    sectorChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels  : chartData.labels,
                            datasets: [{
                                data           : chartData.percentages,
                                backgroundColor: chartColors,
                                borderWidth    : 3,
                                borderColor    : '#ffffff',
                                hoverOffset    : 6,
                            }]
                        },
                        options: {
                            responsive        : true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend : { display: false },
                                tooltip: {
                                    backgroundColor: '#0f2e5c',
                                    titleFont: { family: 'Inter', size: 13 },
                                    bodyFont: { family: 'Inter', size: 13 },
                                    padding: 12,
                                    cornerRadius: 8,
                                    callbacks: {
                                        label(context) {
                                            const label = context.label || '';
                                            const value = context.raw  || 0;
                                            const total = chartData.totals[context.dataIndex];
                                            return ` ${label}: ${value}% (${total} Unit)`;
                                        }
                                    }
                                }
                            },
                            cutout: '65%'
                        }
                    });
                }
            } else {
                setLegend(legendItem('#d4af37', 'Basis data sedang diperbarui...'));
            }
        } catch (error) {
            console.error('Error:', error);
            setLegend(legendItem('#ef4444', 'Terjadi kendala memuat data server'));
        }

        try {
            const statsResponse = await fetch('/api/statistics');
            const statsData     = await statsResponse.json();

            if (statsData.success) {
                document.getElementById('totalUmkmValue').innerText    = statsData.total_umkm.toLocaleString('id-ID');
                document.getElementById('growthValue').innerText        = statsData.growth;
                document.getElementById('totalSektorValue').innerText   = statsData.total_sektor;
                document.getElementById('topKategori').innerText        = statsData.top_kategori;
                document.getElementById('topKategoriTotal').innerText   = statsData.top_kategori_total.toLocaleString('id-ID');
            }
        } catch (error) {
            console.error('Stats error:', error);
        }
    }

    // Set realtime polling interval (every 30 seconds)
    // Server caches the API responses for 30s to ensure no overload
    setInterval(() => {
        loadDataFromDatabase();
    }, 30000);
    
    // Navbar Scroll
    const navbar   = document.getElementById('navbar');
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.nav-link');

    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 40);

        let current = '';
        sections.forEach(section => {
            if (window.scrollY >= (section.offsetTop - 250)) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    });
    
    // Mobile Menu
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const closeBtn = document.getElementById('closeMenuBtn');
    const mobileLinks = document.querySelectorAll('.mobile-nav-link');
    
    mobileLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            const target = link.getAttribute('href');
            if (target && target.startsWith('#')) {
                e.preventDefault();
                closeMenu();
                const element = document.querySelector(target);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });
    
    function openMenu() {
        mobileMenu.classList.add('active');
        mobileOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMenu() {
        mobileMenu.classList.remove('active');
        mobileOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    if (mobileBtn) mobileBtn.addEventListener('click', openMenu);
    if (closeBtn) closeBtn.addEventListener('click', closeMenu);
    if (mobileOverlay) mobileOverlay.addEventListener('click', closeMenu);

    // Premium Scroll Reveal Animation
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15
    };
    
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Auto-inject reveal class to elements to make the site look premium
    const elementsToReveal = document.querySelectorAll('.stats-header, .stat-card, .data-header, .chart-box, .legend-box, .section-title, .news-card, .why-content, .why-card, .quote-icon, .quote-text, .quote-author, .footer-grid > div, .map-header, .map-wrapper');
    
    elementsToReveal.forEach((el, index) => {
        el.classList.add('reveal');
        // Add staggered delay for grid items
        if(el.classList.contains('stat-card') || el.classList.contains('news-card') || el.classList.contains('why-card') || el.parentElement.classList.contains('footer-grid')) {
             el.style.transitionDelay = `${(index % 4) * 0.15}s`;
        }
        observer.observe(el);
    });
    
    document.addEventListener('DOMContentLoaded', loadDataFromDatabase);

    // ==========================================================================
    // PETA INTERAKTIF UMKM — Leaflet.js
    // ==========================================================================
    let umkmMap = null;
    let umkmMarkers = null;
    let allMapData = [];
    let mapInitialized = false;

    // Custom Icon for UMKM Markers
    function createUmkmIcon(umkm) {
        return L.divIcon({
            className: 'custom-umkm-marker',
            html: `<div style="
                width: 44px; height: 44px;
                background: white;
                border-radius: 50% 50% 50% 0;
                transform: rotate(-45deg);
                border: 3px solid #0f2e5c;
                box-shadow: 0 6px 16px rgba(15, 46, 92, 0.4);
                display: flex; align-items: center; justify-content: center;
                overflow: hidden;
                position: relative;
            ">
                <img src="${umkm.foto}" style="
                    width: 100%; height: 100%;
                    object-fit: cover;
                    transform: rotate(45deg) scale(1.42);
                    border-radius: 50%;
                " onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(umkm.nama_usaha)}&background=0f2e5c&color=fff'">
            </div>`,
            iconSize: [44, 44],
            iconAnchor: [22, 44],
            popupAnchor: [0, -44],
        });
    }

    function buildPopupContent(umkm) {
        const avatarHtml = umkm.foto
            ? `<img src="${umkm.foto}" alt="${umkm.nama_usaha}" class="umkm-popup-avatar" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
               <div class="umkm-popup-avatar-placeholder" style="display:none;">${umkm.nama_usaha.charAt(0)}</div>`
            : `<div class="umkm-popup-avatar-placeholder">${umkm.nama_usaha.charAt(0)}</div>`;

        return `
            <div class="umkm-popup">
                <div class="umkm-popup-header">
                    ${avatarHtml}
                    <div>
                        <div class="umkm-popup-name">${umkm.nama_usaha}</div>
                        <div class="umkm-popup-badges">
                            <span class="umkm-popup-badge">${umkm.kategori}</span>
                            <span class="umkm-popup-badge sektor">${umkm.sektor}</span>
                        </div>
                    </div>
                </div>
                <div class="umkm-popup-detail">
                    <div><i class="fas fa-user"></i> ${umkm.pemilik}</div>
                    <div><i class="fas fa-map-pin"></i> ${umkm.kelurahan}</div>
                    <div><i class="fas fa-location-dot"></i> ${umkm.alamat}</div>
                </div>
                <div style="margin-top: 12px; border-top: 1px solid #f1f5f9; padding-top: 12px; text-align: center; display: flex; flex-direction: column; gap: 8px;">
                    <a href="https://www.google.com/maps/search/?api=1&query=${umkm.latitude},${umkm.longitude}" target="_blank" style="display: inline-block; background: #0f2e5c; color: white; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; width: 100%; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#1a4382'" onmouseout="this.style.backgroundColor='#0f2e5c'">
                        <i class="fas fa-map-marked-alt" style="margin-right: 4px;"></i> Buka di Google Maps
                    </a>
                    ${umkm.telp && umkm.telp !== '-' ? `
                    <a href="https://wa.me/${umkm.telp.replace(/\D/g, '').replace(/^0/, '62')}?text=Halo%20${encodeURIComponent(umkm.nama_usaha)},%20saya%20melihat%20UMKM%20Anda%20di%20Mandalaloka." target="_blank" style="display: inline-block; background: #25D366; color: white; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; width: 100%; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#20ba59'" onmouseout="this.style.backgroundColor='#25D366'">
                        <i class="fab fa-whatsapp" style="margin-right: 4px;"></i> Hubungi via WhatsApp
                    </a>
                    ` : ''}
                </div>
            </div>
        `;
    }

    function initUmkmMap() {
        if (mapInitialized) return;
        mapInitialized = true;

        // Pusat Kecamatan Mandalajati, Bandung
        umkmMap = L.map('umkmMap', {
            center: [-6.8868, 107.6545],
            zoom: 14,
            scrollWheelZoom: true,
            zoomControl: true,
        });

        // OpenStreetMap Tile Layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19,
        }).addTo(umkmMap);

        // Marker Cluster Group
        umkmMarkers = L.markerClusterGroup({
            maxClusterRadius: 50,
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true,
            iconCreateFunction: function(cluster) {
                const count = cluster.getChildCount();
                let size = 'small';
                if (count >= 20) size = 'large';
                else if (count >= 10) size = 'medium';

                return L.divIcon({
                    html: `<div><span>${count}</span></div>`,
                    className: `marker-cluster marker-cluster-${size}`,
                    iconSize: L.point(40, 40),
                });
            }
        });

        umkmMap.addLayer(umkmMarkers);

        // Load Data
        loadMapData();
    }

    async function loadMapData() {
        try {
            const response = await fetch('/api/map-data');
            const result = await response.json();

            if (result.success) {
                allMapData = result.data || [];
                populateMapFilters(result.filters || {});
                renderMapMarkers(allMapData);
            }
        } catch (error) {
            console.error('Error loading map data:', error);
        }
    }

    function populateMapFilters(filters) {
        const sektorSelect = document.getElementById('mapFilterSektor');
        const kelurahanSelect = document.getElementById('mapFilterKelurahan');

        // Ambil dari tabel master (bukan dari data marker)
        const sektors = filters.sektor || [];
        const kelurahans = filters.kelurahan || [];

        sektors.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s;
            opt.textContent = s;
            sektorSelect.appendChild(opt);
        });

        kelurahans.forEach(k => {
            const opt = document.createElement('option');
            opt.value = k;
            opt.textContent = k;
            kelurahanSelect.appendChild(opt);
        });
    }

    function renderMapMarkers(data) {
        umkmMarkers.clearLayers();

        data.forEach(umkm => {
            const icon = createUmkmIcon(umkm);
            const marker = L.marker([umkm.latitude, umkm.longitude], { icon: icon });
            marker.bindPopup(buildPopupContent(umkm), {
                maxWidth: 300,
                minWidth: 260,
            });
            umkmMarkers.addLayer(marker);
        });

        // Update counter
        document.getElementById('mapMarkerCount').textContent = data.length;

        // Fit bounds if there's data
        if (data.length > 0) {
            const bounds = L.latLngBounds(data.map(d => [d.latitude, d.longitude]));
            umkmMap.fitBounds(bounds, { padding: [40, 40], maxZoom: 16 });
        }
    }

    function filterMapMarkers() {
        const searchQuery = document.getElementById('mapSearchInput') ? document.getElementById('mapSearchInput').value.toLowerCase() : '';
        const sektorFilter = document.getElementById('mapFilterSektor').value;
        const kelurahanFilter = document.getElementById('mapFilterKelurahan').value;

        let filtered = allMapData;

        if (searchQuery) {
            filtered = filtered.filter(d => 
                (d.nama_usaha && d.nama_usaha.toLowerCase().includes(searchQuery)) ||
                (d.pemilik && d.pemilik.toLowerCase().includes(searchQuery)) ||
                (d.kategori && d.kategori.toLowerCase().includes(searchQuery))
            );
        }
        if (sektorFilter) {
            filtered = filtered.filter(d => d.sektor === sektorFilter);
        }
        if (kelurahanFilter) {
            filtered = filtered.filter(d => d.kelurahan === kelurahanFilter);
        }

        renderMapMarkers(filtered);
    }

    // Lazy-initialize map when section scrolls into view
    const mapObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !mapInitialized) {
                initUmkmMap();
                mapObserver.unobserve(entry.target);
            }
        });
    }, { rootMargin: '200px' });

    const mapSection = document.getElementById('peta-umkm');
    if (mapSection) {
        mapObserver.observe(mapSection);
    }
</script>

