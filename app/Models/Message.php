<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Message extends Model
{
    
    public $guarded = [];

    public function conversations(){
        return $this->belongsTo(Conversation::class);
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::get(fn ($value) => Carbon::parse($value)->format('F j, Y, g:i A'));
    }
    protected function createdAt(): Attribute
    {
        return Attribute::get(fn ($value) => Carbon::parse($value)->format('F j, Y, g:i A'));
    }
}
