<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\TenantController as AdminTenantController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Dashboard\BookingController as DashboardBookingController;
use App\Http\Controllers\Dashboard\CalendarController;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\ServiceController;
use App\Http\Controllers\Dashboard\WorkingHourController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\TenantPublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public marketplace
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/explore', [MarketplaceController::class, 'index'])->name('marketplace.index');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Tenant dashboard (authenticated business owners only)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'tenant'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');

    Route::get('/bookings', [DashboardBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [DashboardBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [DashboardBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{booking}/complete', [DashboardBookingController::class, 'complete'])->name('bookings.complete');

    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::post('/services/{service}/toggle', [ServiceController::class, 'toggle'])->name('services.toggle');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

    Route::get('/working-hours', [WorkingHourController::class, 'index'])->name('working-hours.index');
    Route::put('/working-hours', [WorkingHourController::class, 'update'])->name('working-hours.update');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('index');
    Route::get('/tenants', [AdminTenantController::class, 'index'])->name('tenants.index');
    Route::post('/tenants/{tenant}/toggle', [AdminTenantController::class, 'toggle'])->name('tenants.toggle');
});

/*
|--------------------------------------------------------------------------
| Public tenant page + booking flow
|--------------------------------------------------------------------------
| Kept last: {tenant} is a catch-all slug and must not shadow the named
| routes above. Reserved slugs are also rejected at tenant-creation time
| (see Tenant::RESERVED_SLUGS) as a second line of defence.
*/

Route::get('/{tenant:slug}', [TenantPublicController::class, 'show'])->name('tenants.show');
Route::get('/{tenant:slug}/book', [TenantPublicController::class, 'book'])->name('tenants.book');
Route::get('/{tenant:slug}/book/dates', [TenantPublicController::class, 'availableDates'])->name('tenants.book.dates');
Route::get('/{tenant:slug}/book/slots', [TenantPublicController::class, 'availableSlots'])->name('tenants.book.slots');
Route::post('/{tenant:slug}/book', [BookingController::class, 'store'])->name('tenants.book.store');
Route::get('/{tenant:slug}/book/confirmation/{booking:public_id}', [TenantPublicController::class, 'confirmation'])->name('tenants.book.confirmation');
