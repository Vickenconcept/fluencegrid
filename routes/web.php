<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CampaignInquiryController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\InfluencerController;
use App\Http\Controllers\InfluncersGroupController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResellerController;
use App\Models\Influencer;
use App\Services\FacebookInfluencerService;
use App\Services\InfluencerService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;

Route::get('/', function () {
    return view('welcome');
});




Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::view('register', 'auth.register')->name('register');
    Route::view('register/success', 'auth.success')->name('register.success');
    // Route::view('detail', 'auth.web-detail')->name('detail');


    Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function () {
        Route::post('/register', 'register')->name('register');
        Route::post('/login', 'login')->name('login');
    });
    Route::controller(PasswordResetController::class)->group(function () {
        Route::get('forgot-password', 'index')->name('password.request');
        Route::post('forgot-password', 'store')->name('password.email');
        Route::get('/reset-password/{token}', 'reset')->name('password.reset');
        Route::post('/reset-password', 'update')->name('password.update');
    });
});

Route::get('campaigns/share/{campaign}', [CampaignController::class, 'share'])->name('campaign.share');
Route::get('campaigns/view', [CampaignController::class, 'viewCampaign'])->name('campaign.view');
Route::post('campaigns/respond', [CampaignController::class, 'recordResponse'])->name('campaign.respond');

Route::get('conversation/{uuid}/influencer', [ConversationController::class, 'influencer_chat'])->name('conversation.influencer');





Route::middleware(['auth'])->group(function () {
    Route::get('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/home', function () {
        return view('dashboard');
    })->name('home');

    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    // Mark all notifications as read
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    Route::view('profile', 'profile')->name('profile');
    Route::post('profile/name', [ProfileController::class, 'changeName'])->name('changeName');
    Route::post('profile/password', [ProfileController::class, 'changePassword'])->name('changePassword');

    Route::get('platform/{platform}', [PlatformController::class, 'platform'])->name('platform.search');
    Route::get('creator-profile/{influencer_id}', function ($influencer_id) {
        return view('influencer', compact('influencer_id'));
    })->name('show.influencer');

    Route::post('influencers/{influencer}', [InfluencerController::class, 'destroy'])->name('influencer.delete');
    Route::resource('influencers', InfluencerController::class);
    Route::post('groups/update-name', [InfluncersGroupController::class, 'changeName'])->name('changeGroupName');
    Route::resource('groups', InfluncersGroupController::class);
    Route::post('campaign/update-name', [CampaignController::class, 'changeName'])->name('changeCampaignName');
    Route::get('campaign/response', [CampaignController::class, 'campaign_response'])->name('campaigns.response');
    Route::resource('campaigns', CampaignController::class);
    Route::resource('reseller', ResellerController::class);
    Route::get('conversation/{uuid}/owner', [ConversationController::class, 'owner_chat'])->name('conversation.owner');

    // Route::post('inquiries/{campaignId}/{influencerId}', [CampaignInquiryController::class, 'sendInquiry']);
});




Route::get('test', function () {

    $client = new \GuzzleHttp\Client();

    $response = $client->request('GET', 'https://dev.creatordb.app/v2/instagramBasic?instagramId=goodalicia', [
        'headers' => [
            'Accept' => 'application/json',
            'apiId' => 'b3e3a97d2e6f09e2-nSso42D8yDMHR2aP3NgM',
        ],
    ]);

    $data = json_decode($response->getBody(), true); // Use `true` to decode as an array

    Cache::put('instagram_data', $data, now()->addDays(30));

    $cachedData = Cache::get('instagram_data');


    $validatedData = [
        'selectedGroups' =>  [1],
        'selectInfluencer' =>  $cachedData['data']['basicInstagram'],
    ];

    foreach ($validatedData['selectedGroups'] as $groupId) {
        Influencer::create([
            'influncers_group_id' => $groupId,
            'influnencer_id' => $validatedData['selectInfluencer']['instagramId'],
            'platform' => 'instagram',
            'content' => json_encode($validatedData['selectInfluencer']),
        ]);
    }

    dd($cachedData);
});


Route::get('/proxy-image', function () {
    $imageUrl = request('url');
    $response = Http::get($imageUrl);

    if ($response->ok()) {
        return response($response->body(), 200)->header('Content-Type', 'image/jpeg');
    }

    return response('Image not found', 404);
});
