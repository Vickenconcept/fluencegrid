<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CampaignInquiry;
use Illuminate\Http\Request;

class CampaignInquiryController extends Controller
{
    public function sendInquiry($campaignId, $influencerId)
    {
        CampaignInquiry::create([
            'campaign_id' => $campaignId,
            'influencer_id' => $influencerId,
            'status' => 'Pending',
        ]);

        // Add email sending logic here.

        return redirect()->back()->with('success', 'Inquiry sent successfully.');
    }
}
