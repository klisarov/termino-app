<?php

namespace App\Services\Booking;

use App\Models\Booking;
use App\Models\BreakTime;
use App\Models\Service;
use App\Models\Tenant;
use App\Models\WorkingHour;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

/**
 * Turns a tenant's working hours, breaks and existing bookings into the list
 * of appointment slots a customer can actually pick. Nothing here is stored
 * in the database - slots are always computed on the fly (see spec §15),
 * which keeps the calendar correct even when working hours change.
 *
 * This service is intentionally framework-thin (no HTTP concerns) so it can
 * be reused from the public booking page, the tenant dashboard, and any
 * future API/mobile client.
 */
class AvailabilityService
{
    /**
     * Return the dates (within the given window) that have at least one
     * available slot for the given service, as 'Y-m-d' strings.
     */
    public function getAvailableDates(Tenant $tenant, Service $service, int $daysAhead = 30): array
    {
        $dates = [];
        $today = CarbonImmutable::today();

        for ($i = 0; $i < $daysAhead; $i++) {
            $date = $today->addDays($i);

            if (! empty($this->getAvailableSlots($tenant, $service, $date->toDateString()))) {
                $dates[] = $date->toDateString();
            }
        }

        return $dates;
    }

    /**
     * Return the available start times ('H:i') for a given tenant/service/date.
     */
    public function getAvailableSlots(Tenant $tenant, Service $service, string $date): array
    {
        $date = CarbonImmutable::parse($date);
        $dayOfWeek = $date->dayOfWeek;

        $workingHour = WorkingHour::query()
            ->where('tenant_id', $tenant->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (! $workingHour) {
            return [];
        }

        $duration = max(1, (int) $service->duration_minutes);
        $windowStart = $this->toMinutes($workingHour->start_time);
        $windowEnd = $this->toMinutes($workingHour->end_time);

        if ($windowEnd <= $windowStart) {
            return [];
        }

        $breaks = BreakTime::query()
            ->where('tenant_id', $tenant->id)
            ->where('day_of_week', $dayOfWeek)
            ->get()
            ->map(fn (BreakTime $break) => [
                'start' => $this->toMinutes($break->start_time),
                'end' => $this->toMinutes($break->end_time),
            ])
            ->all();

        $bookedRanges = Booking::query()
            ->where('tenant_id', $tenant->id)
            ->where('booking_date', $date->toDateString())
            ->active()
            ->get(['start_time', 'end_time'])
            ->map(fn (Booking $booking) => [
                'start' => $this->toMinutes($booking->start_time),
                'end' => $this->toMinutes($booking->end_time),
            ])
            ->all();

        $now = Carbon::now();
        $isToday = $date->isSameDay($now);
        $nowMinutes = $isToday ? ($now->hour * 60 + $now->minute) : null;

        $slots = [];

        for ($start = $windowStart; $start + $duration <= $windowEnd; $start += $duration) {
            $end = $start + $duration;

            if ($isToday && $start <= $nowMinutes) {
                continue;
            }

            if ($this->overlapsAny($start, $end, $breaks)) {
                continue;
            }

            if ($this->overlapsAny($start, $end, $bookedRanges)) {
                continue;
            }

            $slots[] = $this->toTimeString($start);
        }

        return $slots;
    }

    /**
     * Re-validate that one specific slot is still bookable. Used as the
     * final, authoritative check right before a booking is written - never
     * trust the slot list the browser was rendered with (spec §18/§19).
     */
    public function isSlotAvailable(Tenant $tenant, Service $service, string $date, string $startTime): bool
    {
        $normalized = $this->toTimeString($this->toMinutes($startTime));

        return in_array($normalized, $this->getAvailableSlots($tenant, $service, $date), true);
    }

    private function overlapsAny(int $start, int $end, array $ranges): bool
    {
        foreach ($ranges as $range) {
            if ($start < $range['end'] && $end > $range['start']) {
                return true;
            }
        }

        return false;
    }

    private function toMinutes(string $time): int
    {
        [$hours, $minutes] = array_map('intval', explode(':', $time));

        return ($hours * 60) + $minutes;
    }

    private function toTimeString(int $minutes): string
    {
        return sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
    }
}
