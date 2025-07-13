<?php

namespace App\Mail;

use App\Models\MaintenanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestAssignedToClient extends Mailable
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
            subject: 'تحديث بخصوص طلب الصيانة الخاص بك', // Subject: Update on Your Maintenance Request
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.request_assigned_to_client',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}