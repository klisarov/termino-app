<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $tenant = $request->user()->tenant;

        $todayCount = Booking::query()
            ->where('tenant_id', $tenant->id)
            ->active()
            ->whereDate('booking_date', today())
            ->count();

        $upcomingCount = Booking::query()
            ->where('tenant_id', $tenant->id)
            ->active()
            ->whereDate('booking_date', '>=', today())
            ->count();

        $servicesCount = $tenant->services()->count();

        $todaysBookings = Booking::query()
            ->where('tenant_id', $tenant->id)
            ->whereDate('booking_date', today())
            ->with(['service', 'customer'])
            ->orderBy('start_time')
            ->get();

        return view('dashboard.index', compact('tenant', 'todayCount', 'upcomingCount', 'servicesCount', 'todaysBookings'));
    }
}
