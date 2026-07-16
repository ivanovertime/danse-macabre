<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:60,1')->group(function () {
    Route::get('/slots/{date}', [AppointmentController::class, 'slots']);
    Route::get('/slots/month/{year}/{month}', [AppointmentController::class, 'slotsMonth']);
    Route::post('/appointments', [AppointmentController::class, 'store']);
});
