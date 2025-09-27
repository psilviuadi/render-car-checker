<?php

use App\CarChecker\Controllers\CarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('vue');
});

Route::group([], function() {
    Route::get('/api/ves/{plate}', [CarController::class, 'getVES'])->name('car.ves');
    Route::get('/api/mot/{plate}', [CarController::class, 'getMOT'])->name('car.mot');
});