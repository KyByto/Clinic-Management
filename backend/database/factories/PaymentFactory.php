<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isPaid = rand(0, 1);
        
        return [
            'booking_id' => Booking::factory(),
            'amount' => fake()->randomFloat(2, 50, 500),
            'currency' => 'USD',
            'payment_method' => $isPaid ? fake()->randomElement(['credit_card', 'paypal', 'bank_transfer']) : null,
            'transaction_id' => $isPaid ? fake()->uuid() : null,
            'payment_intent_id' => $isPaid ? 'pi_' . fake()->md5() : null,
            'status' => $isPaid ? 'completed' : 'pending',
            'paid_at' => $isPaid ? now() : null,
        ];
    }

    /**
     * Indicate that the payment is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'payment_method' => fake()->randomElement(['credit_card', 'paypal', 'bank_transfer']),
            'transaction_id' => fake()->uuid(),
            'payment_intent_id' => 'pi_' . fake()->md5(),
            'paid_at' => now(),
        ]);
    }
}
