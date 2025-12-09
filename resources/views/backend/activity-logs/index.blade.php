@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Activity Logs</h1>
            <p class="text-gray-600">Track all system activities and user actions</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="showCleanupModal()" class="inline-flex items-center justify-center text-sm font-medium border border-input bg-background hover:bg-accent h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="M3 6h18"></path>
                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                </svg>
                Cleanup Old Logs
            </button>
            <button onclick="exportLogs()" class="inline-flex items-center justify-center text-sm font-medium border border-input bg-background hover:bg-accent h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Export CSV
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Logs</p>
                    <p class="text-2xl font-bold">{{ number_format($stats['total']) }}</p>
                </div>
                <div class="p-2 bg-blue-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-500">
                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Today</p>
                    <p class="text-2xl font-bold">{{ number_format($stats['today']) }}</p>
                </div>
                <div class="p-2 bg-green-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-500">
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">This Week</p>
                    <p class="text-2xl font-bold">{{ number_format($stats['this_week']) }}</p>
                </div>
                <div class="p-2 bg-purple-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-purple-500">
                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                        <path d="M21 3v5h-5"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">This Month</p>
                    <p class="text-2xl font-bold">{{ number_format($stats['this_month']) }}</p>
                </div>
                <div class="p-2 bg-orange-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-orange-500">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Users</p>
                    <p class="text-2xl font-bold">{{ number_format($stats['unique_users']) }}</p>
                </div>
                <div class="p-2 bg-red-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-red-500">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-lg border bg-white shadow-sm p-6">
        <form action="{{ route('admin.activity-logs.index') }}" method="GET">
            <div class="flex flex-col md:flex-row gap-4 mb-4">
                <div class="relative flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input type="search" name="search" value="{{ request('search') }}" class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm pl-8" placeholder="Search logs..."/>
                </div>
                <button type="button" onclick="toggleFilters()" class="inline-flex items-center justify-center text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 rounded-md px-3 h-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                    </svg>
                    Filters
                </button>
            </div>

            <!-- Filter Panel -->
            <div id="filter-panel" class="hidden p-4 border rounded-lg bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="text-sm font-medium mb-2 block">User</label>
                        <select name="user_id" class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->first_name }} {{ $user->last_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-2 block">Action</label>
                        <select name="action" class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm">
                            <option value="">All Actions</option>
                            @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $action)) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-2 block">Model Type</label>
                        <select name="model_type" class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm">
                            <option value="">All Types</option>
                            @foreach($modelTypes as $modelType)
                            <option value="{{ $modelType }}" {{ request('model_type') == $modelType ? 'selected' : '' }}>
                                {{ class_basename($modelType) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-2 block">From Date</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-2 block">To Date</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm">
                    </div>
                </div>
                <div class="flex gap-2 mt-4">
                    <button type="submit" class="inline-flex items-center justify-center text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 h-9 rounded-md px-4">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.activity-logs.index') }}" class="inline-flex items-center justify-center text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-4">
                        Clear Filters
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Activity Logs Table -->
    <div class="rounded-lg border bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            {{ $log->created_at->format('M d, Y') }}<br>
                            <span class="text-gray-500">{{ $log->created_at->format('H:i:s') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($log->user)
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2">
                                    <span class="text-xs font-semibold">{{ substr($log->user->first_name, 0, 1) }}{{ substr($log->user->last_name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="font-medium">{{ $log->user->first_name }} {{ $log->user->last_name }}</div>
                                    <div class="text-gray-500 text-xs">{{ $log->user->email }}</div>
                                </div>
                            </div>
                            @else
                            <span class="text-gray-500">System</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                {{ str_contains($log->action, 'create') ? 'bg-green-100 text-green-800' : '' }}
                                {{ str_contains($log->action, 'update') ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ str_contains($log->action, 'delete') ? 'bg-red-100 text-red-800' : '' }}
                                {{ !str_contains($log->action, 'create') && !str_contains($log->action, 'update') && !str_contains($log->action, 'delete') ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucwords(str_replace('_', ' ', $log->action)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($log->model_type)
                            <div>
                                <div class="font-medium">{{ class_basename($log->model_type) }}</div>
                                @if($log->model_id)
                                <div class="text-gray-500 text-xs">ID: {{ $log->model_id }}</div>
                                @endif
                            </div>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm max-w-xs truncate">
                            {{ $log->description ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $log->ip_address ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button onclick="viewLog({{ $log->id }})" class="text-blue-600 hover:text-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No activity logs found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>

<!-- View Log Modal -->
<div id="view-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-semibold">Activity Log Details</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="log-content"></div>
        </div>
    </div>
</div>

<!-- Cleanup Modal -->
<div id="cleanup-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold">Cleanup Old Logs</h3>
                <button onclick="closeCleanupModal()" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="cleanup-form">
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium mb-2 block">Delete logs older than (days)</label>
                        <input type="number" name="days" min="1" max="365" value="90" required class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm">
                        <p class="text-xs text-gray-500 mt-1">Recommended: 90 days</p>
                    </div>
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">⚠️ This action cannot be undone. All logs older than the specified days will be permanently deleted.</p>
                    </div>
                    <div class="flex gap-2 pt-4">
                        <button type="button" onclick="closeCleanupModal()" class="flex-1 inline-flex items-center justify-center text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-4">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 inline-flex items-center justify-center text-sm font-medium bg-red-600 text-white hover:bg-red-700 h-9 rounded-md px-4">
                            Delete Logs
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function toggleFilters() {
    document.getElementById('filter-panel').classList.toggle('hidden');
}

async function viewLog(logId) {
    try {
        const response = await fetch(`/admin/activity-logs/${logId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayLog(data.log);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to load log details');
    }
}

function displayLog(log) {
    let userHtml = 'System';
    if (log.user) {
        userHtml = `
            <div>
                <div class="font-medium">${log.user.name}</div>
                <div class="text-sm text-gray-500">${log.user.email}</div>
                <div class="text-xs text-gray-500">${log.user.role}</div>
            </div>
        `;
    }

    let changesHtml = '';
    if (log.old_values || log.new_values) {
        const oldValues = log.old_values || {};
        const newValues = log.new_values || {};
        const allKeys = [...new Set([...Object.keys(oldValues), ...Object.keys(newValues)])];
        
        changesHtml = `
            <div class="col-span-2 mt-4 pt-4 border-t">
                <h4 class="font-semibold mb-3">Changes</h4>
                <div class="space-y-2">
                    ${allKeys.map(key => `
                        <div class="grid grid-cols-3 gap-4 text-sm p-3 bg-gray-50 rounded">
                            <div class="font-medium text-gray-700">${key}</div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Old Value</div>
                                <div class="text-red-600">${oldValues[key] !== undefined ? oldValues[key] : 'N/A'}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">New Value</div>
                                <div class="text-green-600">${newValues[key] !== undefined ? newValues[key] : 'N/A'}</div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }

    const content = `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-600 mb-1">ID</p>
                    <p class="font-medium">${log.id}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Date/Time</p>
                    <p class="font-medium">${log.created_at}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">User</p>
                    ${userHtml}
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Action</p>
                    <p class="font-medium">${log.action}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Model Type</p>
                    <p class="font-medium">${log.model_type || 'N/A'}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Model ID</p>
                    <p class="font-medium">${log.model_id || 'N/A'}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-600 mb-1">Description</p>
                    <p class="font-medium">${log.description || 'N/A'}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">IP Address</p>
                    <p class="font-medium">${log.ip_address || 'N/A'}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-600 mb-1">User Agent</p>
                    <p class="font-medium text-xs">${log.user_agent || 'N/A'}</p>
                </div>
                ${changesHtml}
            </div>
        </div>
    `;
    
    document.getElementById('log-content').innerHTML = content;
    document.getElementById('view-modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('view-modal').classList.add('hidden');
}

function showCleanupModal() {
    document.getElementById('cleanup-modal').classList.remove('hidden');
}

function closeCleanupModal() {
    document.getElementById('cleanup-modal').classList.add('hidden');
}

document.getElementById('cleanup-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!confirm('Are you sure you want to delete old activity logs? This action cannot be undone.')) {
        return;
    }
    
    const formData = new FormData(this);
    const data = {
        days: parseInt(formData.get('days'))
    };
    
    try {
        const response = await fetch('/admin/activity-logs/cleanup', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            closeCleanupModal();
            window.location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to cleanup logs');
    }
});

function exportLogs() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '/admin/activity-logs/export?' + params.toString();
}
</script>

@endsection