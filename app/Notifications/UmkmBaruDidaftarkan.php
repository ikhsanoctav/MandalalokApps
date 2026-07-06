<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class UmkmBaruDidaftarkan extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    public $umkm;

    /**
     * Create a new notification instance.
     */
    public function __construct($umkm)
    {
        $this->umkm = $umkm;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'UMKM Baru Terdaftar',
            'message' => 'UMKM ' . $this->umkm->nama_usaha . ' telah terdaftar dan otomatis terverifikasi.',
            'url' => route('admin.umkm.show', $this->umkm->id_umkm),
            'type' => 'info',
            'umkm_id' => $this->umkm->id_umkm
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'UMKM Baru Terdaftar',
            'message' => 'UMKM ' . $this->umkm->nama_usaha . ' telah terdaftar dan otomatis terverifikasi.',
            'url' => route('admin.umkm.show', $this->umkm->id_umkm),
            'type' => 'info',
            'umkm_id' => $this->umkm->id_umkm
        ]);
    }
}
