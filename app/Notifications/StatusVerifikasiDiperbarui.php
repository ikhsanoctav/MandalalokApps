<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class StatusVerifikasiDiperbarui extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    public $umkm;
    public $status;
    public $catatan;

    public function __construct($umkm, $status, $catatan = null)
    {
        $this->umkm = $umkm;
        $this->status = $status;
        $this->catatan = $catatan;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Status Pengajuan UMKM Diperbarui',
            'message' => 'Pengajuan UMKM ' . $this->umkm->nama_usaha . ' telah di ' . $this->status . ($this->catatan ? ': ' . $this->catatan : ''),
            'url' => route('dashboard'), // Atau halaman riwayat yang sesuai
            'type' => $this->status === 'Disetujui' ? 'success' : 'error',
            'umkm_id' => $this->umkm->id
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Status Pengajuan UMKM Diperbarui',
            'message' => 'Pengajuan UMKM ' . $this->umkm->nama_usaha . ' telah di ' . $this->status,
            'url' => route('dashboard'),
            'type' => $this->status === 'Disetujui' ? 'success' : 'error',
            'umkm_id' => $this->umkm->id
        ]);
    }
}
