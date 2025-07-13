<?php

namespace App\Mail;

use App\Models\MaintenanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use PDF;

class ForwardMaintenanceRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $maintenanceRequest;
    public $notes; // To hold the optional notes

    public function __construct(MaintenanceRequest $maintenanceRequest, ?string $notes = null)
    {
        $this->maintenanceRequest = $maintenanceRequest;
        $this->notes = $notes;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'أمر صيانة موجه إليك: طلب رقم #' . $this->maintenanceRequest->id,
        );
    }

    public function content(): Content
    {
        // We will create this new view next
        return new Content(
            view: 'emails.forward_request',
        );
    }

    public function attachments(): array
    {
        $pdf = PDF::loadView('client.pdf.maintenance', ['request' => $this->maintenanceRequest]);
        return [
            Attachment::fromData(fn () => $pdf->output(), 'Maintenance_Request_'.$this->maintenanceRequest->id.'.pdf')
                ->withMime('application/pdf'),
        ];
    }
}