<?php

use Illuminate\Support\Facades\Route;

// ─── Public Landing Page ────────────────────────────────────────
Route::get('/', function () {
    return view('landing');
})->name('home');

// ─── Authenticated User Routes ──────────────────────────────────
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/notes', function () {
        return view('notes');
    })->name('notes');

    Route::get('/categories', function () {
        return view('categories');
    })->name('categories');

    Route::get('/reminders', function () {
        return view('reminders');
    })->name('reminders');

    Route::get('/history', function () {
        return view('history');
    })->name('history');
});

// ─── Admin Routes ────────────────────────────────────────────────
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'admin',
])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin');
    })->name('dashboard');
});
