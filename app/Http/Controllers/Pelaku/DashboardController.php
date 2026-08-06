<?php

namespace App\Http\Controllers\Pelaku;

use App\Http\Controllers\Controller;
use App\Models\Pemilik;
use App\Models\UMKM;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $pemilik = null;
        if ($user->nik_hash) {
            $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();
        }

        $umkms = collect();
        if ($pemilik) {
            $umkms = UMKM::where('id_pemilik', $pemilik->id_pemilik)->get();
        }

        return view('pelaku.dashboard', compact('user', 'pemilik', 'umkms'));
    }
}
