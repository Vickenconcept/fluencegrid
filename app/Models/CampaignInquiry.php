<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignInquiry extends Model
{
    protected $guarded = [];

    public function influencer()
    {
        return $this->belongsTo(Influencer::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
