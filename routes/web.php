<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Default welcome page
Route::get('/', [TaskController::class, 'landing'])->name('welcome');

// Routes protected by authentication middleware
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {

        
        return redirect()->route('tasks.index');
    })->name('dashboard');

    // Profile management routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Task management routes
    Route::resource('tasks', TaskController::class);
});

// Include Breeze authentication routes
require __DIR__.'/auth.php';
