<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Influencer extends Model
{
    protected $guarded = [];
    
    public function inquiries()
    {
        return $this->hasMany(CampaignInquiry::class);
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_influencer')
            ->withPivot('task_status'); 
    }

}
