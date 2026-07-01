<?php

namespace App\Mail;

use App\Models\JobOpportunityApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $studentName;
    public $offerTitle;
    public $companyName;
    public $approvalStatus;
    public $feedback;

    /**
     * Create a new message instance.
     */
    public function __construct(JobOpportunityApplication $application)
    {
        $this->application = $application;
        $this->studentName = $application->fullname;
        $this->offerTitle = $application->offer?->title ?? 'Oferta laboral';
        $this->companyName = $application->offer?->company?->name ?? 'Empresa';
        $this->approvalStatus = $application->status === 'accepted' ? 'Aceptada' : $application->status;
        $this->feedback = $application->feedback;
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->subject('Estado de tu postulación actualizado - ' . $this->offerTitle)
                    ->view('emails.application-approved');
    }
}
