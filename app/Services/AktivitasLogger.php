<?php

namespace App\Services;

use App\Models\Aktivitas;
use Illuminate\Http\Request;

class AktivitasLogger
{
    public static function log(
        string $deskripsi,
        ?string $type = null,
        ?string $umkmId = null,
        ?Request $request = null
    ): Aktivitas {
        $request = $request ?? request();

        return Aktivitas::create([
            'user_id' => auth()->id(),
            'umkm_id' => $umkmId,
            'deskripsi' => $deskripsi,
            'type' => $type,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
