<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OllamaService;
use App\Models\UMKM;

class AdaptiveDssController extends Controller
{
    protected $ollamaService;

    public function __construct(OllamaService $ollamaService)
    {
        $this->ollamaService = $ollamaService;
    }

    public function index()
    {
        $user   = auth()->user();
        $layout = 'layouts.petugas';

        if ($user->hasRole('super_admin')) {
            $layout = 'layouts.superadmin';
        } elseif ($user->hasRole('admin_kecamatan')) {
            $layout = 'layouts.admin';
        }

        return view('dss.adaptive', compact('layout'));
    }

    public function getIndicators(Request $request)
    {
        $request->validate([
            'intervention' => 'required|string|max:2000'
        ]);

        $indicators = $this->ollamaService->generateAdaptiveIndicators($request->intervention);

        if (!$indicators) {
            return response()->json(['error' => 'Gagal mendapatkan indikator dari AI. Pastikan Ollama menyala.'], 500);
        }

        return response()->json($indicators);
    }

    public function scoreAndRank(Request $request)
    {
        $request->validate([
            'required_attributes' => 'required|array',
            'priority_category' => 'nullable|string'
        ]);

        $attributes = $request->required_attributes;
        
        $query = UMKM::with(['kategori', 'sektor', 'pemilik.kelurahanRel']);
        $umkms = $query->get();
        $ranked = [];

        foreach ($umkms as $umkm) {
            $score = 0;
            $details = [];

            foreach ($attributes as $attr) {
                switch ($attr) {
                    case 'instagram':
                    case 'marketplace':
                        $sosmed = is_string($umkm->media_sosial) ? json_decode($umkm->media_sosial, true) : $umkm->media_sosial;
                        if (!empty($sosmed)) {
                            $score += 20;
                            $details[$attr] = 'Ada';
                        } else {
                            $details[$attr] = 'Tidak ada';
                        }
                        break;
                    case 'foto_produk':
                        if ($umkm->foto_utama || $umkm->foto_gallery) {
                            $score += 15;
                            $details[$attr] = 'Ada';
                        } else {
                            $details[$attr] = 'Tidak ada';
                        }
                        break;
                    case 'omzet':
                        $omzet = (float) $umkm->perkiraan_omset;
                        if ($omzet > 0) {
                            $score += min(30, ($omzet / 10000000) * 5);
                            $details[$attr] = 'Rp ' . number_format($omzet, 0, ',', '.');
                        } else {
                            $details[$attr] = 'Data kosong';
                        }
                        break;
                    case 'kapasitas_produksi':
                        if ($umkm->kapasitas_produksi) {
                            $score += 15;
                            $details[$attr] = 'Ada';
                        } else {
                            $details[$attr] = 'Tidak ada';
                        }
                        break;
                    case 'nib':
                    case 'pirt':
                    case 'halal':
                    case 'bpom':
                        $docs = is_string($umkm->dokumen_legalitas) ? json_decode($umkm->dokumen_legalitas, true) : $umkm->dokumen_legalitas;
                        if (!empty($docs)) {
                            $score += 15;
                            $details[$attr] = 'Memiliki dokumen legalitas';
                        } else {
                            $details[$attr] = 'Belum/Tidak ada';
                        }
                        break;
                    default:
                        $score += 5;
                        $details[$attr] = 'Ada data';
                        break;
                }
            }

            // Bonus priority category
            if ($request->priority_category && $umkm->kategori) {
                if (stripos($umkm->kategori->nama_kategori, $request->priority_category) !== false) {
                    $score += 30; // Big bonus for matching priority category
                    $details['kategori'] = 'Sesuai Prioritas (' . $umkm->kategori->nama_kategori . ')';
                }
            }

            $ranked[] = [
                'id' => $umkm->id_umkm,
                'nama' => $umkm->nama_usaha,
                'pemilik' => $umkm->pemilik ? $umkm->pemilik->nama_lengkap : '-',
                'kelurahan' => $umkm->pemilik ? $umkm->pemilik->kelurahan : '-',
                'kategori' => $umkm->kategori ? $umkm->kategori->nama_kategori : '-',
                'score' => min(100, round($score)), // Max 100
                'details' => $details,
                'raw_data' => [
                    'kategori' => $umkm->kategori ? $umkm->kategori->nama_kategori : '-',
                    'omzet' => $umkm->perkiraan_omset,
                    'tahun_berdiri' => $umkm->tahun_berdiri,
                ],
                'reasoning' => null // To be filled by AI later for top results
            ];
        }

        // Sort by score desc
        usort($ranked, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Return Top 20
        return response()->json(array_slice($ranked, 0, 20));
    }

    public function explainRecommendation(Request $request)
    {
        $request->validate([
            'intervention' => 'required|string',
            'umkm' => 'required|array'
        ]);

        $explanation = $this->ollamaService->generateAdaptiveExplanation($request->intervention, $request->umkm);

        return response()->json([
            'explanation' => $explanation ?? 'AI sedang sibuk atau offline.'
        ]);
    }
}
