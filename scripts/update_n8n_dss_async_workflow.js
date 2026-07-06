const fs = require('fs');

const input = process.argv[2] || '/tmp/workflow-current.json';
const output = process.argv[3] || '/tmp/workflow-dss-async.json';
const exported = JSON.parse(fs.readFileSync(input, 'utf8'));
const workflow = Array.isArray(exported)
  ? exported.find((item) => item.nodes?.some((node) => node.name === 'Webhook – DSS'))
  : exported;

if (!workflow) {
  throw new Error('Workflow DSS not found');
}

const webhookNode = workflow.nodes.find((node) => node.name === 'Webhook – DSS');
const dssNode = workflow.nodes.find((node) => ['DSS Async Ollama Callback', 'DSS Ollama LLM JSON', 'DSS Deterministic JSON'].includes(node.name));
const responseNodeIndex = workflow.nodes.findIndex((node) => node.name === 'Balas ke Laravel DSS');

if (!webhookNode || !dssNode) {
  throw new Error('Required DSS nodes not found');
}

webhookNode.parameters.responseMode = 'onReceived';
webhookNode.parameters.responseData = 'firstEntryJson';
webhookNode.parameters.options = {
  responseCode: 202,
};

const previousName = dssNode.name;
dssNode.name = 'DSS Async Ollama Callback';
dssNode.parameters.jsCode = String.raw`const body = $json.body || $json;
const jobId = body.job_id;
const callbackUrl = body.callback_url;
const callbackToken = body.callback_token;
const decisionFocus = String(body.decision_focus || '').trim();

if (!jobId || !callbackUrl || !callbackToken) {
  throw new Error('Payload async DSS tidak lengkap.');
}

let stats = {};
try {
  stats = typeof body.stats_json === 'string' ? JSON.parse(body.stats_json) : (body.stats_json || {});
} catch (e) {
  throw new Error('stats_json tidak valid: ' + e.message);
}

const schema = {
  ringkasan_eksekutif: '2-3 kalimat untuk pimpinan: kondisi UMKM, risiko utama, dan peluang kebijakan',
  keputusan_strategis_utama: [
    'Keputusan pimpinan tentang prioritas program/anggaran/wilayah',
    'Keputusan pimpinan tentang sektor/kategori yang perlu didorong',
    'Keputusan pimpinan tentang tindakan lintas petugas/kelurahan'
  ],
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
    kekuatan: ['poin', 'poin'],
    kelemahan: ['poin', 'poin'],
    peluang: ['poin', 'poin'],
    ancaman: ['poin', 'poin']
  },
  analisis_verifikasi: {
    insight: 'Makna angka verifikasi bagi kualitas basis keputusan dan risiko pelayanan UMKM',
    tingkat_keberhasilan: 'Penilaian singkat capaian verifikasi dari sudut pandang pimpinan',
    rekomendasi_operasional: 'Arahan lapangan: target backlog, pembagian petugas, jadwal validasi, atau jemput bola'
  },
  analisis_kategori: {
    kategori_dominan: '',
    persentase_dominan: 0,
    insight_ekosistem: 'Implikasi dominasi kategori terhadap arah program pembinaan dan bantuan',
    potensi_kolaborasi: 'Kolaborasi program dengan kelurahan, komunitas usaha, pasar, koperasi, BUMD, kampus, atau pelaku swasta'
  },
  analisis_sektor: {
    sektor_dominan: '',
    insight_dan_risiko: 'Implikasi konsentrasi sektor terhadap ekonomi wilayah',
    rekomendasi_diversifikasi: 'Keputusan program untuk mendorong sektor pelengkap, akses pasar, kemitraan, atau pelatihan usaha'
  },
  analisis_wilayah: {
    kelurahan_terkuat: '',
    kelurahan_perlu_perhatian: ['nama'],
    insight_ketimpangan: 'Ketimpangan sebaran UMKM dan konsekuensinya bagi prioritas pembinaan wilayah',
    strategi_pemerataan: 'Keputusan pemerataan: prioritas kelurahan, jadwal intervensi, dan bentuk dukungan'
  },
  analisis_pertumbuhan: {
    tren_umum: 'Makna tren pertumbuhan bagi momentum kebijakan UMKM',
    bulan_terbaik: '',
    insight_musiman: 'Peluang kalender program, event pasar, atau musim usaha jika terlihat dari data',
    proyeksi: 'Arah kebijakan 3-6 bulan berdasarkan tren data'
  },
  rekomendasi_kebijakan: {
    jangka_pendek_1_3_bulan: [
      'Keputusan cepat yang bisa dipimpin langsung oleh kecamatan/dinas',
      'Prioritas wilayah/sektor yang perlu dieksekusi segera',
      'Arahan koordinasi petugas/kelurahan/mitra'
    ],
    jangka_menengah_3_6_bulan: [
      'Program pembinaan atau kemitraan yang butuh persiapan',
      'Paket intervensi wilayah/sektor berbasis data'
    ],
    jangka_panjang_6_12_bulan: [
      'Agenda penguatan ekosistem UMKM lintas wilayah',
      'Kebijakan berulang/tahunan berbasis hasil evaluasi'
    ]
  },
  program_kerja_kategori: [
    { kategori: '', skor_kebutuhan_pelatihan: 80, usulan_program: 'Nama program pembinaan/akses pasar/kemitraan yang spesifik', alasan: 'Alasan dampak kebijakan berdasarkan data' },
    { kategori: '', skor_kebutuhan_pelatihan: 75, usulan_program: 'Nama program lain yang spesifik', alasan: 'Alasan dampak kebijakan berdasarkan data' }
  ],
  prioritas_intervensi: [
    { area: 'Kelurahan/sektor/kategori prioritas', alasan: 'Alasan berbasis data', skor_urgensi: 9, aksi_konkret: 'Keputusan tindakan lapangan atau program yang harus dijalankan' },
    { area: 'Kelurahan/sektor/kategori prioritas lain', alasan: 'Alasan berbasis data', skor_urgensi: 7, aksi_konkret: 'Keputusan tindakan lapangan atau program yang harus dijalankan' }
  ],
  catatan_metodologi: 'Analisis dibuat oleh Ollama dari statistik database Mandalaloka.'
};

const prompt = [
  'Anda adalah staf ahli kebijakan ekonomi wilayah untuk Camat/Kepala Dinas.',
  'DSS Mandalaloka dipakai untuk membantu pemegang keputusan menentukan prioritas kebijakan UMKM, bukan untuk memberi saran pengembangan sistem/aplikasi.',
  'Tulis sebagai bahan rapat pimpinan: keputusan yang harus diambil, prioritas wilayah, prioritas sektor/kategori, program, alokasi perhatian, dan tindakan lapangan.',
  'Jangan memberi rekomendasi teknis seperti "kembangkan sistem", "perbaiki aplikasi", "optimalkan database", "buat dashboard", atau "bangun sistem verifikasi".',
  'Jika membahas verifikasi, arahkan ke keputusan manajerial/lapangan: target penyelesaian backlog, pembagian petugas, jadwal jemput bola, validasi berkas, pendampingan UMKM, dan prioritas kelurahan.',
  'Setiap rekomendasi harus berbentuk keputusan eksekutif yang bisa diputuskan pimpinan kecamatan/dinas.',
  decisionFocus ? 'Instruksi fokus tambahan dari Laravel:' : '',
  decisionFocus,
  'Analisis data berikut dan kembalikan HANYA JSON valid. Jangan markdown, jangan teks pembuka.',
  'Gunakan angka hanya dari data. Hitung angka final, jangan menulis rumus.',
  '',
  'DATA:',
  JSON.stringify(stats, null, 2),
  '',
  'JSON wajib memakai struktur ini:',
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
      num_predict: 2400
    }
  },
  json: true,
  timeout: 240000
});

const raw = String(result.response || '').trim();
const start = raw.indexOf('{');
const end = raw.lastIndexOf('}');
const output = start !== -1 && end !== -1 && end > start ? raw.slice(start, end + 1) : raw;

await this.helpers.httpRequest({
  method: 'POST',
  url: callbackUrl,
  headers: { 'Content-Type': 'application/json' },
  body: {
    job_id: jobId,
    token: callbackToken,
    output,
  },
  json: true,
  timeout: 30000
});

return [{ json: { job_id: jobId, callback_sent: true } }];`;

if (previousName !== dssNode.name && workflow.connections?.[previousName]) {
  workflow.connections[dssNode.name] = workflow.connections[previousName];
  delete workflow.connections[previousName];
}

for (const source of Object.values(workflow.connections || {})) {
  for (const channel of Object.values(source || {})) {
    for (const group of channel || []) {
      for (const connection of group || []) {
        if (connection.node === previousName || connection.node === 'Balas ke Laravel DSS') {
          connection.node = dssNode.name;
        }
      }
    }
  }
}

workflow.connections[dssNode.name] = { main: [[]] };

if (responseNodeIndex !== -1) {
  workflow.nodes.splice(responseNodeIndex, 1);
}

fs.writeFileSync(output, JSON.stringify(Array.isArray(exported) ? exported : workflow, null, 2) + '\n');
console.log(output);
