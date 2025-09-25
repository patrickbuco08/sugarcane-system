<?php

namespace Bocum\Http\Controllers;

use Bocum\Models\HarvestBatch;
use Illuminate\Http\Request;

class GraphController extends Controller
{
    public function brixAndLkgtcGraph()
    {
        // Eager load relationships to prevent N+1 queries
        $batches = HarvestBatch::with(['samples', 'weeklyPrice'])->orderBy('week_of')->get();

        $result = $batches->map(function ($batch) {
            if ($batch->samples->isEmpty()) {
                return null; // or handle batches with no samples as needed
            }

            // Step 1: Compute average brix per batch
            $avgBrix = $batch->samples->avg('avg_brix');

            // Step 2: Compute lkgtc
            $lkgTc = $avgBrix * $batch->recovery_coeff;

            // Step 3: Compute lkg per batch and round to 1 decimal place
            $lkg = round($lkgTc * $batch->tons_harvested, 1);

            // Get b_domestic from the related weekly price
            $bDomestic = optional($batch->weeklyPrice)->b_domestic ?? 0;

            // Step 4: Compute profit
            $profit = $batch->tons_harvested * $lkg * $batch->farmers_share * $bDomestic;

            return [
                'week_of' => $batch->week_of,
                'avg_brix' => round($avgBrix, 2),
                'lkg_tc' => round($lkgTc, 2),
                'tons' => (float) $batch->tons_harvested,
                'lkg' => round($lkg, 2),
                'b_domestic' => (float) $bDomestic,
                'profit' => round($profit, 2),
            ];
        })->filter(); // Remove null entries for batches without samples

        return response()->json($result->values());
    }
}
