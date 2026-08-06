<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Imports\MasterSektorImport;
use App\Models\SektorUmkm;
use App\Services\AktivitasLogger;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class SektorController extends Controller
{
    public function index()
    {
        $sektors = SektorUmkm::latest()->get();

        return view('superadmin.master.sektor.index', compact('sektors'));
    }

    public function create()
    {
        return view('superadmin.master.sektor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sektor' => 'required|string|max:255|unique:sektor_umkms,nama_sektor',
            'deskripsi' => 'nullable|string',
        ]);

        $sektor = SektorUmkm::create($request->all());

        AktivitasLogger::log('Menambahkan Sektor UMKM: '.$sektor->nama_sektor, 'tambah', null);

        return redirect()->route('superadmin.sektor.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data Sektor UMKM berhasil ditambahkan.',
        ]);
    }

    public function edit($id)
    {
        $sektor = SektorUmkm::findOrFail($id);

        return view('superadmin.master.sektor.edit', compact('sektor'));
    }

    public function update(Request $request, $id)
    {
        $sektor = SektorUmkm::findOrFail($id);

        $request->validate([
            'nama_sektor' => 'required|string|max:255|unique:sektor_umkms,nama_sektor,'.$id,
            'deskripsi' => 'nullable|string',
        ]);

        $sektor->update($request->all());

        AktivitasLogger::log('Mengupdate Sektor UMKM: '.$sektor->nama_sektor, 'update', null);

        return redirect()->route('superadmin.sektor.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data Sektor UMKM berhasil diperbarui.',
        ]);
    }

    public function destroy($id)
    {
        $sektor = SektorUmkm::findOrFail($id);
        $nama = $sektor->nama_sektor;

        if ($sektor->umkms()->count() > 0) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sektor ini sedang digunakan oleh data UMKM.'
                ], 422);
            }
            return redirect()->route('superadmin.sektor.index')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal Menghapus!',
                'message' => 'Sektor ini sedang digunakan oleh data UMKM.',
            ]);
        }

        $sektor->delete();

        AktivitasLogger::log('Menghapus Sektor UMKM: '.$nama, 'hapus', null);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Sektor UMKM berhasil dihapus.'
            ]);
        }

        return redirect()->route('superadmin.sektor.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data Sektor UMKM berhasil dihapus.',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,csv,xls|max:5120',
        ]);

        try {
            Excel::import(new MasterSektorImport, $request->file('file_excel'));
            AktivitasLogger::log('Mengimport data Sektor UMKM', 'import', null);

            return redirect()->route('superadmin.sektor.index')->with('toast', ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Data Sektor berhasil diimport.']);
        } catch (\Exception $e) {
            return redirect()->route('superadmin.sektor.index')->with('toast', ['type' => 'error', 'title' => 'Gagal!', 'message' => 'Error: '.$e->getMessage()]);
        }
    }

    public function template()
    {
        $headers = ['nama_sektor'];
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

        return Excel::download($export, 'Template_Sektor.xlsx');
    }
}
