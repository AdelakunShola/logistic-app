<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::with('assignedWarehouse');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%");
            });
        }

        // Role Filter
        if ($request->filled('role') && $request->role != 'all') {
            $query->where('role', $request->role);
        }

        // Status Filter
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Warehouse Filter
        if ($request->filled('warehouse_id')) {
            $query->where('assigned_warehouse_id', $request->warehouse_id);
        }

        // Availability Filter (for drivers)
        if ($request->filled('availability')) {
            $query->where('is_available', $request->availability);
        }

        // Date Range Filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(15);

        // Get warehouses for filter
        $warehouses = Warehouse::where('status', 'active')->get();

        // Statistics
        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'drivers' => User::where('role', 'driver')->count(),
            'customers' => User::where('role', 'customer')->count(),
            'managers' => User::where('role', 'manager')->count(),
            'dispatchers' => User::where('role', 'dispatcher')->count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'available_drivers' => User::where('role', 'driver')
                                      ->where('is_available', true)
                                      ->where('status', 'active')
                                      ->count(),
            'assigned_warehouse' => User::whereNotNull('assigned_warehouse_id')->count(),
            'unassigned_warehouse' => User::whereNull('assigned_warehouse_id')->count(),
        ];

        $roles = ['admin', 'driver', 'customer', 'manager', 'dispatcher'];
        $statuses = ['active', 'inactive', 'suspended', 'on_leave'];

        return view('backend.users.index', compact('users', 'stats', 'roles', 'statuses', 'warehouses'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = ['admin', 'driver', 'customer', 'manager', 'dispatcher'];
        $managers = User::where('role', 'manager')->where('status', 'active')->get();
        $warehouses = Warehouse::where('status', 'active')->get();
        
        return view('backend.users.create', compact('roles', 'managers', 'warehouses'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,driver,customer,manager,dispatcher',
            'status' => 'required|in:active,inactive,suspended,on_leave',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'profile_photo' => 'nullable|image|max:2048',
            
            // Warehouse assignment
            'assigned_warehouse_id' => 'nullable|exists:warehouses,id',
            
            // Driver-specific
            'license_number' => 'required_if:role,driver|nullable|string|max:255',
            'vehicle_type' => 'nullable|string|max:50',
            'vehicle_number' => 'nullable|string|max:50',
            'vehicle_capacity' => 'nullable|numeric',
            
            // Employee fields
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id',
            'department' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'joining_date' => 'nullable|date',
            'salary' => 'nullable|numeric',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $validated['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
            }

            // Hash password
            $validated['password'] = Hash::make($validated['password']);
            
            // Generate username if not provided
            if (!isset($validated['user_name'])) {
                $validated['user_name'] = strtolower($validated['first_name'] . '.' . $validated['last_name']);
            }

            $user = User::create($validated);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User created successfully',
                    'user' => $user
                ]);
            }

            return redirect()->route('admin.users.index')
                           ->with('success', 'User created successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 422);
            }

            return back()->with('error', 'Error: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = User::with(['manager', 'assignedWarehouse'])->findOrFail($id);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        }

        return view('backend.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = ['admin', 'driver', 'customer', 'manager', 'dispatcher'];
        $managers = User::where('role', 'manager')
                       ->where('status', 'active')
                       ->where('id', '!=', $id)
                       ->get();
        $warehouses = Warehouse::where('status', 'active')->get();

        return view('backend.users.edit', compact('user', 'roles', 'managers', 'warehouses'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,driver,customer,manager,dispatcher',
            'status' => 'required|in:active,inactive,suspended,on_leave',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'profile_photo' => 'nullable|image|max:2048',
            
            // Warehouse assignment
            'assigned_warehouse_id' => 'nullable|exists:warehouses,id',
            
            // Driver-specific
            'license_number' => 'required_if:role,driver|nullable|string|max:255',
            'vehicle_type' => 'nullable|string|max:50',
            'vehicle_number' => 'nullable|string|max:50',
            'vehicle_capacity' => 'nullable|numeric',
            'is_available' => 'nullable|boolean',
            
            // Employee fields
            'employee_id' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'department' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'joining_date' => 'nullable|date',
            'salary' => 'nullable|numeric',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                $validated['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
            }

            // Hash password if provided
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully',
                    'user' => $user->fresh()
                ]);
            }

            return redirect()->route('admin.users.index')
                           ->with('success', 'User updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 422);
            }

            return back()->with('error', 'Error: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent deleting own account
            if ($user->id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account'
                ], 422);
            }

            // Delete profile photo
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Bulk delete users
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id'
        ]);

        try {
            $users = User::whereIn('id', $validated['ids'])
                        ->where('id', '!=', Auth::id())
                        ->get();

            foreach ($users as $user) {
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                $user->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Selected users deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update user status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,suspended,on_leave',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'message' => 'User status updated successfully',
                'user' => $user->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update driver availability
     */
    public function updateAvailability(Request $request, $id)
    {
        $validated = $request->validate([
            'is_available' => 'required|boolean',
        ]);

        try {
            $user = User::findOrFail($id);
            
            if ($user->role !== 'driver') {
                throw new \Exception('Only drivers can have availability status');
            }

            $user->update(['is_available' => $validated['is_available']]);

            return response()->json([
                'success' => true,
                'message' => 'Driver availability updated successfully',
                'user' => $user->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Assign warehouse to user
     */
    public function assignWarehouse(Request $request, $id)
    {
        $validated = $request->validate([
            'assigned_warehouse_id' => 'required|exists:warehouses,id',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->update(['assigned_warehouse_id' => $validated['assigned_warehouse_id']]);

            return response()->json([
                'success' => true,
                'message' => 'Warehouse assigned successfully',
                'user' => $user->fresh()->load('assignedWarehouse')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, $id)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Export users data
     */
    public function export(Request $request)
    {
        $query = User::with('assignedWarehouse');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('warehouse_id')) {
            $query->where('assigned_warehouse_id', $request->warehouse_id);
        }

        $users = $query->get();

        // Return CSV
        $filename = 'users_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Phone', 'Role', 'Status', 
                'Employee ID', 'Department', 'Assigned Warehouse', 'Joining Date', 'Created At'
            ]);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->first_name . ' ' . $user->last_name,
                    $user->email,
                    $user->phone,
                    $user->role,
                    $user->status,
                    $user->employee_id,
                    $user->department,
                    $user->assignedWarehouse ? $user->assignedWarehouse->name : 'N/A',
                    $user->joining_date,
                    $user->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }



















    public function profile()
{
    $user = Auth::user()->load(['manager', 'assignedWarehouse']);
    
    return view('admin.admin_profile_view', compact('user'));
}

/**
 * Update the authenticated user's profile
 */
public function updateProfile(Request $request)
{
    $user = Auth::user();

    try {
        DB::beginTransaction();

        $data = $request->only([
            'first_name',
            'last_name',
            'email',
            'phone',
            'address',
            'city',
            'state',
            'country',
            'postal_code',
            'date_of_birth',
            'gender',
            'license_number',
            'vehicle_type',
            'vehicle_number',
            'vehicle_capacity',
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user->update($data);

        DB::commit();

        return redirect()->route('profile.index')
                       ->with('success', 'Profile updated successfully');

    } catch (\Exception $e) {
        DB::rollBack();

        return back()->with('error', 'Error: ' . $e->getMessage())
                    ->withInput();
    }
}

/**
 * Update the authenticated user's password
 */
public function updatePassword(Request $request)
{
    $validated = $request->validate([
        'current_password' => 'required|string',
        'password' => 'required|string|min:8|confirmed',
    ]);

    try {
        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->with('error', 'Current password is incorrect')->withInput();
        }

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('profile.index')
                       ->with('success', 'Password updated successfully');

    } catch (\Exception $e) {
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}
}