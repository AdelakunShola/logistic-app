@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">All Notifications</h1>
            <p class="text-gray-600">View and manage your notifications</p>
        </div>
        <button onclick="markAllAsRead()" class="inline-flex items-center justify-center text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 h-9 rounded-md px-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            Mark All as Read
        </button>
    </div>

    <!-- Filter Tabs -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <button onclick="filterNotifications('all')" id="tab-all" class="notification-tab active whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                All
            </button>
            <button onclick="filterNotifications('unread')" id="tab-unread" class="notification-tab whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Unread
            </button>
            <button onclick="filterNotifications('read')" id="tab-read" class="notification-tab whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Read
            </button>
        </nav>
    </div>

    <!-- Notifications List -->
    <div class="space-y-2" id="notifications-container">
        @forelse($notifications as $notification)
        <div class="notification-item bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition {{ $notification->is_read ? 'read-notification' : 'unread-notification' }}" data-notification-id="{{ $notification->id }}" data-read="{{ $notification->is_read ? 'true' : 'false' }}">
            <div class="flex items-start gap-4">
                <!-- Icon -->
                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center {{ $notification->color_class }}">
                    @if($notification->type === 'success')
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    @elseif($notification->type === 'warning')
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    @elseif($notification->type === 'error')
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    @elseif($notification->type === 'shipment_update')
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    </svg>
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    @endif
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <h3 class="text-base font-semibold {{ $notification->is_read ? 'text-gray-700' : 'text-gray-900' }}">
                                {{ $notification->title }}
                                @if(!$notification->is_read)
                                <span class="inline-block w-2 h-2 bg-blue-600 rounded-full ml-2"></span>
                                @endif
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                            
                            <!-- Meta Info -->
                            <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    {{ $notification->time_ago }}
                                </span>
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $notification->type === 'success' ? 'bg-green-100 text-green-800' : '' }} {{ $notification->type === 'warning' ? 'bg-yellow-100 text-yellow-800' : '' }} {{ $notification->type === 'error' ? 'bg-red-100 text-red-800' : '' }} {{ $notification->type === 'shipment_update' ? 'bg-blue-100 text-blue-800' : '' }} {{ $notification->type === 'info' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2">
                            @if($notification->action_url)
                            <a href="{{ route('admin.notifications.view', $notification->id) }}" class="inline-flex items-center justify-center text-sm font-medium text-blue-600 hover:text-blue-700 h-8 rounded-md px-3">
                                View
                            </a>
                            @endif
                            
                            @if(!$notification->is_read)
                            <button onclick="markAsRead({{ $notification->id }})" class="inline-flex items-center justify-center text-sm font-medium text-gray-600 hover:text-gray-900 h-8 rounded-md px-3">
                                Mark Read
                            </button>
                            @endif
                            
                            <button onclick="deleteNotification({{ $notification->id }})" class="inline-flex items-center justify-center text-sm font-medium text-red-600 hover:text-red-700 h-8 w-8 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mx-auto mb-4 text-gray-300">
                <path d="M10.268 21a2 2 0 0 0 3.464 0"></path>
                <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">No notifications yet</h3>
            <p class="text-gray-500">When you receive notifications, they'll appear here</p>
        </div>
        @endforelse
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Filter notifications
function filterNotifications(filter) {
    const notifications = document.querySelectorAll('.notification-item');
    const tabs = document.querySelectorAll('.notification-tab');
    
    // Update active tab
    tabs.forEach(tab => {
        tab.classList.remove('active', 'border-blue-500', 'text-blue-600');
        tab.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    });
    
    const activeTab = document.getElementById(`tab-${filter}`);
    activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    
    // Filter notifications
    notifications.forEach(notification => {
        const isRead = notification.dataset.read === 'true';
        
        if (filter === 'all') {
            notification.style.display = 'block';
        } else if (filter === 'unread' && !isRead) {
            notification.style.display = 'block';
        } else if (filter === 'read' && isRead) {
            notification.style.display = 'block';
        } else {
            notification.style.display = 'none';
        }
    });
}

// Mark single notification as read
async function markAsRead(notificationId) {
    try {
        const response = await fetch(`/admin/notifications/${notificationId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            const notification = document.querySelector(`[data-notification-id="${notificationId}"]`);
            notification.classList.remove('unread-notification');
            notification.classList.add('read-notification');
            notification.dataset.read = 'true';
            
            // Remove unread badge
            const badge = notification.querySelector('.bg-blue-600.rounded-full');
            if (badge) badge.remove();
            
            // Remove "Mark Read" button
            const markReadBtn = notification.querySelector('button[onclick*="markAsRead"]');
            if (markReadBtn) markReadBtn.remove();
            
            // Update text color
            const title = notification.querySelector('h3');
            title.classList.remove('text-gray-900');
            title.classList.add('text-gray-700');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to mark notification as read');
    }
}

// Mark all as read
async function markAllAsRead() {
    if (!confirm('Mark all notifications as read?')) return;
    
    try {
        const response = await fetch('/admin/notifications/mark-all-as-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to mark all as read');
    }
}

// Delete notification
async function deleteNotification(notificationId) {
    if (!confirm('Delete this notification?')) return;
    
    try {
        const response = await fetch(`/admin/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            const notification = document.querySelector(`[data-notification-id="${notificationId}"]`);
            notification.style.transition = 'opacity 0.3s';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to delete notification');
    }
}

// Tab styling
document.addEventListener('DOMContentLoaded', function() {
    const activeTab = document.querySelector('.notification-tab.active');
    if (activeTab) {
        activeTab.classList.add('border-blue-500', 'text-blue-600');
    }
    
    document.querySelectorAll('.notification-tab:not(.active)').forEach(tab => {
        tab.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    });
});
</script>

@endsection