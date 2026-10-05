<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\Booking\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $tenant = $request->user()->tenant;

        $filter = $request->string('filter', 'upcoming')->value();

        $query = Booking::query()->where('tenant_id', $tenant->id)->with(['service', 'customer']);

        match ($filter) {
            'today' => $query->whereDate('booking_date', today()),
            'past' => $query->where(function ($q) {
                $q->whereDate('booking_date', '<', today())->orWhere('status', '!=', Booking::STATUS_BOOKED);
            }),
            default => $query->whereDate('booking_date', '>=', today())->where('status', Booking::STATUS_BOOKED),
        };

        $bookings = $query->orderBy('booking_date')->orderBy('start_time')->paginate(20)->withQueryString();

        return view('dashboard.bookings.index', compact('bookings', 'filter'));
    }

    public function show(Request $request, Booking $booking): View
    {
        $this->authorize('view', $booking);

        $booking->load(['service', 'customer']);

        return view('dashboard.bookings.show', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking, BookingService $bookingService): RedirectResponse
    {
        $this->authorize('update', $booking);

        $bookingService->cancelBooking($booking);

        return back()->with('status', 'Booking cancelled.');
    }

    public function complete(Request $request, Booking $booking, BookingService $bookingService): RedirectResponse
    {
        $this->authorize('update', $booking);

        $bookingService->completeBooking($booking);

        return back()->with('status', 'Booking marked as completed.');
    }
}
