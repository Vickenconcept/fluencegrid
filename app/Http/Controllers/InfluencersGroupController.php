<?php

namespace App\Http\Controllers;

use App\Models\InfluencersGroup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InfluencersGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('group.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);

        $user = auth()->user();
        $user->influencersGroups()->create($validateData);

        return back()->with('success', 'Created Successfully');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(InfluencersGroup $group)
    {
        $group->load('influencers');
        return view('group.show', compact('group'));
    }

    public function changeName(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $group = InfluencersGroup::where('id', $request->input('id'))->firstOrFail();
        $group->name = $request->input('name');
        $group->update();

        return back()->with('success', 'Updated successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InfluencersGroup $InfluencersGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InfluencersGroup $InfluencersGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InfluencersGroup $group)
    {
        $group->delete();
        return back()->with('success', 'Deleted Successfully');
    }
}
