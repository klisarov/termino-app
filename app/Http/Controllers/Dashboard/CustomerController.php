<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $tenant = $request->user()->tenant;

        $search = $request->string('search')->trim()->value();

        $customers = $tenant->customers()
            ->withCount('bookings')
            ->with([
                'bookings' => fn ($q) => $q
                    ->latest('booking_date')
                    ->latest('start_time')
                    ->limit(1),
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('dashboard.customers.index', compact(
            'customers',
            'search'
        ));
    }
}