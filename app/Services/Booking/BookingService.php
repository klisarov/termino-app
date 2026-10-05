<?php

namespace App\Services\Booking;

use App\Exceptions\SlotUnavailableException;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Tenant;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

/**
 * Owns every state change a booking can go through. Creation is the
 * sensitive path: it re-validates everything server-side and guards
 * against two customers grabbing the same slot at once (spec §18/§19).
 */
class BookingService
{
    public function __construct(
        private readonly AvailabilityService $availability,
    ) {
    }

    /**
     * @param  array{name: string, email: string, phone: string, address?: ?string, city?: ?string}  $customerData
     *
     * @throws SlotUnavailableException
     */
    public function createBooking(
        Tenant $tenant,
        Service $service,
        string $date,
        string $startTime,
        array $customerData,
        ?string $note = null,
    ): Booking {
        return DB::transaction(function () use ($tenant, $service, $date, $startTime, $customerData, $note) {
            // Lock the tenant row for the duration of the transaction. Every
            // concurrent booking attempt for this tenant has to wait for this
            // lock, which serializes availability checks per tenant and is
            // what actually prevents double bookings under load.
            $lockedTenant = Tenant::query()->whereKey($tenant->id)->lockForUpdate()->firstOrFail();

            if (! $lockedTenant->is_active) {
                throw new SlotUnavailableException('This business is not currently accepting bookings.');
            }

            $service = Service::query()
                ->where('tenant_id', $lockedTenant->id)
                ->where('is_active', true)
                ->findOrFail($service->id);

            $parsedDate = CarbonImmutable::parse($date);

            if ($parsedDate->startOfDay()->lt(CarbonImmutable::today())) {
                throw new SlotUnavailableException('That date is in the past.');
            }

            if (! $this->availability->isSlotAvailable($lockedTenant, $service, $parsedDate->toDateString(), $startTime)) {
                throw new SlotUnavailableException;
            }

            $start = CarbonImmutable::parse($startTime);
            $end = $start->addMinutes((int) $service->duration_minutes);

            $customer = Customer::query()->updateOrCreate(
                [
                    'tenant_id' => $lockedTenant->id,
                    'email' => $customerData['email'],
                ],
                [
                    'name' => $customerData['name'],
                    'phone' => $customerData['phone'],
                    'address' => $customerData['address'] ?? null,
                    'city' => $customerData['city'] ?? null,
                ],
            );

            return Booking::query()->create([
                'tenant_id' => $lockedTenant->id,
                'service_id' => $service->id,
                'customer_id' => $customer->id,
                'booking_date' => $parsedDate->toDateString(),
                'start_time' => $start->format('H:i'),
                'end_time' => $end->format('H:i'),
                'status' => Booking::STATUS_BOOKED,
                'customer_note' => $note,
            ]);
        });
    }

    public function cancelBooking(Booking $booking): Booking
    {
        $booking->update(['status' => Booking::STATUS_CANCELLED]);

        return $booking->fresh();
    }

    public function completeBooking(Booking $booking): Booking
    {
        $booking->update(['status' => Booking::STATUS_COMPLETED]);

        return $booking->fresh();
    }
}
