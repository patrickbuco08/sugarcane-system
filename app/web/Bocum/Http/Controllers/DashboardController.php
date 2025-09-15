<?php

namespace Bocum\Http\Controllers;

use Bocum\Models\Sample;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index()
    {
        $samples = Sample::latest()->paginate(3);
        $latestId = Sample::latest()->value('id');

        return view('dashboard.index', [
            'samples' => $samples,
            'latestId' => $latestId,
        ]);
    }

    public function latest(): JsonResponse
    {
        $latest = Sample::latest()->first();

        return response()->json([
            'success' => true,
            'sample' => $latest,
        ]);
    }
}
