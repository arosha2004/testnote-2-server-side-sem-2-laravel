<?php

use Illuminate\Support\Facades\Route;

// ─── Public Landing Page ────────────────────────────────────────
Route::get('/', function () {
    return view('landing');
})->name('home');


