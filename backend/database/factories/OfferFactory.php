<?php

namespace Database\Factories;

use App\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $availableDays = [];
        
        foreach ($days as $day) {
            if (rand(0, 1)) {
                $startHour = rand(8, 12);
                $endHour = rand($startHour + 2, 18);
                
                $availableDays[$day] = [
                    'available' => true,
                    'start_time' => sprintf('%02d:00', $startHour),
                    'end_time' => sprintf('%02d:00', $endHour),
                ];
            } else {
                $availableDays[$day] = [
                    'available' => false,
                ];
            }
        }

        return [
            'clinic_id' => Clinic::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 50, 500),
            'is_active' => true,
            'available_days' => $availableDays,
        ];
    }
}
