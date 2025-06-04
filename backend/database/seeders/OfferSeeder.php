<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Offer;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all clinics
        $clinics = Clinic::all();
        
        // Create offers for each clinic
        foreach ($clinics as $clinic) {
            Offer::factory()
                ->count(rand(3, 8))
                ->create([
                    'clinic_id' => $clinic->id
                ]);
        }
    }
}
