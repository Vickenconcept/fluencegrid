<?php

namespace App\Livewire;

use App\Models\influencersGroup;
use Livewire\Component;
use Livewire\WithPagination;

class GroupIndex extends Component
{

    use WithPagination;
    public $search, $sortOrder;


    public function render()
    {
        $groups = influencersGroup::with('latestInfluencer');  

        if ($this->search) {
            $groups->where("name", 'like', '%' . $this->search . '%');
        }

        if ($this->sortOrder === 'latest') {
            $groups->latest();  
        } elseif ($this->sortOrder === 'oldest') {
            $groups->oldest();  
        }

        $groups = $groups->paginate(9);

        return view('livewire.group-index', compact('groups'));
    }
}
