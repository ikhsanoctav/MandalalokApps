<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemilik;
use Illuminate\Http\Request;

class PemilikApiController extends Controller
{
    public function checkNik(Request $request)
    {
        $nik = $request->query('nik');
        if (!$nik || strlen($nik) !== 16) {
            return response()->json(['found' => false]);
        }

        $nikHash = hash('sha256', $nik);
        $pemilik = Pemilik::where('nik_hash', $nikHash)->first();

        if ($pemilik) {
            app(\App\Services\UmkmService::class)->syncPemilikWilayah($pemilik);
            
            return response()->json([
                'found' => true,
                'data' => [
                    'nama_lengkap' => $pemilik->nama_lengkap,
                    'tempat_lahir' => $pemilik->tempat_lahir,
                    'tanggal_lahir' => $pemilik->tanggal_lahir ? $pemilik->tanggal_lahir->format('Y-m-d') : null,
                    'jenis_kelamin' => $pemilik->jenis_kelamin,
                    'no_hp' => $pemilik->no_hp,
                    'email' => $pemilik->email,
                    'alamat' => $pemilik->alamat,
                    'rt' => $pemilik->rt,
                    'rw' => $pemilik->rw,
                    'kelurahan' => $pemilik->kelurahan,
                ]
            ]);
        }

        return response()->json(['found' => false]);
    }
}
