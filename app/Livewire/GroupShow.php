<?php

namespace App\Livewire;

use App\Mail\InfluencerCampaignInvite;
use App\Mail\InfluencerCustomInvite;
use App\Models\Campaign;
use App\Models\Influencer;
use App\Models\InfluencersGroup;
use App\Services\ChatGptService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class GroupShow extends Component
{
    use WithPagination;

    public
        $group,
        $emails, $campaign, $customEmailBody, $evaluation = [];

    public $selectedInfluencer, $selectedEmail, $influencerName;
    public $selectedCampaignUuid;
    public $invitationLink, $allInvitationLinks;
    public $youtubeId;

    public $compensation, $campaignTitle, $acceptanceDeadline;

    public function mount($group)
    {
        $this->group = $group;
        // $this->influencers = $group->influencers;
    }

    public function setInfluencer($influencer_id, $emails = null)
    {
        $this->selectedInfluencer = $influencer_id;
        $this->emails = $emails;
        $this->selectedEmail = "";
    }
    public function setCampaign($data)
    {
        $this->selectedCampaignUuid = $data;
        $this->campaign = Campaign::whereUuid($this->selectedCampaignUuid)->firstOrFail();

        $influencer = Influencer::find($this->selectedInfluencer);

        if ($influencer) {
            # code...
            $content = json_decode($influencer->content, true);
            $key = "{$influencer->platform}Name";
            $this->influencerName = $content[$key];

            $this->compensation = '$' . $this->campaign->budget . ' per post';
            $this->acceptanceDeadline = $this->campaign->invite_end_date;
            $this->campaignTitle = $this->campaign->title;


            $token = Crypt::encryptString("campaign_id={$this->campaign->id}&influencer_id={$influencer->id}");
            $this->invitationLink = route('campaign.view', ['token' => $token]);
            $this->addToEditor($this->campaign->id);
        } else {
            $influencers = Influencer::where('influencers_group_id', $this->group->id)->get();
            $this->compensation = '$' . $this->campaign->budget . ' per post';
            $this->acceptanceDeadline = $this->campaign->invite_end_date;
            $this->campaignTitle = $this->campaign->title;

            foreach ($influencers as $influencer) {
                $token = Crypt::encryptString("campaign_id={$this->campaign->id}&influencer_id={$influencer->id}");
                $invitationLink = route('campaign.view', ['token' => $token]);

                // Store the unique link per influencer
                $this->allInvitationLinks[$influencer->id] = $invitationLink;
            }

            $this->addAllToEditor($this->campaign->id);
            # code...
        }
    }


    public function getEmails($influencer_id, $youtubeId = null)
    {
        $this->selectedInfluencer = $influencer_id;
        $this->youtubeId = $youtubeId;

        $client = new \GuzzleHttp\Client();

        if ($this->youtubeId != "" && $this->youtubeId != null) {
            $response = $client->request('GET', "https://dev.creatordb.app/v2/contactInformation?youtubeId={$this->youtubeId}", [
                'headers' => [
                    'Accept' => 'application/json',
                    'apiId' => env('INFLUENCER_API'),
                ],
            ]);

            $searchResults = json_decode($response->getBody(), true);

            $influencer = Influencer::find($this->selectedInfluencer);

            if ($influencer) {
                $existingEmails = json_decode($influencer->emails, true) ?? [];

                $newEmails = $searchResults['data']['emails'] ?? [];

                $mergedEmails = array_merge($existingEmails, $newEmails);
                $mergedEmails = array_values(array_unique($mergedEmails));

                $influencer->emails = json_encode($mergedEmails);

                $influencer->update();
            }

            $this->dispatch('refreshPage');
            return;
        }

        $this->dispatch('refreshPage');
        return;
    }

    public function setEmail($email)
    {
        $this->selectedEmail = $email;
    }


    public function checkContactWithAI(ChatGptService $chatGptService, $influencers_data, $influencers_id)
    {
        $data = json_decode($influencers_data, true);

        unset($data['avatar'], $data['cover']);

        $prompt = "The following data contains information about an influencer. Please extract and return any contact information (email addresses and phone numbers) as an array. If no contact information is found, return an empty array or null. Do not include any additional text or information.\n\n"
            . json_encode($data, JSON_PRETTY_PRINT);

        $response = $chatGptService->generateContent($prompt);

        $response = trim($response, "\"`json");

        $contactInfo = json_decode($response, true);
        if (is_array($contactInfo) && !empty($contactInfo)) {
            $emails = [];
            $numbers = [];

            foreach ($contactInfo as $contact) {
                if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
                    $emails[] = $contact;
                } elseif (preg_match('/^[0-9]{10,15}$/', $contact)) {
                    $numbers[] = $contact;
                }
            }

            $influencer = Influencer::find($influencers_id);

            if ($influencer) {
                $updated = false;

                if (!empty($emails)) {
                    $existingEmails = json_decode($influencer->emails, true) ?? [];
                    $mergedEmails = array_merge($existingEmails, $emails);
                    $influencer->emails = json_encode(array_values(array_unique($mergedEmails)));
                    $updated = true;
                } else {
                    $influencer->emails = json_encode([]);
                    $updated = true;
                }

                if (!empty($numbers)) {
                    $existingNumbers = json_decode($influencer->phone_numbers, true) ?? [];
                    $mergedNumbers = array_merge($existingNumbers, $numbers);
                    $influencer->phone_numbers = json_encode(array_values(array_unique($mergedNumbers)));
                    $updated = true;
                } else {
                    $influencer->phone_numbers = json_encode([]);
                    $updated = true;
                }

                if ($updated) {
                    $influencer->update();
                }
            }
        } else {
            // $influencer = Influencer::find($influencers_id);
            // if ($influencer) {
            //     $influencer->emails = json_encode([]);
            //     $influencer->phone_numbers = json_encode([]);
            //     $influencer->update();
            // }
        }

        $this->dispatch('refreshPage');
    }


    public function sendCampaignInvite()
    {
        $brandName = null;
        $targetAudience = null;
        // $targetAudience = 'Young Adults (18-25)';

        if ($this->selectedEmail == 'null' || $this->selectedEmail == "") {
            $this->dispatch('email-sent', status: 'error', msg: 'Failed: Select email');
            return;
        }


        Mail::to('vicken408@gmail.com')->send(new InfluencerCampaignInvite(
            $this->influencerName,
            $this->campaignTitle,
            $brandName,
            $targetAudience,
            $this->compensation,
            $this->acceptanceDeadline,
            $this->invitationLink
        ));
        $this->dispatch('email-sent', status: 'success',  msg: 'Email set successfully');

        return;
    }
    public function sendCustomCampaignInvite()
    {
        if ($this->selectedEmail == 'null' || $this->selectedEmail == "") {
            $this->dispatch('email-sent', status: 'error', msg: 'Failed: Select email');
            return;
        }


        Mail::to('vicken408@gmail.com')->send(new InfluencerCustomInvite(
            $this->customEmailBody,
        ));

        $this->dispatch('email-sent', status: 'success',  msg: 'Email set successfully');
        return;
    }
    // public function sendInviteToAll()
    // {
    //     $influencers = Influencer::where('influencers_group_id', $this->group->id)->get();

    //     dd($this->allInvitationLinks);

    //     Mail::to('vicken408@gmail.com')->send(new InfluencerCustomInvite(
    //         $this->customEmailBody,
    //     ));

    //     $this->dispatch('email-sent', status: 'success',  msg: 'Email set successfully');
    //     return;
    // }

    public function sendInviteToAll()
    {
        $influencers = Influencer::where('influencers_group_id', $this->group->id)->get();

        foreach ($influencers as $influencer) {
            $emails = json_decode($influencer->emails, true); // Decode JSON emails column
            $invitationLink = $this->allInvitationLinks[$influencer->id] ?? null; // Get the unique link

            if ($invitationLink && is_array($emails)) {
                foreach ($emails as $email) {
                    Mail::to('vicken408@gmail.com')->queue(new InfluencerCustomInvite($this->customEmailBody, $invitationLink));
                    // Mail::to($email)->send(new InfluencerCustomInvite($this->customEmailBody, $invitationLink));
                }
            }
        }

        $this->dispatch('email-sent', status: 'success', msg: 'Emails sent successfully');
    }



    public function addToEditor($id)
    {

        $this->customEmailBody = "<div class='px-3 py-5 bg-gray-100  '>
                                            <p class='text-xl font-semibold mb-3'>Hi $this->influencerName,</p>
                                            <p>We’re thrilled to invite you to join an exclusive campaign
                                                that’s perfectly tailored to your unique influence and
                                                style. Here’s a quick overview of the campaign:</p>
                                            <ul class='py-5'>
                                                <li>
                                                    <strong style='margin-right: 6px;'>&#10003;</strong>
                                                    <strong>🌟 Campaign Name:</strong> $this->campaignTitle
                                                </li>
                                                <li>
                                                    <strong style='margin-right: 6px;'>&#10003;</strong>
                                                    <strong>💰 Compensation:</strong> $this->compensation
                                                </li>
                                                <li>
                                                    <strong style='margin-right: 6px;'>&#10003;</strong>
                                                    <strong>🗓️ Acceptance Deadline:</strong> $this->acceptanceDeadline
                                                </li>
                                            </ul>

                                            <p class='mb-4'>If you’re ready to showcase your talent and make an
                                                impact,
                                                simply click the link below to review and accept the
                                                campaign details:</p>

                                            <a href=" . "'" . $this->invitationLink . "'" . " style=' display: inline-block; background-color: #007BFF; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; '  >👉 Accept the Campaign Now</a>

                                            <p class='my-4'>We’re excited to work with you and can’t wait to see the
                                                amazing content you’ll create for this campaign. If you
                                                have any questions or need more details, don’t hesitate to reach out to
                                                us.
                                            </p>

                                            <p style='font-size: 16px; font-family: sans-serif;'>Best regards,<br>
                                                <strong>" . env('APP_NAME') . " Team</strong>
                                            </p>
                                        </div>";

        $this->dispatch('addToEditor',  content: $this->customEmailBody, id: $id);
    }

    public function addAllToEditor($id)
    {

        $this->customEmailBody = "<div class='px-3 py-5 bg-gray-100  '>
                                            <p class='text-xl font-semibold mb-3'>Hi $this->influencerName,</p>
                                            <p>We’re thrilled to invite you to join an exclusive campaign
                                                that’s perfectly tailored to your unique influence and
                                                style. Here’s a quick overview of the campaign:</p>
                                            <ul class='py-5'>
                                                <li>
                                                    <strong style='margin-right: 6px;'>&#10003;</strong>
                                                    <strong>🌟 Campaign Name:</strong> $this->campaignTitle
                                                </li>
                                                <li>
                                                    <strong style='margin-right: 6px;'>&#10003;</strong>
                                                    <strong>💰 Compensation:</strong> $this->compensation
                                                </li>
                                                <li>
                                                    <strong style='margin-right: 6px;'>&#10003;</strong>
                                                    <strong>🗓️ Acceptance Deadline:</strong> $this->acceptanceDeadline
                                                </li>
                                            </ul>

                                            <p class='my-4'>We’re excited to work with you and can’t wait to see the
                                                amazing content you’ll create for this campaign. If you
                                                have any questions or need more details, don’t hesitate to reach out to
                                                us.
                                            </p>

                                            <p style='font-size: 16px; font-family: sans-serif;'>Best regards,<br>
                                                <strong>" . env('APP_NAME') . " Team</strong>
                                            </p>
                                        </div>";

        $this->dispatch('addAllToEditor',  content: $this->customEmailBody, id: $id);
    }


    public function evaluateinfluencerWithAI(ChatGptService $chatGptService, $influencer_id, $influencers_data)
    {

        unset($influencers_data['avatar'], $influencers_data['cover']);

        $prompt = "Evaluate this influencer's data and extract the key metrics that a user can use to determine how well the influencer is performing. This will help the user decide whether to engage in business with the influencer.

        Return the result as an associative array with two main keys:
        1. `evaluation`: An associative array containing key metrics (e.g., engagement ratio, follower count, etc.).
        2. `video_or_post`: An array of associative arrays, each containing details about the influencer's posts (e.g., URL, likes, views).

        Here is the influencer's data:
        " . json_encode($influencers_data, JSON_PRETTY_PRINT) . "

        Format the output like this:
        {
        \"evaluation\": {
            \"engagement_ratio\": \"12%\",
            \"follower_count\": 100000,
            \"average_likes\": 5000,
            \"average_views\": 20000,
            \"audience_demographics\": {
            \"age_range\": \"18-34\",
            \"location\": \"United States\"
            }
        },
        \"video_or_post\": [
            {
            \"url\": \"https://example.com/post1\",
            \"likes\": 1000,
            \"views\": 10000
            },
            {
            \"url\": \"https://example.com/post2\",
            \"likes\": 1500,
            \"views\": 12000
            }
        ]
        }, Don't add any additionaly sentence or phrase just the object ";


        $response = $chatGptService->generateContent($prompt);
        // $this->evaluation = json_decode($response, true);

        $response = trim($response, "\"`json");

        $cacheKey = "inflencer_{$influencer_id}_user_" . auth()->id();

        Cache::put($cacheKey, json_decode($response, true), now()->addDays(30));
        $this->evaluation =  Cache::get($cacheKey, []);
    }
    public function getEveluatedData($influencer_id)
    {

        $cacheKey = "inflencer_{$influencer_id}_user_" . auth()->id();
        $this->evaluation =  Cache::get($cacheKey, []);
    }
    // public function submit()
    // {

    //     $rawContent = $this->customEmailBody;

    //     $cleanedContent = stripslashes($rawContent);
    //     $cleanedContent = str_replace(['"', '\n', '\r'], '', $cleanedContent);

    //     $this->customEmailBody = $cleanedContent;
    // }
    public function render()
    {
        $campaigns = Campaign::latest()->get();
        $influencers = $this->group->influencers()->paginate(10);
        return view('livewire.group-show', compact('influencers', 'campaigns'));
    }
}
