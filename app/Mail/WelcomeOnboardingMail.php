<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeOnboardingMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $careerName,
        public int $matchScore,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Arivexa career path is ready',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.welcome-onboarding',
        );
    }
}
