<?php

namespace Bocum\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaturityController extends Controller
{
    /**
     * Display a listing of maturities.
     */
    public function index()
    {
        $maturities = DB::table('maturities')
            ->leftJoin('samples as top_sample', 'maturities.top_sample_id', '=', 'top_sample.id')
            ->leftJoin('samples as bottom_sample', 'maturities.bottom_sample_id', '=', 'bottom_sample.id')
            ->select(
                'maturities.*',
                'top_sample.avg_brix as top_brix',
                'top_sample.label as top_label',
                'bottom_sample.avg_brix as bottom_brix',
                'bottom_sample.label as bottom_label'
            )
            ->orderBy('maturities.created_at', 'desc')
            ->paginate(15);

        return view('maturities.index', compact('maturities'));
    }

    /**
     * Show the form for creating a new maturity.
     */
    public function create()
    {
        $samples = DB::table('samples')
            ->select('id', 'label', 'avg_brix', 'position')
            ->orderBy('label')
            ->get();

        return view('maturities.create', compact('samples'));
    }

    /**
     * Store a newly created maturity in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'top_sample_id' => 'required|exists:samples,id',
            'bottom_sample_id' => 'required|exists:samples,id',
        ]);

        // Check that top and bottom samples are different
        if ($validated['top_sample_id'] == $validated['bottom_sample_id']) {
            return back()->withErrors(['bottom_sample_id' => 'Top and bottom samples must be different.'])->withInput();
        }

        DB::table('maturities')->insert([
            'name' => $validated['name'],
            'top_sample_id' => $validated['top_sample_id'],
            'bottom_sample_id' => $validated['bottom_sample_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('maturities.index')
            ->with('success', 'Maturity record created successfully.');
    }

    /**
     * Display the specified maturity.
     */
    public function show($id)
    {
        $maturity = DB::table('maturities')
            ->leftJoin('samples as top_sample', 'maturities.top_sample_id', '=', 'top_sample.id')
            ->leftJoin('samples as bottom_sample', 'maturities.bottom_sample_id', '=', 'bottom_sample.id')
            ->select(
                'maturities.*',
                'top_sample.avg_brix as top_brix',
                'top_sample.label as top_label',
                'top_sample.pol as top_pol',
                'bottom_sample.avg_brix as bottom_brix',
                'bottom_sample.label as bottom_label',
                'bottom_sample.pol as bottom_pol'
            )
            ->where('maturities.id', $id)
            ->first();

        if (!$maturity) {
            abort(404);
        }

        return view('maturities.show', compact('maturity'));
    }

    /**
     * Show the form for editing the specified maturity.
     */
    public function edit($id)
    {
        $maturity = DB::table('maturities')->where('id', $id)->first();
        
        if (!$maturity) {
            abort(404);
        }

        $samples = DB::table('samples')
            ->select('id', 'label', 'avg_brix', 'position')
            ->orderBy('label')
            ->get();

        return view('maturities.edit', compact('maturity', 'samples'));
    }

    /**
     * Update the specified maturity in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'top_sample_id' => 'required|exists:samples,id',
            'bottom_sample_id' => 'required|exists:samples,id',
        ]);

        // Check that top and bottom samples are different
        if ($validated['top_sample_id'] == $validated['bottom_sample_id']) {
            return back()->withErrors(['bottom_sample_id' => 'Top and bottom samples must be different.'])->withInput();
        }

        DB::table('maturities')
            ->where('id', $id)
            ->update([
                'name' => $validated['name'],
                'top_sample_id' => $validated['top_sample_id'],
                'bottom_sample_id' => $validated['bottom_sample_id'],
                'updated_at' => now(),
            ]);

        return redirect()->route('maturities.index')
            ->with('success', 'Maturity record updated successfully.');
    }

    /**
     * Remove the specified maturity from storage.
     */
    public function destroy($id)
    {
        DB::table('maturities')->where('id', $id)->delete();

        return redirect()->route('maturities.index')
            ->with('success', 'Maturity record deleted successfully.');
    }
}
