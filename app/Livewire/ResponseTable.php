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
    public $status;

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

    public function render()
    {

        // $responses = DB::table('campaign_influencer')->whereNotNull('task_status');
        // $responses = DB::table('campaign_influencer')
        // ->join('campaigns', 'campaign_influencer.campaign_id', '=', 'campaigns.id')
        // ->whereNotNull('campaign_influencer.task_status')
        // ->where('campaigns.user_id', auth()->id());

        $responses = DB::table('campaign_influencer')
            ->join('campaigns', 'campaign_influencer.campaign_id', '=', 'campaigns.id')
            ->whereNotNull('campaign_influencer.task_status')
            ->where('campaigns.user_id', auth()->id())
            ->select('campaign_influencer.*');




        if ($this->status === 'declined') {
            $responses->where('task_status', $this->status);
        } elseif ($this->status === 'accepted') {
            $responses->where('task_status', $this->status);
        } elseif ($this->status === 'all') {
            // No filtering needed for 'all', just remove the where clause
        }


        $responses = $responses->paginate(10);

        return view('livewire.response-table', compact('responses'));
    }
}
