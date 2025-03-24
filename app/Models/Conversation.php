<?php

namespace App\Models;

use App\Models\Scopes\DataAccessScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Conversation extends Model
{


    public $guarded = [];

    protected $casts = [
        'ip_addresses' => 'array',
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }
    public function messages(){
        return $this->hasMany(Message::class);
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::get(fn ($value) => Carbon::parse($value)->format('F j, Y, g:i A'));
    }
    protected function createdAt(): Attribute
    {
        return Attribute::get(fn ($value) => Carbon::parse($value)->format('F j, Y, g:i A'));
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
