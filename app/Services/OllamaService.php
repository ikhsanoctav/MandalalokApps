<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaService
{
    /**
     * Analyze sentiment of a public report via n8n webhook
     */
    public function analyzeSentiment(string $text): array
    {
        $webhookUrl = config('services.n8n.sentiment_webhook_url');

        try {
            $response = Http::timeout(60)->post($webhookUrl, [
                'text' => $text
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'sentimen' => $data['sentimen'] ?? 'Netral',
                    'ringkasan' => $data['ringkasan'] ?? $text
                ];
            }
        } catch (\Exception $e) {
            Log::error('n8n Sentiment Webhook Error: ' . $e->getMessage());
        }

        // Fallback jika gagal
        return [
            'sentimen' => 'Netral',
            'ringkasan' => 'Gagal dianalisis oleh AI. ' . substr($text, 0, 100),
        ];
    }

    /**
     * Parse natural language for executive assistant into database query intent via n8n
     */
    public function parseExecutiveIntent(string $query): array
    {
        $webhookUrl = config('services.n8n.assistant_webhook_url');

        try {
            $response = Http::timeout(60)->post($webhookUrl, [
                'query' => $query
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // If n8n returns the unparsed string in raw_response
                if (isset($data['raw_response'])) {
                    $parsed = json_decode($data['raw_response'], true);
                    if (is_array($parsed)) {
                        $data = array_merge($data, $parsed);
                    }
                }

                // Expecting n8n to return: { intent: "count", kategori: "...", kelurahan: "..." }
                return [
                    'intent' => $data['intent'] ?? 'unknown',
                    'kategori' => $data['kategori'] ?? null,
                    'kelurahan' => $data['kelurahan'] ?? null,
                ];
            }
        } catch (\Exception $e) {
            Log::error('n8n Assistant Webhook Error: ' . $e->getMessage());
        }

        return ['intent' => 'unknown'];
    }

    /**
     * Generate DSS Report based on comprehensive database stats.
     * Calls Ollama directly for highest accuracy and richest analysis.
     */
    public function generateDssReport(array $stats, ?string $intervention = null): ?string
    {
        $statsJson = json_encode($stats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $prompt = <<<PROMPT
Kamu adalah Dr. Analis Senior dari Badan Riset Kebijakan Daerah, dengan spesialisasi pada analisis ekosistem UMKM di Indonesia.
Tugasmu adalah menghasilkan LAPORAN ANALISIS KEBIJAKAN yang profesional, mendalam, dan dapat langsung digunakan oleh Camat dan Kepala Seksi Ekonomi sebagai dasar pengambilan keputusan strategis.

DATA STATISTIK AKTUAL DARI DATABASE SISTEM MANDALALOKA:
{$statsJson}

INSTRUKSI KRITIS:
- Analisis HANYA berdasarkan data yang diberikan di atas. DILARANG KERAS mengarang data atau angka yang tidak ada.
- Semua angka yang kamu sebutkan HARUS berasal dari data di atas.
- Berikan insight yang tajam, bukan hanya menyebut ulang angka.
- Output HARUS berupa JSON valid murni, tanpa markdown, tanpa teks lain di luar JSON.
- DILARANG KERAS menuliskan operasi matematika/rumus pembagian di nilai JSON (contoh: DILARANG menulis 7 / 130 * 100). Kamu HARUS menghitungnya sendiri hingga selesai dan menuliskan hasilnya sebagai angka desimal/bulat literal langsung (contoh: 5.38 atau 22.3).
- Semua kunci (keys) JSON harus persis sesuai template di bawah.
- Di bagian "program_kerja_kategori", berikan rekomendasi usulan_program yang kreatif, spesifik dan realistis. Program bisa menargetkan kategori usaha (Mikro/Kecil/Menengah) ATAU sektor usaha (Kuliner/Fashion/dll). Contoh: jika sektor Kuliner kurang berkembang, rekomendasikan program seperti "Pelatihan Higienitas & Digital Marketing Kuliner". Jika untuk kategori Mikro, rekomendasikan program seperti "Pelatihan Pembukuan Keuangan Sederhana untuk Usaha Mikro". DILARANG menggunakan satu nama pelatihan generik yang sama untuk semua kategori.
EOF;

        $prompt = <<<PROMPT
Anda adalah staf ahli kebijakan ekonomi wilayah untuk Camat/Kepala Dinas. DSS Mandalaloka dipakai untuk membantu pemegang keputusan menentukan prioritas kebijakan UMKM, bukan untuk memberi saran pengembangan sistem/aplikasi.

FOKUS OUTPUT:
- Tulis sebagai bahan rapat pimpinan: keputusan yang harus diambil, prioritas wilayah, prioritas sektor/kategori, program, alokasi perhatian, dan tindakan lapangan.
- Jangan memberi rekomendasi teknis seperti "kembangkan sistem", "perbaiki aplikasi", "optimalkan database", "buat dashboard", atau "bangun sistem verifikasi".
- Jika membahas verifikasi, arahkan ke keputusan manajerial/lapangan: target penyelesaian backlog, pembagian petugas, jadwal jemput bola, validasi berkas, pendampingan UMKM, dan prioritas kelurahan.
- Setiap rekomendasi harus berbentuk keputusan eksekutif yang bisa diputuskan pimpinan kecamatan/dinas.
- Analisis data berikut dan kembalikan HANYA JSON valid. Jangan markdown, jangan teks pembuka.
- Gunakan angka hanya dari data. Hitung angka final, jangan menulis rumus.
PROMPT;

        if ($intervention) {
            $prompt .= <<<EOF

PERINTAH INTERVENSI KEBIJAKAN DARI USER: "{$intervention}"
PENTING: Anda WAJIB memprioritaskan perintah intervensi di atas dalam analisis Anda. Jadikan ini sebagai fokus utama laporan strategi, terutama pada bagian keputusan_strategis_utama dan rekomendasi prioritas wilayah/sektor, sambil tetap mendasarkan analisis pada data yang diberikan.
EOF;
        }

        $prompt .= <<<PROMPT

DATA:
{$statsJson}


JSON wajib memakai struktur ini:
{
  "ringkasan_eksekutif": "2-3 kalimat ringkasan untuk pimpinan: kondisi UMKM, risiko utama, dan peluang kebijakan",
  "keputusan_strategis_utama": [
    "Keputusan pimpinan 1: prioritas program/anggaran/wilayah yang harus ditetapkan",
    "Keputusan pimpinan 2: sektor/kategori yang perlu didorong atau dikendalikan",
    "Keputusan pimpinan 3: tindakan lintas petugas/kelurahan yang harus dieksekusi"
  ],
  "indikator_kunci": {
    "tingkat_verifikasi_persen": 0,
    "rasio_penolakan_persen": 0,
    "jumlah_kelurahan_kritis": 0,
    "rata_rata_tenaga_kerja": 0,
    "kategori_paling_dominan": "",
    "sektor_paling_dominan": "",
    "kelurahan_paling_aktif": ""
  },
  "analisis_swot": {
    "kekuatan": ["poin", "poin"],
    "kelemahan": ["poin", "poin"],
    "peluang": ["poin", "poin"],
    "ancaman": ["poin", "poin"]
  },
  "analisis_verifikasi": {
    "insight": "Makna angka verifikasi bagi kualitas basis keputusan dan risiko pelayanan UMKM",
    "tingkat_keberhasilan": "Penilaian singkat capaian verifikasi dari sudut pandang pimpinan",
    "rekomendasi_operasional": "Arahan lapangan, bukan pengembangan sistem: target backlog, pembagian petugas, jadwal validasi, atau jemput bola"
  },
  "analisis_kategori": {
    "kategori_dominan": "",
    "persentase_dominan": 0,
    "insight_ekosistem": "Implikasi dominasi kategori terhadap arah program pembinaan dan bantuan",
    "potensi_kolaborasi": "Kolaborasi program dengan kelurahan, komunitas usaha, pasar, koperasi, BUMD, kampus, atau pelaku swasta"
  },
  "analisis_sektor": {
    "sektor_dominan": "",
    "insight_dan_risiko": "Implikasi konsentrasi sektor terhadap ekonomi wilayah",
    "rekomendasi_diversifikasi": "Keputusan program untuk mendorong sektor pelengkap, akses pasar, kemitraan, atau pelatihan usaha"
  },
  "analisis_wilayah": {
    "kelurahan_terkuat": "",
    "kelurahan_perlu_perhatian": ["nama"],
    "insight_ketimpangan": "Ketimpangan sebaran UMKM dan konsekuensinya bagi prioritas pembinaan wilayah",
    "strategi_pemerataan": "Keputusan pemerataan: prioritas kelurahan, jadwal intervensi, dan bentuk dukungan"
  },
  "analisis_pertumbuhan": {
    "tren_umum": "Makna tren pertumbuhan bagi momentum kebijakan UMKM",
    "bulan_terbaik": "",
    "insight_musiman": "Peluang kalender program, event pasar, atau musim usaha jika terlihat dari data",
    "proyeksi": "Arah kebijakan 3-6 bulan berdasarkan tren data"
  },
  "rekomendasi_kebijakan": {
    "jangka_pendek_1_3_bulan": [
      "Keputusan cepat yang bisa dipimpin langsung oleh kecamatan/dinas",
      "Prioritas wilayah/sektor yang perlu dieksekusi segera",
      "Arahan koordinasi petugas/kelurahan/mitra"
    ],
    "jangka_menengah_3_6_bulan": [
      "Program pembinaan atau kemitraan yang butuh persiapan",
      "Paket intervensi wilayah/sektor berbasis data"
    ],
    "jangka_panjang_6_12_bulan": [
      "Agenda penguatan ekosistem UMKM lintas wilayah",
      "Kebijakan berulang/tahunan berbasis hasil evaluasi"
    ]
  },
  "program_kerja_kategori": [
    {"kategori": "", "skor_kebutuhan_pelatihan": 80, "usulan_program": "Nama program pembinaan/akses pasar/kemitraan yang spesifik", "alasan": "Alasan dampak kebijakan berdasarkan data"},
    {"kategori": "", "skor_kebutuhan_pelatihan": 75, "usulan_program": "Nama program lain yang spesifik", "alasan": "Alasan dampak kebijakan berdasarkan data"}
  ],
  "prioritas_intervensi": [
    {"area": "Kelurahan/sektor/kategori prioritas", "alasan": "Alasan berbasis data", "skor_urgensi": 9, "aksi_konkret": "Keputusan tindakan lapangan atau program yang harus dijalankan"},
    {"area": "Kelurahan/sektor/kategori prioritas lain", "alasan": "Alasan berbasis data", "skor_urgensi": 7, "aksi_konkret": "Keputusan tindakan lapangan atau program yang harus dijalankan"}
  ],
  "catatan_metodologi": "Analisis dibuat oleh Ollama dari statistik database Mandalaloka."
}
PROMPT;

        try {
            $ollamaUrl = rtrim(config('services.ollama.base_url', 'http://host.docker.internal:11434'), '/') . '/api/generate';
            $model = config('services.ollama.dss_model', 'qwen2.5:1.5b');

            $response = Http::timeout(240)->post($ollamaUrl, [
                'model' => $model,
                'prompt' => $prompt,
                'stream' => false,
                'format' => 'json',
                'options' => [
                    'temperature' => 0.25,
                    'top_p' => 0.9,
                    'num_predict' => 2400,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();

                $ret = is_string($data['response'] ?? null)
                    ? trim($data['response'])
                    : $response->body();

                $jsonStart = strpos($ret, '{');
                $jsonEnd = strrpos($ret, '}');
                if ($jsonStart !== false && $jsonEnd !== false && $jsonEnd > $jsonStart) {
                    $ret = substr($ret, $jsonStart, $jsonEnd - $jsonStart + 1);
                }

                \Log::info('OllamaService final return string', ['return' => $ret]);
                return $ret;
            } else {
                Log::error('Ollama DSS Generate Failed', [
                    'status' => $response->status(),
                    'body'   => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Ollama DSS Error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Generate Chatbot Response directly from Ollama
     */
    public function generateChatResponse(string $systemContext, string $message, array $history = []): ?string
    {
        try {
            $ollamaUrl = rtrim(config('services.ollama.base_url', 'http://host.docker.internal:11434'), '/') . '/api/chat';
            $model = 'qwen2.5:3b'; // Switch back to 3b for better intelligence and natural language

            $messages = [
                ['role' => 'system', 'content' => $systemContext]
            ];

            // Append short history if any
            foreach (array_slice($history, -4) as $h) {
                if (isset($h['sender']) && isset($h['text'])) {
                    $role = $h['sender'] === 'user' ? 'user' : 'assistant';
                    $messages[] = ['role' => $role, 'content' => $h['text']];
                }
            }

            // Append current message
            $messages[] = ['role' => 'user', 'content' => $message];

            $response = Http::timeout(300)->post($ollamaUrl, [
                'model' => $model,
                'messages' => $messages,
                'stream' => false,
                'options' => [
                    'temperature' => 0.1,
                    'top_p' => 0.9,
                    'num_predict' => 400,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['message']['content'] ?? null;
            } else {
                Log::error('Ollama Chat Generate Failed', [
                    'status' => $response->status(),
                    'body'   => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Ollama Chat Error: ' . $e->getMessage());
        }

        return null;
    }
}
