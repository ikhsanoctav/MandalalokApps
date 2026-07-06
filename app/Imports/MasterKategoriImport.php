<?php

namespace App\Imports;

use App\Models\KategoriUMKM;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MasterKategoriImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['nama_kategori'])) {
            return null;
        }

        return new KategoriUMKM([
            'nama_kategori' => $row['nama_kategori'],
        ]);
    }
}
