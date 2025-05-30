<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InfluencerCustomInvite extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $emailBody;
    public $invitationLink;
    /**
     * Create a new message instance.
     */
    public function __construct($emailBody, $invitationLink = null)
    {
        $this->emailBody = $emailBody;
        $this->invitationLink = $invitationLink;
    }


    public function build()
    {
        return $this->subject('📢 Exciting Campaign Opportunity Just for You!')
            ->view('emails.influencer-custom-invite')
            ->with([
                'emailBody' => $this->emailBody,
                'invitationLink' => $this->invitationLink,
            ]);
    }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Influencer Custom Invite',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
