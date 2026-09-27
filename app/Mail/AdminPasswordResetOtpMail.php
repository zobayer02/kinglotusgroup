<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminPasswordResetOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otp,
        public int $expiryMinutes = 15
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Password Reset Verification Code - King Lotus Group',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset-otp',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
