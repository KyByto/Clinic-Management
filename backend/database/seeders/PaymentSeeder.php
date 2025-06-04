<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Offer;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all paid bookings
        $paidBookings = Booking::where('payment_status', 'paid')->get();
        
        // Create payment records for each paid booking
        foreach ($paidBookings as $booking) {
            $offer = Offer::find($booking->offer_id);
            
            Payment::factory()
                ->completed()
                ->create([
                    'booking_id' => $booking->id,
                    'amount' => $offer->price,
                ]);
        }
    }
}
