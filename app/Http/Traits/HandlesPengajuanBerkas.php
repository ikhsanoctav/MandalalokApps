<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;

trait HandlesPengajuanBerkas
{
    /**
     * Default file fields for pengajuan documents.
     */
    protected function pengajuanBerkasFields(): array
    {
        return [
            'berkas_ktp' => 'KTP Pemilik',
            'berkas_kk' => 'Kartu Keluarga',
            'berkas_foto_usaha' => 'Foto Tempat Usaha',
            'berkas_nib_sku' => 'Izin Usaha / NIB / SKU',
            'berkas_laporan_keuangan' => 'Laporan Keuangan',
            'berkas_rab' => 'Rencana Anggaran Biaya (RAB)',
            'berkas_foto_produk' => 'Foto Produk',
        ];
    }

    /**
     * Default validation rules for pengajuan berkas files.
     */
    public function pengajuanBerkasRules(): array
    {
        return [
            'berkas_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'berkas_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'berkas_foto_usaha' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'berkas_nib_sku' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'berkas_laporan_keuangan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'berkas_rab' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'berkas_foto_produk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }

    /**
     * Process uploaded berkas files and return structured array.
     *
     * @return array<int, array{nama_dokumen: string, file_path: string}>
     */
    protected function processBerkasUploads(Request $request): array
    {
        $uploadedBerkas = [];

        foreach ($this->pengajuanBerkasFields() as $key => $label) {
            if ($request->hasFile($key)) {
                $path = $request->file($key)->store('pengajuan/documents', 'public');
                $uploadedBerkas[] = [
                    'nama_dokumen' => $label,
                    'file_path' => $path,
                ];
            }
        }

        return $uploadedBerkas;
    }
}
