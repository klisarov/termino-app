<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(Request $request): View
    {
        $tenant = $request->user()->tenant;

        $month = CarbonImmutable::parse($request->string('month', now()->format('Y-m-01'))->value())->startOfMonth();
        $selectedDate = CarbonImmutable::parse($request->string('date', $month->toDateString())->value());

        $bookings = Booking::query()
            ->where('tenant_id', $tenant->id)
            ->whereBetween('booking_date', [$month->startOfMonth()->toDateString(), $month->endOfMonth()->toDateString()])
            ->with(['service', 'customer'])
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn (Booking $booking) => $booking->booking_date->toDateString());

        $selectedDayBookings = $bookings->get($selectedDate->toDateString(), collect());

        return view('dashboard.calendar.index', compact('month', 'selectedDate', 'bookings', 'selectedDayBookings'));
    }
}
