@extends('admin.admin_dashboard')
@section('admin')

<style>
    .priority-critical { border-left: 4px solid #dc2626; }
    .priority-high { border-left: 4px solid #ea580c; }
    .priority-medium { border-left: 4px solid #f59e0b; }
    .priority-low { border-left: 4px solid #3b82f6; }
    
    .status-badge-pending { background: #fef3c7; color: #92400e; }
    .status-badge-investigating { background: #dbeafe; color: #1e40af; }
    .status-badge-resolved { background: #d1fae5; color: #065f46; }
    .status-badge-closed { background: #e5e7eb; color: #374151; }
    
    .priority-badge-critical { background: #fee2e2; color: #991b1b; }
    .priority-badge-high { background: #ffedd5; color: #9a3412; }
    .priority-badge-medium { background: #fef3c7; color: #92400e; }
    .priority-badge-low { background: #dbeafe; color: #1e40af; }
</style>



<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-12">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.issues.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Back to Issues
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

           
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Issue Header -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="priority-badge-{{ $issue->priority }} text-xs font-semibold px-2.5 py-1 rounded-full uppercase">
                                {{ $issue->priority }}
                            </span>
                            <span class="status-badge-{{ $issue->status }} text-xs font-semibold px-2.5 py-1 rounded-full uppercase">
                                {{ str_replace('_', ' ', $issue->status) }}
                            </span>
                            <span class="text-sm text-gray-500">#ISS-{{ str_pad($issue->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ ucfirst(str_replace('_', ' ', $issue->issue_type)) }}</h1>
                        
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <span>Reported {{ $issue->created_at->diffForHumans() }}</span>
                            <span>•</span>
                            <span>by {{ $issue->reportedBy?->name ?? 'Guest User' }}</span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="font-semibold mb-2">Description</h3>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $issue->description }}</p>
                    </div>
                </div>

                <!-- Resolution Section -->
                @if($issue->status === 'resolved' && $issue->resolution)
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <div class="flex items-start gap-3">
                        <div class="bg-green-100 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-green-900 mb-2">Issue Resolved</h3>
                            <p class="text-sm text-green-800 mb-2">{{ $issue->resolution }}</p>
                            <p class="text-xs text-green-700">Resolved by @if($issue->assignedTo)
        {{ $issue->assignedTo->first_name }} {{ $issue->assignedTo->last_name }}
    @else
        <span class="text-gray-500 italic">Unassigned</span>
    @endif
  on {{ $issue->resolved_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Forms -->
                @if($issue->status !== 'resolved' && $issue->status !== 'closed')
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="font-semibold mb-4">Update Issue</h3>
                    
                    <form action="{{ route('admin.issues.update-status', $issue->id) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium mb-2">Status</label>
                            <select name="status" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="pending" {{ $issue->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="investigating" {{ $issue->status == 'investigating' ? 'selected' : '' }}>Investigating</option>
                                <option value="resolved" {{ $issue->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $issue->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                        </div>
                        
                        <div id="resolutionField" class="{{ $issue->status == 'resolved' ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium mb-2">Resolution Details <span class="text-red-500">*</span></label>
                            <textarea 
                                name="resolution" 
                                rows="4" 
                                placeholder="Describe how the issue was resolved..."
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            >{{ $issue->resolution }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">This will be sent to the customer</p>
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Activity Timeline -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="font-semibold mb-4">Activity Timeline</h3>
                    
                    <div class="space-y-4">
                        @if($issue->resolved_at)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="bg-green-100 p-2 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                </div>
                                <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
                            </div>
                            <div class="flex-1 pb-4">
                                <p class="font-medium text-sm">Issue Resolved</p>
                                <p class="text-xs text-gray-600 mt-1">@if($issue->assignedTo)
        {{ $issue->assignedTo->first_name }} {{ $issue->assignedTo->last_name }}
    @else
        <span class="text-gray-500 italic">Unassigned</span>
    @endif
   marked this as resolved</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $issue->resolved_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($issue->assigned_to)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="bg-blue-100 p-2 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </div>
                                <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
                            </div>
                            <div class="flex-1 pb-4">
                                <p class="font-medium text-sm">Issue Assigned</p>
                                <p class="text-xs text-gray-600 mt-1">
    @if($issue->assignedTo)
        {{ $issue->assignedTo->first_name }} {{ $issue->assignedTo->last_name }}
    @else
        <span class="text-gray-500 italic">Unassigned</span>
    @endif
</p>

                                <p class="text-xs text-gray-500 mt-1">{{ $issue->updated_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="bg-orange-100 p-2 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-600">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-sm">Issue Reported</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $issue->reportedBy?->name ?? 'Guest user' }} reported this issue</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $issue->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


              <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="font-semibold mb-4">Quick Actions</h3>
                    
                    <div class="space-y-3">
                       @if(!$issue->assigned_to)
    <!-- No one assigned yet — show Assign button -->
    <form action="{{ route('admin.issues.assign', $issue->id) }}" method="POST">
        @csrf
        <input type="hidden" name="assigned_to" value="{{ auth()->id() }}">
        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
            Assign to Me
        </button>
    </form>
@elseif($issue->assigned_to == auth()->id())
    <!-- Already assigned to current user -->
    <p class="text-sm text-gray-600">
        ✅ You are assigned to this issue.
    </p>
@else
    <!-- Assigned to another user -->
    <p class="text-sm text-gray-600">
        👤 Assigned to @if($issue->assignedTo)
        {{ $issue->assignedTo->first_name }} {{ $issue->assignedTo->last_name }}
    @else
        <span class="text-gray-500 italic">Unassigned</span>
    @endif

    </p>
@endif

                        
                        @if($issue->status !== 'investigating' && $issue->status !== 'resolved')
                        <form action="{{ route('admin.issues.update-status', $issue->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="investigating">
                            <button type="submit" class="w-full px-4 py-2 border rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                                Start Investigation
                            </button>
                        </form>
                        @endif
                        
                        <a href="{{ route('admin.shipment.track.show', $issue->shipment->tracking_number) }}" class="block w-full px-4 py-2 border rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium text-center">
                            View Shipment
                        </a>
                        
                        @if($issue->reportedBy)
                        <a href="mailto:{{ $issue->reportedBy->email }}" class="block w-full px-4 py-2 border rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium text-center">
                            Contact Reporter
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Issue Details -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="font-semibold mb-4">Issue Details</h3>
                    
                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-gray-600 mb-1">Issue ID</p>
                            <p class="font-mono font-medium">#ISS-{{ str_pad($issue->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 mb-1">Priority</p>
                            <span class="priority-badge-{{ $issue->priority }} text-xs font-semibold px-2.5 py-1 rounded-full uppercase">
                                {{ $issue->priority }}
                            </span>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 mb-1">Status</p>
                            <span class="status-badge-{{ $issue->status }} text-xs font-semibold px-2.5 py-1 rounded-full uppercase">
                                {{ str_replace('_', ' ', $issue->status) }}
                            </span>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 mb-1">Assigned To</p>
                        <p class="font-medium">
    @if($issue->assignedTo)
        {{ $issue->assignedTo->first_name }} {{ $issue->assignedTo->last_name }}
    @else
        <span class="text-gray-500 italic">Unassigned</span>
    @endif
</p>

                        </div>
                        
                        <div>
                            <p class="text-gray-600 mb-1">Reported By</p>
                            <p class="font-medium">{{ $issue->reportedBy?->name ?? 'Guest User' }}</p>
                            @if($issue->reportedBy)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $issue->reportedBy->email }}</p>
                            @endif
                        </div>
                        
                        <div>
                            <p class="text-gray-600 mb-1">Created</p>
                            <p class="font-medium">{{ $issue->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $issue->created_at->format('g:i A') }}</p>
                        </div>
                        
                        @if($issue->resolved_at)
                        <div>
                            <p class="text-gray-600 mb-1">Resolved</p>
                            <p class="font-medium">{{ $issue->resolved_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $issue->resolved_at->format('g:i A') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Shipment Info -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="font-semibold mb-4">Shipment Information</h3>
                    
                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-gray-600 mb-1">Tracking Number</p>
                            <p class="font-mono font-medium">{{ $issue->shipment->tracking_number }}</p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 mb-1">Current Status</p>
                            <p class="font-medium capitalize">{{ str_replace('_', ' ', $issue->shipment->status) }}</p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 mb-1">Customer</p>
                            <p class="font-medium">{{ $issue->shipment->customer->name ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 mb-1">Origin</p>
                            <p class="font-medium">{{ $issue->shipment->origin_address ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 mb-1">Destination</p>
                            <p class="font-medium">{{ $issue->shipment->destination_address ?? 'N/A' }}</p>
                        </div>
                        
                        <a href="{{ route('admin.shipment.track.show', $issue->shipment->tracking_number) }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                            View Full Details
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Reporter Info -->
                @if($issue->reporter_ip)
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="font-semibold mb-4">Reporter Information</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600 mb-1">IP Address</p>
                            <p class="font-mono text-xs">{{ $issue->reporter_ip }}</p>
                        </div>
                        
                        @if($issue->reporter_user_agent)
                        <div>
                            <p class="text-gray-600 mb-1">User Agent</p>
                            <p class="text-xs text-gray-700 break-words">{{ Str::limit($issue->reporter_user_agent, 80) }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

           
        </div>
    </div>
</div>


<script>
    // Show/hide resolution field based on status
    document.querySelector('select[name="status"]')?.addEventListener('change', function() {
        const resolutionField = document.getElementById('resolutionField');
        const resolutionTextarea = resolutionField.querySelector('textarea');
        
        if (this.value === 'resolved') {
            resolutionField.classList.remove('hidden');
            resolutionTextarea.required = true;
        } else {
            resolutionField.classList.add('hidden');
            resolutionTextarea.required = false;
        }
    });
</script>
@endsection