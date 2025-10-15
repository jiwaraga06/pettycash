<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PettyCashEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $details;
    protected $subjectEmail;
    protected $viewEmail;
    protected $fromEmail;
    protected $fromName;

    public function __construct($details, $subjectEmail, $viewEmail, $fromEmail, $fromName = null)
    {
        $this->details = $details;
        $this->subjectEmail = $subjectEmail;
        $this->viewEmail = $viewEmail;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName ?? $fromEmail;
    }

    public function build()
    {
        return $this->from($this->fromEmail, $this->fromName)
            ->subject($this->subjectEmail)
            ->view($this->viewEmail)
            ->with('details', $this->details);
    }
    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Petty Cash Email',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         // view: 'email.emailPettyCash',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
