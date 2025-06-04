<?php

namespace Database\Seeders;

use App\Models\Clinic;
use Illuminate\Database\Seeder;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a demo clinic for easy login
        Clinic::factory()->create([
            'name' => 'Demo Clinic',
            'email' => 'demo@example.com',
            'password' => bcrypt('password'),
        ]);
        
        // Create additional random clinics
        Clinic::factory()->count(5)->create();
    }
}
