<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InfluencerCampaignInvite extends Mailable
{
    use Queueable, SerializesModels;

    public $influencerName;
    public $campaignTitle;
    public $brandName;
    public $targetAudience;
    public $compensation;
    public $acceptanceDeadline;
    public $campaignLink;

    /**
     * Create a new message instance.
     */
    public function __construct($influencerName, $campaignTitle, $brandName = null , $targetAudience = null, $compensation = null, $acceptanceDeadline, $campaignLink)
    {
        $this->influencerName = $influencerName;
        $this->campaignTitle = $campaignTitle;
        $this->brandName = $brandName;
        $this->targetAudience = $targetAudience;
        $this->compensation = $compensation;
        $this->acceptanceDeadline = $acceptanceDeadline;
        $this->campaignLink = $campaignLink;
    }


    public function build()
    {
        return $this->subject('📢 Exciting Campaign Opportunity Just for You!')
            ->view('emails.influencer-campaign-invite')
            ->with([
                'influencerName' => $this->influencerName,
                'campaignTitle' => $this->campaignTitle,
                'brandName' => $this->brandName,
                'targetAudience' => $this->targetAudience,
                'compensation' => $this->compensation,
                'acceptanceDeadline' => $this->acceptanceDeadline,
                'campaignLink' => $this->campaignLink,
            ]);
    }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Influencer Campaign Invite',
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
