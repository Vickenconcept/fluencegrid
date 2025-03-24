<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FacebookInfluencerService
{
    private $client;
    private $apiId;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiId = 'b3e3a97d2e6f09e2-F1vuybqkFX8mLk3wBbNR'; // Replace with your valid API ID
    }

    public function searchInfluencers($maxResults = 50, $flowersCount = 1000)
    {
        $response = $this->client->request('POST', 'https://dev.creatordb.app/v2/facebookAdvancedSearch', [
            'body' => json_encode([
                "maxResults" => min($maxResults, 50), // Limit max results to 50
                "sortBy" => "followers",
                "offset" => 0,
                "desc" => true,
                "filters" => [
                    [
                        "filterKey" => "followers",
                        "op" => ">",
                        "value" => $flowersCount,
                    ]
                ],
            ]),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'apiId' => $this->apiId,
            ],
        ]);

        $searchResults = json_decode($response->getBody(), true);
        
        return $searchResults['data'] ?? []; // Extract Facebook IDs
    }

    // public function fetchInfluencerDetails($facebookIds)
    // {
    //     if (empty($facebookIds)) {
    //         return [];
    //     }

    //     // Fetch the first influencer's details
    //     $firstId = array_shift($facebookIds);
    //     $firstDetail = $this->fetchDetailById($firstId);

    //     // Store the first result in the cache
    //     $details = [$firstDetail];
    //     Cache::put('facebook_influencer_details', $details, now()->addDays(30));

    //     // Fetch the rest asynchronously
    //     dispatch(function () use ($facebookIds) {
    //         foreach ($facebookIds as $facebookId) {
    //             try {
    //                 $detail = $this->fetchDetailById($facebookId);
    //                 $cachedDetails = Cache::get('facebook_influencer_details', []);
    //                 $cachedDetails[] = $detail;

    //                 // Update the cache
    //                 Cache::put('facebook_influencer_details', $cachedDetails, now()->addDays(30));
    //             } catch (\Exception $e) {
    //                 Log::error("Error fetching details for ID $facebookId: " . $e->getMessage());
    //             }
    //         }
    //     });

    //     return $details;
    // }

    public function fetchInfluencerDetails($facebookIds)
    {
        if (empty($facebookIds)) {
            return [];
        }

        // Fetch the first 10 influencer details
        $firstBatch = array_splice($facebookIds, 0, 10); // Get the first 10 IDs
        $firstDetails = [];
        foreach ($firstBatch as $facebookId) {
            try {
                $firstDetails[] = $this->fetchDetailById($facebookId);
            } catch (\Exception $e) {
                Log::error("Error fetching details for ID $facebookId: " . $e->getMessage());
            }
        }
        
        // Store the first 10 results in the cache
        Cache::put('facebook_influencer_details', $firstDetails, now()->addDays(30));
        
        // Fetch the rest asynchronously
        dispatch(function () use ($facebookIds) {
            foreach ($facebookIds as $facebookId) {
                try {
                    $detail = $this->fetchDetailById($facebookId);
                    $cachedDetails = Cache::get('facebook_influencer_details', []);
                    $cachedDetails[] = $detail;
                    
                    // Update the cache
                    Cache::put('facebook_influencer_details', $cachedDetails, now()->addDays(30));
                } catch (\Exception $e) {
                    Log::error("Error fetching details for ID $facebookId: " . $e->getMessage());
                }
            }
        });
        dd(Cache::get('facebook_influencer_details'));

        return $firstDetails;
    }


    private function fetchDetailById($facebookId)
    {
        $response = $this->client->request('GET', 'https://dev.creatordb.app/v2/facebookBasic', [
            'headers' => [
                'Accept' => 'application/json',
                'apiId' => $this->apiId,
            ],
            'query' => [
                'facebookId' => $facebookId,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
