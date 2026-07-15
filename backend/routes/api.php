<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('/slots/{date}', [AppointmentController::class, 'slots']);
Route::get('/slots/month/{year}/{month}', [AppointmentController::class, 'slotsMonth']);
Route::post('/appointments', [AppointmentController::class, 'store']);
