<?php

namespace App\Mail;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompanyApprovalRequest extends Mailable
{
    use Queueable, SerializesModels;

    public Company $company;
    public string $companyEmail;

    public function __construct(Company $company)
    {
        $this->company = $company;
        $this->companyEmail = $company->email;
    }

    public function build(): static
    {
        return $this->subject('Solicitud de aprobación de empresa - ' . $this->company->name)
                    ->view('emails.company-approval-request');
    }
}
