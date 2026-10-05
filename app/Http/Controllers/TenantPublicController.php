<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Tenant;
use App\Services\Booking\AvailabilityService;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantPublicController extends Controller
{
    public function show(Tenant $tenant): View
    {
        abort_unless($tenant->is_active, 404);

        $tenant->load(['category', 'services' => fn ($q) => $q->active()->orderBy('price')]);

        return view('tenants.show', compact('tenant'));
    }

    public function book(Request $request, Tenant $tenant, AvailabilityService $availability): View
    {
        abort_unless($tenant->is_active, 404);

        $service = Service::query()
            ->where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->findOrFail($request->integer('service_id'));

        $availableDates = $availability->getAvailableDates($tenant, $service, 30);

        return view('tenants.book', compact('tenant', 'service', 'availableDates'));
    }

    public function availableDates(Request $request, Tenant $tenant, AvailabilityService $availability): JsonResponse
    {
        $service = Service::query()
            ->where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->findOrFail($request->integer('service_id'));

        return response()->json([
            'dates' => $availability->getAvailableDates($tenant, $service, 60),
        ]);
    }

    public function availableSlots(Request $request, Tenant $tenant, AvailabilityService $availability): JsonResponse
    {
        $service = Service::query()
            ->where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->findOrFail($request->integer('service_id'));

        $date = CarbonImmutable::parse($request->string('date'));

        return response()->json([
            'slots' => $availability->getAvailableSlots($tenant, $service, $date->toDateString()),
        ]);
    }

    public function confirmation(Tenant $tenant, Booking $booking): View
    {
        abort_unless($booking->tenant_id === $tenant->id, 404);

        $booking->load(['service', 'customer']);

        return view('tenants.confirmation', compact('tenant', 'booking'));
    }
}
