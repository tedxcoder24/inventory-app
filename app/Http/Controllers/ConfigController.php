<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfigStoreRequest;
use App\Http\Resources\ConfigResource;
use App\Models\Config;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render("Config/Index", [
            'config_data' => ConfigResource::collection(Config::all()),
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Config/Edit', [
            'config_data' => ConfigResource::collection(Config::all()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ConfigStoreRequest $request, string $id)
    {
        $validted_data = $request->validated();

        Config::findOrFail($id)->update($validted_data);

        return redirect('/config')->with('success','Config has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
