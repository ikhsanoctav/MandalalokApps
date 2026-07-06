<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Imports\MasterKategoriImport;
use App\Models\KategoriUMKM;
use App\Services\AktivitasLogger;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = KategoriUMKM::latest()->get();

        return view('superadmin.master.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('superadmin.master.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_umkms,nama_kategori',
        ]);

        $kategori = KategoriUMKM::create($request->all());

        AktivitasLogger::log('Menambahkan Kategori UMKM: '.$kategori->nama_kategori, 'tambah', null);

        return redirect()->route('superadmin.kategori.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data Kategori UMKM berhasil ditambahkan.',
        ]);
    }

    public function edit($id)
    {
        $kategori = KategoriUMKM::findOrFail($id);

        return view('superadmin.master.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriUMKM::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_umkms,nama_kategori,'.$id,
        ]);

        $kategori->update($request->all());

        AktivitasLogger::log('Mengupdate Kategori UMKM: '.$kategori->nama_kategori, 'update', null);

        return redirect()->route('superadmin.kategori.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data Kategori UMKM berhasil diperbarui.',
        ]);
    }

    public function destroy($id)
    {
        $kategori = KategoriUMKM::findOrFail($id);
        $nama = $kategori->nama_kategori;

        if ($kategori->umkms()->count() > 0) {
            return redirect()->route('superadmin.kategori.index')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal Menghapus!',
                'message' => 'Kategori ini sedang digunakan oleh data UMKM.',
            ]);
        }

        $kategori->delete();

        AktivitasLogger::log('Menghapus Kategori UMKM: '.$nama, 'hapus', null);

        return redirect()->route('superadmin.kategori.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data Kategori UMKM berhasil dihapus.',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,csv,xls|max:5120',
        ]);

        try {
            Excel::import(new MasterKategoriImport, $request->file('file_excel'));
            AktivitasLogger::log('Mengimport data Kategori UMKM', 'import', null);

            return redirect()->route('superadmin.kategori.index')->with('toast', ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Data Kategori berhasil diimport.']);
        } catch (\Exception $e) {
            return redirect()->route('superadmin.kategori.index')->with('toast', ['type' => 'error', 'title' => 'Gagal!', 'message' => 'Error: '.$e->getMessage()]);
        }
    }

    public function template()
    {
        $headers = ['nama_kategori'];
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

        return Excel::download($export, 'Template_Kategori.xlsx');
    }
}
