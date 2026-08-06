<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Imports\MasterKelurahanImport;
use App\Models\Kelurahan;
use App\Models\Rw;
use App\Services\AktivitasLogger;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class KelurahanController extends Controller
{
    public function index()
    {
        $kelurahans = Kelurahan::withCount('rws')->latest()->get();

        return view('superadmin.master.kelurahan.index', compact('kelurahans'));
    }

    public function create()
    {
        return view('superadmin.master.kelurahan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kelurahan' => 'required|string|max:20|unique:kelurahans,kode_kelurahan',
            'nama_kelurahan' => 'required|string|max:255|unique:kelurahans,nama_kelurahan',
            'jumlah_rw' => 'nullable|integer|min:1|max:100',
        ]);

        $kelurahan = Kelurahan::create($request->only(['kode_kelurahan', 'nama_kelurahan']));

        if ($request->filled('jumlah_rw')) {
            $jml = (int) $request->jumlah_rw;
            for ($i = 1; $i <= $jml; $i++) {
                Rw::create([
                    'kelurahan_id' => $kelurahan->id,
                    'nomor_rw' => str_pad($i, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        AktivitasLogger::log('Menambahkan Kelurahan: '.$kelurahan->nama_kelurahan, 'tambah', null);

        return redirect()->route('superadmin.kelurahan.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data Kelurahan berhasil ditambahkan.',
        ]);
    }

    public function edit($id)
    {
        $kelurahan = Kelurahan::findOrFail($id);

        return view('superadmin.master.kelurahan.edit', compact('kelurahan'));
    }

    public function update(Request $request, $id)
    {
        $kelurahan = Kelurahan::findOrFail($id);

        $request->validate([
            'kode_kelurahan' => 'required|string|max:20|unique:kelurahans,kode_kelurahan,'.$id,
            'nama_kelurahan' => 'required|string|max:255|unique:kelurahans,nama_kelurahan,'.$id,
            'jumlah_rw' => 'nullable|integer|min:1|max:100',
        ]);

        $kelurahan->update($request->only(['kode_kelurahan', 'nama_kelurahan']));

        if ($request->filled('jumlah_rw')) {
            $jml = (int) $request->jumlah_rw;
            $existing = $kelurahan->rws()->count();
            if ($jml > $existing) {
                for ($i = $existing + 1; $i <= $jml; $i++) {
                    Rw::firstOrCreate([
                        'kelurahan_id' => $kelurahan->id,
                        'nomor_rw' => str_pad($i, 3, '0', STR_PAD_LEFT),
                    ]);
                }
            }
        }

        AktivitasLogger::log('Mengupdate Kelurahan: '.$kelurahan->nama_kelurahan, 'update', null);

        return redirect()->route('superadmin.kelurahan.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data Kelurahan berhasil diperbarui.',
        ]);
    }

    public function destroy($id)
    {
        $kelurahan = Kelurahan::findOrFail($id);
        $nama = $kelurahan->nama_kelurahan;

        // Hapus paksa semua RW dan RT yang terkait dengan kelurahan ini
        foreach ($kelurahan->rws as $rw) {
            $rw->rts()->delete(); // hapus semua RT di dalam RW ini
            $rw->delete();        // hapus RW ini
        }

        $kelurahan->delete();

        AktivitasLogger::log('Menghapus Kelurahan: '.$nama, 'hapus', null);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Kelurahan berhasil dihapus.'
            ]);
        }

        return redirect()->route('superadmin.kelurahan.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data Kelurahan berhasil dihapus.',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,csv,xls|max:5120',
        ]);

        try {
            Excel::import(new MasterKelurahanImport, $request->file('file_excel'));
            AktivitasLogger::log('Mengimport data Kelurahan', 'import', null);

            return redirect()->route('superadmin.kelurahan.index')->with('toast', ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Data Kelurahan berhasil diimport.']);
        } catch (\Exception $e) {
            return redirect()->route('superadmin.kelurahan.index')->with('toast', ['type' => 'error', 'title' => 'Gagal!', 'message' => 'Error: '.$e->getMessage()]);
        }
    }

    public function template()
    {
        $headers = ['kode_kelurahan', 'nama_kelurahan'];
        $export = new class($headers) implements FromArray, WithHeadings
        {
            protected $headings;

            public function __construct($headings)
            {
                $this->headings = $headings;
            }

            public function array(): array
            {
                return [];
            }

            public function headings(): array
            {
                return $this->headings;
            }
        };

        return Excel::download($export, 'Template_Kelurahan.xlsx');
    }
}
