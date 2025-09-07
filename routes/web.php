<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;

Route::get('/car', [CarController::class, 'lookup'])->name('car.lookup');


Route::get('/', function () {
    return view('vue');
});

Route::group([], function() {
    Route::get('/api/ves/{plate}', [CarController::class, 'getVES'])->name('car.ves');
    Route::get('/api/mot/{plate}', [CarController::class, 'getMOT'])->name('car.mot');
});