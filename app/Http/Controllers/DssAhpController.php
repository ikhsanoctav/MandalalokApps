<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DssAhpController extends Controller
{
    public function kriteriaIndex()
    {
        $kriterias = \App\Models\Kriteria::orderBy('id')->get();
        return view('dss.kriteria', compact('kriterias'));
    }

    public function storeKriteria(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:kriterias,kode',
            'nama_kriteria' => 'required',
            'tipe' => 'required|in:benefit,cost'
        ]);

        \App\Models\Kriteria::create($request->all());
        return back()->with('success', 'Kriteria berhasil ditambahkan');
    }

    public function updateKriteria(Request $request, $id)
    {
        $kriteria = \App\Models\Kriteria::findOrFail($id);
        $request->validate([
            'kode' => 'required|unique:kriterias,kode,'.$id,
            'nama_kriteria' => 'required',
            'tipe' => 'required|in:benefit,cost'
        ]);

        $kriteria->update($request->all());
        return back()->with('success', 'Kriteria berhasil diupdate');
    }

    public function deleteKriteria($id)
    {
        \App\Models\Kriteria::findOrFail($id)->delete();
        return back()->with('success', 'Kriteria berhasil dihapus');
    }

    public function ahpPembobotan()
    {
        $kriterias = \App\Models\Kriteria::where('aktif', true)->orderBy('id')->get();
        // Cek bobot saat ini
        $bobots = \App\Models\DssBobot::where('profil', 'default')->get()->keyBy('kriteria_id');
        return view('dss.ahp', compact('kriterias', 'bobots'));
    }

    public function calculateAhp(Request $request)
    {
        $matrix = $request->input('matrix'); // e.g. matrix[kriteria_id_1][kriteria_id_2]
        $kriterias = \App\Models\Kriteria::where('aktif', true)->orderBy('id')->get();
        $n = $kriterias->count();

        if($n == 0) return back()->with('error', 'Tidak ada kriteria aktif');

        $kriteriaIds = $kriterias->pluck('id')->toArray();
        $mat = [];
        
        // Build and fill the matrix
        foreach($kriteriaIds as $i) {
            foreach($kriteriaIds as $j) {
                if($i == $j) {
                    $mat[$i][$j] = 1;
                } elseif($i < $j) {
                    // Hanya baca dari input form untuk $i < $j
                    $val = isset($matrix[$i][$j]) ? (float)$matrix[$i][$j] : 1;
                    $mat[$i][$j] = $val;
                    $mat[$j][$i] = 1 / $val;
                }
            }
        }

        // 1. Sum each column
        $colSum = [];
        foreach($kriteriaIds as $j) {
            $sum = 0;
            foreach($kriteriaIds as $i) {
                $sum += $mat[$i][$j];
            }
            $colSum[$j] = $sum;
        }

        // 2. Normalize matrix & calculate weights (eigen vector)
        $weights = [];
        foreach($kriteriaIds as $i) {
            $rowSum = 0;
            foreach($kriteriaIds as $j) {
                $norm = $mat[$i][$j] / $colSum[$j];
                $rowSum += $norm;
            }
            $weights[$i] = $rowSum / $n;
        }

        // 3. Calculate Consistency Ratio (CR)
        $lambdaMax = 0;
        foreach($kriteriaIds as $i) {
            $weightedSum = 0;
            foreach($kriteriaIds as $j) {
                $weightedSum += $mat[$i][$j] * $weights[$j];
            }
            $lambdaMax += $weightedSum / $weights[$i];
        }
        $lambdaMax = $lambdaMax / $n;

        $ci = ($lambdaMax - $n) / max(($n - 1), 1);
        
        // Random Index values for n=1 to 15
        $riTable = [0, 0, 0, 0.58, 0.9, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49, 1.51, 1.48, 1.56, 1.57, 1.59];
        $ri = $n < count($riTable) ? $riTable[$n] : 1.59;
        
        $cr = $ri == 0 ? 0 : $ci / $ri;

        // Save weights
        \App\Models\DssBobot::where('profil', 'default')->delete();
        foreach($weights as $k_id => $w) {
            \App\Models\DssBobot::create([
                'kriteria_id' => $k_id,
                'bobot' => $w,
                'cr_value' => $cr,
                'profil' => 'default'
            ]);
        }

        $msg = $cr <= 0.1 ? "Bobot berhasil dihitung dan konsisten (CR = " . round($cr, 3) . ")" : "Bobot berhasil dihitung namun TIDAK konsisten (CR = " . round($cr, 3) . " > 0.1). Disarankan revisi penilaian.";
        return back()->with($cr <= 0.1 ? 'success' : 'warning', $msg);
    }

    public function sawRanking(Request $request)
    {
        $profil = 'default';
        $bobots = \App\Models\DssBobot::where('profil', $profil)->get()->keyBy('kriteria_id');
        $kriterias = \App\Models\Kriteria::where('aktif', true)->get()->keyBy('id');
        
        if($bobots->count() == 0) {
            return back()->with('error', 'Silakan lakukan pembobotan AHP terlebih dahulu.');
        }

        $penilaians = \App\Models\DssPenilaian::with('umkm')->get()->groupBy('umkm_id');
        if($penilaians->count() == 0) {
            return back()->with('error', 'Data penilaian mentah kosong.');
        }

        // Cari max/min per kriteria
        $minMax = [];
        foreach($kriterias as $kId => $k) {
            $vals = \App\Models\DssPenilaian::where('kriteria_id', $kId)->pluck('nilai')->toArray();
            if(count($vals) > 0) {
                $minMax[$kId] = [
                    'max' => max($vals),
                    'min' => min($vals)
                ];
            }
        }

        \App\Models\DssHasilSaw::where('profil', $profil)->delete();

        $hasilAkhir = [];

        foreach($penilaians as $umkmId => $scores) {
            $totalSkor = 0;
            $breakdowns = [];

            foreach($scores as $score) {
                $kId = $score->kriteria_id;
                if(!isset($kriterias[$kId]) || !isset($bobots[$kId])) continue;

                $k = $kriterias[$kId];
                $b = $bobots[$kId]->bobot;
                
                $max = $minMax[$kId]['max'] ?? 1;
                $min = $minMax[$kId]['min'] ?? 1;
                
                $val = (float)$score->nilai;

                // Normalisasi
                if($k->tipe == 'benefit') {
                    $norm = $max != 0 ? $val / $max : 0;
                } else {
                    $norm = $val != 0 ? $min / $val : 0;
                }

                $kontribusi = $norm * $b;
                $totalSkor += $kontribusi;

                $breakdowns[] = [
                    'umkm_id' => $umkmId,
                    'kriteria_id' => $kId,
                    'nilai_normalisasi' => $norm,
                    'bobot' => $b,
                    'kontribusi_skor' => $kontribusi,
                    'profil' => $profil,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $hasilAkhir[] = [
                'umkm_id' => $umkmId,
                'total_skor' => $totalSkor,
                'breakdowns' => $breakdowns
            ];
        }

        // Sort by total_skor descending
        usort($hasilAkhir, function($a, $b) {
            return $b['total_skor'] <=> $a['total_skor'];
        });

        // Insert to database with ranking
        foreach($hasilAkhir as $index => $hasil) {
            $rank = $index + 1;
            
            // Insert Total Row (kriteria_id = null)
            \App\Models\DssHasilSaw::create([
                'umkm_id' => $hasil['umkm_id'],
                'kriteria_id' => null,
                'total_skor' => $hasil['total_skor'],
                'ranking' => $rank,
                'profil' => $profil
            ]);

            // Insert Breakdowns
            foreach($hasil['breakdowns'] as $b) {
                $b['ranking'] = $rank;
                $b['total_skor'] = $hasil['total_skor'];
                \App\Models\DssHasilSaw::insert($b);
            }
        }

        return redirect()->route('dss.saw.hasil')->with('success', 'Perankingan SAW berhasil dihitung.');
    }

    public function hasilSaw()
    {
        $profil = 'default';
        $ranks = \App\Models\DssHasilSaw::with('umkm', 'umkm.pemilik')
            ->whereNull('kriteria_id')
            ->where('profil', $profil)
            ->orderBy('ranking')
            ->take(50) // top 50
            ->get();
            
        $breakdownsRaw = \App\Models\DssHasilSaw::with('kriteria')
            ->whereNotNull('kriteria_id')
            ->where('profil', $profil)
            ->get()
            ->groupBy('umkm_id');

        return view('dss.hasil', compact('ranks', 'breakdownsRaw'));
    }
}
