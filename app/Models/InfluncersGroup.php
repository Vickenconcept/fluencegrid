<?php

namespace App\Models;

use App\Models\Scopes\DataAccessScope;
use Illuminate\Database\Eloquent\Model;

class InfluncersGroup extends Model
{
    protected $guarded = [];



    public function user()
    {
        return $this->belongsTo(User::class); 
    }

    public function latestInfluencer()
    {
        return $this->hasOne(Influencer::class)->latest(); // Orders by 'created_at' descending by default
    }

    /**
     * Get all influencers associated with the group.
     */
    public function influencers()
    {
        return $this->hasMany(Influencer::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new DataAccessScope);

        static::deleting(function ($group) {
            $group->influencers()->delete();
        });
    }

}
