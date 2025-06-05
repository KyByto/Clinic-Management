<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\OfferController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ClinicController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\PaymentController;

    Route::get('/offers', [OfferController::class, 'index']);
    Route::get('/offers/{id}', [OfferController::class, 'show']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/bookings', [BookingController::class, 'store']);
        Route::get('/bookings', [BookingController::class, 'index']); // New endpoint to get user's bookings
        Route::post('bookings/{booking}/send-notification', [BookingController::class, 'sendNotification']);

        Route::post('bookings/{bookingId}/pay', [PaymentController::class, 'processPayment']);
    });
