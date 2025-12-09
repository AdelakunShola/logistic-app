<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
class ShipmentTracking extends Model
{
    use HasFactory;
use LogsActivity;
    protected $table = 'shipment_tracking';

    protected $guarded = [];


  

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

   

    // Relationships
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }


    public function hub()
    {
        return $this->belongsTo(Hub::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getLocationNameAttribute()
    {
        if ($this->branch) {
            return $this->branch->name;
        }

        if ($this->warehouse) {
            return $this->warehouse->name;
        }
        
        
        if ($this->hub) {
            return $this->hub->name;
        }
        
        return $this->location ?? 'Unknown Location';
    }

    // Scopes
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}