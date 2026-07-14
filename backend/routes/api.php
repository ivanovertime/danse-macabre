<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('/slots/{date}', [AppointmentController::class, 'slots']);
Route::post('/appointments', [AppointmentController::class, 'store']);
Route::get('/appointments/{id}', [AppointmentController::class, 'show']);
Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);
