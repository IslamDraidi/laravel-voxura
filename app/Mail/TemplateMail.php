<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private EmailTemplate $template,
        private array $vars = []
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->template->renderSubject($this->vars));
    }

    public function content(): Content
    {
        return new Content(htmlString: $this->template->renderBody($this->vars));
    }
}
