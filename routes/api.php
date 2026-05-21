<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ReminderController;
use App\Http\Controllers\Api\AdminController;

// ─── Web Routes (with web middleware) ────────────────────────────
Route::middleware('web')->group(function () {
    // Public Landing Page
    Route::get('/', function () {
        return view('landing');
    })->name('home');

    // Authenticated User Routes
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

    // Admin Routes
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
});

// ─── API Routes (with api middleware & /api prefix) ──────────────
Route::middleware('api')->prefix('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('notes', NoteController::class);
        Route::apiResource('reminders', ReminderController::class);

        // Admin Routes
        Route::middleware('admin')->prefix('admin')->group(function () {
            Route::get('/stats', [AdminController::class, 'stats']);
            Route::get('/users', [AdminController::class, 'users']);
            Route::get('/notes', [AdminController::class, 'notes']);
            Route::post('/users/{user}/promote', [AdminController::class, 'promote']);
            Route::post('/users/{user}/demote', [AdminController::class, 'demote']);
            Route::delete('/users/{user}', [AdminController::class, 'deleteUser']);
            Route::delete('/notes/{note}', [AdminController::class, 'deleteNote']);
        });
    });
});
