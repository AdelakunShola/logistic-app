<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;
use LogsActivity;
    protected $guarded = [];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'last_restock_date' => 'date',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity_in_stock <= $this->reorder_level;
    }

    public function isOutOfStock(): bool
    {
        return $this->quantity_in_stock == 0;
    }
}