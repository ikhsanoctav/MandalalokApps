<?php

namespace App\Imports;

use App\Models\Kelurahan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MasterKelurahanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['kode_kelurahan']) || empty($row['nama_kelurahan'])) {
            return null;
        }

        return new Kelurahan([
            'kode_kelurahan' => $row['kode_kelurahan'],
            'nama_kelurahan' => $row['nama_kelurahan'],
        ]);
    }
}
