<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get notifications for dropdown (AJAX ONLY)
     */
    public function dropdown(Request $request)
    {
        $user = Auth::user();
        
        $notifications = NotificationService::getNotifications(
            $user->id,
            $user->role,
            10 // Only show 10 in dropdown
        );

        return response()->json([
            'success' => true,
            'notifications' => $notifications->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'color_class' => $notification->color_class,
                    'is_read' => $notification->is_read,
                    'time_ago' => $notification->time_ago,
                    'action_url' => $notification->action_url,
                    'created_at' => $notification->created_at->format('M d, Y H:i'),
                ];
            }),
            'unread_count' => NotificationService::getUnreadCount($user->id, $user->role),
        ]);
    }

    /**
     * Get unread count for badge (AJAX)
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        
        $count = NotificationService::getUnreadCount($user->id, $user->role);
        
        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = Notification::find($id);
        
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }
        
        // Check permission
        $isAdmin = in_array($user->role, ['admin', 'super_admin']);
        
        if (!$isAdmin && $notification->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        
        $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
            'unread_count' => NotificationService::getUnreadCount($user->id, $user->role),
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        $count = NotificationService::markAllAsRead($user->id, $user->role);
        
        return response()->json([
            'success' => true,
            'message' => "{$count} notifications marked as read",
            'unread_count' => 0,
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $notification = Notification::find($id);
        
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }
        
        // Check permission
        $isAdmin = in_array($user->role, ['admin', 'super_admin']);
        
        if (!$isAdmin && $notification->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        
        $notification->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification deleted',
        ]);
    }

    /**
     * Show all notifications page
     */
    public function all()
    {
        $user = Auth::user();
        
        $notifications = NotificationService::getNotifications(
            $user->id,
            $user->role,
            100 // Show more on the full page
        );
        
        return view('backend.notifications.all', compact('notifications'));
    }






    public function details($id)
{
    $user = Auth::user();
    $notification = Notification::with('related_model')->find($id);
    
    if (!$notification) {
        return response()->json([
            'success' => false,
            'message' => 'Notification not found',
        ], 404);
    }
    
    // Check permission
    $isAdmin = in_array($user->role, ['admin', 'super_admin']);
    
    if (!$isAdmin && $notification->user_id !== $user->id) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
        ], 403);
    }
    
    // Get the related data (e.g., maintenance record)
    $relatedData = null;
    if ($notification->related_type && $notification->related_id) {
        $modelClass = "App\\Models\\" . $notification->related_type;
        if (class_exists($modelClass)) {
            $relatedData = $modelClass::find($notification->related_id);
        }
    }
    
    return response()->json([
        'success' => true,
        'data' => $relatedData,
    ]);
}


public function view($id)
{
    $user = Auth::user();
    $notification = Notification::find($id);
    
    if (!$notification) {
        return redirect()->route('admin.notifications.all')
            ->with('error', 'Notification not found');
    }
    
    // Check permission
    $isAdmin = in_array($user->role, ['admin', 'super_admin']);
    
    if (!$isAdmin && $notification->user_id !== $user->id) {
        return redirect()->route('admin.notifications.all')
            ->with('error', 'Unauthorized');
    }
    
    // Mark as read
    if (!$notification->is_read) {
        $notification->markAsRead();
    }
    
    // Get the related data
    $relatedData = null;
    if ($notification->related_type && $notification->related_id) {
        $modelClass = "App\\Models\\" . $notification->related_type;
        if (class_exists($modelClass)) {
            $relatedData = $modelClass::with('vehicle')->find($notification->related_id);
        }
    }
    
    return view('backend.notifications.view', compact('notification', 'relatedData'));
}






































///////////DRIVER NOTIFICATION METHODS//////////


    public function dropdowndriver(Request $request)
    {
        $user = Auth::user();
        
        $notifications = NotificationService::getNotifications(
            $user->id,
            $user->role,
            10 // Only show 10 in dropdown
        );

        return response()->json([
            'success' => true,
            'notifications' => $notifications->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'color_class' => $notification->color_class,
                    'is_read' => $notification->is_read,
                    'time_ago' => $notification->time_ago,
                    'action_url' => $notification->action_url,
                    'created_at' => $notification->created_at->format('M d, Y H:i'),
                ];
            }),
            'unread_count' => NotificationService::getUnreadCount($user->id, $user->role),
        ]);
    }

    /**
     * Get unread count for badge (AJAX)
     */
    public function getUnreadCountdriver()
    {
        $user = Auth::user();
        
        $count = NotificationService::getUnreadCount($user->id, $user->role);
        
        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    /**
     * Mark single notification as read
     */
    public function markAsReaddriver($id)
    {
        $user = Auth::user();
        $notification = Notification::find($id);
        
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }
        
        // Check permission
        $isAdmin = in_array($user->role, ['admin', 'super_admin']);
        
        if (!$isAdmin && $notification->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        
        $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
            'unread_count' => NotificationService::getUnreadCount($user->id, $user->role),
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsReaddriver()
    {
        $user = Auth::user();
        
        $count = NotificationService::markAllAsRead($user->id, $user->role);
        
        return response()->json([
            'success' => true,
            'message' => "{$count} notifications marked as read",
            'unread_count' => 0,
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroydriver($id)
    {
        $user = Auth::user();
        $notification = Notification::find($id);
        
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }
        
        // Check permission
        $isAdmin = in_array($user->role, ['admin', 'super_admin']);
        
        if (!$isAdmin && $notification->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        
        $notification->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification deleted',
        ]);
    }

    /**
     * Show all notifications page
     */
    public function alldriver()
    {
        $user = Auth::user();
        
        $notifications = NotificationService::getNotifications(
            $user->id,
            $user->role,
            100 // Show more on the full page
        );
        
        return view('driver.notifications.all', compact('notifications'));
    }






    public function detailsdriver($id)
{
    $user = Auth::user();
    $notification = Notification::with('related_model')->find($id);
    
    if (!$notification) {
        return response()->json([
            'success' => false,
            'message' => 'Notification not found',
        ], 404);
    }
    
    // Check permission
    $isAdmin = in_array($user->role, ['admin', 'super_admin']);
    
    if (!$isAdmin && $notification->user_id !== $user->id) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
        ], 403);
    }
    
    // Get the related data (e.g., maintenance record)
    $relatedData = null;
    if ($notification->related_type && $notification->related_id) {
        $modelClass = "App\\Models\\" . $notification->related_type;
        if (class_exists($modelClass)) {
            $relatedData = $modelClass::find($notification->related_id);
        }
    }
    
    return response()->json([
        'success' => true,
        'data' => $relatedData,
    ]);
}


public function viewdriver($id)
{
    $user = Auth::user();
    $notification = Notification::find($id);
    
    if (!$notification) {
        return redirect()->route('admin.notifications.all')
            ->with('error', 'Notification not found');
    }
    
    // Check permission
    $isAdmin = in_array($user->role, ['admin', 'super_admin']);
    
    if (!$isAdmin && $notification->user_id !== $user->id) {
        return redirect()->route('admin.notifications.all')
            ->with('error', 'Unauthorized');
    }
    
    // Mark as read
    if (!$notification->is_read) {
        $notification->markAsRead();
    }
    
    // Get the related data
    $relatedData = null;
    if ($notification->related_type && $notification->related_id) {
        $modelClass = "App\\Models\\" . $notification->related_type;
        if (class_exists($modelClass)) {
            $relatedData = $modelClass::with('vehicle')->find($notification->related_id);
        }
    }
    
    return view('backend.notifications.view', compact('notification', 'relatedData'));
}
}