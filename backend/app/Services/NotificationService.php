<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send a simple booking confirmation notification (simulated with logs)
     *
     * @param Booking $booking
     * @return bool
     */
    public function sendBookingConfirmation(Booking $booking): bool
    {
        // Simulate a small delay as if contacting an external service
        usleep(300000); // 300ms delay
        
        // Log the notification for simulation purposes
        Log::info('NOTIFICATION SENT: Booking confirmation', [
            'booking_id' => $booking->id,
            'client_id' => $booking->client_id,
            'client_email' => $booking->client->email ?? 'unknown',
            'client_phone' => $booking->client->phone ?? 'unknown',
            'offer_title' => $booking->offer->title ?? 'unknown',
            'booking_date' => $booking->booking_date,
            'message' => "Your booking for {$booking->offer->title} has been confirmed for {$booking->booking_date}. Thank you!"
        ]);
        
        return true;
    }
}
