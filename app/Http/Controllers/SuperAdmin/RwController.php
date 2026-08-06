<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Kelurahan;
use App\Models\Rt;
use App\Models\Rw;
use App\Services\AktivitasLogger;
use Illuminate\Http\Request;

class RwController extends Controller
{
    public function index()
    {
        $rws = Rw::with('kelurahan')->withCount('rts')->latest()->get();

        return view('superadmin.master.rw.index', compact('rws'));
    }

    public function create()
    {
        $kelurahans = Kelurahan::all();

        return view('superadmin.master.rw.create', compact('kelurahans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'nomor_rw' => 'required|string|max:10',
            'jumlah_rt' => 'nullable|integer|min:1|max:100',
        ]);

        $rw = Rw::create($request->only(['kelurahan_id', 'nomor_rw']));

        if ($request->filled('jumlah_rt')) {
            $jml = (int) $request->jumlah_rt;
            for ($i = 1; $i <= $jml; $i++) {
                Rt::create([
                    'rw_id' => $rw->id,
                    'nomor_rt' => str_pad($i, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        AktivitasLogger::log('Menambahkan RW: '.$rw->nomor_rw, 'tambah', null);

        return redirect()->route('superadmin.rw.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data RW berhasil ditambahkan.',
        ]);
    }

    public function edit($id)
    {
        $rw = Rw::findOrFail($id);
        $kelurahans = Kelurahan::all();

        return view('superadmin.master.rw.edit', compact('rw', 'kelurahans'));
    }

    public function update(Request $request, $id)
    {
        $rw = Rw::findOrFail($id);

        $request->validate([
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'nomor_rw' => 'required|string|max:10',
            'jumlah_rt' => 'nullable|integer|min:1|max:100',
        ]);

        $rw->update($request->only(['kelurahan_id', 'nomor_rw']));

        if ($request->filled('jumlah_rt')) {
            $jml = (int) $request->jumlah_rt;
            $existing = $rw->rts()->count();
            if ($jml > $existing) {
                for ($i = $existing + 1; $i <= $jml; $i++) {
                    Rt::firstOrCreate([
                        'rw_id' => $rw->id,
                        'nomor_rt' => str_pad($i, 3, '0', STR_PAD_LEFT),
                    ]);
                }
            }
        }

        AktivitasLogger::log('Mengupdate RW: '.$rw->nomor_rw, 'update', null);

        return redirect()->route('superadmin.rw.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data RW berhasil diperbarui.',
        ]);
    }

    public function destroy($id)
    {
        $rw = Rw::findOrFail($id);
        $nama = $rw->nomor_rw;

        // Hapus paksa semua RT yang terkait dengan RW ini
        $rw->rts()->delete();

        $rw->delete();

        AktivitasLogger::log('Menghapus RW: '.$nama, 'hapus', null);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data RW berhasil dihapus.'
            ]);
        }

        return redirect()->route('superadmin.rw.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data RW berhasil dihapus.',
        ]);
    }
}
