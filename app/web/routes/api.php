<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Bocum\Http\Controllers\Api\HoneySampleController;
use Bocum\Http\Controllers\Api\SugarcaneSampleController;
use Bocum\Http\Controllers\Api\ConfigurationController;
use Bocum\Http\Controllers\Api\PredictionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('api.token')->post('/honey-samples', [HoneySampleController::class, 'store']);
Route::middleware('api.token')->post('/sugarcane-samples', [SugarcaneSampleController::class, 'store']);
Route::middleware('api.token')->post('/predictions', [PredictionController::class, 'predict']);

// Configuration API routes (protected by auth middleware)
// Route::middleware(['auth'])->group(function () {
//     Route::apiResource('configurations', ConfigurationController::class);
// });
