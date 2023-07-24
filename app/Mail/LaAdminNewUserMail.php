<?php

namespace MaaximOne\LaAdmin\Mail;

use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class LaAdminNewUserMail extends Mailable
{
    use Queueable, SerializesModels;

    protected string $_email;
    protected string $_token;

    public function __construct($_email, $_token)
    {
        $this->_email = $_email;
        $this->_token = $_token;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: env('MAIL_FROM_ADDRESS'),
            to: $this->_email,
            subject: 'LaAdminPanel Регистрация',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'laadmin::mail.la-admin-new-user',
            with: [
                'email' => $this->_email,
                'token' => $this->_token
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
