<?php

namespace Bocum\Http\Controllers;

use Bocum\Models\WeeklyPrice;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WeeklyPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $weeklyPrices = WeeklyPrice::orderBy('week_of', 'desc')
            ->paginate(10);

        return view('weekly-prices.index', compact('weeklyPrices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('weekly-prices.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'week_of' => 'required|date|unique:weekly_prices,week_of',
            'b_domestic' => 'required|numeric|min:0',
            'a_us_quota' => 'nullable|numeric|min:0',
            'molasses_mt' => 'nullable|numeric|min:0',
            'source' => 'nullable|string|max:120',
        ]);

        WeeklyPrice::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Weekly price created successfully.']);
        }

        return redirect()->route('weekly-prices.index')
            ->with('success', 'Weekly price created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WeeklyPrice $weeklyPrice)
    {
        return view('weekly-prices.edit', compact('weeklyPrice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WeeklyPrice $weeklyPrice)
    {
        $validated = $request->validate([
            'week_of' => 'required|date|unique:weekly_prices,week_of,' . $weeklyPrice->id,
            'b_domestic' => 'required|numeric|min:0',
            'a_us_quota' => 'nullable|numeric|min:0',
            'molasses_mt' => 'nullable|numeric|min:0',
            'source' => 'nullable|string|max:120',
        ]);

        $weeklyPrice->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Weekly price updated successfully.']);
        }

        return redirect()->route('weekly-prices.index')
            ->with('success', 'Weekly price updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WeeklyPrice $weeklyPrice)
    {
        // Check if this weekly price is being used by any harvest batches
        if ($weeklyPrice->harvestBatches()->count() > 0) {
            return redirect()->route('weekly-prices.index')
                ->with('error', 'Cannot delete weekly price that is being used by harvest batches.');
        }

        $weeklyPrice->delete();

        return redirect()->route('weekly-prices.index')
            ->with('success', 'Weekly price deleted successfully.');
    }
}
