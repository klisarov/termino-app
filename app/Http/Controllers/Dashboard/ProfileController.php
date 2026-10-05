<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTenantProfileRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $tenant = $request->user()->tenant;
        $categories = Category::query()->active()->orderBy('name')->get();

        return view('dashboard.profile.edit', compact('tenant', 'categories'));
    }

    public function update(UpdateTenantProfileRequest $request): RedirectResponse
    {
        $tenant = $request->user()->tenant;
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tenants', 'public');
        } else {
            unset($data['image']);
        }

        $tenant->update($data);

        return redirect()->route('dashboard.profile.edit')->with('status', 'Business profile updated.');
    }
}
