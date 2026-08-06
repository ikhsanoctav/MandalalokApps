<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DssKriteria;
use App\Services\DssService;

class DssController extends Controller
{
    protected $dssService;

    public function __construct(DssService $dssService)
    {
        $this->dssService = $dssService;
    }

    public function index()
    {
        $rankingBantuan = $this->dssService->calculateSAW('bantuan');
        $rankingPelatihan = $this->dssService->calculateSAW('pelatihan');

        // Batasi hanya menampilkan top 20
        $rankingBantuan = $rankingBantuan->take(20);
        $rankingPelatihan = $rankingPelatihan->take(20);

        return view('superadmin.dss.index', compact('rankingBantuan', 'rankingPelatihan'));
    }

    public function kriteria()
    {
        $kriteriaBantuan = DssKriteria::where('tipe_dss', 'bantuan')->get();
        $kriteriaPelatihan = DssKriteria::where('tipe_dss', 'pelatihan')->get();

        return view('superadmin.dss.kriteria', compact('kriteriaBantuan', 'kriteriaPelatihan'));
    }

    public function updateKriteria(Request $request)
    {
        $request->validate([
            'kriteria' => 'required|array',
            'kriteria.*.bobot' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($request->kriteria as $id => $data) {
            $kriteria = DssKriteria::find($id);
            if ($kriteria) {
                $kriteria->bobot = $data['bobot'];
                // Jika ingin menambah opsi edit jenis benefit/cost, bisa ditambahkan di sini
                // $kriteria->jenis = $data['jenis'];
                $kriteria->save();
            }
        }

        return redirect()->back()->with('success', 'Bobot Kriteria berhasil diperbarui! Ranking DSS telah disesuaikan secara otomatis.');
    }
}
