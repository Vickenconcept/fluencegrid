<?php

namespace App\Models;

use App\Models\Scopes\DataAccessScope;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{


    public $guarded = [];


    public function user(){
        return $this->belongsTo(User::class);
    }
    public function messages(){
        return $this->hasMany(Message::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new DataAccessScope);

        static::deleting(function ($conversation) {
            // Delete the associated messages
            $conversation->messages()->delete();
        });
    }
}
