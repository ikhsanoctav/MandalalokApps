<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriUMKM;
use App\Models\Kelurahan;
use App\Models\Rt;
use App\Models\Rw;
use App\Models\SektorUmkm;

/**
 * API Wilayah & Master Data Controller
 * Data dropdown untuk form di MandalalokaApk
 */
class WilayahApiController extends Controller
{
    /**
     * GET /api/wilayah/kelurahan
     * List semua kelurahan
     */
    public function kelurahan()
    {
        $data = Kelurahan::select('id', 'kode_kelurahan', 'nama_kelurahan')
            ->orderBy('nama_kelurahan')
            ->get();

        return response()->json(['data' => $data]);
    }

    /**
     * GET /api/wilayah/rw/{kelurahan_id}
     * List RW berdasarkan kelurahan
     */
    public function rw($kelurahan_id)
    {
        $data = Rw::where('kelurahan_id', $kelurahan_id)
            ->select('id', 'nomor_rw')
            ->orderBy('nomor_rw')
            ->get()
            ->map(fn ($rw) => [
                'id' => $rw->id,
                'nomor_rw' => $rw->nomor_rw,
                'nama_rw' => $rw->nomor_rw,
            ]);

        return response()->json(['data' => $data]);
    }

    /**
     * GET /api/wilayah/rt/{rw_id}
     * List RT berdasarkan RW
     */
    public function rt($rw_id)
    {
        $data = Rt::where('rw_id', $rw_id)
            ->select('id', 'nomor_rt')
            ->orderBy('nomor_rt')
            ->get();

        $data = $data->map(fn ($rt) => [
            'id' => $rt->id,
            'nomor_rt' => $rt->nomor_rt,
            'nama_rt' => $rt->nomor_rt,
        ]);

        return response()->json(['data' => $data]);
    }

    /**
     * GET /api/kategori-umkm
     * List semua kategori UMKM untuk dropdown
     */
    public function kategori()
    {
        $data = KategoriUMKM::select('id', 'nama_kategori')
            ->orderBy('nama_kategori')
            ->get();

        return response()->json(['data' => $data]);
    }

    /**
     * GET /api/sektor-umkm
     * List semua sektor UMKM untuk dropdown
     */
    public function sektor()
    {
        $data = SektorUmkm::select('id', 'nama_sektor')
            ->orderBy('nama_sektor')
            ->get();

        return response()->json(['data' => $data]);
    }
}
