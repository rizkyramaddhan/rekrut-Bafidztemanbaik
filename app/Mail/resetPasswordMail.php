<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
<<<<<<< HEAD
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;
=======
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
>>>>>>> a4d1d5e8ba46a0a90d34e6a0ae3d62c6a686f004

class resetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

<<<<<<< HEAD
    public $user;
    public $token;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
=======
    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
>>>>>>> a4d1d5e8ba46a0a90d34e6a0ae3d62c6a686f004
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
<<<<<<< HEAD
            replyTo: [
                new Address('k5YHg@example.com', 'Admin'),
            ],
=======
>>>>>>> a4d1d5e8ba46a0a90d34e6a0ae3d62c6a686f004
            subject: 'Reset Password Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
<<<<<<< HEAD
            view: 'mails.resetPasswordMail',
=======
            view: 'view.mails.resetPasswordMail',
>>>>>>> a4d1d5e8ba46a0a90d34e6a0ae3d62c6a686f004
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
<<<<<<< HEAD
}
=======
}
>>>>>>> a4d1d5e8ba46a0a90d34e6a0ae3d62c6a686f004
