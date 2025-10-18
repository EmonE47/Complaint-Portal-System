<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $complaint;
    public $user;
    public $status;
    public $updatedAt;

    public function __construct($complaint, $user, $status, $updatedAt)
    {
        $this->complaint = $complaint;
        $this->user = $user;
        $this->status = $status;
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Complaint Status Updated',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.complaint-status-updated',
            with: [
                'complaint' => $this->complaint,
                'user' => $this->user,
                'status' => $this->status,
                'updatedAt' => $this->updatedAt,
            ],
        );
    }

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
