<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\AktivitasLogger;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $files = $disk->files(config('backup.backup.name'));
        $backups = [];

        foreach ($files as $file) {
            if (substr($file, -4) == '.zip' && $disk->exists($file)) {
                $backups[] = [
                    'file_path' => $file,
                    'file_name' => str_replace(config('backup.backup.name').'/', '', $file),
                    'file_size' => $this->humanFilesize($disk->size($file)),
                    'last_modified' => Carbon::createFromTimestamp($disk->lastModified($file))->format('Y-m-d H:i:s'),
                ];
            }
        }

        $backups = array_reverse($backups);

        return view('superadmin.backup.index', compact('backups'));
    }

    public function create()
    {
        try {
            // run backup command
            Artisan::call('backup:run', ['--only-db' => true]);
            $output = Artisan::output();

            AktivitasLogger::log('Membuat Backup Database Baru', 'backup', null);

            return redirect()->route('superadmin.backup.index')->with('toast', [
                'type' => 'success',
                'title' => 'Backup Berhasil!',
                'message' => 'Proses backup database berhasil dijalankan.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('superadmin.backup.index')->with('toast', [
                'type' => 'error',
                'title' => 'Backup Gagal!',
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ]);
        }
    }

    public function download($file_name)
    {
        $file = config('backup.backup.name').'/'.$file_name;
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);

        if ($disk->exists($file)) {
            AktivitasLogger::log('Mengunduh file backup: '.$file_name, 'download', null);

            return Storage::download($file);
        }

        abort(404, "Backup file doesn't exist.");
    }

    public function delete($file_name)
    {
        $file = config('backup.backup.name').'/'.$file_name;
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);

        if ($disk->exists($file)) {
            $disk->delete($file);
            AktivitasLogger::log('Menghapus file backup: '.$file_name, 'hapus', null);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'File backup berhasil dihapus.'
                ]);
            }

            return redirect()->route('superadmin.backup.index')->with('toast', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'File backup berhasil dihapus.',
            ]);
        }

        abort(404, "Backup file doesn't exist.");
    }

    private function humanFilesize($size, $precision = 2)
    {
        $units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $step = 1024;
        $i = 0;
        while (($size / $step) > 0.9) {
            $size = $size / $step;
            $i++;
        }

        return round($size, $precision).' '.$units[$i];
    }
}
