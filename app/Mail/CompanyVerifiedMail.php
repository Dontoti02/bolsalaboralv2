<?php

namespace App\Mail;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompanyVerifiedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Company $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    public function build(): static
    {
        return $this->subject('¡Tu empresa ha sido verificada! - Bolsa Laboral')
                    ->view('emails.company-verified');
    }
}
