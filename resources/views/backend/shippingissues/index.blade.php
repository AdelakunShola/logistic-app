@extends('admin.admin_dashboard')
@section('admin')

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-red-100 p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Issue Management</h1>
                        <p class="text-sm text-gray-500">Track and resolve shipment issues</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.issues.export') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border rounded-lg hover:bg-gray-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Export
                    </a>
                    <button onclick="location.reload()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border rounded-lg hover:bg-gray-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/>
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Issues</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">All time</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['pending'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Needs attention</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-600">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Investigating</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['investigating'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Being worked on</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Resolved</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['resolved'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ number_format(($stats['resolved'] / max($stats['total'], 1)) * 100, 1) }}% resolution rate</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Issues List -->
        <div class="bg-white rounded-lg shadow-sm border">
            <!-- Tabs -->
            <div class="border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex gap-6">
                        <a href="{{ route('admin.issues.index') }}" class="py-2 text-sm {{ !request('status') ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors">
                            All Issues ({{ $stats['total'] }})
                        </a>
                        <a href="{{ route('admin.issues.index', ['status' => 'pending']) }}" class="py-2 text-sm {{ request('status') == 'pending' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors">
                            Pending ({{ $stats['pending'] }})
                        </a>
                        <a href="{{ route('admin.issues.index', ['status' => 'investigating']) }}" class="py-2 text-sm {{ request('status') == 'investigating' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors">
                            Investigating ({{ $stats['investigating'] }})
                        </a>
                        <a href="{{ route('admin.issues.index', ['status' => 'resolved']) }}" class="py-2 text-sm {{ request('status') == 'resolved' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition-colors">
                            Resolved ({{ $stats['resolved'] }})
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="p-6 border-b bg-gray-50">
                <form method="GET" action="{{ route('admin.issues.index') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search by tracking number, issue type..." 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <select name="priority" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Priorities</option>
                        <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                    <select name="assigned" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Assignees</option>
                        <option value="unassigned" {{ request('assigned') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                        <option value="me" {{ request('assigned') == 'me' ? 'selected' : '' }}>Assigned to Me</option>
                        @foreach($supportUsers as $user)
                            <option value="{{ $user->id }}" {{ request('assigned') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Apply Filters
                    </button>
                    @if(request()->hasAny(['search', 'priority', 'assigned', 'status']))
                        <a href="{{ route('admin.issues.index') }}" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Issues List -->
            <div class="divide-y">
                @forelse($issues as $issue)
                <div class="p-6 hover:bg-gray-50 transition-colors priority-{{ $issue->priority }} cursor-pointer" onclick="window.location='{{ route('admin.issues.show', $issue->id) }}'">
                    <div class="flex items-start gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="priority-badge-{{ $issue->priority }} text-xs font-semibold px-2.5 py-1 rounded-full uppercase">
                                    {{ $issue->priority }}
                                </span>
                                <span class="status-badge-{{ $issue->status }} text-xs font-semibold px-2.5 py-1 rounded-full uppercase">
                                    {{ str_replace('_', ' ', $issue->status) }}
                                </span>
                                <span class="text-xs text-gray-500">#ISS-{{ str_pad($issue->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            
                            <h3 class="font-semibold text-gray-900 mb-1">{{ ucfirst(str_replace('_', ' ', $issue->issue_type)) }}</h3>
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $issue->description }}</p>
                            
                            <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500">
                                <div class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span>Reported by: <span class="font-medium text-gray-700">{{ $issue->reportedBy?->name ?? 'Guest' }}</span></span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <span>{{ $issue->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span class="font-mono">{{ $issue->shipment->tracking_number }}</span>
                                </div>
                                @if($issue->assignedTo)
                                <div class="flex items-center gap-1.5 bg-blue-50 px-2 py-0.5 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span class="font-medium text-blue-700">Assigned:  @if($issue->assignedTo)
                                            {{ $issue->assignedTo->first_name }} {{ $issue->assignedTo->last_name }}
                                        @else
                                            <span class="text-gray-500 italic">Unassigned</span>
                                        @endif
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            @if(!$issue->assignedTo)
                            <button onclick="event.stopPropagation(); openAssignModal({{ $issue->id }})" class="px-3 py-1.5 text-sm border rounded-lg hover:bg-gray-50 transition-colors">
                                Assign
                            </button>
                            @endif
                            <button onclick="event.stopPropagation(); window.location='{{ route('admin.issues.show', $issue->id) }}'" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No issues found</h3>
                    <p class="text-sm text-gray-500">There are no issues matching your criteria.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($issues->hasPages())
            <div class="px-6 py-4 border-t bg-gray-50">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        Showing <span class="font-medium">{{ $issues->firstItem() }}</span> to <span class="font-medium">{{ $issues->lastItem() }}</span> of <span class="font-medium">{{ $issues->total() }}</span> issues
                    </p>
                    <div class="flex gap-2">
                        {{ $issues->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Assign Modal -->
<div id="assignModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b">
            <h3 class="text-lg font-semibold">Assign Issue</h3>
            <button onclick="closeAssignModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        
        <form id="assignForm" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
              <div>
    <label class="block text-sm font-medium mb-2">Assign to</label>
    
    <!-- Hidden input to store the actual value -->
    <input type="hidden" name="assigned_to" id="assigned_to" required>
    
    <!-- Custom Searchable Dropdown -->
    <div class="relative">
        <!-- Dropdown Button -->
        <button 
            type="button"
            id="dropdownButton"
            onclick="toggleDropdown()"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-left flex items-center justify-between bg-white hover:bg-gray-50"
        >
            <span id="selectedText" class="text-gray-500">Select a team member</span>
            <svg class="w-5 h-5 text-gray-400 transition-transform" id="dropdownIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
        
        <!-- Dropdown Menu -->
<div 
    id="dropdownMenu" 
    class="hidden absolute z-50 w-full mt-1 bg-white border rounded-lg shadow-lg overflow-hidden flex flex-col"
    style="max-height: 320px;"
>
    <!-- Search Input -->
    <div class="p-2 border-b bg-white flex-shrink-0">
        <div class="relative">
            <input 
                type="text" 
                id="searchInput"
                placeholder="Search team members..."
                class="w-full px-3 py-2 pl-9 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                onkeyup="filterUsers()"
            >
            <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>
    
    <!-- Options List -->
    <div class="overflow-y-auto flex-1" id="usersList">
        @foreach($supportUsers as $user)
            @php
                $initials = strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name ?? '', 0, 1));
                $colors = ['blue', 'green', 'purple', 'pink', 'indigo', 'red', 'yellow'];
                $color = $colors[$user->id % count($colors)];
            @endphp
            <div 
                class="px-4 py-2 hover:bg-blue-50 cursor-pointer user-option border-l-2 border-transparent hover:border-blue-500" 
                data-value="{{ $user->id }}" 
                data-name="{{ $user->first_name }} {{ $user->last_name }}"
                onclick="selectUser('{{ $user->id }}', '{{ $user->first_name }} {{ $user->last_name }}')"
            >
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-{{ $color }}-100 flex items-center justify-center text-sm font-semibold text-{{ $color }}-700">
                        {{ $initials }}
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</div>
                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                    </div>
                </div>
            </div>
        @endforeach
        
        <!-- No results message -->
        <div id="noResults" class="hidden px-4 py-8 text-center text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <p class="text-sm">No team members found</p>
        </div>
    </div>
</div>
    </div>
</div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">Priority (optional)</label>
                    <select name="priority" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Keep current</option>
                        <option value="critical">Critical</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeAssignModal()" class="flex-1 px-4 py-2 border rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Assign Issue
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

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
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    function openAssignModal(issueId) {
        const modal = document.getElementById('assignModal');
        const form = document.getElementById('assignForm');
        form.action = `/admin/issues/${issueId}/assign`;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAssignModal() {
        const modal = document.getElementById('assignModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('assignForm').reset();
    }

    document.getElementById('assignModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAssignModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAssignModal();
        }
    });








    function toggleDropdown() {
    const menu = document.getElementById('dropdownMenu');
    const icon = document.getElementById('dropdownIcon');
    const searchInput = document.getElementById('searchInput');
    
    menu.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
    
    if (!menu.classList.contains('hidden')) {
        searchInput.focus();
        searchInput.value = '';
        filterUsers();
    }
}

function selectUser(value, name) {
    document.getElementById('assigned_to').value = value;
    document.getElementById('selectedText').textContent = name;
    document.getElementById('selectedText').classList.remove('text-gray-500');
    document.getElementById('selectedText').classList.add('text-gray-900');
    toggleDropdown();
}

function filterUsers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const options = document.querySelectorAll('.user-option');
    let visibleCount = 0;
    
    options.forEach(option => {
        const name = option.getAttribute('data-name').toLowerCase();
        if (name.includes(searchTerm)) {
            option.style.display = '';
            visibleCount++;
        } else {
            option.style.display = 'none';
        }
    });
    
    // Show/hide no results message
    const noResults = document.getElementById('noResults');
    if (visibleCount === 0) {
        noResults.classList.remove('hidden');
    } else {
        noResults.classList.add('hidden');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdownMenu');
    const button = document.getElementById('dropdownButton');
    
    if (!dropdown.contains(event.target) && !button.contains(event.target)) {
        if (!dropdown.classList.contains('hidden')) {
            toggleDropdown();
        }
    }
});
</script>
@endsection