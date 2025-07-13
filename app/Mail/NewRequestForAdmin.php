<?php

namespace App\Mail;

use App\Models\MaintenanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewRequestForAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $request;

    public function __construct(MaintenanceRequest $request)
    {
        $this->request = $request;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'طلب صيانة جديد تم استلامه', // Subject: New Maintenance Request Received
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new_request_for_admin', // We will create this view next
        );
    }

    public function attachments(): array
    {
        return [];
    }
}