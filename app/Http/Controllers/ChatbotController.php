<?php

namespace App\Http\Controllers;

use App\Models\UMKM;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    private function buildSystemContext($relevantUmkms = null, $chatHistory = []): string
    {
        try {
            $totalUmkm = UMKM::where('status_verifikasi', 'terverifikasi')->count();

            $thisMonth = UMKM::where('status_verifikasi', 'terverifikasi')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            $beritaList = Berita::where('status', 'published')
                ->where('published_at', '<=', now())
                ->latest('published_at')
                ->take(5)
                ->get()
                ->map(function ($b) {
                    return "- [{$b->published_at->format('d-m-Y')}] {$b->judul}";
                })
                ->implode("\n");

            $sektorList = \App\Models\SektorUmkm::all()
                ->map(fn($s) => "- {$s->nama_sektor}" . ($s->deskripsi ? ": {$s->deskripsi}" : ''))
                ->implode("\n");

            $kategoriList = \App\Models\KategoriUMKM::all()
                ->map(fn($k) => "- {$k->nama_kategori}")
                ->implode("\n");

            $programBantuan = \App\Models\Setting::get('program_bantuan_list', '');
            $programBantuanFormatted = collect(explode("\n", str_replace("\r", "", $programBantuan)))
                ->map(fn($p) => trim($p))
                ->filter()
                ->map(fn($p) => "- {$p}")
                ->implode("\n");

            $limitBantuan = number_format((float)\App\Models\Setting::get('max_financing_limit', 50000000), 0, ',', '.');
            $statusPengajuan = \App\Models\Setting::get('pelaku_submission_active', false) ? 'Dibuka/Aktif' : 'Ditutup/Tidak Aktif';

            $context = <<<CONTEXT
Anda adalah "Mang Loka", asisten virtual resmi (AI) Sistem Mandalaloka untuk Kecamatan Mandalajati, Kota Bandung.
Tugas Anda: Menjawab pertanyaan warga dengan RAMAH, SINGKAT (maksimal 3 kalimat), dan 100% BERDASARKAN DATA di bawah ini.

PANDUAN INFORMASI WAJIB (JADIKAN REFERENSI UTAMA):
1. ALAMAT & KONTAK: Kantor Kecamatan Mandalajati beralamat di Jl. Pasir Impun No.33, Karang Pamulang, Kota Bandung, Jawa Barat 40194. Telepon: (022) 1234567. Email: humas@mandalajati.bandung.go.id.
2. PENDAFTARAN UMKM: Warga dapat mendaftar via menu Register, isi data lengkap dan KTP. Akun langsung aktif tanpa menunggu verifikasi.
3. PERIZINAN (NIB/Halal/PIRT): Diurus secara gratis melalui platform OSS dan BPJPH. Kecamatan menyediakan pendampingan gratis.
4. BIAYA: Seluruh pendaftaran di MandalalokaApps 100% GRATIS.
5. STATISTIK SISTEM SAAT INI: Terdapat {$totalUmkm} UMKM terdaftar secara keseluruhan, dan {$thisMonth} pendaftar baru bulan ini.
CONTEXT;

            if ($relevantUmkms && $relevantUmkms->count() > 0) {
                $umkmList = $relevantUmkms->map(function ($item) {
                    return "- " . ($item->nama_usaha ?? '-') . " (Pemilik: " . ($item->pemilik?->nama_lengkap ?? '-') . " | Kategori: " . ($item->kategori?->nama_kategori ?? '-') . " | Kelurahan: " . ($item->pemilik?->kelurahan ?? '-') . " | Kontak: " . ($item->telp_usaha ?? '-') . ")";
                })->implode("\n");

                $context .= "\n\nDATA UMKM DARI DATABASE KAMI:\n" . $umkmList;
            } else {
                $context .= "\n\nDATA UMKM DARI DATABASE KAMI:\n[Data kosong atau pencarian tidak spesifik]";
            }

            $context .= <<<CONTEXT

ATURAN SUPER KETAT UNTUK ANDA (DILARANG DILANGGAR):
1. JANGAN PERNAH mengarang, menebak, atau membuat-buat data UMKM, Alamat, atau Nama Orang yang tidak ada dalam "PANDUAN INFORMASI WAJIB" atau "DATA UMKM DARI DATABASE KAMI" di atas.
2. JIKA pengguna bertanya tentang suatu hal yang TIDAK ADA datanya di teks atas, Anda WAJIB menjawab: "Mohon maaf, saya belum memiliki informasi mengenai hal tersebut." (Jangan berhalusinasi/menebak).
3. Anda melayani masyarakat lokal (Sunda/Bandung), jadi gunakan sapaan ramah seperti "Halo", "Akang/Teteh", atau "Bapak/Ibu".
4. Jawablah langsung ke intinya (maksimal 3 kalimat). Jangan bertele-tele.
5. ANDA BERADA DI KOTA BANDUNG, JAWA BARAT. JANGAN PERNAH MENYEBUT JAKARTA ATAU KOTA LAIN.
CONTEXT;

            return $context;

        } catch (\Exception $e) {
            Log::warning('ChatbotController: Could not build system context. ' . $e->getMessage());
            return "Kamu adalah Mang Loka, asisten AI resmi sistem Mandalaloka Kecamatan Mandalajati. Jawab pertanyaan seputar UMKM dengan ramah dan jujur.";
        }
    }

    private function getIndonesianDate(): string
    {
        $days   = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $now = now();
        return $days[$now->dayOfWeek] . ', ' . $now->day . ' ' . $months[$now->month] . ' ' . $now->year;
    }

    public function sendMessage(Request $request, \App\Services\OllamaService $ollamaService)
    {
        $validator = Validator::make($request->all(), [
            'message'   => 'required|string|max:2000',
            'name'      => 'nullable|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'phone'     => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $message   = $request->input('message');

        try {
            $keywords = array_filter(explode(' ', preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($message))), function($w) {
                return strlen($w) >= 3;
            });

            $relevantUmkms = collect();
            if (count($keywords) > 0) {
                try {
                    $searchQuery = UMKM::where('status_verifikasi', 'terverifikasi')->with(['kategori', 'pemilik']);
                    $searchQuery->where(function($q) use ($keywords) {
                        foreach($keywords as $word) {
                            $q->orWhere('nama_usaha', 'like', '%' . $word . '%')
                              ->orWhere('deskripsi', 'like', '%' . $word . '%')
                              ->orWhereHas('pemilik', function($q2) use ($word) {
                                  $q2->where('kelurahan', 'like', '%' . $word . '%');
                              })
                              ->orWhereHas('kategori', function($q3) use ($word) {
                                  $q3->where('nama_kategori', 'like', '%' . $word . '%');
                              });
                        }
                    });
                    $relevantUmkms = $searchQuery->take(10)->get();
                } catch (\Throwable $e) {
                    Log::warning('ChatbotController: UMKM lookup skipped. ' . $e->getMessage());
                }
            }

            $chatHistory = $request->input('history', []);
            $systemContext = $this->buildSystemContext($relevantUmkms, $chatHistory);

            // Directly call Ollama instead of n8n webhook
            $reply = $ollamaService->generateChatResponse($systemContext, $message, $chatHistory);

            if (is_null($reply) || empty(trim($reply))) {
                Log::warning('ChatbotController: Empty reply from OllamaService.');
                return response()->json(['success' => false]);
            }

            // Parse basic markdown: bold and italic
            $reply = htmlspecialchars($reply);
            $reply = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $reply);
            $reply = preg_replace('/(?<!\*)\*(?!\*)(.*?)(?<!\*)\*(?!\*)/', '<em>$1</em>', $reply);
            $reply = nl2br($reply);
            
            // Just return success:true and the string, the frontend will append it as innerHTML
            return response()->json([
                'success' => true,
                'reply'   => $reply
            ]);

        } catch (\Exception $e) {
            Log::error('ChatbotController: Exception occurred: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return response()->json(['success' => false]);
        }
    }

    public function searchUmkm(Request $request)
    {
        $expectedKey = config('services.n8n.api_key');
        if ($expectedKey) {
            $providedKey = $request->header('X-N8N-API-KEY') ?? $request->query('api_key');
            if ($providedKey !== $expectedKey) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 401);
            }
        }

        $query = $request->input('q');
        $kelurahan = $request->input('kelurahan');
        $kategori = $request->input('kategori');
        $limit = $request->input('limit', 10);

        $umkmQuery = UMKM::where('status_verifikasi', 'terverifikasi')->with(['kategori', 'sektor', 'pemilik']);

        if ($query) {
            $umkmQuery->where(function($q) use ($query) {
                $q->where('nama_usaha', 'like', '%' . $query . '%')
                  ->orWhere('deskripsi', 'like', '%' . $query . '%')
                  ->orWhereHas('pemilik', function($q2) use ($query) {
                      $q2->where('nama_lengkap', 'like', '%' . $query . '%')
                         ->orWhere('kelurahan', 'like', '%' . $query . '%');
                  });
            });
        }

        if ($kelurahan) {
            $umkmQuery->whereHas('pemilik', function($q) use ($kelurahan) {
                $q->where('kelurahan', 'like', '%' . $kelurahan . '%');
            });
        }

        if ($kategori) {
            $umkmQuery->whereHas('kategori', function($q) use ($kategori) {
                $q->where('nama_kategori', 'like', '%' . $kategori . '%');
            });
        }

        $results = $umkmQuery->take($limit)->get()->map(function($item) {
            $mediaSosial = [];
            if (is_array($item->media_sosial)) {
                foreach ($item->media_sosial as $platform => $url) {
                    if (!empty($url)) {
                        $mediaSosial[] = "{$platform}: {$url}";
                    }
                }
            }

            return [
                'nama_usaha' => $item->nama_usaha ?? '-',
                'pemilik' => $item->pemilik?->nama_lengkap ?? 'Tidak diketahui',
                'kategori' => $item->kategori?->nama_kategori ?? 'Lainnya',
                'sektor' => $item->sektor?->nama_sektor ?? 'Lainnya',
                'kelurahan' => $item->pemilik?->kelurahan ?? '-',
                'alamat_lengkap' => $item->alamat_usaha ?? 'Tidak tersedia',
                'telepon' => $item->telp_usaha ?? '-',
                'deskripsi' => $item->deskripsi ? Str::limit($item->deskripsi, 100) : '-',
                'media_sosial' => !empty($mediaSosial) ? implode(', ', $mediaSosial) : '-'
            ];
        });

        return response()->json([
            'success' => true,
            'count' => $results->count(),
            'data' => $results
        ]);
    }
}
