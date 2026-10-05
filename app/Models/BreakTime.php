<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents a row in the `breaks` table (e.g. a lunch break). Named
 * BreakTime rather than Break because `break` is a reserved PHP keyword.
 */
class BreakTime extends Model
{
    use HasFactory;

    protected $table = 'breaks';

    protected $fillable = [
        'tenant_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
        ];
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
