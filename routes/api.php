<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ReminderController;
use App\Http\Controllers\Api\AdminController;

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
