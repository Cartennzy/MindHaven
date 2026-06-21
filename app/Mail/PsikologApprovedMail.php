<?php

namespace App\Mail;

use App\Models\Psikolog;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PsikologApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $psikolog;
    public $plainPassword;

    public function __construct(Psikolog $psikolog, string $plainPassword)
    {
        $this->psikolog = $psikolog;
        $this->plainPassword = $plainPassword;
    }

    public function build()
    {
        return $this->subject('Akun Psikolog MindHaven Telah Disetujui')
            ->view('emails.psikolog-approved');
    }
}