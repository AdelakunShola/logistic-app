<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];





     public function model()
    {
        return $this->morphTo();
    }


    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

   

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

   

     public function scopeByModel($query, $modelType, $modelId = null)
    {
        $query->where('model_type', $modelType);
        
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        
        return $query;
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->first_name . ' ' . $this->user->last_name : 'System';
    }

    public function getActionBadgeAttribute()
    {
        $badges = [
            'created' => 'success',
            'updated' => 'info',
            'deleted' => 'danger',
            'viewed' => 'secondary',
            'exported' => 'warning',
            'assigned_driver' => 'info',
            'tracked' => 'primary',
            'maintenance_scheduled' => 'warning',
        ];

        return $badges[$this->action] ?? 'secondary';
    }

    public function getActionIconAttribute()
    {
        $icons = [
            'created' => 'plus-circle',
            'updated' => 'edit',
            'deleted' => 'trash',
            'viewed' => 'eye',
            'exported' => 'download',
            'assigned_driver' => 'user-plus',
            'tracked' => 'map-marker',
            'maintenance_scheduled' => 'wrench',
        ];

        return $icons[$this->action] ?? 'activity';
    }

  



     public static function logActivity(
        string $action,
        ?string $modelType = null,
        ?int $modelId = null,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $userId = null
    ): self {
        return self::create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    public function getChanges()
    {
        if (!$this->old_values || !$this->new_values) {
            return [];
        }

        $changes = [];
        foreach ($this->new_values as $key => $newValue) {
            $oldValue = $this->old_values[$key] ?? null;
            if ($oldValue != $newValue) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changes;
    }


    public function getChangesAttribute()
    {
        if (empty($this->old_values) || empty($this->new_values)) {
            return [];
        }

        $changes = [];
        foreach ($this->new_values as $key => $newValue) {
            $oldValue = $this->old_values[$key] ?? null;
            if ($oldValue != $newValue) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changes;
    }










    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }



    public function getActionLabelAttribute()
    {
        $labels = [
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'status_updated' => 'Status Updated',
            'assigned_to_driver' => 'Assigned to Driver',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ];

        return $labels[$this->action] ?? ucfirst(str_replace('_', ' ', $this->action));
    }

    public function getModelNameAttribute()
    {
        if (!$this->model_type) {
            return 'N/A';
        }

        $parts = explode('\\', $this->model_type);
        return end($parts);
    }

   

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

  

     public static function log(string $action, $model = null, ?string $description = null): self
    {
        return self::logActivity(
            action: $action,
            modelType: $model ? get_class($model) : null,
            modelId: $model?->id,
            description: $description ?? self::generateDescription($action, $model),
            oldValues: null,
            newValues: $model?->toArray()
        );
    }
 












    /**
     * Scope for filtering by action
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for filtering by model type
     */
    public function scopeModelType($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Get formatted action name
     */
    public function getFormattedActionAttribute()
    {
        return ucfirst($this->action);
    }

    /**
     * Get action color for badges
     */
    public function getActionColorAttribute()
    {
        $colors = [
            'created' => 'green',
            'updated' => 'blue',
            'deleted' => 'red',
            'cancelled' => 'orange',
            'assigned' => 'purple',
            'completed' => 'green',
        ];

        return $colors[$this->action] ?? 'gray';
    }





    public function scopeForModel($query, string $modelType)
    {
        return $query->where('model_type', $modelType);
    }

  

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Helper method for manual logging with better API
     */
   

    /**
     * Generate smart descriptions
     */
    private static function generateDescription(string $action, $model): string
    {
        if (!$model) {
            return ucfirst($action);
        }

        $modelName = class_basename($model);
        $identifier = $model->name ?? $model->title ?? $model->tracking_number ?? "#{$model->id}";

        return ucfirst($action) . " {$modelName}: {$identifier}";
    }
}




