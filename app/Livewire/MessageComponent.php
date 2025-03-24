<?php

namespace App\Livewire;

// use App\Events\UserTypingEvent;
use App\Models\Conversation;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Str;

class MessageComponent extends Component
{
    public $uuid, $conversation;
    public $message = '',
        $messages = '',
        $isTyping = false;

    public $amount,
        $url,
        $description;


    public function mount($uuid)
    {
        $this->uuid = $uuid;
        $this->conversation = Conversation::whereUuid($uuid)->first();
        $this->fetchMessages();
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
        $sender = auth()->check() ? 'owner' : 'influencer';

        $this->conversation->messages()->create([
            'sender' => $sender,
            'message' => $this->message,
        ]);
        $this->message = '';
        // $this->reset('message');
        session()->flash('success', 'Sent Successfully !');
        $this->dispatch('scrollUp');
    }

    public function createContract()
    {
        $shortCode = Str::random(8);

        // Save to the database
        $shortenedUrl = ShortenedUrl::create([
            'original_url' => $this->url,
            'short_code' => $shortCode,
            'clicks' => 0,
            'ip_addresses' => [],
        ]);

        // Return the unique URL
        $uniqueUrl = url('/short/' . $shortCode);

        session()->flash('success', 'Contract created successfully! Unique URL: ' . $uniqueUrl);
    }

    public function render()
    {
        // $messages = $this->conversation->messages()->get();

        // return view('livewire.message-component', compact('messages'));
        return view('livewire.message-component');
    }
}
