<?php

namespace App\Livewire;

use App\Models\Campaign;
use Livewire\Component;
use Livewire\WithPagination;

class CampaignComponent extends Component
{
    use WithPagination;
    public  $search;
    public $sortOrder = 'latest';

    public function render()
    {

        $campaigns = Campaign::query();

        if ($this->search) {
            $campaigns->where("title", 'like', '%' . $this->search . '%');
        }

        if ($this->sortOrder === 'latest') {
            $campaigns->latest();
        } elseif ($this->sortOrder === 'oldest') {
            $campaigns->oldest();
        }

        $campaigns = $campaigns->paginate(7);
        return view('livewire.campaign-component', compact('campaigns'));
    }
}
