<?php

namespace Bocum\Http\Controllers\Api;

use Bocum\Http\Controllers\Controller;
use Bocum\Services\ConfigurationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PredictionController extends Controller
{
    protected $configurationService;

    /**
     * Inject ConfigurationService
     */
    public function __construct(ConfigurationService $configurationService)
    {
        $this->configurationService = $configurationService;
    }

    /**
     * Predict brix and pol values based on AS7263 sensor channels
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function predict(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'R' => 'required|numeric',
            'S' => 'required|numeric',
            'T' => 'required|numeric',
            'U' => 'required|numeric',
            'V' => 'required|numeric',
            'W' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get channel values from request
        $R = $request->R;
        $S = $request->S;
        $T = $request->T;
        $U = $request->U;
        $V = $request->V;
        $W = $request->W;

        // Get coefficients from configuration
        $brixCoefficients = $this->configurationService->getConfiguration('brix_coefficients');
        $polCoefficients = $this->configurationService->getConfiguration('pol_coefficients');

        // Check if coefficients exist
        if (!$brixCoefficients || !$polCoefficients) {
            return response()->json([
                'message' => 'Prediction coefficients not configured'
            ], 500);
        }

        // Calculate Brix
        // Brix = Intercept + (R * Coefficient_R) + (S * Coefficient_S) + (T * Coefficient_T) + (U * Coefficient_U) + (V * Coefficient_V) + (W * Coefficient_W)
        $brix = $brixCoefficients['intercept'] +
                ($R * $brixCoefficients['coefficients'][0]) +
                ($S * $brixCoefficients['coefficients'][1]) +
                ($T * $brixCoefficients['coefficients'][2]) +
                ($U * $brixCoefficients['coefficients'][3]) +
                ($V * $brixCoefficients['coefficients'][4]) +
                ($W * $brixCoefficients['coefficients'][5]);

        // Calculate Pol
        // Pol = Intercept + (R * Coefficient_R) + (S * Coefficient_S) + (T * Coefficient_T) + (U * Coefficient_U) + (V * Coefficient_V) + (W * Coefficient_W)
        $pol = $polCoefficients['intercept'] +
               ($R * $polCoefficients['coefficients'][0]) +
               ($S * $polCoefficients['coefficients'][1]) +
               ($T * $polCoefficients['coefficients'][2]) +
               ($U * $polCoefficients['coefficients'][3]) +
               ($V * $polCoefficients['coefficients'][4]) +
               ($W * $polCoefficients['coefficients'][5]);

        // Return the predictions
        return response()->json([
            'message' => 'Prediction successful',
            'data' => [
                'brix' => round($brix, 2),
                'pol' => round($pol, 2),
                'channels' => [
                    'R' => $R,
                    'S' => $S,
                    'T' => $T,
                    'U' => $U,
                    'V' => $V,
                    'W' => $W,
                ]
            ]
        ], 200);
    }
}
