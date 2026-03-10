@extends('admin.admin_dashboard')
@section('admin')

<div class="min-h-screen bg-gray-50">
    <div class="bg-white border-b sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <h1 class="text-xl font-bold text-gray-900">Roles & Permissions</h1>
                <button onclick="document.getElementById('create-role-modal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Add Role
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Roles Grid -->
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse($roles as $role)
                <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $role->display_name }}</h3>
                                <p class="text-sm text-gray-500">{{ $role->name }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick="openEditModal({{ $role->id }})" class="p-1.5 text-gray-400 hover:text-blue-600 rounded-md hover:bg-blue-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </button>
                                @if($role->users_count === 0)
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Delete this role?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded-md hover:bg-red-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        @if($role->description)
                            <p class="text-sm text-gray-600 mb-4">{{ $role->description }}</p>
                        @endif

                        <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                {{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>
                                {{ $role->permissions_count }} {{ Str::plural('permission', $role->permissions_count) }}
                            </span>
                        </div>

                        @if($role->permissions->count() > 0)
                            <div class="flex flex-wrap gap-1">
                                @foreach($role->permissions->take(6) as $permission)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-blue-50 text-blue-700">{{ $permission->display_name }}</span>
                                @endforeach
                                @if($role->permissions->count() > 6)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600">+{{ $role->permissions->count() - 6 }} more</span>
                                @endif
                            </div>
                        @else
                            <p class="text-xs text-gray-400 italic">No permissions assigned</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto text-gray-300 mb-4"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                    <p class="text-gray-500 font-medium">No roles defined yet</p>
                    <p class="text-sm text-gray-400 mt-1">Create your first role to get started</p>
                </div>
            @endforelse
        </div>

        <!-- Permissions Overview -->
        <div class="mt-10">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Permissions by Module</h2>
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                @forelse($permissionsByModule as $module => $modulePermissions)
                    <div class="border-b last:border-b-0">
                        <div class="px-6 py-3 bg-gray-50">
                            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">{{ ucfirst($module) }}</h3>
                        </div>
                        <div class="px-6 py-3">
                            <div class="flex flex-wrap gap-2">
                                @foreach($modulePermissions as $permission)
                                    <span class="inline-flex items-center px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700" title="{{ $permission->description }}">
                                        {{ $permission->display_name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <p>No permissions defined yet.</p>
                        <p class="text-sm mt-1">Run the permissions seeder to create default permissions.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Create Role Modal -->
<div id="create-role-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50" onclick="document.getElementById('create-role-modal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold">Create New Role</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role Name *</label>
                        <input type="text" name="name" required placeholder="e.g. warehouse_manager" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Display Name *</label>
                        <input type="text" name="display_name" required placeholder="e.g. Warehouse Manager" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="2" placeholder="Brief description of this role..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    @if($permissions->count() > 0)
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-700">Permissions</label>
                                <label class="flex items-center gap-2 text-xs text-blue-600 cursor-pointer">
                                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 select-all-create" onchange="toggleAllPermissions(this, 'create-role-modal')">
                                    Select All
                                </label>
                            </div>
                            <div class="max-h-60 overflow-y-auto border rounded-lg p-3 space-y-3">
                                @foreach($permissionsByModule as $module => $modulePermissions)
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <p class="text-xs font-semibold text-gray-500 uppercase">{{ ucfirst($module) }}</p>
                                            <label class="flex items-center gap-1 text-xs text-gray-500 cursor-pointer">
                                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 select-module" data-module="create-{{ $module }}" onchange="toggleModulePermissions(this, 'create-{{ $module }}')">
                                                All
                                            </label>
                                        </div>
                                        <div class="space-y-1">
                                            @foreach($modulePermissions as $permission)
                                                <label class="flex items-center gap-2 text-sm">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 perm-checkbox perm-create-{{ $module }}">
                                                    {{ $permission->display_name }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="px-6 py-4 border-t flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('create-role-modal').classList.add('hidden')" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Role Modals -->
@foreach($roles as $role)
<div id="edit-role-modal-{{ $role->id }}" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50" onclick="document.getElementById('edit-role-modal-{{ $role->id }}').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold">Edit Role: {{ $role->display_name }}</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Display Name *</label>
                        <input type="text" name="display_name" value="{{ $role->display_name }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $role->description }}</textarea>
                    </div>
                    @if($permissions->count() > 0)
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-700">Permissions</label>
                                <label class="flex items-center gap-2 text-xs text-blue-600 cursor-pointer">
                                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" onchange="toggleAllPermissions(this, 'edit-role-modal-{{ $role->id }}')">
                                    Select All
                                </label>
                            </div>
                            <div class="max-h-60 overflow-y-auto border rounded-lg p-3 space-y-3">
                                @foreach($permissionsByModule as $module => $modulePermissions)
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <p class="text-xs font-semibold text-gray-500 uppercase">{{ ucfirst($module) }}</p>
                                            <label class="flex items-center gap-1 text-xs text-gray-500 cursor-pointer">
                                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 select-module" data-module="edit{{ $role->id }}-{{ $module }}" onchange="toggleModulePermissions(this, 'edit{{ $role->id }}-{{ $module }}')">
                                                All
                                            </label>
                                        </div>
                                        <div class="space-y-1">
                                            @foreach($modulePermissions as $permission)
                                                <label class="flex items-center gap-2 text-sm">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" {{ $role->permissions->contains($permission->id) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 perm-checkbox perm-edit{{ $role->id }}-{{ $module }}">
                                                    {{ $permission->display_name }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="px-6 py-4 border-t flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('edit-role-modal-{{ $role->id }}').classList.add('hidden')" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update Role</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@if(session('success'))
<div id="success-toast" class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
    {{ session('success') }}
</div>
<script>setTimeout(() => document.getElementById('success-toast')?.remove(), 3000);</script>
@endif
@if(session('error'))
<div id="error-toast" class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
    {{ session('error') }}
</div>
<script>setTimeout(() => document.getElementById('error-toast')?.remove(), 5000);</script>
@endif

<script>
function openEditModal(roleId) {
    document.getElementById('edit-role-modal-' + roleId).classList.remove('hidden');
}

function toggleAllPermissions(checkbox, modalId) {
    const modal = document.getElementById(modalId);
    const checkboxes = modal.querySelectorAll('input[name="permissions[]"]');
    const moduleChecks = modal.querySelectorAll('.select-module');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    moduleChecks.forEach(cb => cb.checked = checkbox.checked);
}

function toggleModulePermissions(checkbox, moduleClass) {
    const checkboxes = document.querySelectorAll('.perm-' + moduleClass);
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
}
</script>

@endsection
