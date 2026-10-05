<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use App\Models\Tenant;
use App\Services\Booking\AvailabilityService;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function index(Request $request, AvailabilityService $availability): View
    {
        $query = Tenant::query()->active()->with('category');

        if ($search = $request->string('q')->trim()->value()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('services', fn ($s) => $s->where('name', 'like', "%{$search}%")->active());
            });
        }

        if ($categorySlug = $request->string('category')->trim()->value()) {
            $query->whereHas('category', fn ($c) => $c->where('slug', $categorySlug));
        }

        if ($city = $request->string('city')->trim()->value()) {
            $query->where('city', 'like', "%{$city}%");
        }

        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('services', function ($s) use ($request) {
                $s->active();
                if ($request->filled('min_price')) {
                    $s->where('price', '>=', (float) $request->input('min_price'));
                }
                if ($request->filled('max_price')) {
                    $s->where('price', '<=', (float) $request->input('max_price'));
                }
            });
        }

        if ($request->boolean('available_today')) {
            $today = CarbonImmutable::today()->dayOfWeek;
            $query->whereHas('workingHours', fn ($w) => $w->where('day_of_week', $today)->where('is_active', true));
        }

        $tenants = $query->with(['services' => fn ($s) => $s->active()->orderBy('price')])
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        // Compute a lightweight "starting from" price and next available slot
        // for each card. Bounded to the current page (12 tenants) so it stays cheap.
        $tenants->getCollection()->transform(function (Tenant $tenant) use ($availability) {
            $tenant->starting_price = $tenant->services->min('price');
            $tenant->next_available = $this->nextAvailableLabel($tenant, $availability);

            return $tenant;
        });

        return view('explore', [
            'tenants' => $tenants,
            'categories' => Category::query()->active()->orderBy('name')->get(),
            'filters' => $request->only(['q', 'category', 'city', 'min_price', 'max_price', 'available_today']),
        ]);
    }

    private function nextAvailableLabel(Tenant $tenant, AvailabilityService $availability): ?string
    {
        /** @var Service|null $service */
        $service = $tenant->services->first();

        if (! $service) {
            return null;
        }

        $dates = $availability->getAvailableDates($tenant, $service, 7);

        if (empty($dates)) {
            return null;
        }

        $date = CarbonImmutable::parse($dates[0]);
        $slots = $availability->getAvailableSlots($tenant, $service, $date->toDateString());

        if (empty($slots)) {
            return null;
        }

        $label = $date->isToday() ? 'Today' : ($date->isTomorrow() ? 'Tomorrow' : $date->format('D, M j'));

        return "{$label} {$slots[0]}";
    }
}
