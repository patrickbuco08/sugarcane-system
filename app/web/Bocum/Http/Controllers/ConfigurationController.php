<?php

namespace Bocum\Http\Controllers;

use Bocum\Models\Configuration;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $configurations = Configuration::orderBy('key')->paginate(15);
        return view('configuration.index', compact('configurations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('configuration.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:configurations,key|max:255',
            'value' => 'required',
        ]);

        Configuration::create($validated);

        return redirect()->route('configurations.index')
            ->with('success', 'Configuration created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Configuration $configuration)
    {
        return view('configuration.edit', compact('configuration'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Configuration $configuration)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:configurations,key,' . $configuration->id,
            'value' => 'required',
        ]);

        $configuration->update($validated);

        return redirect()->route('configurations.index')
            ->with('success', 'Configuration updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Configuration $configuration)
    {
        $configuration->delete();

        return redirect()->route('configurations.index')
            ->with('success', 'Configuration deleted successfully.');
    }
}
