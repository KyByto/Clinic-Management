<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Offer;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all offers and clients
        $offers = Offer::all();
        $clients = Client::all();
        
        // Create bookings
        foreach ($offers as $offer) {
            // Generate between 0 and 5 bookings for each offer
            $bookingCount = rand(0, 5);
            
            for ($i = 0; $i < $bookingCount; $i++) {
                $client = $clients->random();
                
                Booking::factory()
                    ->create([
                        'offer_id' => $offer->id,
                        'client_id' => $client->id,
                    ]);
            }
        }
    }
}
