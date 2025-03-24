<?php

namespace App\Livewire;

use App\Models\Influencer;
use App\Models\InfluncersGroup;
use App\Services\InfluencerService;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Instagram extends Component
{
    public $details = [];
    public
        $platform = 'instagram',
        $category = null,
        $categoryBusiness = null,
        $isVerified = null,
        $isBusinessAccount = null,
        $isPrivateAccount = null,
        $followers = 10000,
        $engageRate = null,
        $country = null,
        $lang = null,
        $hasEmail = null,
        $hashtags = null,
        $topic = null,
        $niche = null;

    public $selectedGroups = [], $selectInfluencer;
    public $groups, $name, $description;

    public $followersRange = '',
        $minRange,
        $maxRange;


    public function mount()
    {
        $this->followers = 1000;
        $this->details =  Cache::get("{$this->platform}_details" . auth()->id()) ?? [];

        $this->groups = InfluncersGroup::latest()->get();
    }

    public function getInfluencer()
    {
        $instagramConfig = [
            'apiId' => env('INFLUENCER_API'),
            'searchEndpoint' => 'https://dev.creatordb.app/v2/instagramAdvancedSearch',
            'detailsEndpoint' => 'https://dev.creatordb.app/v2/instagramBasic',
        ];

        $service = new InfluencerService($instagramConfig);
        // Fetch details for instagram influencers
        $this->followers = 4000;

        $this->details = $service->fetchPlatformInfluencerDetails(
            $this->platform,
            $this->category,
            $this->categoryBusiness,
            $this->isVerified,
            $this->isBusinessAccount,
            $this->isPrivateAccount,
            $this->followers = $this->getFiltersByRange(),
            $this->engageRate,
            $this->country,
            $this->lang,
            $this->hasEmail,
            strtolower($this->hashtags),
            $this->topic,
            $this->niche,

        );

        // dd($this->details);
        return $this->details;
    }

    public function resetData()
    {

        Cache::forget("{$this->platform}_details" . auth()->id());
        $this->dispatch('refreshPage');
        return;
    }



    public function creatGroup()
    {
        $validateData = $this->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);

        $user = auth()->user();
        $user->influncersGroups()->create($validateData);

        $this->groups = InfluncersGroup::latest()->get();
        $this->name = '';
        $this->description = '';
    }

    public function setInfluencer($influencer)
    {
        $this->selectInfluencer = is_string($influencer) ? json_decode($influencer, true) : $influencer;

        logger($this->selectInfluencer);
    }
    public function addToGrop()
    {
        $validatedData = $this->validate([
            'selectedGroups' =>  'required|array',
            'selectInfluencer' =>  'required',
        ]);

        foreach ($validatedData['selectedGroups'] as $groupId) {
            Influencer::create([
                'influncers_group_id' => $groupId,
                'influnencer_id' => $validatedData['selectInfluencer']['instagramId'],
                'platform' => 'instagram',
                'content' => json_encode($validatedData['selectInfluencer']),
            ]);
        }
    }



    public function getFiltersByRange()
    {
        $filters = [];

        // Apply custom range if both min and max are provided
        if ($this->minRange && $this->maxRange) {
            $filters[] = ["filterKey" => "followers", "op" => ">", "value" => (int)$this->minRange];
            $filters[] = ["filterKey" => "followers", "op" => "<", "value" => (int)$this->maxRange];
        }
        // If no custom range, apply dropdown range
        else {
            switch ($this->followersRange) {
                case '0-10000':
                    $filters[] = ["filterKey" => "followers", "op" => "<", "value" => 10000];
                    break;
                case '10000-50000':
                    $filters[] = ["filterKey" => "followers", "op" => ">", "value" => 10000];
                    $filters[] = ["filterKey" => "followers", "op" => "<", "value" => 50000];
                    break;
                case '50000-500000':
                    $filters[] = ["filterKey" => "followers", "op" => ">", "value" => 50000];
                    $filters[] = ["filterKey" => "followers", "op" => "<", "value" => 500000];
                    break;
                case '500000-1000000':
                    $filters[] = ["filterKey" => "followers", "op" => ">", "value" => 500000];
                    $filters[] = ["filterKey" => "followers", "op" => "<", "value" => 1000000];
                    break;
                case '1000000+':
                    $filters[] = ["filterKey" => "followers", "op" => ">", "value" => 1000000];
                    break;
                default:
                    $filters[] = ["filterKey" => "followers", "op" => ">", "value" => 10000];
                    $filters[] = ["filterKey" => "followers", "op" => "<", "value" => 50000];
                    break;
            }
        }

        // Output the filters for debugging
        return $filters;
    }


    public function render()
    {
        return view('livewire.instagram');
    }
}
