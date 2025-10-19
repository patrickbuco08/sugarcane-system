<?php

namespace Bocum\Http\Controllers;

use Bocum\Models\Sample;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index()
    {
        $query = Sample::latest();

        // Add search functionality
        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('label', 'like', "%{$search}%")
                    ->orWhere('avg_brix', 'like', "%{$search}%")
                    ->orWhere('pol', 'like', "%{$search}%")
                    ->orWhere('purity', 'like', "%{$search}%")
                    ->orWhere('created_at', 'like', "%{$search}%");
            });
        }

        $samples = $query->paginate(3);
        $latestId = Sample::latest()->value('id');

        return view('dashboard.index', [
            'samples' => $samples,
            'latestId' => $latestId,
        ]);
    }

    public function latest(): JsonResponse
    {
        $latestSample = Sample::latest()->first();

        return response()->json([
            'id' => $latestSample ? $latestSample->id : null,
            'label' => $latestSample ? $latestSample->label : null,
        ]);
    }
}
