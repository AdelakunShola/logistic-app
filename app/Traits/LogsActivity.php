<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    /**
     * Boot the trait
     */
    protected static function bootLogsActivity()
    {
        // Created event
        static::created(function ($model) {
            if ($model->shouldLogActivity('created')) {
                ActivityLog::logActivity(
                    'created',
                    get_class($model),
                    $model->id,
                    $model->getActivityDescription('created') ?? class_basename($model) . ' created',
                    null,
                    $model->getLoggableAttributes()
                );
            }
        });

        // Updated event
        static::updated(function ($model) {
            if ($model->shouldLogActivity('updated') && $model->wasChanged()) {
                ActivityLog::logActivity(
                    'updated',
                    get_class($model),
                    $model->id,
                    $model->getActivityDescription('updated') ?? class_basename($model) . ' updated',
                    array_intersect_key($model->getOriginal(), $model->getChanges()),
                    $model->getChanges()
                );
            }
        });

        // Deleted event
        static::deleted(function ($model) {
            if ($model->shouldLogActivity('deleted')) {
                ActivityLog::logActivity(
                    'deleted',
                    get_class($model),
                    $model->id,
                    $model->getActivityDescription('deleted') ?? class_basename($model) . ' deleted',
                    $model->getLoggableAttributes(),
                    null
                );
            }
        });
    }

    /**
     * Determine if the activity should be logged
     */
    protected function shouldLogActivity(string $action): bool
    {
        // Check if logging is temporarily disabled
        if (property_exists($this, 'disableActivityLogging') && $this->disableActivityLogging) {
            return false;
        }

        // Check if specific actions are excluded
        if (property_exists($this, 'excludedActivityActions')) {
            if (in_array($action, $this->excludedActivityActions)) {
                return false;
            }
        }

        // Check if only specific actions are included
        if (property_exists($this, 'logOnlyActivityActions')) {
            if (!in_array($action, $this->logOnlyActivityActions)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the description for the activity
     */
    protected function getActivityDescription(string $action): ?string
    {
        // Check if custom description method exists
        $method = 'get' . ucfirst($action) . 'ActivityDescription';
        
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        // Check if custom descriptions array exists
        if (property_exists($this, 'activityDescriptions') && isset($this->activityDescriptions[$action])) {
            return $this->activityDescriptions[$action];
        }

        return null;
    }

    /**
     * Get the attributes that should be logged
     * Override this to exclude sensitive attributes
     */
    protected function getLoggableAttributes(): array
    {
        $attributes = $this->toArray();

        // Exclude sensitive attributes
        if (property_exists($this, 'hiddenFromActivityLog')) {
            $attributes = array_diff_key($attributes, array_flip($this->hiddenFromActivityLog));
        }

        return $attributes;
    }

    /**
     * Temporarily disable activity logging
     */
    public function withoutActivityLogging(callable $callback)
    {
        $this->disableActivityLogging = true;
        
        try {
            return $callback($this);
        } finally {
            $this->disableActivityLogging = false;
        }
    }

    /**
     * Get activities for this model instance
     */
    public function activities()
    {
        return ActivityLog::where('model_type', get_class($this))
            ->where('model_id', $this->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}