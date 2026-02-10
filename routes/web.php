<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\BlogController;

Route::get('/', function () {
    return view('welcome');
});

/**
 * Google OAuth Routes
 */
Route::get('/admin/oauth/google', [GoogleAuthController::class, 'redirect'])
    ->name('google.login');

Route::get('/admin/oauth/google/callback', [GoogleAuthController::class, 'callback']);

Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/api/blog/{id}/stats', [BlogController::class, 'getViewStats']);
Route::post('/api/blog/{id}/track', [BlogController::class, 'trackView']);