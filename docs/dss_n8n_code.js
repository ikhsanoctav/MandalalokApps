const body = $json.body || $json;
let stats = {};
try {
  stats = typeof body.stats_json === 'string' ? JSON.parse(body.stats_json) : (body.stats_json || {});
} catch (e) {
  throw new Error('stats_json tidak valid: ' + e.message);
}

const decisionFocus = body.decision_focus || '';

const schema = {
  ringkasan_eksekutif: '',
  hasil_intervensi_khusus: 'JAWABAN LENGKAP FORMAT MARKDOWN. JIKA USER MINTA TABEL, BUAT TABEL MARKDOWN. JIKA USER MINTA GRAFIK, BUAT GRAFIK MERMAID JS (Gunakan blok kode ```mermaid). WAJIB SESUAIKAN DENGAN PERMINTAAN USER.',
  keputusan_strategis_utama: ['', '', ''],
  indikator_kunci: {
    tingkat_verifikasi_persen: 0,
    rasio_penolakan_persen: 0,
    jumlah_kelurahan_kritis: 0,
    rata_rata_tenaga_kerja: 0,
    kategori_paling_dominan: '',
    sektor_paling_dominan: '',
    kelurahan_paling_aktif: ''
  },
  analisis_swot: {
    kekuatan: ['', ''],
    kelemahan: ['', ''],
    peluang: ['', ''],
    ancaman: ['', '']
  },
  analisis_verifikasi: {
    insight: '',
    tingkat_keberhasilan: '',
    rekomendasi_operasional: ''
  },
  analisis_kategori: {
    kategori_dominan: '',
    persentase_dominan: 0,
    insight_ekosistem: '',
    potensi_kolaborasi: ''
  },
  analisis_sektor: {
    sektor_dominan: '',
    insight_dan_risiko: '',
    rekomendasi_diversifikasi: ''
  },
  analisis_wilayah: {
    kelurahan_terkuat: '',
    kelurahan_perlu_perhatian: [],
    insight_ketimpangan: '',
    strategi_pemerataan: ''
  },
  analisis_pertumbuhan: {
    tren_umum: '',
    bulan_terbaik: '',
    insight_musiman: '',
    proyeksi: ''
  },
  rekomendasi_kebijakan: {
    jangka_pendek_1_3_bulan: ['', '', ''],
    jangka_menengah_3_6_bulan: ['', ''],
    jangka_panjang_6_12_bulan: ['', '']
  },
  program_kerja_kategori: [
    {
      kategori: '',
      skor_kebutuhan_pelatihan: 80,
      usulan_program: '',
      alasan: ''
    }
  ],
  prioritas_intervensi: [
    {
      area: '',
      alasan: '',
      skor_urgensi: 8,
      aksi_konkret: ''
    }
  ],
  catatan_metodologi: ''
};

const prompt = [
  'Kamu adalah analis kebijakan UMKM senior untuk DSS Mandalaloka.',
  'Analisis HARUS berbasis data statistik aktual berikut, jangan mengarang angka di luar data.',
  JSON.stringify(stats, null, 2),
  '',
  decisionFocus,
  '',
  'Tugas:',
  '1. Buat analisis kebijakan UMKM yang tajam, spesifik, dan bisa dieksekusi.',
  '2. Gunakan bahasa Indonesia formal.',
  '3. Semua angka harus dihitung final, bukan rumus.',
  '4. Wajib kembalikan JSON valid murni tanpa markdown.',
  '5. Semua key harus sama persis dengan schema berikut.',
  JSON.stringify(schema, null, 2)
].join('\n');

const result = await this.helpers.httpRequest({
  method: 'POST',
  url: 'http://host.docker.internal:11434/api/generate',
  headers: { 'Content-Type': 'application/json' },
  body: {
    model: 'qwen2.5:1.5b',
    prompt,
    stream: false,
    format: 'json',
    options: {
      temperature: 0.25,
      top_p: 0.9,
      num_predict: 6000
    }
  },
  json: true,
  timeout: 85000
});
const raw = String(result.response || '').trim();

function parseJson(text) {
  try {
    return JSON.parse(text);
  } catch (firstError) {
    const start = text.indexOf('{');
    const end = text.lastIndexOf('}');
    if (start !== -1 && end !== -1 && end > start) {
      return JSON.parse(text.slice(start, end + 1));
    }
    throw firstError;
  }
}

function arrayValue(value, fallback = []) {
  return Array.isArray(value) ? value : fallback;
}

function objectValue(value, fallback = {}) {
  return value && typeof value === 'object' && !Array.isArray(value) ? value : fallback;
}

const analysis = objectValue(parseJson(raw));
analysis.keputusan_strategis_utama = arrayValue(analysis.keputusan_strategis_utama, []);
analysis.indikator_kunci = objectValue(analysis.indikator_kunci);
analysis.analisis_swot = objectValue(analysis.analisis_swot);
analysis.analisis_swot.kekuatan = arrayValue(analysis.analisis_swot.kekuatan, []);
analysis.analisis_swot.kelemahan = arrayValue(analysis.analisis_swot.kelemahan, []);
analysis.analisis_swot.peluang = arrayValue(analysis.analisis_swot.peluang, []);
analysis.analisis_swot.ancaman = arrayValue(analysis.analisis_swot.ancaman, []);
analysis.analisis_verifikasi = objectValue(analysis.analisis_verifikasi);
analysis.analisis_kategori = objectValue(analysis.analisis_kategori);
analysis.analisis_sektor = objectValue(analysis.analisis_sektor);
analysis.analisis_wilayah = objectValue(analysis.analisis_wilayah);
analysis.analisis_wilayah.kelurahan_perlu_perhatian = arrayValue(analysis.analisis_wilayah.kelurahan_perlu_perhatian, []);
analysis.analisis_pertumbuhan = objectValue(analysis.analisis_pertumbuhan);
analysis.rekomendasi_kebijakan = objectValue(analysis.rekomendasi_kebijakan);
analysis.rekomendasi_kebijakan.jangka_pendek_1_3_bulan = arrayValue(analysis.rekomendasi_kebijakan.jangka_pendek_1_3_bulan, []);
analysis.rekomendasi_kebijakan.jangka_menengah_3_6_bulan = arrayValue(analysis.rekomendasi_kebijakan.jangka_menengah_3_6_bulan, []);
analysis.rekomendasi_kebijakan.jangka_panjang_6_12_bulan = arrayValue(analysis.rekomendasi_kebijakan.jangka_panjang_6_12_bulan, []);
analysis.program_kerja_kategori = arrayValue(analysis.program_kerja_kategori, []);
analysis.prioritas_intervensi = arrayValue(analysis.prioritas_intervensi, []);
analysis.catatan_metodologi = analysis.catatan_metodologi || 'Analisis dibuat oleh Ollama melalui n8n berdasarkan statistik database terbaru.';

return [{ json: { output: JSON.stringify(analysis) } }];
