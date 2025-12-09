<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    /**
     * Create a notification for a specific user
     */
    public static function create(
        ?int $userId,
        string $title,
        string $message,
        string $type = 'info',
        ?string $actionUrl = null,
        ?array $data = null,
        ?int $shipmentId = null,
        ?int $orderId = null
    ): Notification {
        return Notification::create([
            'user_id' => $userId,
            'shipment_id' => $shipmentId,
            'order_id' => $orderId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'action_url' => $actionUrl,
            'data' => $data,
        ]);
    }

    /**
     * Create notification for all admins
     * user_id = NULL means it's visible to ALL admins
     */
    public static function notifyAdmins(
        string $title,
        string $message,
        string $type = 'info',
        ?string $actionUrl = null,
        ?array $data = null
    ): Notification {
        return Notification::create([
            'user_id' => null, // NULL = visible to all admins
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'action_url' => $actionUrl,
            'data' => $data,
        ]);
    }

    /**
     * Notify specific user about shipment update
     */
    public static function shipmentUpdate(
        int $userId,
        int $shipmentId,
        string $message,
        ?string $actionUrl = null
    ): Notification {
        return self::create(
            userId: $userId,
            title: 'Shipment Update',
            message: $message,
            type: 'shipment_update',
            actionUrl: $actionUrl ?? route('admin.shipments.show', $shipmentId),
            shipmentId: $shipmentId
        );
    }

    /**
     * Notify user about order update
     */
    public static function orderUpdate(
        int $userId,
        int $orderId,
        string $message,
        string $type = 'info',
        ?string $actionUrl = null
    ): Notification {
        return self::create(
            userId: $userId,
            title: 'Order Update',
            message: $message,
            type: $type,
            actionUrl: $actionUrl ?? route('admin.orders.show', $orderId),
            orderId: $orderId
        );
    }

    /**
     * Notify about payment received
     */
    public static function paymentReceived(
        int $userId,
        string $amount,
        string $orderNumber,
        ?string $actionUrl = null
    ): Notification {
        return self::create(
            userId: $userId,
            title: 'Payment Received',
            message: "Payment of {$amount} received for Order {$orderNumber}",
            type: 'success',
            actionUrl: $actionUrl
        );
    }

    /**
     * Notify about delivery delay
     */
    public static function deliveryDelay(
        int $userId,
        int $shipmentId,
        string $reason,
        ?string $actionUrl = null
    ): Notification {
        return self::create(
            userId: $userId,
            title: 'Delivery Delay Alert',
            message: "Shipment delayed. Reason: {$reason}",
            type: 'warning',
            actionUrl: $actionUrl ?? route('admin.shipments.show', $shipmentId),
            shipmentId: $shipmentId
        );
    }

    /**
     * Get notifications for user based on role
     * Admin: sees ALL notifications (user_id = NULL + all others)
     * User: only sees their own (user_id = their ID)
     */
    public static function getNotifications(int $userId, string $userRole, int $limit = 10)
    {
        $isAdmin = in_array($userRole, ['admin', 'super_admin']);

        if ($isAdmin) {
            // Admin sees ALL notifications
            return Notification::orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        }

        // Regular user only sees their own
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get unread count
     */
    public static function getUnreadCount(int $userId, string $userRole): int
    {
        $isAdmin = in_array($userRole, ['admin', 'super_admin']);

        if ($isAdmin) {
            // Admin sees all unread
            return Notification::unread()->count();
        }

        // Regular user only their own
        return Notification::unread()
            ->where('user_id', $userId)
            ->count();
    }

    /**
     * Mark notification as read
     */
    public static function markAsRead(int $notificationId): bool
    {
        $notification = Notification::find($notificationId);

        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark all as read for user
     */
    public static function markAllAsRead(int $userId, string $userRole): int
    {
        $isAdmin = in_array($userRole, ['admin', 'super_admin']);

        if ($isAdmin) {
            // Admin marks all as read
            return Notification::unread()->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        // Regular user marks only their own
        return Notification::unread()
            ->where('user_id', $userId)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Delete old read notifications (cleanup)
     */
    public static function cleanup(int $days = 30): int
    {
        return Notification::where('created_at', '<', now()->subDays($days))
            ->where('is_read', true)
            ->delete();
    }
}