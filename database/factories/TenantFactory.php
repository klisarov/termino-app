<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TenantFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->company();
        $cities = ['Skopje', 'Bitola', 'Ohrid', 'Tetovo', 'Prilep', 'Kumanovo'];

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'description' => fake()->paragraph(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->companyEmail(),
            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement($cities),
            'latitude' => fake()->latitude(41.0, 42.3),
            'longitude' => fake()->longitude(20.5, 23.0),
            'is_active' => true,
        ];
    }
}
