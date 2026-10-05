<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    /**
     * Slugs that would collide with top-level application routes and can
     * never be used as a tenant's public booking page slug.
     */
    public const RESERVED_SLUGS = [
        'login', 'register', 'logout', 'forgot-password', 'reset-password',
        'dashboard', 'admin', 'explore', 'api', 'storage', 'up', 'book',
        'about', 'contact', 'terms', 'privacy',
    ];

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'description',
        'image',
        'phone',
        'email',
        'address',
        'city',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function workingHours()
    {
        return $this->hasMany(WorkingHour::class);
    }

    public function breaks()
    {
        return $this->hasMany(BreakTime::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
