<?php

namespace App\Livewire;

use App\Models\Reseller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ResellTable extends Component
{


    public function deletUser($id){
       $user = User::find($id);

    
       $user->delete();


    }

    public function render()
    {

        $owner = auth()->user();

        $users = User::whereHas('resellers', function ($query) use ($owner) {
            $query->where('resell_id', $owner->id);
        })->paginate(10);
        

        return view('livewire.resell-table', compact('users'));
    }
}
