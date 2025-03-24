<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;



class FetchInfluencerDetailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $platformIds;
    private $cacheKey;
    private $config;
    private $platform;

    public function __construct($platformIds, $cacheKey, $config, $platform)
    {
        $this->platformIds = $platformIds;
        $this->cacheKey = $cacheKey;
        $this->config = $config;
        $this->platform = $platform;
    }

    public function handle()
    {
        $client = new Client();

        foreach ($this->platformIds as $platformId) {
            try {
                // Make the GET request to fetch the details
                $response = $client->request('GET', $this->config['detailsEndpoint'], [
                    'headers' => [
                        'Accept' => 'application/json',
                        'apiId' => $this->config['apiId'],
                    ],
                    'query' => [
                        "{$this->platform}Id" => $platformId,
                    ],
                ]);

                // Decode the response into an array
                // $detail = json_decode($response->getBody(), true);

                $rawBody = $response->getBody();

                $detail = json_decode($rawBody, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('JSON decoding failed:', ['error' => json_last_error_msg()]);
                    return;
                }

                // Log::info('Decoded response detail:', ['detail' => $detail]);


                $platformKey = '';

                if ($this->platform == 'tiktok') {
                    $platformKey = 'basic' . str_replace('t', 'T', $this->platform);
                } else {
                    $platformKey =  'basic' . ucfirst($this->platform);
                }




                // foreach (['avatar', 'cover'] as $key) {
                //     if (isset($detail['data'][$platformKey][$key])) {
                //         $imageUrl = $detail['data'][$platformKey][$key];

                //         if (!empty($imageUrl) && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                //             Log::info("Processing image URL for {$key}: {$imageUrl}");

                //             try {
                //                 $imageData = file_get_contents($imageUrl);

                //                 if ($imageData !== false) {
                //                     $imageType = mime_content_type($imageUrl) ?: 'image/jpeg'; 
                //                     $base64Image = base64_encode($imageData);

                //                     $detail['data'][$platformKey][$key] = 'data:' . $imageType . ';base64,' . $base64Image;

                //                     Log::info("Base64 encoded image for {$key} on platform {$this->platform}");
                //                 } else {
                //                     Log::warning("Failed to fetch image data from URL for {$key}: {$imageUrl}");
                //                 }
                //             } catch (\Exception $e) {
                //                 Log::error("Error fetching image: {$e->getMessage()} for {$key} on platform {$this->platform}");
                //             }
                //         } else {
                //             Log::warning("Invalid or empty image URL for {$key}: {$imageUrl}");
                //         }
                //     }
                // }




                // foreach (['avatar', 'cover'] as $key) {
                //     if (isset($detail['data'][$platformKey][$key])) {
                //         $imageUrl = $detail['data'][$platformKey][$key];

                //         if (!empty($imageUrl) && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                //             Log::info("Processing image URL for {$key}: {$imageUrl}");

                //             try {
                //                 $imageData = @file_get_contents($imageUrl);

                //                 if ($imageData !== false) {
                //                     $imageType = @mime_content_type($imageUrl) ?: 'image/jpeg';
                //                     $base64Image = base64_encode($imageData);
                //                     $detail['data'][$platformKey][$key] = 'data:' . $imageType . ';base64,' . $base64Image;
                //                     Log::info("Base64 encoded image for {$key} on platform {$this->platform}");
                //                 } else {
                //                     Log::warning("Failed to fetch image data from URL for {$key}: {$imageUrl}");

                //                     $detail['data'][$platformKey][$key] = 'https://i.pravatar.cc/300';

                //                     Log::warning("After setting fallback: {$key} = " . json_encode($detail['data'][$platformKey][$key]));
                //                 }
                //             } catch (\Exception $e) {
                //                 Log::error("Error fetching image: {$e->getMessage()} for {$key} on platform {$this->platform}");

                //                 $detail['data'][$platformKey][$key] = 'https://i.pravatar.cc/300';

                //                 Log::warning("After setting fallback (Exception): {$key} = " . json_encode($detail['data'][$platformKey][$key]));
                //             }
                //         } else {
                //             Log::warning("Invalid or empty image URL for {$key}: {$imageUrl}");

                //             $detail['data'][$platformKey][$key] = 'https://i.pravatar.cc/300';

                //             Log::warning("After setting fallback (Invalid URL): {$key} = " . json_encode($detail['data'][$platformKey][$key]));
                //         }
                //     }
                // }


                foreach (['avatar', 'cover'] as $key) {
                    if (isset($detail['data'][$platformKey][$key])) {
                        $imageUrl = $detail['data'][$platformKey][$key];

                        if (!empty($imageUrl) && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                            Log::info("Processing image URL for {$key}: {$imageUrl}");

                            try {
                                $imageData = @file_get_contents($imageUrl);

                                if ($imageData !== false) {
                                    $imageType = @mime_content_type($imageUrl) ?: 'image/jpeg';
                                    $base64Image = base64_encode($imageData);
                                    $detail['data'][$platformKey][$key] = 'data:' . $imageType . ';base64,' . $base64Image;
                                    Log::info("Base64 encoded image for {$key} on platform {$this->platform}");
                                } else {
                                    Log::warning("Failed to fetch image data from URL for {$key}: {$imageUrl}");

                                    // Randomly select a size between 300 and 800
                                    $randomSize = rand(300, 800);
                                    $detail['data'][$platformKey][$key] = "https://i.pravatar.cc/{$randomSize}";

                                    Log::warning("After setting fallback: {$key} = " . json_encode($detail['data'][$platformKey][$key]));
                                }
                            } catch (\Exception $e) {
                                Log::error("Error fetching image: {$e->getMessage()} for {$key} on platform {$this->platform}");

                                // Randomly select a size between 300 and 800
                                $randomSize = rand(300, 800);
                                $detail['data'][$platformKey][$key] = "https://i.pravatar.cc/{$randomSize}";

                                Log::warning("After setting fallback (Exception): {$key} = " . json_encode($detail['data'][$platformKey][$key]));
                            }
                        } else {
                            Log::warning("Invalid or empty image URL for {$key}: {$imageUrl}");

                            // Randomly select a size between 300 and 800
                            $randomSize = rand(300, 800);
                            $detail['data'][$platformKey][$key] = "https://i.pravatar.cc/{$randomSize}";

                            Log::warning("After setting fallback (Invalid URL): {$key} = " . json_encode($detail['data'][$platformKey][$key]));
                        }
                    }
                }



                $cachedDetails = Cache::get($this->cacheKey, []);
                $cachedDetails[] = $detail;

                Cache::put($this->cacheKey, $cachedDetails, now()->addDays(30));
            } catch (\Exception $e) {
                Log::error("Error fetching details for ID $platformId on platform {$this->platform}: " . $e->getMessage());
            }
        }
    }
}
