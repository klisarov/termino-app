<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function index(Request $request): View
    {
        $query = Tenant::query()->with(['user', 'category'])->withCount('bookings');

        if ($search = $request->string('q')->trim()->value()) {
            $query->where('name', 'like', "%{$search}%");
        }

        $tenants = $query->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.tenants.index', compact('tenants'));
    }

    public function toggle(Tenant $tenant): RedirectResponse
    {
        $tenant->update(['is_active' => ! $tenant->is_active]);

        return back()->with('status', $tenant->is_active ? 'Tenant activated.' : 'Tenant deactivated.');
    }
}
