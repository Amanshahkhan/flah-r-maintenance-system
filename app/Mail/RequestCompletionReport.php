<?php

namespace App\Mail;

use App\Models\MaintenanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestCompletionReport extends Mailable
{
    use Queueable, SerializesModels;

    public $maintenanceRequest;
    public $customMessage; // Can be null

    // The constructor now accepts a nullable custom message
    public function __construct(MaintenanceRequest $maintenanceRequest, public string $customSubject, ?string $customMessage = null)
    {
        $this->maintenanceRequest = $maintenanceRequest;
        $this->customMessage = $customMessage;
    }

    public function build()
    {
        // We load the relationships here to make sure they are available in the email view
        $this->maintenanceRequest->load('user', 'representative', 'products');
        
        return $this->subject($this->customSubject)
                    ->view('emails.completion_report');
    }
}