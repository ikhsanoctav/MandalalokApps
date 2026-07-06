const fs = require('fs');

const input = process.argv[2] || '/tmp/workflow-dss-stable.json';
const output = process.argv[3] || '/tmp/workflow-dss-full.json';
const exported = JSON.parse(fs.readFileSync(input, 'utf8'));
const workflow = Array.isArray(exported)
  ? exported.find((item) => item.nodes?.some((node) => node.name === 'DSS Deterministic JSON'))
  : exported;
const dssNode = workflow?.nodes?.find((node) => node.name === 'DSS Deterministic JSON');

if (!dssNode) {
  throw new Error('DSS Deterministic JSON node not found');
}

dssNode.parameters.jsCode = String.raw`const body = $json.body || $json;
let stats = {};
try {
  stats = typeof body.stats_json === 'string' ? JSON.parse(body.stats_json) : (body.stats_json || {});
} catch (e) {
  stats = {};
}

const ringkasan = stats['Ringkasan Sistem'] || {};
const kategori = stats['Distribusi Berdasarkan Kategori Usaha'] || {};
const sektor = stats['Distribusi Berdasarkan Sektor Usaha'] || {};
const kelurahan = stats['Distribusi Berdasarkan Kelurahan'] || {};
const growth = stats['Pertumbuhan UMKM 6 Bulan Terakhir'] || {};
const kritis = Array.isArray(stats['Kelurahan Kritis (UMKM < 5)']) ? stats['Kelurahan Kritis (UMKM < 5)'] : [];

const entriesDesc = (obj) => Object.entries(obj || {}).sort((a, b) => Number(b[1] || 0) - Number(a[1] || 0));
const topEntry = (obj) => entriesDesc(obj)[0] || ['-', 0];
const bottomEntry = (obj) => entriesDesc(obj).filter(([, value]) => Number(value || 0) > 0).pop() || ['-', 0];
const percent = (value, total) => total > 0 ? Math.round((Number(value || 0) / total) * 1000) / 10 : 0;
const listTopNames = (obj, limit = 3) => entriesDesc(obj).slice(0, limit).map(([name]) => name);

const total = Number(ringkasan['Total Seluruh UMKM Terdaftar'] ?? 0);
const verified = Number(ringkasan['UMKM Terverifikasi'] ?? 0);
const pending = Number(ringkasan['UMKM Menunggu Verifikasi'] ?? 0);
const rejected = Number(ringkasan['UMKM Ditolak'] ?? 0);
const avgWorkers = Number(ringkasan['Rata-rata Tenaga Kerja per UMKM'] ?? 0);
const verificationRate = Number(ringkasan['Tingkat Verifikasi (%)'] ?? percent(verified, total));

const [kategoriTop, kategoriTopCount] = topEntry(kategori);
const [sektorTop, sektorTopCount] = topEntry(sektor);
const [kelurahanTop, kelurahanTopCount] = topEntry(kelurahan);
const [kelurahanLow] = bottomEntry(kelurahan);
const kategoriPercent = percent(kategoriTopCount, total);
const sektorPercent = percent(sektorTopCount, total);
const rejectionRate = percent(rejected, total);

const growthEntries = Object.entries(growth || {});
const growthValues = growthEntries.map(([, value]) => Number(value || 0));
const growthTotal = growthValues.reduce((sum, value) => sum + value, 0);
const bestGrowth = growthEntries.slice().sort((a, b) => Number(b[1] || 0) - Number(a[1] || 0))[0] || ['-', 0];
const lastGrowth = growthValues.length ? growthValues[growthValues.length - 1] : 0;
const firstGrowth = growthValues.length ? growthValues[0] : 0;
const growthTrend = lastGrowth > firstGrowth ? 'meningkat' : (lastGrowth < firstGrowth ? 'menurun' : 'stabil');
const kategoriNames = listTopNames(kategori, 4);
const sectorNames = listTopNames(sektor, 3);
const criticalAreas = kritis.length ? kritis : (kelurahanLow !== '-' ? [kelurahanLow] : []);
const hasData = total > 0;

const programSources = (kategoriNames.length ? kategoriNames : sectorNames.length ? sectorNames : ['UMKM Umum']).slice(0, 5);
const programs = programSources.map((name, index) => {
  const count = Number((kategori[name] ?? sektor[name] ?? 0));
  const dominance = percent(count, total);
  let programName = 'Kelas Pembukuan, Legalitas, dan Pemasaran Digital untuk ' + name;
  if (name.toLowerCase().includes('kuliner')) {
    programName = 'Pelatihan Higienitas, Kemasan, dan Pemasaran Digital Kuliner';
  }
  if (name.toLowerCase().includes('fashion')) {
    programName = 'Klinik Branding Produk Fashion dan Optimasi Marketplace';
  }
  return {
    kategori: name,
    skor_kebutuhan_pelatihan: Math.max(60, Math.min(95, Math.round(88 - (index * 7) + (pending > 0 ? 4 : 0)))),
    usulan_program: programName,
    alasan: 'Kelompok ' + name + ' mencakup sekitar ' + dominance + '% dari data UMKM sehingga intervensi pada kategori ini berdampak langsung pada basis pelaku usaha terbesar.'
  };
});

const priorities = [];
criticalAreas.slice(0, 2).forEach((area, index) => priorities.push({
  area,
  alasan: 'Jumlah UMKM di area ini masih rendah sehingga perlu validasi data, pendampingan, dan aktivasi pelaku usaha lokal.',
  skor_urgensi: Math.max(7, 9 - index),
  aksi_konkret: 'Jadwalkan pendataan lapangan, klinik legalitas, dan sosialisasi program UMKM di ' + area + '.'
}));
if (pending > 0) {
  priorities.push({
    area: 'Verifikasi UMKM tertunda',
    alasan: pending + ' UMKM masih menunggu verifikasi sehingga data program bantuan belum sepenuhnya siap dipakai.',
    skor_urgensi: 8,
    aksi_konkret: 'Tetapkan target verifikasi harian dan prioritaskan berkas yang sudah lengkap.'
  });
}
if (sektorTop !== '-') {
  priorities.push({
    area: 'Sektor ' + sektorTop,
    alasan: 'Sektor ini paling dominan dengan ' + sektorTopCount + ' UMKM dan perlu program pembinaan agar kualitas usaha meningkat.',
    skor_urgensi: 7,
    aksi_konkret: 'Buat kelas tematik sektor ' + sektorTop + ' yang fokus pada pemasaran, kualitas produk, dan akses pembiayaan.'
  });
}
while (priorities.length < 3) {
  priorities.push({
    area: priorities.length === 0 ? 'Pemutakhiran data UMKM' : 'Pembinaan UMKM lintas kelurahan',
    alasan: 'Data perlu terus diperkaya agar keputusan kebijakan lebih presisi.',
    skor_urgensi: 6,
    aksi_konkret: 'Lakukan monitoring rutin dan sinkronisasi data dengan petugas wilayah.'
  });
}

const analysis = {
  ringkasan_eksekutif: hasData
    ? 'Sistem mencatat ' + total + ' UMKM, dengan ' + verified + ' sudah terverifikasi, ' + pending + ' menunggu verifikasi, dan ' + rejected + ' ditolak. Kategori dominan adalah ' + kategoriTop + ' (' + kategoriTopCount + ' UMKM), sektor dominan ' + sektorTop + ', dan kelurahan paling aktif ' + kelurahanTop + '. Tingkat verifikasi berada di angka ' + verificationRate + '%, sehingga prioritas kebijakan perlu diarahkan pada validasi data, penguatan sektor dominan, dan pemerataan wilayah.'
    : 'Belum ada data UMKM yang cukup untuk dianalisis. Sistem perlu diisi data UMKM, kategori, sektor, dan wilayah agar rekomendasi DSS dapat ditampilkan secara bermakna.',
  keputusan_strategis_utama: [
    pending > 0 ? 'Percepat verifikasi ' + pending + ' UMKM tertunda agar data siap dipakai untuk program bantuan dan pembinaan.' : 'Pertahankan kualitas verifikasi data dan lakukan audit berkala pada UMKM yang sudah tervalidasi.',
    sektorTop !== '-' ? 'Fokuskan program pembinaan awal pada sektor ' + sektorTop + ' karena menjadi konsentrasi terbesar UMKM saat ini.' : 'Lengkapi data sektor usaha agar pembinaan dapat ditargetkan dengan presisi.',
    criticalAreas.length ? 'Lakukan intervensi wilayah pada ' + criticalAreas.slice(0, 2).join(', ') + ' karena jumlah UMKM masih rendah.' : 'Gunakan data kelurahan untuk menjaga pemerataan pembinaan antar wilayah.'
  ],
  indikator_kunci: {
    tingkat_verifikasi_persen: verificationRate,
    rasio_penolakan_persen: rejectionRate,
    jumlah_kelurahan_kritis: criticalAreas.length,
    rata_rata_tenaga_kerja: avgWorkers,
    kategori_paling_dominan: kategoriTop,
    sektor_paling_dominan: sektorTop,
    kelurahan_paling_aktif: kelurahanTop
  },
  analisis_swot: {
    kekuatan: [
      hasData ? 'Basis data UMKM sudah tersedia sebanyak ' + total + ' entitas sehingga kebijakan dapat mulai berbasis data.' : 'Sistem DSS sudah siap menerima data UMKM untuk analisis kebijakan.',
      sektorTop !== '-' ? 'Sektor ' + sektorTop + ' memberi titik fokus yang jelas untuk program pembinaan.' : 'Struktur data sektor memungkinkan pemetaan potensi ekonomi lokal.'
    ],
    kelemahan: [
      pending > 0 ? 'Masih ada ' + pending + ' UMKM menunggu verifikasi yang dapat mengurangi akurasi keputusan.' : 'Pemutakhiran data tetap perlu dijaga agar data tidak cepat usang.',
      criticalAreas.length ? 'Sebaran UMKM belum merata, terlihat dari area kritis: ' + criticalAreas.slice(0, 3).join(', ') + '.' : 'Analisis pemerataan wilayah membutuhkan pemantauan berkala.'
    ],
    peluang: [
      kategoriTop !== '-' ? 'Dominasi kategori ' + kategoriTop + ' dapat dijadikan basis program pelatihan tematik dan kolaborasi pasar.' : 'Pengisian kategori usaha membuka peluang program tematik yang lebih akurat.',
      growthTotal > 0 ? 'Data pertumbuhan bulanan dapat dipakai untuk membaca momentum pendampingan UMKM.' : 'Kanal pendataan baru dapat meningkatkan cakupan UMKM yang terdaftar.'
    ],
    ancaman: [
      rejectionRate > 10 ? 'Rasio penolakan ' + rejectionRate + '% perlu diawasi karena bisa menandakan kualitas berkas atau pendampingan awal belum optimal.' : 'Kualitas berkas perlu terus dijaga agar rasio penolakan tidak meningkat.',
      sectorNames.length <= 1 ? 'Konsentrasi pada sedikit sektor dapat membuat ekosistem kurang tahan terhadap perubahan pasar.' : 'Ketimpangan antar sektor perlu dipantau agar program tidak hanya terkonsentrasi pada sektor dominan.'
    ]
  },
  analisis_verifikasi: {
    insight: 'Dari ' + total + ' UMKM, ' + verified + ' terverifikasi, ' + pending + ' menunggu, dan ' + rejected + ' ditolak. Angka ini menunjukkan kesiapan data berada pada level ' + verificationRate + '%.',
    tingkat_keberhasilan: verificationRate >= 80 ? 'Baik' : (verificationRate >= 50 ? 'Cukup dan perlu percepatan' : 'Perlu perhatian serius'),
    rekomendasi_operasional: pending > 0 ? 'Buat antrean prioritas verifikasi berdasarkan kelengkapan berkas dan jadwalkan tindak lanjut petugas setiap hari.' : 'Lakukan audit sampel berkala dan pertahankan standar verifikasi.'
  },
  analisis_kategori: {
    kategori_dominan: kategoriTop + (kategoriTop !== '-' ? ' dengan ' + kategoriTopCount + ' UMKM' : ''),
    persentase_dominan: kategoriPercent,
    insight_ekosistem: kategoriTop !== '-' ? 'Dominasi ' + kategoriTop + ' menunjukkan kebutuhan pembinaan yang seragam dan peluang efisiensi program.' : 'Data kategori belum cukup untuk membaca pola ekosistem usaha.',
    potensi_kolaborasi: kategoriNames.length > 1 ? 'Fasilitasi kolaborasi antara ' + kategoriNames.slice(0, 3).join(', ') + ' melalui bazar, paket promosi, dan kanal digital bersama.' : 'Dorong pengayaan kategori usaha agar kolaborasi antar pelaku dapat dipetakan.'
  },
  analisis_sektor: {
    sektor_dominan: sektorTop,
    insight_dan_risiko: sektorTop !== '-' ? 'Sektor ' + sektorTop + ' mencakup sekitar ' + sektorPercent + '% data UMKM. Konsentrasi ini baik untuk program tematik, tetapi berisiko jika permintaan pasar sektor tersebut melemah.' : 'Data sektor belum cukup untuk membaca risiko konsentrasi usaha.',
    rekomendasi_diversifikasi: 'Dorong diversifikasi melalui pelatihan lintas sektor, inkubasi produk turunan, dan akses kemitraan pasar.'
  },
  analisis_wilayah: {
    kelurahan_terkuat: kelurahanTop,
    kelurahan_perlu_perhatian: criticalAreas,
    insight_ketimpangan: kelurahanTop !== '-' ? 'Kelurahan ' + kelurahanTop + ' menjadi area paling aktif dengan ' + kelurahanTopCount + ' UMKM, sedangkan ' + kelurahanLow + ' tercatat lebih rendah. Ini menunjukkan perlunya pemerataan pendataan dan pembinaan.' : 'Data kelurahan belum cukup untuk membaca ketimpangan wilayah.',
    strategi_pemerataan: 'Prioritaskan pendataan lapangan pada kelurahan rendah UMKM, bentuk klinik layanan bergerak, dan gunakan jadwal pembinaan bergilir antar kelurahan.'
  },
  analisis_pertumbuhan: {
    tren_umum: growthEntries.length ? 'Dalam 6 bulan terakhir tren pendaftaran UMKM terlihat ' + growthTrend + ' dengan total tambahan ' + growthTotal + ' UMKM.' : 'Data pertumbuhan bulanan belum tersedia secara memadai.',
    bulan_terbaik: bestGrowth[0],
    insight_musiman: growthEntries.length ? 'Bulan ' + bestGrowth[0] + ' menjadi periode terbaik dengan ' + bestGrowth[1] + ' UMKM baru. Pola ini perlu dibandingkan dengan agenda pembinaan dan sosialisasi wilayah.' : 'Belum dapat disimpulkan pola musiman karena data pertumbuhan terbatas.',
    proyeksi: growthTrend === 'meningkat' ? 'Jika tren berlanjut, kebutuhan verifikasi dan pembinaan akan meningkat sehingga kapasitas petugas perlu disiapkan.' : 'Pertumbuhan perlu didorong lewat sosialisasi, kemudahan pendaftaran, dan program kelurahan aktif.'
  },
  rekomendasi_kebijakan: {
    jangka_pendek_1_3_bulan: [
      pending > 0 ? 'Selesaikan verifikasi ' + pending + ' UMKM tertunda dengan target harian petugas.' : 'Lakukan audit kualitas data UMKM terverifikasi secara sampling.',
      criticalAreas.length ? 'Lakukan pendataan lapangan di ' + criticalAreas.slice(0, 2).join(', ') + '.' : 'Perbarui data kelurahan dan pemilik agar pemetaan wilayah makin akurat.',
      'Jalankan klinik legalitas, NIB, dan kelengkapan berkas untuk UMKM yang belum siap program bantuan.'
    ],
    jangka_menengah_3_6_bulan: [
      sektorTop !== '-' ? 'Bangun program pembinaan tematik sektor ' + sektorTop + ' dengan modul pemasaran digital dan kualitas produk.' : 'Susun kurikulum pembinaan berdasarkan kategori dan sektor setelah data lengkap.',
      'Buat dashboard monitoring bulanan untuk status verifikasi, pertumbuhan, dan sebaran wilayah.'
    ],
    jangka_panjang_6_12_bulan: [
      'Bangun ekosistem kemitraan pasar antara UMKM, kelurahan, komunitas bisnis, dan kanal digital.',
      'Integrasikan hasil DSS sebagai dasar prioritas anggaran pembinaan UMKM tahunan.'
    ]
  },
  program_kerja_kategori: programs,
  prioritas_intervensi: priorities.slice(0, 3),
  catatan_metodologi: 'Analisis ini dibuat otomatis dari statistik database terbaru. Rekomendasi bersifat rule-based agar stabil dan tidak bergantung pada respons LLM yang dapat timeout atau menghasilkan JSON tidak valid.'
};

return [{ json: { output: JSON.stringify(analysis) } }];`;

fs.writeFileSync(output, JSON.stringify(Array.isArray(exported) ? exported : workflow, null, 2) + '\n');
console.log(output);
