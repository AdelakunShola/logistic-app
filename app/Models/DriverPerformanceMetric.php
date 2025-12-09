<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;
// DriverPerformanceMetric Model
class DriverPerformanceMetric extends Model
{
    use LogsActivity;
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'on_time_percentage' => 'decimal:2',
        'distance_travelled' => 'decimal:2',
        'fuel_consumed' => 'decimal:2',
        'hours_worked' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'earnings' => 'decimal:2',
        'additional_metrics' => 'array',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}