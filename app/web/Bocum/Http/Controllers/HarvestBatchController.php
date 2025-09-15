<?php

namespace Bocum\Http\Controllers;

use Bocum\Models\HarvestBatch;
use Bocum\Models\WeeklyPrice;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HarvestBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $batches = HarvestBatch::with('weeklyPrice')
            ->latest('week_of')
            ->paginate(10);

        return view('harvest-batches.index', compact('batches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('harvest-batches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'weekly_price_id' => 'required|exists:weekly_prices,id',
            'tons_harvested' => 'required|numeric|min:0',
            'recovery_coeff' => 'required|numeric|between:0,1',
            'farmers_share' => 'required|numeric|between:0,1',
        ]);

        // Get the weekly price to set the week_of field
        $weeklyPrice = WeeklyPrice::findOrFail($validated['weekly_price_id']);
        $validated['week_of'] = $weeklyPrice->week_of;

        HarvestBatch::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Harvest batch created successfully.']);
        }

        return redirect()->route('harvest-batches.index')
            ->with('success', 'Harvest batch created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HarvestBatch $harvestBatch)
    {
        return view('harvest-batches.edit', compact('harvestBatch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HarvestBatch $harvestBatch)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'weekly_price_id' => 'required|exists:weekly_prices,id',
            'tons_harvested' => 'required|numeric|min:0',
            'recovery_coeff' => 'required|numeric|between:0,1',
            'farmers_share' => 'required|numeric|between:0,1',
        ]);

        // Get the weekly price to set the week_of field
        $weeklyPrice = WeeklyPrice::findOrFail($validated['weekly_price_id']);
        $validated['week_of'] = $weeklyPrice->week_of;

        $harvestBatch->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Harvest batch updated successfully.']);
        }

        return redirect()->route('harvest-batches.index')
            ->with('success', 'Harvest batch updated successfully.');
    }
}
