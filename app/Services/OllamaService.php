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
Anda adalah staf ahli ekonomi dan analis kebijakan publik (PNS) di lingkungan Kecamatan / Dinas Pemerintahan. Tugas utama Anda adalah merumuskan STRATEGI PEMBINAAN UMKM dan PROGRAM KERJA PEMERINTAH.
Sistem DSS ini ditujukan untuk memetakan kondisi ekonomi warga, BUKAN untuk mengembangkan aplikasi atau software.

ATURAN MUTLAK (DILARANG DILANGGAR):
1. DILARANG KERAS memberikan rekomendasi teknis IT, seperti: "kembangkan sistem", "buat aplikasi", "perbaiki UI/UX", "optimalkan database", "integrasi platform", atau hal teknis software lainnya.
2. Semua program yang diusulkan harus spesifik pada ranah pemerintah/pembinaan (misal: "Pelatihan Kemasan Produk", "Bantuan Modal Mesin Jahit", "Pameran/Bazar UMKM Tingkat Kecamatan", "Pelatihan Higienitas Kuliner").
3. Fokus analisis ini HANYA untuk tingkat KECAMATAN secara LOKAL. JANGAN PERNAH menyarankan kebijakan tingkat Provinsi, Nasional, atau menyebut kata "Indonesia". Anda hanya melayani satu wilayah kelurahan/kecamatan berdasarkan data ini.
4. Analisis HANYA berdasarkan data statistik yang diberikan. Gunakan angka literal hasil perhitungan akhir Anda.
5. Kembalikan HANYA JSON valid. Jangan menggunakan teks pembuka, markdown di luar nilai JSON, atau catatan tambahan.

DATA STATISTIK UMKM:
{$statsJson}

PROMPT;

        if ($intervention) {
            // === MODE INTERVENSI: Prompt bebas, fokus 100% menjawab permintaan user ===
            $prompt .= <<<EOF

PERINTAH INTERVENSI KEBIJAKAN DARI USER: "{$intervention}"

INSTRUKSI UTAMA:
Anda WAJIB menjawab perintah intervensi di atas secara LENGKAP, MENDETAIL, dan SPESIFIK.
Jawaban Anda harus BENAR-BENAR menjawab apa yang diminta user, bukan sekadar mengulang analisis umum.
Gunakan data statistik di atas sebagai landasan faktual.
Ekstrak secara cerdas sasaran, hasil yang diharapkan, atau pendekatan JIKA pengguna sudah menyebutkannya secara eksplisit atau implisit di teks intervensinya. Gunakan untuk mengisi field `sasaran_program`, `hasil_diharapkan`, dan `pendekatan`.

Contoh bentuk jawaban yang diharapkan (sesuaikan dengan permintaan user):
- Jika user minta program kerja → buatkan tabel program kerja lengkap (nama program, sasaran, anggaran estimasi, timeline, indikator keberhasilan)
- Jika user minta stimulus kegiatan → buatkan daftar kegiatan stimulus yang konkret
- Jika user minta analisis kelurahan tertentu → analisis mendalam kelurahan tersebut
- Jika user minta grafik/perbandingan → buatkan tabel perbandingan yang informatif

ATURAN SANGAT PENTING: JANGAN PERNAH MENGEMBALIKAN ARRAY KOSONG `[]`. Anda WAJIB mengisi semua field array (seperti `keputusan_strategis_utama`, `rekomendasi_kebijakan`, dll) dengan pemikiran strategis yang sesungguhnya berdasarkan data. Jika Anda mengembalikan array kosong, itu dianggap GAGAL.

Format JSON yang WAJIB dan HARUS dikembalikan secara absolut (TIDAK BOLEH DIBUNGKUS DALAM OBJEK LAIN, HARUS STRUKTUR ROOT):
{
  "ringkasan_eksekutif": "2-3 kalimat yang merangkum jawaban Anda terhadap permintaan intervensi user",
  "intervensi_kebijakan": {
    "fokus": "Tuliskan fokus dari intervensi ini",
    "tujuan_utama": "Tujuan utama berdasarkan intervensi",
    "sasaran_program": "Sasaran program",
    "pendekatan": "Pendekatan strategis yang disarankan",
    "hasil_diharapkan": "Target akhir atau hasil yang diharapkan"
  },
  "hasil_intervensi_khusus": "[Isi dengan jawaban lengkap dalam format Markdown. Buat tabel Markdown atau grafik mermaid.js (```mermaid) jika diminta pengguna. Sesuaikan dengan detail permintaan intervensi.]",
  "keputusan_strategis_utama": [
    "Keputusan 1 yang terkait langsung dengan intervensi user",
    "Keputusan 2 yang mendukung intervensi user",
    "Keputusan 3 tindak lanjut dari intervensi user"
  ],
  "indikator_kunci": {
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
    {"area": "Kelurahan/sektor prioritas 2", "alasan": "Alasan berbasis data", "skor_urgensi": 8, "aksi_konkret": "Keputusan tindakan lapangan kedua"}
  ],
  "catatan_metodologi": "Analisis ini difokuskan pada intervensi kebijakan yang diminta oleh pemegang keputusan."
}
EOF;
        } else {
            // === MODE STANDAR: Template lengkap untuk analisis umum ===
            $prompt .= <<<EOF

Kembalikan JSON dengan struktur ini:

ATURAN SANGAT PENTING: JANGAN PERNAH MENGEMBALIKAN ARRAY ATAU STRING KOSONG. Anda WAJIB mengisi semua field dengan analisis dan pemikiran strategis yang sesungguhnya berdasarkan data UMKM yang diberikan.

{
  "ringkasan_eksekutif": "2-3 kalimat ringkasan untuk pimpinan",
  "intervensi_kebijakan": {
    "fokus": "Pengembangan Ekosistem UMKM Menyeluruh",
    "tujuan_utama": "Tujuan utama berdasarkan kondisi terkini",
    "sasaran_program": "Sasaran program secara umum",
    "pendekatan": "Pendekatan strategis yang disarankan",
    "hasil_diharapkan": "Target akhir atau hasil yang diharapkan"
  },
  "hasil_intervensi_khusus": "",
  "keputusan_strategis_utama": [
    "Keputusan pimpinan 1: prioritas program/anggaran/wilayah yang harus ditetapkan",
    "Keputusan pimpinan 2: sektor/kategori yang perlu didorong atau dikendalikan",
    "Keputusan pimpinan 3: tindakan lintas petugas/kelurahan yang harus dieksekusi"
  ],
  "indikator_kunci": {
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
EOF;
        }

        try {
            $ollamaUrl = rtrim(config('services.ollama.base_url', 'http://host.docker.internal:11434'), '/') . '/api/generate';
            $model = config('services.ollama.model', 'qwen2.5:3b');

            $response = Http::timeout(300)->post($ollamaUrl, [
                'model' => $model,
                'prompt' => $prompt,
                'stream' => false,
                'format' => 'json',
                'options' => [
                    'temperature' => $intervention ? 0.5 : 0.25,
                    'top_p' => 0.9,
                    'num_predict' => 6000,
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

                // Sanitize: remove actual control characters (not escaped ones)
                // Replace real newlines/tabs with escaped versions inside the JSON string
                $ret = str_replace(["\r\n", "\r"], "\n", $ret);
                
                // Smart newline fix: only escape newlines that are inside JSON string values
                $fixed = '';
                $inString = false;
                $escaped = false;
                for ($i = 0; $i < strlen($ret); $i++) {
                    $char = $ret[$i];
                    if ($escaped) {
                        $fixed .= $char;
                        $escaped = false;
                        continue;
                    }
                    if ($char === '\\') {
                        $fixed .= $char;
                        $escaped = true;
                        continue;
                    }
                    if ($char === '"') {
                        $inString = !$inString;
                        $fixed .= $char;
                        continue;
                    }
                    if ($inString && $char === "\n") {
                        $fixed .= '\\n';
                        continue;
                    }
                    if ($inString && $char === "\t") {
                        $fixed .= '\\t';
                        continue;
                    }
                    $fixed .= $char;
                }
                $ret = $fixed;

                // Try to parse
                $testDecode = json_decode($ret, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::warning('OllamaService: JSON rusak, mencoba perbaikan otomatis', [
                        'error' => json_last_error_msg(),
                        'raw_snippet' => substr($ret, 0, 500),
                    ]);
                    
                    // Count unclosed braces/brackets and close them
                    $openBraces = substr_count($ret, '{') - substr_count($ret, '}');
                    $openBrackets = substr_count($ret, '[') - substr_count($ret, ']');
                    
                    // Remove trailing incomplete key-value and comma
                    $ret = preg_replace('/,\s*"[^"]*"\s*:\s*"?[^"}\]]*$/', '', $ret);
                    $ret = preg_replace('/,\s*$/', '', $ret);
                    
                    // Close any open strings
                    $quoteCount = substr_count($ret, '"') - substr_count($ret, '\\"');
                    if ($quoteCount % 2 !== 0) {
                        $ret .= '"';
                    }
                    
                    // Recount after trimming
                    $openBraces = substr_count($ret, '{') - substr_count($ret, '}');
                    $openBrackets = substr_count($ret, '[') - substr_count($ret, ']');
                    
                    for ($i = 0; $i < $openBrackets; $i++) {
                        $ret .= ']';
                    }
                    for ($i = 0; $i < $openBraces; $i++) {
                        $ret .= '}';
                    }
                    
                    // Final check
                    $testDecode = json_decode($ret, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        Log::error('OllamaService: JSON tetap rusak setelah perbaikan', [
                            'error' => json_last_error_msg(),
                            'raw_first_500' => substr($ret, 0, 500),
                            'raw_last_500' => substr($ret, -500),
                        ]);
                    }
                }

                \Log::info('OllamaService final return string (length: ' . strlen($ret) . ')');
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

    /**
     * Generate Dynamic Indicators based on intervention text
     */
    public function generateAdaptiveIndicators(string $intervention): ?array
    {
        try {
            $ollamaUrl = rtrim(config('services.ollama.base_url', 'http://host.docker.internal:11434'), '/') . '/api/generate';
            $model = config('services.ollama.model', 'qwen2.5:3b');

            $schema = '{
  "intervention": "",
  "focus": "",
  "priority_category": "",
  "required_attributes": []
}';
            
            $prompt = <<<EOF
Kamu adalah AI perancang indikator kebijakan UMKM.
Tugas: Pahami intervensi berikut dan tentukan atribut UMKM yang relevan untuk proses seleksi.
Pilih dari atribut berikut (masukkan dalam bentuk array string ke required_attributes):
instagram, marketplace, foto_produk, kemasan, logo, omzet, modal, laba, kapasitas_produksi, permintaan, usia_usaha, jumlah_pegawai, nib, pirt, halal, bpom.

Intervensi: "{$intervention}"

Kembalikan murni JSON sesuai skema berikut tanpa tambahan apapun:
{$schema}
EOF;

            $response = Http::timeout(60)->post($ollamaUrl, [
                'model' => $model,
                'prompt' => $prompt,
                'stream' => false,
                'format' => 'json',
                'options' => [
                    'temperature' => 0.1,
                    'num_predict' => 500,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $json = $data['response'] ?? '';
                return json_decode($json, true);
            }
        } catch (\Exception $e) {
            Log::error('Ollama Adaptive Indicator Error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Generate explanation for recommended UMKM
     */
    public function generateAdaptiveExplanation(string $intervention, array $umkmData): ?string
    {
        try {
            $ollamaUrl = rtrim(config('services.ollama.base_url', 'http://host.docker.internal:11434'), '/') . '/api/generate';
            $model = config('services.ollama.model', 'qwen2.5:3b');

            $umkmJson = json_encode($umkmData, JSON_PRETTY_PRINT);
            
            $prompt = <<<EOF
Kamu adalah AI analis kebijakan UMKM.
Intervensi Pemerintah: "{$intervention}"

Berikut adalah data UMKM yang direkomendasikan sistem skoring untuk intervensi tersebut:
{$umkmJson}

Tugas: Jelaskan mengapa UMKM ini sangat layak direkomendasikan berdasarkan profil usahanya (sebutkan datanya) dan kaitkan dengan tujuan intervensi di atas. 
Gunakan bahasa Indonesia yang profesional, transparan, dan ringkas (maksimal 3-4 kalimat). Tidak perlu memakai awalan/sapaan.
EOF;

            $response = Http::timeout(90)->post($ollamaUrl, [
                'model' => $model,
                'prompt' => $prompt,
                'stream' => false,
                'options' => [
                    'temperature' => 0.2,
                    'num_predict' => 500,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return trim($data['response'] ?? '');
            }
        } catch (\Exception $e) {
            Log::error('Ollama Adaptive Explanation Error: ' . $e->getMessage());
        }

        return null;
    }
}
