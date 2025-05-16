<?php

namespace App\Livewire;

use App\Models\Influencer;
use App\Models\InfluencersGroup;
use App\Services\InfluencerService;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Youtube extends Component
{
    public $details = [];
    public
        $platform = 'youtube',
        $category = null,
        $categoryBusiness = null,
        $isVerified = null,
        $isBusinessAccount = null,
        $isPrivateAccount = null,
        $followers = 10000,
        $engageRate = null,
        $country = null,
        $hasEmail = null,
        $lang = null,
        $hashtags = null,
        $topic = null,
        $niche = null;

    public $selectedGroups = [], $selectInfluencer;
    public $groups, $name, $description;

    public $subscribersRange = '',
        $minRange,
        $maxRange;


    public function mount()
    {
        $this->followers = 1000;
        $this->details =  Cache::get("{$this->platform}_details" . auth()->id()) ?? [];

        // dd($this->details);
        // $this->groups = InfluencersGroup::latest()->get();

        
        $this->groups = Cache::remember('influencer_groups_'. auth()->id(), now()->addMinutes(10), function () {
            return InfluencersGroup::latest()->get();
        });
    }

    public function getInfluencer()
    {
        $youtubeConfig = [
            'apiId' => env('INFLUENCER_API'),
            'searchEndpoint' => 'https://dev.creatordb.app/v2/youtubeAdvancedSearch',
            'detailsEndpoint' => 'https://dev.creatordb.app/v2/youtubeBasic',
        ];

        $service = new InfluencerService($youtubeConfig);
        // Fetch details for youtube influencers
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
            $this->hashtags,
            $this->topic,
            $this->niche,

        );

        // dd($this->details);
        return $this->details ?? [];
    }


    public function resetData()
    {

        // Cache::forget("{$this->platform}_details" . auth()->id());
        $this->details = [];
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
        $user->influencersGroups()->create($validateData);

        $this->groups = InfluencersGroup::latest()->get();
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
        dd($this->selectedGroups);

        foreach ($validatedData['selectedGroups'] as $groupId) {
            Influencer::create([
                'influencers_group_id' => $groupId,
                'influencer_id' => $validatedData['selectInfluencer']['youtubeId'],
                'platform' => 'youtube',
                'content' => json_encode($validatedData['selectInfluencer']),
            ]);
        }
    }



    public function getFiltersByRange()
    {
        $filters = [];

        // Apply custom range if both min and max are provided
        if ($this->minRange && $this->maxRange) {
            $filters[] = ["filterKey" => "subscribers", "op" => ">", "value" => (int)$this->minRange];
            $filters[] = ["filterKey" => "subscribers", "op" => "<", "value" => (int)$this->maxRange];
        }
        // If no custom range, apply dropdown range
        else {
            switch ($this->subscribersRange) {
                case '0-10000':
                    $filters[] = ["filterKey" => "subscribers", "op" => "<", "value" => 10000];
                    break;
                case '10000-50000':
                    $filters[] = ["filterKey" => "subscribers", "op" => ">", "value" => 10000];
                    $filters[] = ["filterKey" => "subscribers", "op" => "<", "value" => 50000];
                    break;
                case '50000-500000':
                    $filters[] = ["filterKey" => "subscribers", "op" => ">", "value" => 50000];
                    $filters[] = ["filterKey" => "subscribers", "op" => "<", "value" => 500000];
                    break;
                case '500000-1000000':
                    $filters[] = ["filterKey" => "subscribers", "op" => ">", "value" => 500000];
                    $filters[] = ["filterKey" => "subscribers", "op" => "<", "value" => 1000000];
                    break;
                case '1000000+':
                    $filters[] = ["filterKey" => "subscribers", "op" => ">", "value" => 1000000];
                    break;
                default:
                $filters[] = ["filterKey" => "subscribers", "op" => "<", "value" => 10000];
                    break;
            }
        }

        // Output the filters for debugging
        return $filters;
    }


    public function render()
    {
        return view('livewire.youtube');
    }
}
