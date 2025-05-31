<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FromWebsiteMailer extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $subjectLine; // renamed to avoid conflict
    public $messageContent; // renamed from $message

    public function __construct($name, $email, $subject, $message)
    {
        $this->name = $name;
        $this->email = $email;
        $this->subjectLine = $subject;
        $this->messageContent = $message;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'From Website Mailer',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mailer.from_website_mailer',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
