<?php

namespace App\Http\Controllers;

use App\Models\Influencer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InfluencerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $influencers = Influencer::all();
        return view('influencers.index', compact('influencers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:influencers',
            'platform' => 'required',
            'profile_url' => 'required|url',
            'followers_count' => 'required|integer',
            'engagement_rate' => 'required|numeric',
            'category' => 'required',
        ]);

        Influencer::create($request->all());
        return redirect()->back()->with('success', 'Influencer created successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Influencer $influencer)
    {
        return view('influencer.show', compact('influencer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Influencer $influencer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Influencer $influencer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Influencer $influencer)
    {
        $influencer->delete();
        return back()->with('success', 'Deleted Successfully');
    }
}
