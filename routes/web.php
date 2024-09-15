<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\CalendarEventController;
Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('/calendar', [GoogleController::class, 'getCalendarEvents']);
Route::get('/calendar-events', [CalendarEventController::class, 'index']);


Route::get('/', function () {
    return view('welcome');
});
