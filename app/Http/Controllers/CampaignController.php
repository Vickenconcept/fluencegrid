<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Http\Controllers\Controller;
use App\Models\CampaignInquiry;
use App\Models\Conversation;
use App\Models\Influencer;
use App\Models\influencersGroup;
use App\Models\User;
use App\Notifications\CampaignResponseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CampaignController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('campaign.index');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'title' => 'required',
            'budget' => 'required',
            'description' => 'required',
            'task' => 'nullable',
            'start_date' => 'nullable',
            'invite_end_date' => 'nullable',
            'end_date' => 'nullable',
            'status' => 'nullable',
        ]);

        $validatedData['uuid'] = Str::uuid()->toString();
        $validatedData['status'] = 'active';

        $campaign = $user->campaigns()->create($validatedData);

        return back()->with('success', 'Campaign Created Successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function short_code_url($code, Request $request)
    {
        $shortenedUrl = Conversation::where('short_code', $code)->firstOrFail();

        $ip = $request->ip();
        $trackedIps = $shortenedUrl->ip_addresses ?? [];
        if (!in_array($ip, $trackedIps)) {
            $trackedIps[] = $ip;
        }

        $shortenedUrl->update([
            'clicks' => $shortenedUrl->clicks + 1,
            'ip_addresses' => $trackedIps,
        ]);

        return redirect()->to($shortenedUrl->original_url);
    }

    // public function short_code_url($code, Request $request)
    // {
    //     $shortenedUrl = Conversation::where('short_code', $code)->firstOrFail();

    //     $ip = $request->ip();
    //     $trackedIps = $shortenedUrl->ip_addresses ?? [];

    //     if (!in_array($ip, $trackedIps)) {
    //         $trackedIps[] = $ip;

    //         // Update clicks only if it's a new device
    //         $shortenedUrl->update([
    //             'clicks' => $shortenedUrl->clicks + 1,
    //             'ip_addresses' => $trackedIps,
    //         ]);
    //     }

    //     return redirect()->to($shortenedUrl->original_url);
    // }


    /**
     * Display the specified resource.
     */
    public function show($uuid)
    {
        $campaign = Campaign::whereUuid($uuid)->firstOrFail();

        return view('campaign.show', compact('campaign'));
    }



    public function viewCampaign(Request $request)
    {
        $token = $request->query('token');

        try {
            // Decrypt the token
            $data = Crypt::decryptString($token);
            parse_str($data, $params);

            $campaignId = $params['campaign_id'];
            $influencerId = $params['influencer_id'];

            // Fetch the campaign and influencer
            $campaign = Campaign::findOrFail($campaignId);
            $influencer = Influencer::findOrFail($influencerId);


            $currentStatus = $campaign->influencers()
                ->wherePivot('influencer_id', $influencerId)
                ->value('task_status');


            if ($currentStatus !== 'accepted') {
                # code...
                return view('campaign.view', compact('campaign', 'influencer'));
            } else {
                $conversation = Conversation::where('influencer_id', $influencer->id)
                    ->where('status', 'pending')
                    ->first();

                return redirect()->route('conversation.influencer', ['uuid' => $conversation->uuid]);
            }
        } catch (\Exception $e) {
            abort(404, 'Invalid or expired link.');
        }
    }

    public function recordResponse(Request $request)
    {
        $token = $request->input('token');
        $response = $request->input('response');

        // try {
        $data = Crypt::decryptString($token);
        parse_str($data, $params);

        $campaignId = $params['campaign_id'];
        $influencerId = $params['influencer_id'];

        $campaign = Campaign::findOrFail($campaignId);
        $influencer = Influencer::findOrFail($influencerId);

        $currentStatus = $campaign->influencers()
            ->wherePivot('influencer_id', $influencerId)
            ->value('task_status');

        if ($currentStatus !== 'accepted') {
            $campaign->influencers()->syncWithoutDetaching([
                $influencer->id => [
                    'task_status' => $response,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            $group = influencersGroup::with('owner')->find($influencer->influencers_group_id);
            $owner = $group?->owner;

            if ($response == 'accepted') {
                if ($owner) {
                    $owner->conversations()->create([
                        'uuid' => Str::uuid(),
                        'influencer_id' => $influencer->id,
                        'campaign_id' => $campaign->id,
                        'status' => 'pending'
                    ]);
                }
            }

            $owner->notify(new CampaignResponseNotification($campaign, $influencer, $response));

            return view('campaign.thankyou')->with('status', 'Your response has been recorded.');
        } else {
        }
        // } catch (\Exception $e) {
        //     return response()->json(['msg' => 'message']);
        //     return redirect()->route('campaign.error')->with('error', 'Invalid or expired token.');
        // }
    }

    public function share($uuid)
    {
        $campaign = Campaign::whereUuid($uuid)->firstOrFail();

        $influencers = Influencer::all(); // Adjust this query based on your database design.

        $links = $influencers->map(function ($influencer) use ($campaign) {
            // Encrypt the campaign ID and influencer ID to generate a unique token
            $token = Crypt::encryptString("campaign_id={$campaign->id}&influencer_id={$influencer->id}");

            return [
                'influencer' => $influencer->name,
                'email' => $influencer->email,
                'link' => route('campaign.view', ['token' => $token]),
            ];
        });

        return view('campaign.share', compact('campaign', 'links'));
    }

    public function changeName(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        $campaign = Campaign::where('id', $request->input('id'))->firstOrFail();
        $campaign->title = $request->input('title');
        $campaign->update();

        return back()->with('success', 'Updated successfully');
    }

    public function campaign_response()
    {
        return view('campaign.response');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campaign $campaign)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'budget' => 'required',
            'description' => 'required',
            'task' => 'nullable',
            'start_date' => 'nullable',
            'invite_end_date' => 'nullable',
            'end_date' => 'nullable',
            'status' => 'nullable',
            'type' => 'nullable',
        ]);

        $campaign->update($validatedData);

        return back()->with('success', 'Campaign Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        return back()->with('success', 'Deleted Successfully');
    }
}
