<?php

namespace App\Models;

use App\Models\Scopes\DataAccessScope;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{

    protected $guarded = [
        // 'title',
        // 'description',
        // 'task',
        // 'start_date',
        // 'end_date',
        // 'status'
    ];

    public function influencers()
    {
        return $this->belongsToMany(Influencer::class, 'campaign_influencer')
            ->withPivot('task_status'); 
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DataAccessScope);
    }
}
