<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;

// ─── Public Landing Page ────────────────────────────────────────
Route::get('/', function () {
    return view('landing');
})->name('home');

// ─── Google OAuth Routes ────────────────────────────────────────
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
