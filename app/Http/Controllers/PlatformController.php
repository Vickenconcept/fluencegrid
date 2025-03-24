<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public function platform($platform)
    {
        $availablePlatforms = Platform::pluck('name')->map(fn($name) => strtolower($name))->toArray();

        if (in_array(strtolower($platform), $availablePlatforms)) {
            return view('platform', ['platform' => $platform]);
        }

        return abort(404, 'Platform not found');
    }
}
