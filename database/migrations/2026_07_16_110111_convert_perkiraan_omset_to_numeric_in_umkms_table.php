<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Konversi data lama dari string ke numeric
        $umkms = DB::table('umkms')->get();

        foreach ($umkms as $umkm) {
            $newValue = null;
            switch ($umkm->perkiraan_omset) {
                case '< Rp 5 Juta':
                    $newValue = 4000000;
                    break;
                case 'Rp 5 Juta - Rp 10 Juta':
                    $newValue = 7500000;
                    break;
                case 'Rp 10 Juta - Rp 50 Juta':
                    $newValue = 30000000;
                    break;
                case 'Rp 50 Juta - Rp 100 Juta':
                    $newValue = 75000000;
                    break;
                case '> Rp 100 Juta':
                    $newValue = 150000000;
                    break;
                default:
                    // Jika data sudah berbentuk angka, bersihkan dari huruf/spasi
                    if (!empty($umkm->perkiraan_omset)) {
                        $cleaned = preg_replace('/[^0-9]/', '', $umkm->perkiraan_omset);
                        if (!empty($cleaned)) {
                            $newValue = (int)$cleaned;
                        }
                    }
                    break;
            }

            if ($newValue !== null) {
                DB::table('umkms')->where('id', $umkm->id)->update([
                    'perkiraan_omset' => (string)$newValue,
                ]);
            }
        }
        
        // Opsional: kita bisa mengubah kolom menjadi decimal, tapi karena datanya besar (150 Juta)
        // string (varchar) sudah cukup aman atau kita ubah ke decimal(15,2)/bigInteger
        // Untuk amannya dan menjaga kompatibilitas, kita biarkan tipenya string tapi isinya angka murni.
        // Schema::table('umkms', function (Blueprint $table) {
        //    $table->decimal('perkiraan_omset', 15, 2)->nullable()->change();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
