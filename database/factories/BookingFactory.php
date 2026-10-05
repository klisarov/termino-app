<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->randomElement(['09:00', '09:30', '10:00', '11:00', '14:00', '15:30']);

        return [
            'public_id' => (string) Str::uuid(),
            'tenant_id' => Tenant::factory(),
            'service_id' => Service::factory(),
            'customer_id' => Customer::factory(),
            'booking_date' => fake()->dateTimeBetween('-10 days', '+10 days')->format('Y-m-d'),
            'start_time' => $start,
            'end_time' => date('H:i', strtotime($start.' +30 minutes')),
            'status' => Booking::STATUS_BOOKED,
            'customer_note' => fake()->optional()->sentence(),
        ];
    }
}
