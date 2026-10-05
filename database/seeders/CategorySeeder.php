<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Barber', 'Hair Salon', 'Beauty', 'Massage', 'Fitness',
            'Tattoo', 'Health', 'Auto', 'Home Services', 'Education', 'Other',
        ];

        foreach ($categories as $name) {
            Category::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'is_active' => true],
            );
        }
    }
}
