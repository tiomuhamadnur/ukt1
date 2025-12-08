<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CutiMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $mailData;
    public $lampiran;

    public function __construct($mailData, $lampiran = null)
    {
        $this->mailData = $mailData;
        $this->lampiran = $lampiran;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Notifikasi SIGMA] - Pengajuan Cuti',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'page.admin.cuti.email',
        );
    }

    public function attachments(): array
    {
        if ($this->lampiran) {
            return [
                Attachment::fromPath($this->lampiran)
            ];
        }

        return [];
    }
}
