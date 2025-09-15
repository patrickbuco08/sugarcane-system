<?php

namespace Bocum\Http\Controllers;

use Bocum\Models\Sample;
use Bocum\Models\HarvestBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class SampleController extends Controller
{
    public function create()
    {
        $harvestBatches = HarvestBatch::orderBy('created_at', 'desc')->get();
        return view('samples.create', compact('harvestBatches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'avg_brix' => 'required|numeric|min:0',
            'pol' => 'required|numeric|min:0',
            'label' => 'nullable|string|max:120',
            'harvest_batch_id' => 'nullable|exists:harvest_batches,id'
        ]);

        $sample = Sample::create([
            'avg_brix' => $validated['avg_brix'],
            'pol' => $validated['pol'],
            'label' => $validated['label'] ?? null,
            'harvest_batch_id' => $validated['harvest_batch_id'] ?? null,
            'model_version' => 'manual_entry_v1'
        ]);

        return redirect()->route('dashboard')->with('success', 'Sample created successfully');
    }

    public function edit(Sample $sample)
    {
        $harvestBatches = HarvestBatch::orderBy('created_at', 'desc')->get();
        return view('samples.edit', compact('sample', 'harvestBatches'));
    }

    public function update(Request $request, Sample $sample)
    {
        $validated = $request->validate([
            'avg_brix' => 'required|numeric|min:0',
            'pol' => 'required|numeric|min:0',
            'label' => 'nullable|string|max:120',
            'harvest_batch_id' => 'nullable|exists:harvest_batches,id'
        ]);

        $sample->update([
            'avg_brix' => $validated['avg_brix'],
            'pol' => $validated['pol'],
            'label' => $validated['label'] ?? null,
            'harvest_batch_id' => $validated['harvest_batch_id'] ?? null
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Sample updated successfully']);
        }

        return redirect()->route('dashboard')->with('success', 'Sample updated successfully');
    }

    /**
     * Display the specified sample.
     */
    public function show(Sample $sample)
    {
        $harvestBatches = HarvestBatch::latest()->get();
        
        return view('samples.show', [
            'sample' => $sample->load('harvestBatch'),
            'harvestBatches' => $harvestBatches
        ]);
    }

    /**
     * Update the specified sample's harvest batch.
     */
    public function updateBatch(Request $request, Sample $sample)
    {
        $validated = $request->validate([
            'harvest_batch_id' => 'nullable|exists:harvest_batches,id'
        ]);

        $sample->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Sample updated successfully']);
        }

        return back()->with('success', 'Sample updated successfully');
    }

    /**
     * Export samples data.
     */
    public function export()
    {
        // Implementation for export functionality
        // This is a placeholder for now
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Export functionality will be implemented here']);
        }
        
        // You might want to implement a view for non-API exports
        return response('Export functionality will be implemented here', 200);
    }
}
