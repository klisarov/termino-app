<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $services = $request->user()->tenant->services()->orderBy('name')->get();

        return view('dashboard.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('dashboard.services.create');
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $request->user()->tenant->services()->create($request->validated());

        return redirect()->route('dashboard.services.index')->with('status', 'Услугата е креирана.');
    }

    public function edit(Request $request, Service $service): View
    {
        $this->authorize('update', $service);

        return view('dashboard.services.edit', compact('service'));
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $this->authorize('update', $service);

        $service->update($request->validated());

        return redirect()->route('dashboard.services.index')->with('status', 'Service updated.');
    }

    public function toggle(Request $request, Service $service): RedirectResponse
    {
        $this->authorize('update', $service);

        $service->update(['is_active' => ! $service->is_active]);

        return back()->with('status', $service->is_active ? 'Услугата е активирана.' : 'Услугата е деактивирана.');
    }

    public function destroy(Request $request, Service $service): RedirectResponse
    {
        $this->authorize('delete', $service);

        $service->delete();

        return redirect()->route('dashboard.services.index')->with('status', 'Service deleted.');
    }
}
