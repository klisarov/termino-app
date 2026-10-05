<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Tenant;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_tenants' => Tenant::query()->count(),
            'active_tenants' => Tenant::query()->active()->count(),
            'total_bookings' => Booking::query()->count(),
            'total_customers' => Customer::query()->count(),
        ];

        $recentTenants = Tenant::query()->with(['user', 'category'])->latest()->take(5)->get();
        $recentBookings = Booking::query()->with(['tenant', 'service'])->latest()->take(5)->get();

        return view('admin.index', compact('stats', 'recentTenants', 'recentBookings'));
    }
}
