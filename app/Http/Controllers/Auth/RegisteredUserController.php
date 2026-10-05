<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterTenantRequest;
use App\Models\Category;
use App\Models\User;
use App\Services\TenantService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register', [
            'categories' => Category::query()->active()->orderBy('name')->get(),
        ]);
    }

    public function store(RegisterTenantRequest $request, TenantService $tenantService)
    {
        $data = $request->validated();

        $user = User::query()->create([
            'name' => $data['owner_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_TENANT,
        ]);

        $tenantService->createForUser($user, [
            'name' => $data['business_name'],
            'category_id' => $data['category_id'],
            'city' => $data['city'],
            'email' => $data['email'],
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard.index')->with('status', 'Добредојде! Твојата страница со резервации е активна.');
    }
}
