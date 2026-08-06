# Operasional n8n untuk Mandalaloka

Workflow AI harus dijalankan melalui n8n. Laravel mengakses n8n hanya pada jaringan Docker melalui `http://n8n:5678`; jangan mengubah URL ini menjadi alamat publik.

## Konfigurasi aplikasi

Salin nilai berikut ke `.env` produksi dan gunakan nilai rahasia yang berbeda untuk setiap lingkungan.

```dotenv
N8N_WEBHOOK_URL=http://n8n:5678/webhook/chat-api
N8N_DSS_WEBHOOK_URL=http://n8n:5678/webhook/dss-api
N8N_DSS_CALLBACK_URL=http://laravel.test/api/dss/callback
N8N_DSS_ASYNC=true
N8N_API_KEY=isi-dengan-rahasia-panjang-minimal-32-karakteriniini
N8N_CHAT_TIMEOUT=45
N8N_DSS_DISPATCH_TIMEOUT=20
OLLAMA_BASE_URL=http://host.docker.internal:11434
OLLAMA_MODEL=qwen2.5:3b
OLLAMA_DSS_MODEL=qwen2.5:1.5b
```

`N8N_DSS_CALLBACK_URL` harus dapat dijangkau dari container n8n. Untuk Docker Compose bawaan proyek, `laravel.test` adalah nama service yang benar.

## Impor workflow

1. Jalankan n8n dan Ollama, lalu buka editor n8n pada host yang berwenang.
2. Impor `docs/n8n_workflow_unified.json`.
3. Pada kedua node Webhook, set autentikasi **Header Auth** dengan header `X-N8N-API-KEY` dan nilai yang sama dengan `N8N_API_KEY`. Kredensial n8n tidak diekspor bersama workflow, sehingga langkah ini wajib dilakukan di editor n8n.
4. Ekspor workflow yang baru diimpor, lalu ubah cabang DSS menjadi asinkron dengan perintah berikut:

```bash
node scripts/update_n8n_dss_async_workflow.js /path/ke/export-n8n.json /tmp/mandalaloka-n8n-async.json
```

5. Impor `/tmp/mandalaloka-n8n-async.json`, aktifkan workflow, dan pastikan hanya satu workflow dengan webhook `chat-api` dan `dss-api` yang aktif.

Cabang DSS asinkron wajib dipakai. Laravel hanya menunggu pengakuan `202`; n8n kemudian mengirim hasil ke callback dengan token satu-kali-pakai yang dibuat Laravel. Workflow sinkron lama akan membuat permintaan DSS berakhir timeout.

## Pemeriksaan sebelum digunakan pejabat

- Kirim satu pesan ke chatbot dan pastikan respons berasal dari webhook n8n.
- Jalankan DSS dengan data uji. Respons awal harus `202`, lalu hasil muncul setelah callback berhasil.
- Pastikan eksekusi n8n tidak menyimpan atau menampilkan NIK, nomor telepon, atau dokumen identitas pada output AI.
- Batasi akses editor n8n ke admin teknis dan jangan membuka port 5678 ke internet publik.
- Setelah mengganti konfigurasi Laravel, jalankan `php artisan config:clear` dan restart service aplikasi.

Hasil AI adalah bahan analisis, bukan keputusan otomatis. Pejabat tetap perlu memeriksa angka sumber dan menyetujui rekomendasi sebelum ditetapkan.
