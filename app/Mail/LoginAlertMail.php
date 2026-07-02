<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public ?string $loginTime = null,
        public ?string $ipAddress = null,
    ) {
        $this->loginTime = $loginTime ?? now()->timezone('Asia/Colombo')->format('d M Y, h:i A').' (SLST)';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Arivexa — new sign-in to your account',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.login-alert',
        );
    }
}
