<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UploadController;

// Home route
Route::get('/', function () {
    return view('welcome');
});

 
Route::get('/upload_file', function () {
    return view('upload_file');
   });
Route::post('/upload', [UploadController::class, 'upload'])->name('upload');

// File retrieval route (MinIO)
Route::get('/files/minio/{filename}', [UploadController::class, 'getFromMinio'])->name('file.minio');

// Dashboard route (requires auth)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes (requires auth)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes (provided by Laravel Breeze, Jetstream, etc.)
require __DIR__.'/auth.php';
