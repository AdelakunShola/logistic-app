<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;


class Carriers extends Model
{
    use HasFactory;
use LogsActivity;
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}