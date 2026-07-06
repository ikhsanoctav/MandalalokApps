<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TugasLapanganBaru extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    public $umkm;

    public function __construct($umkm)
    {
        $this->umkm = $umkm;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Tugas Verifikasi Lapangan Baru',
            'message' => 'Anda ditugaskan untuk melakukan verifikasi lapangan untuk UMKM ' . $this->umkm->nama_usaha,
            'url' => route('verifikasi.index'), // Rute untuk petugas melihat daftar tugas
            'type' => 'warning',
            'umkm_id' => $this->umkm->id
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Tugas Verifikasi Lapangan Baru',
            'message' => 'Anda ditugaskan memverifikasi UMKM ' . $this->umkm->nama_usaha,
            'url' => route('verifikasi.index'),
            'type' => 'warning',
            'umkm_id' => $this->umkm->id
        ]);
    }
}
