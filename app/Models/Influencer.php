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
}
