<?php

use Illuminate\Support\Facades\Route;
use Bocum\Http\Controllers\Auth\LoginController;
use Bocum\Http\Controllers\HomeController;
use Bocum\Http\Controllers\DashboardController;
use Bocum\Http\Controllers\SampleController;
use Bocum\Http\Controllers\HarvestBatchController;
use Bocum\Http\Controllers\WeeklyPriceController;
use Bocum\Http\Controllers\GraphController;
use Bocum\Http\Controllers\ForecastController;

// Home Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard Routes

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/latest-honey-sample', [DashboardController::class, 'latest'])->name('latest.honey.sample');

    // Sample Routes
    Route::resource('samples', SampleController::class)->except(['index']);
    Route::patch('/samples/{sample}/update-batch', [SampleController::class, 'updateBatch'])->name('samples.update-batch');
    Route::get('/samples/export', [SampleController::class, 'export'])->name('samples.export');

    // Harvest Batch Routes
    Route::resource('harvest-batches', HarvestBatchController::class)->except(['show']);

    // Weekly Price Routes
    Route::resource('weekly-prices', WeeklyPriceController::class)->except(['show']);

    //graphs
    Route::get('/graphs/brix-and-lkgtc', [GraphController::class, 'brixAndLkgtcGraph'])->name('graphs.brix-and-lkgtc');

    // forecast
    Route::get('/forecast', [ForecastController::class, 'index'])->name('forecast');
});
