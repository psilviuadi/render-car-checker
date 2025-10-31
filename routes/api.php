<?php

use App\CarChecker\Controllers\CarController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([], function() {
    Route::get('/ves/{plate}', [CarController::class, 'getVES'])->name('car.ves');
    Route::get('/mot/{plate}', [CarController::class, 'getMOT'])->name('car.mot');
    Route::get('/agent-review/{plate}', [CarController::class, 'getAgentReview'])->name('car.agent_review');
});