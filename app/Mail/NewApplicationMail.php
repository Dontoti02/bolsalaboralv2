<?php

namespace App\Mail;

use App\Models\JobOpportunityApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $studentName;
    public $offerTitle;
    public $applicationDate;

    /**
     * Create a new message instance.
     */
    public function __construct(JobOpportunityApplication $application)
    {
        $this->application = $application;
        $this->studentName = $application->fullname;
        $this->offerTitle = $application->offer?->title ?? 'Oferta laboral';
        $this->applicationDate = $application->created_at
            ? $application->created_at->format('d/m/Y H:i')
            : now()->format('d/m/Y H:i');
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->subject('Nueva postulación recibida - ' . $this->offerTitle)
                    ->view('emails.new-application');
    }
}
