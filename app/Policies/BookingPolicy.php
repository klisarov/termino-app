<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function view(User $user, Booking $booking): bool
    {
        return $user->tenant?->id === $booking->tenant_id;
    }

    public function update(User $user, Booking $booking): bool
    {
        return $user->tenant?->id === $booking->tenant_id;
    }
}
