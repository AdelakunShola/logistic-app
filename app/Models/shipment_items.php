<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
class shipment_items extends Model
{
    use HasFactory;
use LogsActivity;
    protected $guarded = [];
    protected $casts = [
        'quantity' => 'integer',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'value' => 'decimal:2',
        'is_hazardous' => 'boolean',
    ];

    // Relationships
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    // Accessors
    public function getVolumeAttribute()
    {
        if (!$this->length || !$this->width || !$this->height) {
            return 0;
        }
        return $this->length * $this->width * $this->height;
    }

    public function getVolumetricWeightAttribute()
    {
        // Volumetric weight = (L × W × H) / 166 (for inches)
        return $this->volume / 166;
    }

    public function getChargeableWeightAttribute()
    {
        return max($this->weight, $this->volumetric_weight);
    }

    public function getTotalWeightAttribute()
    {
        return $this->weight * $this->quantity;
    }

    public function getTotalValueAttribute()
    {
        return $this->value * $this->quantity;
    }

    public function getFormattedValueAttribute()
    {
        return '$' . number_format($this->value, 2);
    }

    public function getDimensionsAttribute()
    {
        if (!$this->length || !$this->width || !$this->height) {
            return 'N/A';
        }
        return "{$this->length}\" × {$this->width}\" × {$this->height}\"";
    }
}