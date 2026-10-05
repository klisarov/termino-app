<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => fake()->randomElement(['Haircut', 'Beard Trim', 'Massage', 'Manicure', 'Consultation', 'Deep Clean']),
            'description' => fake()->sentence(),
            'duration_minutes' => fake()->randomElement([15, 30, 45, 60]),
            'price' => fake()->randomElement([300, 500, 700, 900, 1200, 1500]),
            'is_active' => true,
        ];
    }
}
