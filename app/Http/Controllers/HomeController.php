<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tenant;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()->active()->orderBy('name')->get();

        $featuredTenants = Tenant::query()
            ->active()
            ->with('category')
            ->withCount(['bookings' => fn ($q) => $q->active()])
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('categories', 'featuredTenants'));
    }
}
