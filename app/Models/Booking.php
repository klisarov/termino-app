<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    public const STATUS_BOOKED = 'booked';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'public_id',
        'tenant_id',
        'service_id',
        'customer_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'customer_note',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            $booking->public_id ??= (string) Str::uuid();
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_BOOKED);
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }
}
