<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\Reseller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ResponseTable extends Component
{
    use WithPagination;
    public $status, $deal;

    #[On('delete-response')]
    public function deletResponse($id)
    {
        $response = DB::table('campaign_influencer')->where('id', (int)$id)->first();
        if ($response) {
            DB::table('campaign_influencer')->where('id', (int)$id)->delete();
            $conversation = Conversation::where('campaign_id', $response->campaign_id)
                ->where('influencer_id', $response->influencer_id)
                ->first();

            $conversation->delete();
        } else {
            session()->flash('error', 'Response not found.');
        }

        // $response->delete();
    }

    // $responses = DB::table('campaign_influencer')
    //     ->join('campaigns', 'campaign_influencer.campaign_id', '=', 'campaigns.id')
    //     ->whereNotNull('campaign_influencer.task_status')
    //     ->where('campaigns.user_id', auth()->id())
    //     ->select('campaign_influencer.*');



    // public function render()
    // {



    //     $responses = DB::table('campaign_influencer')
    //         ->join('campaigns', 'campaign_influencer.campaign_id', '=', 'campaigns.id')
    //         ->whereNotNull('campaign_influencer.task_status')
    //         ->where('campaigns.user_id', auth()->id())
    //         ->select('campaign_influencer.*');




    //     if ($this->deal === 'deal') {
    //         $responses->where('task_status', $this->status);
    //     } elseif ($this->deal === 'pending') {
    //         $responses->where('task_status', $this->status);
    //     } elseif ($this->deal === 'no-deal') {
    //         // No filtering needed for 'all', just remove the where clause
    //     }

    //     if ($this->status === 'declined') {
    //         $responses->where('task_status', $this->status);
    //     } elseif ($this->status === 'accepted') {
    //         $responses->where('task_status', $this->status);
    //     } elseif ($this->status === 'all') {
    //         // No filtering needed for 'all', just remove the where clause
    //     }


    //     $responses = $responses->paginate(10);

    //     return view('livewire.response-table', compact('responses'));
    // }


    // public function render()
    // {
    //     $responses = DB::table('campaign_influencer')
    //         ->join('campaigns', 'campaign_influencer.campaign_id', '=', 'campaigns.id')
    //         ->leftJoin('conversations', function ($join) {
    //             $join->on('campaign_influencer.campaign_id', '=', 'conversations.campaign_id')
    //                 ->on('campaign_influencer.influencer_id', '=', 'conversations.influencer_id');
    //         })
    //         ->whereNotNull('campaign_influencer.task_status')
    //         ->where('campaigns.user_id', auth()->id())
    //         ->select('campaign_influencer.*', 'conversations.status as conversation_status');

    //     // Filter based on the deal type from conversation status
    //     if ($this->deal === 'deal') {
    //         $responses->where('conversations.status', 'deal')
    //             ->where('campaign_influencer.task_status', $this->status);
    //     } elseif ($this->deal === 'pending') {
    //         $responses->where('conversations.status', 'pending')
    //             ->where('campaign_influencer.task_status', $this->status);
    //     } elseif ($this->deal === 'no-deal') {
    //         $responses->where('conversations.status', 'no-deal');
    //     }

    //     // Filter based on task status
    //     if (in_array($this->status, ['declined', 'accepted'])) {
    //         $responses->where('campaign_influencer.task_status', $this->status);
    //     }

    //     // Paginate results
    //     $responses = $responses->paginate(10);

    //     return view('livewire.response-table', compact('responses'));
    // }


    public function render()
    {
        $responses = DB::table('campaign_influencer')
            ->join('campaigns', 'campaign_influencer.campaign_id', '=', 'campaigns.id')
            ->leftJoin('conversations', function ($join) {
                $join->on('campaign_influencer.campaign_id', '=', 'conversations.campaign_id')
                    ->on('campaign_influencer.influencer_id', '=', 'conversations.influencer_id');
            })
            ->whereNotNull('campaign_influencer.task_status')
            ->where('campaigns.user_id', auth()->id())
            ->select('campaign_influencer.*', 'conversations.status as conversation_status');


        if (!empty($this->deal) && in_array($this->deal, ['deal', 'pending'])) {
            $responses->where('conversations.status', $this->deal);
        } elseif ($this->deal === 'no-deal') {
            $responses->whereNull('conversations.status'); // Show only items with no conversation
        } elseif ($this->deal === 'all') {
            // Do nothing to reset the table (show all records)
        }
        


        if (in_array($this->status, ['declined', 'accepted'])) {
            $responses->where('campaign_influencer.task_status', $this->status);
        }

        $responses->orderBy('campaign_influencer.created_at', 'desc');

        $responses = $responses->paginate(10);

        return view('livewire.response-table', compact('responses'));
    }
}
