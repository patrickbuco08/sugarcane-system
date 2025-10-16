<?php

namespace Bocum\Http\Controllers\Api;

use Bocum\Http\Controllers\Controller;
use Bocum\Models\Sample;
use Bocum\Services\ConfigurationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SugarcaneSampleController extends Controller
{

    private const AVERAGE_BRIX = '14.07';
    private const POL = '1.08';

    protected $configurationService;

    /**
     * Inject ConfigurationService
     */
    public function __construct(ConfigurationService $configurationService)
    {
        $this->configurationService = $configurationService;
    }

    /**
     * Store a new sugarcane sample
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'avg_brix' => 'required|numeric',
            'pol' => 'required|numeric',
            'ch_r' => 'required|numeric',
            'ch_s' => 'required|numeric',
            'ch_t' => 'required|numeric',
            'ch_u' => 'required|numeric',
            'ch_v' => 'required|numeric',
            'ch_w' => 'required|numeric',
            'sensor_temp_c' => 'required|numeric',
            'model_version' => 'required|string|max:255',
            'coeff_hash' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Prepare data for sample creation
        $sampleData = [
            'avg_brix' => $request->avg_brix,
            'pol' => $request->pol,
            'ch_r' => $request->ch_r,
            'ch_s' => $request->ch_s,
            'ch_t' => $request->ch_t,
            'ch_u' => $request->ch_u,
            'ch_v' => $request->ch_v,
            'ch_w' => $request->ch_w,
            'sensor_temp_c' => $request->sensor_temp_c,
            'model_version' => $request->model_version,
            'coeff_hash' => $request->coeff_hash,
        ];

        $sampleData['purity'] = ($sampleData['pol'] / $sampleData['avg_brix']) * 100;

        // Create the sample
        $sample = Sample::create($sampleData);

        return response()->json([
            'message' => 'Sugarcane sample created successfully.',
            'data' => $sample
        ], 201);
    }
}
