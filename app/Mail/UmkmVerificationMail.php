<?php

namespace App\Mail;

use App\Models\UMKM;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UmkmVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $umkm;
    public $status;
    public $catatan;

    /**
     * Create a new message instance.
     */
    public function __construct(UMKM $umkm, $status, $catatan = null)
    {
        $this->umkm = $umkm;
        $this->status = $status;
        $this->catatan = $catatan;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusText = $this->status == 'terverifikasi' ? 'Disetujui' : 'Ditolak';
        return new Envelope(
            subject: "Pemberitahuan Status Pengajuan UMKM: {$statusText}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.umkm-verification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
