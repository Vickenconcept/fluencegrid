<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function influencer_chat ($uuid)  {

       return view('conversation.influencer', compact('uuid'));
    }
    
    public function owner_chat ($uuid)  {
        
       return  view('conversation.show', compact('uuid'));
    }
}
