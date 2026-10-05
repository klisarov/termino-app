<?php

namespace Database\Seeders;

use App\Models\BreakTime;
use App\Models\Category;
use App\Models\Tenant;
use App\Models\User;
use App\Models\WorkingHour;
use App\Services\Booking\BookingService;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Throwable;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CategorySeeder::class);

        User::query()->updateOrCreate(
            ['email' => 'admin@termino.mk'],
            [
                'name' => 'Termino Admin',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ],
        );

        $businesses = $this->demoBusinesses();
        $bookingService = app(BookingService::class);

        $demoCustomers = [
            ['name' => 'Ana Ivanova', 'email' => 'ana.ivanova@example.com', 'phone' => '070111222'],
            ['name' => 'Petar Kostov', 'email' => 'petar.kostov@example.com', 'phone' => '070222333'],
            ['name' => 'Sara Ristova', 'email' => 'sara.ristova@example.com', 'phone' => '070333444'],
            ['name' => 'Filip Trajanov', 'email' => 'filip.trajanov@example.com', 'phone' => '070444555'],
        ];

        foreach ($businesses as $business) {
            $category = Category::query()->where('name', $business['tenant']['category'])->first();

            $owner = User::query()->updateOrCreate(
                ['email' => $business['owner']['email']],
                [
                    'name' => $business['owner']['name'],
                    'password' => Hash::make('password'),
                    'role' => User::ROLE_TENANT,
                    'email_verified_at' => now(),
                ],
            );

            $tenant = Tenant::query()->updateOrCreate(
                ['slug' => $business['tenant']['slug']],
                [
                    'user_id' => $owner->id,
                    'category_id' => $category?->id,
                    'name' => $business['tenant']['name'],
                    'description' => $business['tenant']['description'],
                    'city' => $business['tenant']['city'],
                    'phone' => '070'.random_int(100000, 999999),
                    'email' => $business['owner']['email'],
                    'address' => 'Main Street '.random_int(1, 50),
                    'is_active' => true,
                ],
            );

            foreach ($business['services'] as $serviceData) {
                $tenant->services()->updateOrCreate(
                    ['name' => $serviceData['name']],
                    [
                        'description' => null,
                        'duration_minutes' => $serviceData['duration_minutes'],
                        'price' => $serviceData['price'],
                        'is_active' => true,
                    ],
                );
            }

            // Open Monday(1) - Saturday(6), closed Sunday(0), lunch break 13:00-14:00.
            foreach (range(0, 6) as $day) {
                WorkingHour::query()->updateOrCreate(
                    ['tenant_id' => $tenant->id, 'day_of_week' => $day],
                    ['start_time' => '09:00', 'end_time' => '18:00', 'is_active' => $day !== 0],
                );

                BreakTime::query()->where('tenant_id', $tenant->id)->where('day_of_week', $day)->delete();

                if ($day !== 0) {
                    BreakTime::query()->create([
                        'tenant_id' => $tenant->id,
                        'day_of_week' => $day,
                        'start_time' => '13:00',
                        'end_time' => '14:00',
                    ]);
                }
            }

            $service = $tenant->services()->first();

            $plan = [
                ['date' => CarbonImmutable::today(), 'time' => '10:00'],
                ['date' => CarbonImmutable::today(), 'time' => '15:00'],
                ['date' => CarbonImmutable::today()->addDay(), 'time' => '11:00'],
                ['date' => CarbonImmutable::today()->addDays(3), 'time' => '09:30'],
            ];

            $created = [];

            foreach ($plan as $i => $slot) {
                try {
                    $created[] = $bookingService->createBooking(
                        tenant: $tenant,
                        service: $service,
                        date: $slot['date']->toDateString(),
                        startTime: $slot['time'],
                        customerData: $demoCustomers[$i % count($demoCustomers)],
                    );
                } catch (Throwable) {
                    // Slot already taken by a previous seeder run - safe to skip.
                }
            }

            // Give the demo data a realistic mix of statuses.
            if (isset($created[0])) {
                $bookingService->completeBooking($created[0]);
            }
            if (isset($created[2])) {
                $bookingService->cancelBooking($created[2]);
            }
        }
    }

    private function demoBusinesses(): array
    {
        return [
            [
                'owner' => ['name' => 'Luka Petrovski', 'email' => 'luka@termino.mk'],
                'tenant' => [
                    'name' => 'Barber Luka', 'slug' => 'barber-luka', 'city' => 'Skopje', 'category' => 'Barber',
                    'description' => 'Classic and modern cuts in the heart of Skopje. Walk-ins welcome, but booking guarantees your spot.',
                ],
                'services' => [
                    ['name' => 'Haircut', 'duration_minutes' => 30, 'price' => 500],
                    ['name' => 'Haircut + Beard', 'duration_minutes' => 45, 'price' => 700],
                    ['name' => 'Kids Haircut', 'duration_minutes' => 20, 'price' => 350],
                ],
            ],
            [
                'owner' => ['name' => 'Ivana Trajkova', 'email' => 'ivana@termino.mk'],
                'tenant' => [
                    'name' => 'Salon Bella', 'slug' => 'salon-bella', 'city' => 'Skopje', 'category' => 'Hair Salon',
                    'description' => 'Full-service hair salon specialising in colour, cuts and styling for every occasion.',
                ],
                'services' => [
                    ['name' => 'Wash & Cut', 'duration_minutes' => 45, 'price' => 900],
                    ['name' => 'Full Colour', 'duration_minutes' => 90, 'price' => 2200],
                    ['name' => 'Blow Dry', 'duration_minutes' => 30, 'price' => 600],
                ],
            ],
            [
                'owner' => ['name' => 'Elena Georgievska', 'email' => 'elena@termino.mk'],
                'tenant' => [
                    'name' => 'Glow Beauty Studio', 'slug' => 'glow-beauty-studio', 'city' => 'Bitola', 'category' => 'Beauty',
                    'description' => 'Facials, lashes and brows in a calm, modern studio in central Bitola.',
                ],
                'services' => [
                    ['name' => 'Classic Facial', 'duration_minutes' => 60, 'price' => 1500],
                    ['name' => 'Eyebrow Shaping', 'duration_minutes' => 20, 'price' => 400],
                    ['name' => 'Lash Lift', 'duration_minutes' => 45, 'price' => 1200],
                ],
            ],
            [
                'owner' => ['name' => 'Marko Stojanov', 'email' => 'marko@termino.mk'],
                'tenant' => [
                    'name' => 'Zen Massage & Spa', 'slug' => 'zen-massage-spa', 'city' => 'Ohrid', 'category' => 'Massage',
                    'description' => 'Therapeutic and relaxation massage overlooking Lake Ohrid.',
                ],
                'services' => [
                    ['name' => 'Relaxation Massage (60 min)', 'duration_minutes' => 60, 'price' => 1800],
                    ['name' => 'Deep Tissue Massage (90 min)', 'duration_minutes' => 90, 'price' => 2600],
                    ['name' => 'Express Neck & Shoulders', 'duration_minutes' => 30, 'price' => 900],
                ],
            ],
            [
                'owner' => ['name' => 'Dario Naumov', 'email' => 'dario@termino.mk'],
                'tenant' => [
                    'name' => 'FitZone Personal Training', 'slug' => 'fitzone-pt', 'city' => 'Skopje', 'category' => 'Fitness',
                    'description' => 'One-on-one personal training sessions tailored to your goals.',
                ],
                'services' => [
                    ['name' => 'Personal Training Session', 'duration_minutes' => 60, 'price' => 1000],
                    ['name' => 'Fitness Assessment', 'duration_minutes' => 45, 'price' => 700],
                ],
            ],
        ];
    }
}
