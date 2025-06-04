<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Offer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bookingDate = fake()->dateTimeBetween('+1 day', '+2 months');
        $dayOfWeek = strtolower(date('l', $bookingDate->getTimestamp()));
        
        // Generate random booking time
        $hour = rand(9, 17);
        $minute = rand(0, 1) ? '00' : '30';
        $bookingTime = "{$hour}:{$minute}:00";
        
        return [
            'offer_id' => Offer::factory(),
            'client_id' => Client::factory(),
            'booking_date' => $bookingDate,
            'booking_time' => $bookingTime,
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
            'notes' => rand(0, 1) ? fake()->sentence() : null,
            'payment_status' => fake()->randomElement(['unpaid', 'paid']),
        ];
    }

    /**
     * Indicate that the booking is confirmed.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
        ]);
    }

    /**
     * Indicate that the booking is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'paid',
        ]);
    }
}
