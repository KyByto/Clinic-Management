<?php

use App\Http\Controllers\ApiDocumentationController;
use Illuminate\Support\Facades\Route;

// Redirect root to admin panel
Route::get('/', function () {
    return redirect('/admin');
});

// API Documentation route
Route::get('/api-docs', [ApiDocumentationController::class, 'index'])
    ->name('api.documentation');

