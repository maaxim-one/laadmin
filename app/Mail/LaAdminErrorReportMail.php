<?php

namespace MaaximOne\LaAdmin\Mail;

use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class LaAdminErrorReportMail extends Mailable
{
    use Queueable, SerializesModels;

    protected \Throwable $_e;

    public function __construct(\Throwable $e)
    {
        $this->_e = $e;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: env('MAIL_FROM_ADDRESS'),
            to: config('laadmin.report_emails'),
            subject: 'На сайте что-то пошло не так | ' . env('APP_NAME'),
            tags: ['LaAdmin Error']
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'laadmin::mail.error_report',
            with: [
                'e' => $this->_e,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
