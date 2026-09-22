<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $plainPassword,
        public string $profile,
        public ?string $accountNumber = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'IFBank - seus dados de acesso');
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.credentials');
    }
}
