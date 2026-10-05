<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use App\Models\WorkingHour;
use Illuminate\Support\Str;

/**
 * Handles tenant lifecycle concerns that don't belong in a controller:
 * slug generation and bootstrapping a brand new business on registration.
 */
class TenantService
{
    /**
     * Create a tenant for a freshly registered user and seed it with a
     * sensible default Mon-Fri 09:00-17:00 working week so the dashboard
     * and public page aren't empty on day one.
     */
    public function createForUser(User $user, array $data): Tenant
    {
        $tenant = Tenant::query()->create([
            'user_id' => $user->id,
            'category_id' => $data['category_id'] ?? null,
            'name' => $data['name'],
            'slug' => $this->generateUniqueSlug($data['name']),
            'city' => $data['city'] ?? null,
            'email' => $data['email'] ?? $user->email,
            'is_active' => true,
        ]);

        foreach (range(1, 5) as $weekday) { // Monday(1) .. Friday(5)
            WorkingHour::query()->create([
                'tenant_id' => $tenant->id,
                'day_of_week' => $weekday,
                'start_time' => '09:00',
                'end_time' => '17:00',
                'is_active' => true,
            ]);
        }

        return $tenant;
    }

    public function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'business';
        $slug = $base;
        $suffix = 1;

        while (
            in_array($slug, Tenant::RESERVED_SLUGS, true)
            || Tenant::query()->where('slug', $slug)->exists()
        ) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
