<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CampaignResponseNotification extends Notification
{
    use Queueable;

    public $campaign;
    public $influencer;
    public $response;

    public function __construct($campaign, $influencer, $response)
    {
        $this->campaign = $campaign;
        $this->influencer = $influencer;
        $this->response = $response;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $statusMessage = $this->response === 'accepted' ? 'accepted the offer' : 'rejected the offer';
        $subject = "Campaign Response Recorded";
        $title = $this->campaign->title;

        return (new MailMessage)
            ->subject($subject)
            ->line("The influencer with ID {$this->influencer->id} has {$statusMessage} for the campaign titled: {$title}.");
            // ->action('View Campaign', route('campaign.show', $this->campaign->id));
    }

    // Database notification (in-app notification)
    public function toDatabase($notifiable)
    {
        $statusMessage = $this->response === 'accepted' ? 'accepted the offer' : 'rejected the offer';

        return [
            'campaign_id' => $this->campaign->id,
            'campaign_title' => $this->campaign->title,
            'influencer_id' => $this->influencer->id,
            'message' => "The influencer with ID {$this->influencer->id} has {$statusMessage} for the campaign titled: {$this->campaign->title}.",
        ];
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         //
    //     ];
    // }
}
