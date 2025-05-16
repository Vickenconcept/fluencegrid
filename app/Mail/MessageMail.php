<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageMail extends Mailable implements ShouldQueue 
{
    use Queueable, SerializesModels;

    public $sender;
    public $messageBody;
    
    /**
     * Create a new message instance.
     */
    public function __construct($uuid)
    {
        $this->sender = auth()->check() ? auth()->user()->name : 'influencer';
        
        $this->messageBody = "You have a message from {$this->sender}\n\n";
        
        if ($this->sender !== 'influencer') {
            $this->messageBody .= "Check the message here: " . route('conversation.influencer', ['uuid' => $uuid]);
        } else {
            $this->messageBody .= "Check the message here: " . route('conversation.owner', ['uuid' => $uuid]);
        }
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject(env('MAIL_FROM_NAME') . ": " . $this->sender . ", Sent You a Message")
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->text('emails.message_plain') 
                    ->with(['messageBody' => $this->messageBody]);
    }
}