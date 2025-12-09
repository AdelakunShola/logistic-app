<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
class RestockRequest extends Model
{
    use HasFactory, SoftDeletes;
use LogsActivity;
     protected $guarded = [];

    protected $casts = [
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'estimated_cost' => 'decimal:2',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function hub(): BelongsTo
    {
        return $this->belongsTo(Hub::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
