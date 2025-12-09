@extends('admin.admin_dashboard')
@section('admin')

<script src="https://cdn.tailwindcss.com"></script>

<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.activity-logs.index') }}" class="inline-flex items-center justify-center text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 h-9 rounded-md px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                    <path d="m12 19-7-7 7-7"></path>
                    <path d="M19 12H5"></path>
                </svg>
                Back to Logs
            </a>
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Activity Log Details</h1>
                <p class="text-gray-600">Log ID: #{{ $log->id }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold
                {{ str_contains($log->action, 'create') ? 'bg-green-100 text-green-800' : '' }}
                {{ str_contains($log->action, 'update') ? 'bg-blue-100 text-blue-800' : '' }}
                {{ str_contains($log->action, 'delete') ? 'bg-red-100 text-red-800' : '' }}
                {{ str_contains($log->action, 'login') ? 'bg-purple-100 text-purple-800' : '' }}
                {{ str_contains($log->action, 'logout') ? 'bg-orange-100 text-orange-800' : '' }}
                {{ !str_contains($log->action, 'create') && !str_contains($log->action, 'update') && !str_contains($log->action, 'delete') && !str_contains($log->action, 'login') && !str_contains($log->action, 'logout') ? 'bg-gray-100 text-gray-800' : '' }}">
                {{ ucwords(str_replace('_', ' ', $log->action)) }}
            </span>
        </div>
    </div>

    <!-- Basic Information Card -->
    <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6 border-b bg-gray-50">
            <h2 class="text-xl font-semibold">Basic Information</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">User</label>
                    @if($log->user)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                            {{ substr($log->user->first_name, 0, 1) }}{{ substr($log->user->last_name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">{{ $log->user->first_name }} {{ $log->user->last_name }}</div>
                            <div class="text-sm text-gray-500">{{ $log->user->email }}</div>
                            @if($log->user->role)
                            <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                                {{ ucfirst($log->user->role) }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-600">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">System</div>
                            <div class="text-sm text-gray-500">Automated action</div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Action -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Action</label>
                    <p class="text-lg font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $log->action)) }}</p>
                </div>

                <!-- Model Type -->
                @if($log->model_type)
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Model Type</label>
                    <p class="text-lg font-medium text-gray-900">{{ class_basename($log->model_type) }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $log->model_type }}</p>
                </div>
                @endif

                <!-- Model ID -->
                @if($log->model_id)
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Model ID</label>
                    <p class="text-lg font-medium text-gray-900">#{{ $log->model_id }}</p>
                </div>
                @endif

                <!-- IP Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">IP Address</label>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-400">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
                            <path d="M2 12h20"></path>
                        </svg>
                        <p class="text-lg font-medium text-gray-900">{{ $log->ip_address ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Date/Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Date & Time</label>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-400">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <div>
                            <p class="text-lg font-medium text-gray-900">{{ $log->created_at->format('M d, Y') }}</p>
                            <p class="text-sm text-gray-500">{{ $log->created_at->format('h:i:s A') }}</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">{{ $log->created_at->diffForHumans() }}</p>
                </div>
            </div>

            <!-- Description -->
            @if($log->description)
            <div class="mt-6 pt-6 border-t">
                <label class="block text-sm font-medium text-gray-500 mb-2">Description</label>
                <p class="text-base text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $log->description }}</p>
            </div>
            @endif

            <!-- User Agent -->
            @if($log->user_agent)
            <div class="mt-6 pt-6 border-t">
                <label class="block text-sm font-medium text-gray-500 mb-2">User Agent</label>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-700 break-all font-mono">{{ $log->user_agent }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Old Values -->
    @if($log->old_values && count($log->old_values) > 0)
    <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6 border-b bg-red-50">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-red-600">
                    <path d="M3 3h18v18H3zM15 9l-6 6m0-6l6 6"></path>
                </svg>
                <h2 class="text-xl font-semibold text-red-900">Old Values (Before Change)</h2>
            </div>
        </div>
        <div class="p-6">
            <div class="bg-red-50 rounded-lg p-4 overflow-x-auto">
                <table class="min-w-full">
                    <tbody class="divide-y divide-red-200">
                        @foreach($log->old_values as $key => $value)
                        <tr>
                            <td class="py-2 pr-4 text-sm font-medium text-gray-700 w-1/3">{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                            <td class="py-2 text-sm text-red-700 font-mono">
                                @if(is_array($value))
                                    <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                @elseif(is_bool($value))
                                    {{ $value ? 'true' : 'false' }}
                                @elseif(is_null($value))
                                    <span class="text-gray-400 italic">null</span>
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- New Values -->
    @if($log->new_values && count($log->new_values) > 0)
    <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6 border-b bg-green-50">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <h2 class="text-xl font-semibold text-green-900">New Values (After Change)</h2>
            </div>
        </div>
        <div class="p-6">
            <div class="bg-green-50 rounded-lg p-4 overflow-x-auto">
                <table class="min-w-full">
                    <tbody class="divide-y divide-green-200">
                        @foreach($log->new_values as $key => $value)
                        <tr>
                            <td class="py-2 pr-4 text-sm font-medium text-gray-700 w-1/3">{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                            <td class="py-2 text-sm text-green-700 font-mono">
                                @if(is_array($value))
                                    <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                @elseif(is_bool($value))
                                    {{ $value ? 'true' : 'false' }}
                                @elseif(is_null($value))
                                    <span class="text-gray-400 italic">null</span>
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Changes Comparison (for updates) -->
    @if($log->action === 'updated' && $log->old_values && $log->new_values)
    <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6 border-b bg-blue-50">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600">
                    <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
                </svg>
                <h2 class="text-xl font-semibold text-blue-900">Changes Summary</h2>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Old Value</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">→</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">New Value</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($log->new_values as $key => $newValue)
                            @if(isset($log->old_values[$key]) && $log->old_values[$key] !== $newValue)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">
                                    {{ ucwords(str_replace('_', ' ', $key)) }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="bg-red-50 border border-red-200 rounded px-3 py-2 text-red-700 font-mono">
                                        @if(is_array($log->old_values[$key]))
                                            <pre class="text-xs">{{ json_encode($log->old_values[$key]) }}</pre>
                                        @elseif(is_bool($log->old_values[$key]))
                                            {{ $log->old_values[$key] ? 'true' : 'false' }}
                                        @elseif(is_null($log->old_values[$key]))
                                            <span class="italic text-gray-400">null</span>
                                        @else
                                            {{ $log->old_values[$key] }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mx-auto text-gray-400">
                                        <path d="M5 12h14"></path>
                                        <path d="m12 5 7 7-7 7"></path>
                                    </svg>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="bg-green-50 border border-green-200 rounded px-3 py-2 text-green-700 font-mono">
                                        @if(is_array($newValue))
                                            <pre class="text-xs">{{ json_encode($newValue) }}</pre>
                                        @elseif(is_bool($newValue))
                                            {{ $newValue ? 'true' : 'false' }}
                                        @elseif(is_null($newValue))
                                            <span class="italic text-gray-400">null</span>
                                        @else
                                            {{ $newValue }}
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- JSON View (Collapsible) -->
    @if($log->old_values || $log->new_values)
    <div class="rounded-lg border bg-white shadow-sm">
        <button onclick="toggleJsonView()" class="w-full p-6 text-left flex items-center justify-between hover:bg-gray-50">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-500">
                    <polyline points="16 18 22 12 16 6"></polyline>
                    <polyline points="8 6 2 12 8 18"></polyline>
                </svg>
                <h2 class="text-xl font-semibold">Raw JSON Data</h2>
            </div>
            <svg id="json-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="transform transition-transform">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
        <div id="json-view" class="hidden p-6 border-t bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($log->old_values)
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Old Values JSON</h3>
                    <pre class="bg-white p-4 rounded border text-xs overflow-x-auto">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                </div>
                @endif
                @if($log->new_values)
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">New Values JSON</h3>
                    <pre class="bg-white p-4 rounded border text-xs overflow-x-auto">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function toggleJsonView() {
    const jsonView = document.getElementById('json-view');
    const arrow = document.getElementById('json-arrow');
    
    jsonView.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
}
</script>

@endsection