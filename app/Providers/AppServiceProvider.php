<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Policies\BookingPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\ServicePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Service::class, ServicePolicy::class);
        Gate::policy(Booking::class, BookingPolicy::class);
        Gate::policy(Customer::class, CustomerPolicy::class);
    }
}
