<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
class WarehouseInventory extends Model
{
    use HasFactory;
use LogsActivity;
        protected $guarded = [];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
        'is_priority' => 'boolean',
        'requires_special_handling' => 'boolean',
    ];

     /**
     * Relationships
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function checkedInBy()
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    public function checkedOutBy()
    {
        return $this->belongsTo(User::class, 'checked_out_by');
    }

    /**
     * Scopes
     */
    
    // Get only items currently in storage (not checked out)
    public function scopeActive($query)
    {
        return $query->whereNull('checked_out_at');
    }

    // Get only checked out items
    public function scopeCheckedOut($query)
    {
        return $query->whereNotNull('checked_out_at');
    }

    // Get only priority items
    public function scopePriority($query)
    {
        return $query->where('is_priority', true);
    }

    // Get only damaged items
    public function scopeDamaged($query)
    {
        return $query->where('package_condition', 'damaged');
    }

    // Get items requiring special handling
    public function scopeSpecialHandling($query)
    {
        return $query->where('requires_special_handling', true);
    }

    // Get overdue items (in storage more than X days)
    public function scopeOverdue($query, $days = 7)
    {
        return $query->active()
            ->where('checked_in_at', '<=', now()->subDays($days));
    }

    // Filter by warehouse
    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    // Filter by condition
    public function scopeByCondition($query, $condition)
    {
        return $query->where('package_condition', $condition);
    }

    /**
     * Accessors & Attributes
     */
    
    // Check if item is currently in storage
    public function getIsInStorageAttribute()
    {
        return is_null($this->checked_out_at);
    }

    // Check if item is overdue (more than 7 days in storage)
    public function getIsOverdueAttribute()
    {
        if (!$this->is_in_storage) {
            return false;
        }
        
        return $this->checked_in_at->diffInDays(now()) > 7;
    }

    // Get storage duration in hours
    public function getStorageDurationAttribute()
    {
        $endTime = $this->checked_out_at ?? now();
        return round($this->checked_in_at->diffInHours($endTime), 1);
    }

    // Get storage duration in days
    public function getStorageDurationDaysAttribute()
    {
        $endTime = $this->checked_out_at ?? now();
        return round($this->checked_in_at->diffInDays($endTime), 1);
    }

    // Get formatted condition
    public function getFormattedConditionAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->package_condition));
    }

    /**
     * Helper Methods
     */
    
    // Check if package can be checked out
    public function canCheckOut()
    {
        return $this->is_in_storage;
    }

    // Check if package can be deleted
    public function canDelete()
    {
        return is_null($this->checked_out_at);
    }

    // Get condition badge color
    public function getConditionBadgeColor()
    {
        return [
            'good' => 'green',
            'damaged' => 'red',
            'requires_attention' => 'amber',
        ][$this->package_condition] ?? 'gray';
    }

    // Get status label
    public function getStatusLabel()
    {
        return $this->is_in_storage ? 'In Storage' : 'Checked Out';
    }

    // Get status color
    public function getStatusColor()
    {
        return $this->is_in_storage ? 'blue' : 'gray';
    }
}