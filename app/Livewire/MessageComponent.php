<?php

namespace App\Livewire;

// use App\Events\UserTypingEvent;

use App\Mail\MessageMail;
use App\Models\Campaign;
use App\Models\Conversation;
use App\Models\Influencer;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class MessageComponent extends Component
{
    public $uuid, $conversation;
    public $message = '',
        $messages = '',
        $isTyping = false;


    public $amount,
        $url,
        $description,
        $uniqueUrl, $deal;

    public $influencer, $campaign;

    public $clicks;
    public $percentage, $totalIps;

    public $startDate, $endDate;


    public function mount($uuid)
    {
        $this->uuid = $uuid;
        $this->conversation = Conversation::whereUuid($uuid)->firstorFail();
        $this->deal = $this->conversation->status;
        $this->totalIps = count($this->conversation->ip_addresses ?? []);

        $this->influencer = Influencer::findorFail($this->conversation->influencer_id);

        $this->campaign = Campaign::findorFail($this->conversation->campaign_id);

        $this->startDate = \Carbon\Carbon::parse($this->campaign->start_date);
        $this->endDate = \Carbon\Carbon::parse($this->campaign->end_date);

        $this->uniqueUrl = $this->uniqueUrl = url('/short/' . $this->conversation->short_code);
        $this->amount =  $this->conversation->amount;
        $this->description = $this->conversation->description;
        $this->url = $this->conversation->original_url;

        $this->fetchMessages();
        $this->updateClicks();
    }

    public function fetchMessages()
    {
        $this->messages = $this->conversation->messages()->latest()->take(10)->get()->reverse();
    }

    public function typing()
    {
        // broadcast(new UserTypingEvent($this->conversation->id))->toOthers();
    }

    #[On('userTyping')]
    public function userTyping()
    {
        $this->isTyping = true;
    }

    #[On('resetTyping')]
    public function resetTyping()
    {
        $this->isTyping = false;
    }

    public function saveMessage()
    {
        $owner = User::findOrFail($this->conversation->user_id);


        $sender = auth()->check() ? 'owner' : 'influencer';
        $email = auth()->check() ?
          //$this->influencer->emails 
          ['vicken408@gmail.com']:
           $owner->email;


        $this->conversation->messages()->create([
            'sender' => $sender,
            'message' => $this->message,
        ]);
        $this->message = '';
        // $this->reset('message');

        session()->flash('success', 'Sent Successfully !');


        Mail::to($email)->queue(new MessageMail($this->uuid));

        $this->dispatch('scrollUp');
    }

    public function createContract()
    {
        $shortCode = Str::random(8);


        if ($this->conversation) {
            if (!empty($this->url) && !empty($this->description) && !empty($this->amount)) {
                $this->conversation->update([
                    'original_url' => trim($this->url),
                    'short_code' => trim($shortCode),
                    'amount' => (int) $this->amount,
                    'description' => trim($this->description),
                    'status' => 'deal',
                ]);



                $this->uniqueUrl = url('/short/' . $shortCode);
                $this->deal = 'deal';

                $this->url = $this->conversation->original_url;
                $this->amount = $this->conversation->amount;
                $this->conversation = $this->conversation;
                session()->flash('success', 'Contract created successfully!');
            } else {
                session()->flash('error', 'Fill the required fields');
            }
        } else {
            session()->flash('error', 'Conversation not found!');
        }
    }


    public function cancleContract()
    {

        if ($this->conversation) {
            $this->conversation->update([
                'original_url' => null,
                'short_code' => null,
                'amount' => 0,
                'description' => null,
                'clicks' => 0,
                'ip_addresses' => null,
                'status' => 'pending',
            ]);


            session()->flash('success', 'Contract Cancled successfully!');

            sleep(1);
            $this->dispatch('refreshPage');
        }
    }


    public function updateClicks()
    {
        $this->clicks = $this->conversation->clicks;
        $this->percentage = min(100, ($this->clicks / 100) * 100);
    }




    public function sendCampaignReminder()
    {


        // $email = $this->influencer->emails;
        $email = 'vicken408@gmail.com';
        $campaignName = ucfirst(strtolower($this->campaign->title));
        $campaignDeadline = $this->campaign->end_date;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            session()->flash('error', 'Invalid email address.');
            return;
        }

        $messageBody = "Hello, {$this->influencer->influencer_id} \n\n"
            . "This is a reminder that you have an upcoming campaign: \"{$campaignName}\".\n"
            . "Please ensure you complete your tasks before the deadline: {$campaignDeadline}.\n\n"
            . "Below is the campaign link:.\n\n"
            . "{$this->uniqueUrl}.\n\n"
            . "If you have any questions, feel free to reach out.\n\n"
            . "Best regards,\n"
            . "Your Team";

        $subject = "Reminder: Upcoming Campaign - {$campaignName}";
        Mail::raw($messageBody, function ($message) use ($email, $subject) {
            $message->to($email)
                ->subject($subject)
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });


        session()->flash('success', 'Reminder email sent successfully!');
        $this->dispatch('email-sent', status: 'success', msg: 'Emails sent successfully');
    }


    public function render()
    {
        // $messages = $this->conversation->messages()->get();

        // return view('livewire.message-component', compact('messages'));
        return view('livewire.message-component');
    }
}
