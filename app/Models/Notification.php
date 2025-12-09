<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByChannel($query, $channel)
    {
        return $query->where('channel', $channel);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return $this;
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);

        return $this;
    }

    // Accessors
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIconAttribute()
    {
        $icons = [
            'info' => 'info-circle',
            'success' => 'check-circle',
            'warning' => 'exclamation-triangle',
            'error' => 'times-circle',
            'shipment_update' => 'truck',
        ];

        return $icons[$this->type] ?? 'bell';
    }

    public function getColorAttribute()
    {
        $colors = [
            'info' => 'blue',
            'success' => 'green',
            'warning' => 'yellow',
            'error' => 'red',
            'shipment_update' => 'indigo',
        ];

        return $colors[$this->type] ?? 'gray';
    }






    public function scopeRecent($query, $limit = 20)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }

   

    public function getColorClassAttribute()
    {
        $colors = [
            'info' => 'blue',
            'success' => 'green',
            'warning' => 'yellow',
            'error' => 'red',
            'shipment_update' => 'indigo',
        ];

        return $colors[$this->type] ?? 'gray';
    }

   

    // Static Methods
    public static function markAllAsRead($userId)
    {
        return self::where('user_id', $userId)
                   ->where('is_read', false)
                   ->update([
                       'is_read' => true,
                       'read_at' => now(),
                   ]);
    }

    public static function deleteOld($days = 30)
    {
        return self::where('created_at', '<', now()->subDays($days))->delete();
    }

    public static function getUnreadCount($userId)
    {
        return self::where('user_id', $userId)->unread()->count();
    }

    public static function notifyUser($userId, $title, $message, $type = 'info', $shipmentId = null, $actionUrl = null)
    {
        return self::create([
            'user_id' => $userId,
            'shipment_id' => $shipmentId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'channel' => 'system',
            'action_url' => $actionUrl,
        ]);
    }

    public static function notifyShipmentUpdate($userId, $shipmentId, $status, $message = null)
    {
        $shipment = Shipment::find($shipmentId);
        
        if (!$shipment) {
            return null;
        }

        return self::create([
            'user_id' => $userId,
            'shipment_id' => $shipmentId,
            'title' => "Shipment {$shipment->tracking_number} Update",
            'message' => $message ?? "Shipment status: {$status}",
            'type' => 'shipment_update',
            'channel' => 'system',
            'action_url' => route('shipments.show', $shipmentId),
            'data' => [
                'tracking_number' => $shipment->tracking_number,
                'status' => $status,
            ],
        ]);
    }





    /**
     * Get the related order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    /**
     * Scope for filtering by type
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for filtering by channel
     */
    public function scopeChannel($query, $channel)
    {
        return $query->where('channel', $channel);
    }

 

}




