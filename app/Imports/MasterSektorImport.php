<?php

namespace App\Imports;

use App\Models\SektorUmkm;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MasterSektorImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['nama_sektor'])) {
            return null;
        }

        return new SektorUmkm([
            'nama_sektor' => $row['nama_sektor'],
        ]);
    }
}
