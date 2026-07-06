<?php

namespace App\Services;

use App\Models\Pengajuan;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PengajuanService
{
    use \App\Http\Traits\HandlesPengajuanBerkas;

    /**
     * Valid status transitions for Pengajuan.
     */
    public static array $validTransitions = [
        'menunggu'  => ['proses', 'ditolak'],
        'proses'    => ['disetujui', 'ditolak'],
        'disetujui' => ['dicairkan'],
        'ditolak'   => [],
        'dicairkan' => [],
    ];

    /**
     * Check if a status transition is valid.
     */
    public static function isValidTransition(string $from, string $to): bool
    {
        return in_array($to, self::$validTransitions[$from] ?? []);
    }

    /**
     * Create a new Pengajuan from request data.
     *
     * @return Pengajuan
     */
    public function createPengajuan(Request $request): Pengajuan
    {
        $uploadedBerkas = $this->processBerkasUploads($request);

        $pengajuan = Pengajuan::create([
            'id_pengajuan' => (string) Str::uuid(),
            'umkm_id' => $request->umkm_id,
            'jenis_pengajuan' => $request->jenis_pengajuan,
            'nama_program' => $request->jenis_pengajuan === 'bantuan' ? $request->nama_program : null,
            'nominal' => $request->jenis_pengajuan === 'pembiayaan' ? $request->nominal : null,
            'keterangan' => $request->keterangan,
            'status' => 'menunggu',
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'berkas' => $uploadedBerkas,
        ]);

        $pengajuan->load('umkm');

        return $pengajuan;
    }

    /**
     * Validate financing limit.
     *
     * @return string|null Error message, or null if valid.
     */
    public function validateFinancingLimit(Request $request): ?string
    {
        $maxLimit = (float) Setting::get('max_financing_limit', 50000000);

        if ($request->jenis_pengajuan === 'pembiayaan'
            && $request->filled('nominal')
            && (float) $request->nominal > $maxLimit
        ) {
            return 'Nominal pembiayaan melebihi batas maksimal Rp '
                . number_format($maxLimit, 0, ',', '.') . '.';
        }

        return null;
    }

    /**
     * Update pengajuan status with transition validation.
     *
     * @return array{success: bool, message: string}
     */
    public function updateStatus(Pengajuan $pengajuan, string $newStatus, ?string $reason = null): array
    {
        $currentStatus = $pengajuan->status;

        if (! self::isValidTransition($currentStatus, $newStatus)) {
            return [
                'success' => false,
                'message' => "Transisi status tidak valid: {$currentStatus} → {$newStatus}.",
            ];
        }

        $pengajuan->status = $newStatus;

        if (in_array($newStatus, ['disetujui', 'dicairkan'], true)) {
            if (! $pengajuan->tanggal_disetujui) {
                $pengajuan->tanggal_disetujui = now();
            }
        } else {
            $pengajuan->tanggal_disetujui = null;
        }

        if ($newStatus === 'ditolak') {
            $pengajuan->catatan = $reason;
        } elseif ($newStatus === 'proses') {
            $pengajuan->catatan = $reason ?? 'Sedang dalam verifikasi';
        } else {
            $pengajuan->catatan = $reason ?? $pengajuan->catatan;
        }

        $pengajuan->save();

        return [
            'success' => true,
            'message' => 'Status pengajuan berhasil diperbarui.',
        ];
    }

    /**
     * Get pengajuan statistics.
     *
     * @return array<string, int>
     */
    public function getStats(): array
    {
        return [
            'total' => Pengajuan::count(),
            'menunggu' => Pengajuan::where('status', 'menunggu')->count(),
            'proses' => Pengajuan::where('status', 'proses')->count(),
            'disetujui' => Pengajuan::where('status', 'disetujui')->count(),
            'ditolak' => Pengajuan::where('status', 'ditolak')->count(),
        ];
    }
}
