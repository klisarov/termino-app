<?php

namespace App\Http\Controllers;

use App\Exceptions\SlotUnavailableException;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Service;
use App\Models\Tenant;
use App\Services\Booking\BookingService;
use Illuminate\Http\RedirectResponse;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request, Tenant $tenant, BookingService $bookingService): RedirectResponse
    {
        abort_unless($tenant->is_active, 404);

        $data = $request->validated();

        $service = Service::query()
            ->where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->findOrFail($data['service_id']);

        try {
            $booking = $bookingService->createBooking(
                tenant: $tenant,
                service: $service,
                date: $data['booking_date'],
                startTime: $data['start_time'],
                customerData: [
                    'name' => $data['customer_name'],
                    'email' => $data['customer_email'],
                    'phone' => $data['customer_phone'],
                    'address' => $data['customer_address'] ?? null,
                    'city' => $data['customer_city'] ?? null,
                ],
                note: $data['customer_note'] ?? null,
            );
        } catch (SlotUnavailableException $e) {
            return back()
                ->withInput()
                ->with('booking_error', $e->getMessage())
                ->with('service_id', $service->id);
        }

        return redirect()->route('tenants.book.confirmation', [
            'tenant' => $tenant->slug,
            'booking' => $booking->public_id,
        ]);
    }
}
