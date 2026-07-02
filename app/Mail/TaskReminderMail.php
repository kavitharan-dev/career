<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public int $pendingCount,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Arivexa: You have pending learning tasks',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.task-reminder',
        );
    }
}
