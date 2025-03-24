<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use App\Jobs\FetchInfluencerDetailsJob;
use Illuminate\Support\Facades\Log;

class InfluencerService
{
    private $client;
    private $config;

    public function __construct(array $config)
    {
        $this->client = new Client();
        $this->config = $config;
    }

    public function searchInfluencers(
        $category = null,
        $categoryBusiness = null,
        $isVerified = null,
        $isBusinessAccount = null,
        $isPrivateAccount = null,
        $followers = 1000,
        $engageRate = null,
        $country = null,
        $lang = null,
        $hasEmail = null,
        $hashtags = null,
        $topic = null,
        $niche = null,
        $platform = null,
    ) {

        $filters = $this->filtersOption(
            $category,
            $categoryBusiness,
            $isVerified,
            $isBusinessAccount,
            $isPrivateAccount,
            $followers,
            $engageRate,
            $country,
            $lang,
            $hasEmail,
            $hashtags,
            $topic,
            $niche,
        );


        //dd($filters);
        $maxResults = 10;
        $sortBy = $platform == 'youtube' ? 'subscribers' : 'followers';
        $response = $this->client->request('POST', $this->config['searchEndpoint'], [
            'body' => json_encode([
                "maxResults" => min($maxResults, 10),
                "sortBy" => $sortBy,
                // "sortBy" => "followers",
                "offset" => 0,
                "desc" => true,
                "filters" => $filters,
                // "filters" => [
                //     [
                //         "filterKey" => "followers",
                //         "op" => ">",
                //         "value" => 1000,
                //     ]
                // ],

            ]),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'apiId' => $this->config['apiId'],
            ],
        ]);

        $searchResults = json_decode($response->getBody(), true);

        // dd($searchResults['data']);
        return $searchResults['data'] ?? [];
    }

    public function fetchPlatformInfluencerDetails(
        $platform,
        $category,
        $categoryBusiness,
        $isVerified,
        $isBusinessAccount,
        $isPrivateAccount,
        $followers,
        $engageRate,
        $country,
        $lang,
        $hasEmail = null,
        $hashtags,
        $topic,
        $niche,
    ) {
        $cacheKey = "{$platform}_details" . auth()->id();

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }


        // Step 1: Search for influencers and get their IDs
        $influencers = $this->searchInfluencers(
            $category,
            $categoryBusiness,
            $isVerified,
            $isBusinessAccount,
            $isPrivateAccount,
            $followers,
            $engageRate,
            $country,
            $lang,
            $hasEmail,
            $hashtags,
            $topic,
            $niche,
            $platform,
        );

        $platformIds = $influencers;
        if (empty($platformIds)) {
            return [];
        }

        // Step 2: Fetch the first 5 details synchronously
        $firstBatch = array_splice($platformIds, 0, 5);
        $firstDetails = [];
        foreach ($firstBatch as $platformId) {
            try {
                $firstDetails[] = $this->fetchDetailById($platformId, $platform);
            } catch (\Exception $e) {
                \Log::error("Error fetching details for ID $platformId on platform $platform: " . $e->getMessage());
            }
        }

        Cache::put($cacheKey, $firstDetails, now()->addDays(30));

        FetchInfluencerDetailsJob::dispatch($platformIds, $cacheKey, $this->config, $platform)
            ->onQueue('influencer-details');


        return Cache::get("{$platform}_details" . auth()->id());
        // return $firstDetails;
    }

    /**
     * Fetch details for a specific ID.
     *
     * @param string $platformId
     * @param string $platform
     * @return array
     */
    private function fetchDetailById($platformId, $platform)
    {
        $response = $this->client->request('GET', $this->config['detailsEndpoint'], [
            'headers' => [
                'Accept' => 'application/json',
                'apiId' => $this->config['apiId'],
            ],
            'query' => [
                "{$platform}Id" => $platformId,
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);


        $platformKey = '';

        if ($platform == 'tiktok') {
            $platformKey = 'basic' . str_replace('t', 'T', $platform);
        } else {
            $platformKey =  'basic' . ucfirst($platform);
        }




        // foreach (['avatar', 'cover'] as $key) {
        //     if (isset($responseData['data'][$platformKey][$key])) {
        //         $imageUrl = $responseData['data'][$platformKey][$key];

        //         if (!empty($imageUrl) && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
        //             try {
        //                 $imageData = @file_get_contents($imageUrl);

        //                 if ($imageData !== false) {
        //                     $base64Image = base64_encode($imageData);
        //                     $responseData['data'][$platformKey][$key] = 'data:image/jpeg;base64,' . $base64Image;
        //                 } else {
        //                     Log::warning("Failed to fetch image data for {$key} from {$imageUrl}");
        //                     $responseData['data'][$platformKey][$key] = 'https://i.pravatar.cc/300';
        //                 }
        //             } catch (\Exception $e) {
        //                 Log::error("Error fetching image for {$key}: {$e->getMessage()} from {$imageUrl}");
        //                 $responseData['data'][$platformKey][$key] = 'https://i.pravatar.cc/300';
        //             }
        //         } else {
        //             Log::warning("Invalid or empty URL for {$key}: {$imageUrl}");
        //             $responseData['data'][$platformKey][$key] = 'https://i.pravatar.cc/300';
        //         }
        //     }
        // }

        foreach (['avatar', 'cover'] as $key) {
            if (isset($responseData['data'][$platformKey][$key])) {
                $imageUrl = $responseData['data'][$platformKey][$key];

                if (!empty($imageUrl) && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                    try {
                        $imageData = @file_get_contents($imageUrl);

                        if ($imageData !== false) {
                            $base64Image = base64_encode($imageData);
                            $responseData['data'][$platformKey][$key] = 'data:image/jpeg;base64,' . $base64Image;
                        } else {
                            Log::warning("Failed to fetch image data for {$key} from {$imageUrl}");
                            // Randomly select a number between 300 and 800 for the fallback URL
                            $randomSize = rand(300, 800);
                            $responseData['data'][$platformKey][$key] = "https://i.pravatar.cc/{$randomSize}";
                        }
                    } catch (\Exception $e) {
                        Log::error("Error fetching image for {$key}: {$e->getMessage()} from {$imageUrl}");
                        // Randomly select a number between 300 and 800 for the fallback URL
                        $randomSize = rand(300, 800);
                        $responseData['data'][$platformKey][$key] = "https://i.pravatar.cc/{$randomSize}";
                    }
                } else {
                    Log::warning("Invalid or empty URL for {$key}: {$imageUrl}");
                    // Randomly select a number between 300 and 800 for the fallback URL
                    $randomSize = rand(300, 800);
                    $responseData['data'][$platformKey][$key] = "https://i.pravatar.cc/{$randomSize}";
                }
            }
        }




        return  $responseData;
    }



    private function filtersOption(
        $category,
        $categoryBusiness,
        $isVerified,
        $isBusinessAccount,
        $isPrivateAccount,
        $followers,
        $engageRate,
        $country,
        $lang,
        $hasEmail,
        $hashtags,
        $topic,
        $niche,
    ) {
        $filters = [];

        if ($category !== null) {
            $filters[] = [
                "filterKey" => "category",
                "op" => "=",
                "value" => $category
            ];
        }

        if ($categoryBusiness !== null) {
            $filters[] = [
                "filterKey" => "categoryBusiness",
                "op" => "=",
                "value" => $categoryBusiness
            ];
        }

        if ($isVerified !== null) {
            $filters[] = [
                "filterKey" => "isVerified",
                "op" => "=",
                "value" => $isVerified
            ];
        }

        if ($isBusinessAccount !== null) {
            $filters[] = [
                "filterKey" => "isBusinessAccount",
                "op" => "=",
                "value" => $isBusinessAccount
            ];
        }

        if ($isPrivateAccount !== null) {
            $filters[] = [
                "filterKey" => "isPrivateAccount",
                "op" => "=",
                "value" => $isPrivateAccount
            ];
        }

        if ($country !== null) {
            $filters[] = [
                "filterKey" => "country",
                "op" => "=",
                "value" => $country
            ];
        }
        if ($lang !== null) {
            $filters[] = [
                "filterKey" => "lang",
                "op" => "=",
                "value" => $lang
            ];
        }
        if ($hasEmail !== null) {
            $filters[] = [
                "filterKey" => "hasEmail",
                "op" => "=",
                "value" => $hasEmail
            ];
        }

        if ($hashtags !== null) {
            $filters[] = [
                "filterKey" => "hashtags",
                "op" => "=",
                "value" => $hashtags
            ];
        }
        if ($topic !== null) {
            $filters[] = [
                "filterKey" => "topic",
                "op" => "=",
                "value" => $topic
            ];
        }
        if ($niche !== null) {
            $filters[] = [
                "filterKey" => "niche",
                "op" => "=",
                "value" => $niche
            ];
        }

        if ($followers !== null) {
            $filters = array_merge($filters, $followers);
        }

        return $filters;
    }
}
