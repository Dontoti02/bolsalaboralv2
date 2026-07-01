<?php

namespace App\Mail;

use App\Models\JobOpportunityApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $studentName;
    public $offerTitle;
    public $companyName;
    public $applicationDate;

    public function __construct(JobOpportunityApplication $application)
    {
        $this->application = $application;
        $this->studentName = $application->fullname;
        $this->offerTitle = $application->offer?->title ?? 'Oferta laboral';
        $this->companyName = $application->offer?->company?->name ?? 'Empresa';
        $this->applicationDate = $application->created_at
            ? $application->created_at->format('d/m/Y H:i')
            : now()->format('d/m/Y H:i');
    }

    public function build(): static
    {
        return $this->subject('Postulación enviada - ' . $this->offerTitle)
            ->view('emails.application-submitted');
    }
}
