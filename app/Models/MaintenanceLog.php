<?php

// app/Models/MaintenanceLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
class MaintenanceLog extends Model
{
    use HasFactory, SoftDeletes;
use LogsActivity;
    
    protected $guarded = [];

    protected $casts = [
        'maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'cost' => 'decimal:2',
        'mileage_at_maintenance' => 'integer',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessor for formatted cost
    public function getFormattedCostAttribute()
    {
        return '$' . number_format($this->cost, 2);
    }


    /**
     * Scope a query to only include scheduled maintenance
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope a query to only include completed maintenance
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include in-progress maintenance
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope a query to only include preventive maintenance
     */
    public function scopePreventive($query)
    {
        return $query->whereIn('maintenance_type', ['scheduled', 'inspection', 'service']);
    }

    /**
     * Scope a query to only include repair maintenance
     */
    public function scopeRepair($query)
    {
        return $query->whereIn('maintenance_type', ['breakdown', 'repair']);
    }

    /**
     * Scope a query for maintenance within a date range
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('maintenance_date', [$startDate, $endDate]);
    }

    /**
/**
     * Get the status color for display
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'completed' => 'green',
            'in_progress' => 'blue',
            'scheduled' => 'yellow',
            'cancelled' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get the priority color for display
     */
    public function getPriorityColorAttribute()
    {
        return match($this->priority ?? 'medium') {
            'critical' => 'red',
            'high' => 'orange',
            'medium' => 'yellow',
            'low' => 'green',
            default => 'gray'
        };
    }


    /**
     * Check if maintenance is overdue
     */
    public function getIsOverdueAttribute()
    {
        if ($this->status === 'completed' || $this->status === 'cancelled') {
            return false;
        }

        return $this->maintenance_date->isPast();
    }

    /**
     * Get days until maintenance
     */
    public function getDaysUntilAttribute()
    {
        if ($this->status === 'completed' || $this->status === 'cancelled') {
            return null;
        }

        return now()->diffInDays($this->maintenance_date, false);
    }

   

   
}



