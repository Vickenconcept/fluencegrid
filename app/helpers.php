<?php

use App\Services\InfluencerService;

if (!function_exists('format_number')) {
    /**
     * Format a number into a readable string with suffixes.
     *
     * @param float|int $number The number to be formatted.
     * @return string The formatted number.
     */
    function format_number($number)
    {
        if (!is_numeric($number)) {
            return 'Invalid number';
        }

        if ($number < 1000) {
            return (string) $number;
        }

        $suffixes = [
            9  => 'B', // Billion
            6  => 'M', // Million
            3  => 'K', // Thousand
        ];

        foreach ($suffixes as $power => $suffix) {
            $divisor = pow(10, $power);

            if ($number >= $divisor) {
                $formatted = $number / $divisor;

                return (floor($formatted) == $formatted)
                    ? $formatted . $suffix
                    : number_format($formatted, 1) . $suffix;
            }
        }

        return (string) $number;
    }
}

if (!function_exists('flatten_array')) {
    function flatten_array(array $array, string $delimiter = ' | ', ?int $limit = null)
    {
        $flat = [];

        $flatten = function ($items) use (&$flatten, &$flat) {
            foreach ($items as $item) {
                if (is_array($item)) {
                    $flatten($item); // Recursive call for nested arrays
                } else {
                    $flat[] = $item;
                }
            }
        };

        $flatten($array);

        if ($limit !== null) {
            $flat = array_slice($flat, 0, $limit);
        }

        return implode($delimiter, $flat);
    }
}


if (!function_exists('getFacebook')) {
    function getFacebook()
    {
        $facebookConfig = [
            'apiId' => env('INFLUENCER_API'),
            'searchEndpoint' => 'https://dev.creatordb.app/v2/facebookAdvancedSearch',
            'detailsEndpoint' => 'https://dev.creatordb.app/v2/facebookBasic',
        ];

        $service = new InfluencerService($facebookConfig);
        // Fetch details for Facebook influencers
        $followersCount = 1000;
        $platform = 'facebook';

        return $service->fetchPlatformInfluencerDetails($platform, $followersCount, 20);
    }
}
if (!function_exists('getTiktok')) {
    function getTiktok()
    {
        $tiktokConfig = [
            'apiId' => env('INFLUENCER_API'),
            'searchEndpoint' => 'https://dev.creatordb.app/v2/tiktokAdvancedSearch',
            'detailsEndpoint' => 'https://dev.creatordb.app/v2/tiktokBasic',
        ];

        $service = new InfluencerService($tiktokConfig);
        // Fetch details for tiktok influencers
        $followersCount = 1000;
        $platform = 'tiktok';

        return $service->fetchPlatformInfluencerDetails($platform, $followersCount, 20);
    }
}
