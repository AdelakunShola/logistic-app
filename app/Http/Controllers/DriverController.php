<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Notification;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\DriverPerformanceMetric;
use App\Models\DeliveryDelay;
use App\Models\ShipmentDelay;
use App\Models\CustomerFeedback;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;



class DriverController extends Controller
{

    /////DRIVER PART
    public function DriverDashboard()
    {
        return view('driver.index');
    }//end method








    /////////////ADMIN PART



    public function driverindex()
    {
        $drivers = User::where('role', 'driver')
        ->with(['assignedVehicle'])
        ->get()
        ->map(function($driver) {
            // Calculate REAL metrics from shipments
            $shipments = Shipment::where('assigned_driver_id', $driver->id)->get();
            
            $driver->total_deliveries = $shipments->count();
            
            $driver->successful_deliveries = $shipments->whereIn('status', ['delivered', 'completed'])->count();
            
            $driver->failed_deliveries = $shipments->whereIn('status', ['failed', 'cancelled'])->count();
            
            // Calculate on-time deliveries
            $onTimeDeliveries = $shipments->filter(function($shipment) {
                if (!in_array($shipment->status, ['delivered', 'completed'])) {
                    return false;
                }
                if (!$shipment->actual_delivery_date || !$shipment->expected_delivery_date) {
                    return false;
                }
                return Carbon::parse($shipment->actual_delivery_date)->lte(Carbon::parse($shipment->expected_delivery_date));
            })->count();
            
            $driver->on_time_deliveries = $onTimeDeliveries;
            $driver->on_time_rate = $driver->total_deliveries > 0 
                ? round(($onTimeDeliveries / $driver->total_deliveries) * 100, 1) 
                : 0;
            
            // FIX: Calculate average rating from customer ratings on shipments
            // Filter for valid ratings (between 1 and 5)
            $shipmentsWithRatings = $shipments->filter(function($shipment) {
                return !is_null($shipment->customer_rating) 
                    && $shipment->customer_rating > 0 
                    && $shipment->customer_rating <= 5;
            });
            
            // Calculate rating or use database value as fallback
            if ($shipmentsWithRatings->count() > 0) {
                $driver->rating = round($shipmentsWithRatings->avg('customer_rating'), 1);
            } else {
                // Use existing rating from database, or 0 if none exists
                $driver->rating = $driver->rating > 0 ? round($driver->rating, 1) : 0;
            }
            
            $driver->weekly_hours = $this->calculateWeeklyHours($driver->id);
            $driver->monthly_earnings = $this->calculateMonthlyEarnings($driver->id);
            
            // Update the user record with latest metrics INCLUDING rating
            // Only update if there are new calculations
            if ($driver->total_deliveries > 0 || $shipmentsWithRatings->count() > 0) {
                $driver->update([
                    'total_deliveries' => $driver->total_deliveries,
                    'successful_deliveries' => $driver->successful_deliveries,
                    'failed_deliveries' => $driver->failed_deliveries,
                    'rating' => $driver->rating,
                ]);
            }
            
            return $driver;
        
            });

        $totalDrivers = $drivers->count();
        
        // Available drivers: active status with no vehicle assigned
        $availableDrivers = $drivers->where('status', 'active')
            ->filter(function($driver) {
                return $driver->assignedVehicle === null;
            })->count();
        
        // On route drivers: active status with vehicle assigned
        $onRouteDrivers = $drivers->where('status', 'active')
            ->filter(function($driver) {
                return $driver->assignedVehicle !== null;
            })->count();
        
        // Calculate average rating
        $driversWithRatings = $drivers->filter(function($driver) {
            return isset($driver->rating) && $driver->rating > 0;
        });
        
        $avgRating = $driversWithRatings->count() > 0 
            ? $driversWithRatings->avg('rating') 
            : 0;

        $driversAddedThisMonth = User::where('role', 'driver')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $assignments = $this->getDriverAssignments();
        $vehicles = Vehicle::where('status', 'active')->get();

        return view('backend.fleet.driver_assignment', compact(
            'drivers',
            'totalDrivers',
            'availableDrivers',
            'onRouteDrivers',
            'avgRating',
            'driversAddedThisMonth',
            'assignments',
            'vehicles'
        ));
    }

    public function drivershow($id)
    {
        try {
            $driver = User::where('role', 'driver')
                ->with(['assignedVehicle'])
                ->findOrFail($id);
            
            $vehicle = $driver->assignedVehicle;
            
            $currentAssignment = DB::table('driver_assignments')
                ->where('driver_id', $id)
                ->where('status', 'active')
                ->first();
            
            // Calculate real-time metrics
            $shipments = Shipment::where('assigned_driver_id', $driver->id)->get();
            
            $totalDeliveries = $shipments->count();
            $successfulDeliveries = $shipments->whereIn('status', ['delivered', 'completed'])->count();
            $failedDeliveries = $shipments->whereIn('status', ['failed', 'cancelled'])->count();
            
            $onTimeDeliveries = $shipments->filter(function($shipment) {
                if (!in_array($shipment->status, ['delivered', 'completed'])) {
                    return false;
                }
                if (!$shipment->actual_delivery_date || !$shipment->expected_delivery_date) {
                    return false;
                }
                return Carbon::parse($shipment->actual_delivery_date)->lte(Carbon::parse($shipment->expected_delivery_date));
            })->count();
            
            $onTimeRate = $totalDeliveries > 0 
                ? round(($onTimeDeliveries / $totalDeliveries) * 100, 1) 
                : 0;
            
            $documents = [
                'driver_license' => [
                    'exists' => !empty($driver->driver_license),
                    'path' => $driver->driver_license,
                    'expiry' => $driver->license_expiry ? Carbon::parse($driver->license_expiry)->format('Y-m-d') : null,
                ],
                'medical_certificate' => [
                    'exists' => !empty($driver->medical_certificate),
                    'path' => $driver->medical_certificate,
                ],
                'id_proof_document' => [
                    'exists' => !empty($driver->id_proof_document),
                    'path' => $driver->id_proof_document,
                ],
                'address_proof_document' => [
                    'exists' => !empty($driver->address_proof_document),
                    'path' => $driver->address_proof_document,
                ],
            ];
            
            return response()->json([
                'id' => $driver->id,
                'first_name' => $driver->first_name,
                'last_name' => $driver->last_name,
                'email' => $driver->email,
                'phone' => $driver->phone ?? 'N/A',
                'employee_id' => $driver->employee_id,
                'profile_photo' => $driver->profile_photo,
                'status' => $driver->status ?? 'active',
                'rating' => (float)($driver->rating ?? 0),
                'city' => $driver->city ?? 'N/A',
                'state' => $driver->state ?? 'N/A',
                'address' => $driver->address ?? 'N/A',
                'experience_years' => (int)($driver->experience_years ?? 0),
                'joining_date' => $driver->joining_date ? Carbon::parse($driver->joining_date)->format('Y-m-d') : 'N/A',
                'license_number' => $driver->license_number ?? 'N/A',
                'license_expiry' => $driver->license_expiry ? Carbon::parse($driver->license_expiry)->format('Y-m-d') : 'N/A',
                'vehicle_number' => $vehicle ? $vehicle->vehicle_number : 'Not Assigned',
                'route_name' => $currentAssignment ? $currentAssignment->route_name : null,
                'specializations' => $driver->specializations ?? '',
                'total_deliveries' => $totalDeliveries,
                'successful_deliveries' => $successfulDeliveries,
                'failed_deliveries' => $failedDeliveries,
                'on_time_deliveries' => $onTimeDeliveries,
                'on_time_rate' => $onTimeRate,
                'weekly_hours' => $this->calculateWeeklyHours($driver->id),
                'monthly_earnings' => (float)$this->calculateMonthlyEarnings($driver->id),
                'documents' => $documents,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in drivershow: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Error loading driver details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function driverperformance($id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        
        // Get all shipments for this driver
        $shipments = Shipment::where('assigned_driver_id', $driver->id)->get();
        
        $totalDeliveries = $shipments->count();
        $successfulDeliveries = $shipments->whereIn('status', ['delivered', 'completed'])->count();
        $failedDeliveries = $shipments->whereIn('status', ['failed', 'cancelled'])->count();
        
        $onTimeDeliveries = $shipments->filter(function($shipment) {
            if (!in_array($shipment->status, ['delivered', 'completed'])) {
                return false;
            }
            if (!$shipment->actual_delivery_date || !$shipment->expected_delivery_date) {
                return false;
            }
            return Carbon::parse($shipment->actual_delivery_date)->lte(Carbon::parse($shipment->expected_delivery_date));
        })->count();
        
        $onTimeRate = $totalDeliveries > 0 
            ? round(($onTimeDeliveries / $totalDeliveries) * 100, 1) 
            : 0;
        
        // Monthly performance from actual shipments
        $monthlyPerformance = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
            
            $monthShipments = Shipment::where('assigned_driver_id', $driver->id)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->get();
            
            $monthTotal = $monthShipments->count();
            $monthOnTime = $monthShipments->filter(function($shipment) {
                if (!in_array($shipment->status, ['delivered', 'completed'])) {
                    return false;
                }
                if (!$shipment->actual_delivery_date || !$shipment->expected_delivery_date) {
                    return false;
                }
                return Carbon::parse($shipment->actual_delivery_date)->lte(Carbon::parse($shipment->expected_delivery_date));
            })->count();
            
            $monthlyPerformance[] = [
                'month' => $monthStart->format('M Y'),
                'deliveries' => $monthTotal,
                'on_time_rate' => $monthTotal > 0 ? round(($monthOnTime / $monthTotal) * 100, 1) : 0,
            ];
        }
        
        return response()->json([
            'total_deliveries' => $totalDeliveries,
            'successful_deliveries' => $successfulDeliveries,
            'failed_deliveries' => $failedDeliveries,
            'on_time_rate' => $onTimeRate,
            'rating' => (float)($driver->rating ?? 0),
            'monthly_earnings' => (float)$this->calculateMonthlyEarnings($id),
            'weekly_hours' => $this->calculateWeeklyHours($id),
            'monthly_performance' => $monthlyPerformance,
        ]);
    }

    private function calculateWeeklyHours($driverId)
    {
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        
        // Try to get from driver_performance_metrics
        $metrics = DB::table('driver_performance_metrics')
            ->where('driver_id', $driverId)
            ->whereBetween('date', [$weekStart, $weekEnd])
            ->sum('hours_worked');
        
        if ($metrics > 0) {
            return round($metrics, 1) . 'h';
        }
        
        // Fallback: Calculate from driver_assignments
        $assignments = DB::table('driver_assignments')
            ->where('driver_id', $driverId)
            ->where('status', 'active')
            ->whereBetween('start_date', [$weekStart, $weekEnd])
            ->count();
        
        // Estimate 8 hours per active assignment
        $estimatedHours = $assignments * 8;
        
        return $estimatedHours . 'h';
    }

    private function calculateMonthlyEarnings($driverId)
    {
        $driver = User::find($driverId);
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        
        // Try to get from driver_performance_metrics
        $metrics = DB::table('driver_performance_metrics')
            ->where('driver_id', $driverId)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->sum('earnings');
        
        if ($metrics > 0) {
            return $metrics;
        }
        
        // Fallback: Calculate from shipments
        $completedDeliveries = Shipment::where('assigned_driver_id', $driverId)
            ->whereIn('status', ['delivered', 'completed'])
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->count();
        
        $baseSalary = $driver->salary ?? 0;
        $commissionRate = $driver->commission_rate ?? 0;
        $commissionEarnings = $completedDeliveries * 10; // $10 per delivery
        
        return $baseSalary + $commissionEarnings;
    }

    private function getDriverAssignments()
    {
        return User::where('role', 'driver')
            ->with(['assignedVehicle'])
            ->whereHas('assignedVehicle')
            ->get()
            ->map(function($driver) {
                $assignment = DB::table('driver_assignments')
                    ->where('driver_id', $driver->id)
                    ->where('status', 'active')
                    ->latest()
                    ->first();
                
                return (object)[
                    'id' => $assignment->id ?? $driver->id,
                    'driver' => $driver,
                    'vehicle' => $driver->assignedVehicle,
                    'route_name' => $assignment->route_name ?? $this->getCurrentRoute($driver->id),
                    'status' => $driver->status,
                ];
            });
    }

    private function getCurrentRoute($driverId)
    {
        $assignment = DB::table('driver_assignments')
            ->where('driver_id', $driverId)
            ->where('status', 'active')
            ->latest()
            ->first();
        
        return $assignment ? $assignment->route_name : null;
    }

    // Keep all your other methods: driveredit, driverhistory, etc.
    public function driveredit($id)
    {
        $driver = User::where('role', 'driver')
            ->with(['assignedVehicle'])
            ->findOrFail($id);
        
        return response()->json([
            'id' => $driver->id,
            'first_name' => $driver->first_name,
            'last_name' => $driver->last_name,
            'email' => $driver->email,
            'phone' => $driver->phone,
            'date_of_birth' => $driver->date_of_birth ? $driver->date_of_birth->format('Y-m-d') : null,
            'gender' => $driver->gender,
            'address' => $driver->address,
            'city' => $driver->city,
            'state' => $driver->state,
            'country' => $driver->country,
            'postal_code' => $driver->postal_code,
            'status' => $driver->status,
            'license_number' => $driver->license_number,
            'license_expiry' => $driver->license_expiry ? Carbon::parse($driver->license_expiry)->format('Y-m-d') : null,
            'experience_years' => $driver->experience_years,
            'specializations' => $driver->specializations,
            'vehicle_type' => $driver->vehicle_type,
            'vehicle_number' => $driver->vehicle_number,
            'vehicle_capacity' => $driver->vehicle_capacity,
            'emergency_contact_name' => $driver->emergency_contact_name,
            'emergency_contact_phone' => $driver->emergency_contact_phone,
            'id_proof_type' => $driver->id_proof_type,
            'id_proof_number' => $driver->id_proof_number,
            'employee_id' => $driver->employee_id,
            'joining_date' => $driver->joining_date ? Carbon::parse($driver->joining_date)->format('Y-m-d') : null,
            'salary' => $driver->salary,
            'commission_rate' => $driver->commission_rate,
            'bank_name' => $driver->bank_name,
            'account_number' => $driver->account_number,
            'account_holder_name' => $driver->account_holder_name,
            'ifsc_code' => $driver->ifsc_code,
            'profile_photo' => $driver->profile_photo,
            'vehicle_id' => $driver->assignedVehicle ? $driver->assignedVehicle->id : null,
        ]);
    }

    public function driverhistory($id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        
        $assignments = DB::table('driver_assignments')
            ->join('vehicles', 'driver_assignments.vehicle_id', '=', 'vehicles.id')
            ->where('driver_assignments.driver_id', $id)
            ->select(
                'driver_assignments.*',
                'vehicles.vehicle_number',
                'vehicles.vehicle_name'
            )
            ->orderBy('driver_assignments.created_at', 'desc')
            ->get();
        
        $activities = ActivityLog::where('model_type', 'User')
            ->where('model_id', $id)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
        
        return response()->json([
            'assignments' => $assignments,
            'activities' => $activities,
        ]);
    }



    public function driverreassign(Request $request)
    {
        $validated = $request->validate([
            'assignment_id' => 'required|exists:driver_assignments,id',
            'driver_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'route_name' => 'nullable|string',
            'start_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $driver = User::findOrFail($validated['driver_id']);
            $newVehicle = Vehicle::findOrFail($validated['vehicle_id']);
            
            $currentAssignment = DB::table('driver_assignments')
                ->where('id', $validated['assignment_id'])
                ->first();
            
            if (!$currentAssignment) {
                return response()->json(['success' => false, 'message' => 'Assignment not found'], 404);
            }
            
            $oldVehicle = Vehicle::find($currentAssignment->vehicle_id);
            
            DB::table('driver_assignments')
                ->where('id', $validated['assignment_id'])
                ->update([
                    'status' => 'completed',
                    'end_date' => now(),
                    'actual_end_time' => now(),
                    'updated_at' => now(),
                ]);
            
            if ($oldVehicle) {
                $oldVehicle->update(['assigned_driver_id' => null]);
            }
            
            if ($newVehicle->assigned_driver_id && $newVehicle->assigned_driver_id != $driver->id) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Vehicle is already assigned to another driver'], 400);
            }
            
            $newAssignmentId = DB::table('driver_assignments')->insertGetId([
                'driver_id' => $driver->id,
                'vehicle_id' => $newVehicle->id,
                'route_name' => $validated['route_name'],
                'start_date' => $validated['start_date'],
                'status' => 'active',
                'notes' => $validated['notes'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $newVehicle->update(['assigned_driver_id' => $driver->id]);
            
            $driver->update([
                'vehicle_number' => $newVehicle->vehicle_number,
                'vehicle_type' => $newVehicle->vehicle_type,
                'is_available' => false,
            ]);
            
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'reassigned',
                'model_type' => 'User',
                'model_id' => $driver->id,
                'description' => "Reassigned driver {$driver->first_name} {$driver->last_name} from vehicle {$oldVehicle->vehicle_number} to {$newVehicle->vehicle_number}",
                'old_values' => json_encode(['vehicle_id' => $oldVehicle->id, 'assignment_id' => $currentAssignment->id]),
                'new_values' => json_encode(['vehicle_id' => $newVehicle->id, 'assignment_id' => $newAssignmentId]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            Notification::create([
                'user_id' => $driver->id,
                'title' => 'Vehicle Reassignment',
                'message' => "You have been reassigned from vehicle {$oldVehicle->vehicle_number} to {$newVehicle->vehicle_number}" . 
                           ($validated['route_name'] ? " for route {$validated['route_name']}" : ''),
                'type' => 'info',
                'channel' => 'system',
                'data' => json_encode([
                    'old_vehicle_id' => $oldVehicle->id,
                    'new_vehicle_id' => $newVehicle->id,
                    'assignment_id' => $newAssignmentId,
                    'route_name' => $validated['route_name'],
                ]),
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true, 
                'message' => 'Driver reassigned successfully!',
                'data' => [
                    'assignment_id' => $newAssignmentId,
                    'vehicle' => $newVehicle,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error reassigning driver: ' . $e->getMessage()], 500);
        }
    }

    public function viewDocument(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:users,id',
            'document_type' => 'required|in:driver_license,medical_certificate,id_proof_document,address_proof_document',
        ]);
        
        $driver = User::findOrFail($validated['driver_id']);
        $documentPath = $driver->{$validated['document_type']};
        
        if (!$documentPath || !Storage::disk('public')->exists($documentPath)) {
            return response()->json(['success' => false, 'message' => 'Document not found'], 404);
        }
        
        $fullPath = Storage::disk('public')->path($documentPath);
        $mimeType = Storage::disk('public')->mimeType($documentPath);
        
        return response()->json([
            'success' => true,
            'url' => asset('storage/' . $documentPath),
            'mime_type' => $mimeType,
            'filename' => basename($documentPath),
        ]);
    }

    

    public function driverstore(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'license_number' => 'required|string|unique:users,license_number',
            'license_expiry' => 'required|date',
            'driver_license' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'experience_years' => 'required|numeric|min:0',
            'medical_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'specializations' => 'nullable|string',
            'vehicle_type' => 'nullable|string',
            'vehicle_number' => 'nullable|string',
            'vehicle_capacity' => 'nullable|numeric',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'id_proof_type' => 'nullable|string',
            'id_proof_number' => 'nullable|string',
            'id_proof_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'address_proof_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'employee_id' => 'nullable|string|unique:users,employee_id',
            'joining_date' => 'nullable|date',
            'salary' => 'nullable|numeric',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'account_holder_name' => 'nullable|string|max:100',
            'ifsc_code' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if (empty($validated['employee_id'])) {
                $validated['employee_id'] = 'DRV' . str_pad(User::where('role', 'driver')->count() + 1, 3, '0', STR_PAD_LEFT);
            }

            $validated['password'] = Hash::make('Driver@123');
            $validated['role'] = 'driver';
            $validated['status'] = 'active';
            $validated['is_available'] = true;

            if ($request->hasFile('profile_photo')) {
                $validated['profile_photo'] = $request->file('profile_photo')->store('drivers/photos', 'public');
            }

            if ($request->hasFile('driver_license')) {
                $validated['driver_license'] = $request->file('driver_license')->store('drivers/licenses', 'public');
            }

            if ($request->hasFile('medical_certificate')) {
                $validated['medical_certificate'] = $request->file('medical_certificate')->store('drivers/medical', 'public');
            }

            if ($request->hasFile('id_proof_document')) {
                $validated['id_proof_document'] = $request->file('id_proof_document')->store('drivers/id_proofs', 'public');
            }

            if ($request->hasFile('address_proof_document')) {
                $validated['address_proof_document'] = $request->file('address_proof_document')->store('drivers/address_proofs', 'public');
            }

            $driver = User::create($validated);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'created',
                'model_type' => 'User',
                'model_id' => $driver->id,
                'description' => 'Created new driver: ' . $driver->first_name . ' ' . $driver->last_name,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Notification::create([
                'user_id' => $driver->id,
                'title' => 'Welcome to the Fleet',
                'message' => 'Your driver account has been created. Please login and update your password.',
                'type' => 'success',
                'channel' => 'system',
            ]);

            DB::commit();

            return redirect()->route('drivers.index')->with('success', 'Driver added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error adding driver: ' . $e->getMessage())->withInput();
        }
    }

    public function driverupdate(Request $request, $id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'status' => 'required|in:active,inactive,suspended,on_leave',
            'license_number' => 'required|string|unique:users,license_number,' . $id,
            'license_expiry' => 'nullable|date',
            'experience_years' => 'nullable|numeric',
            'specializations' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'salary' => 'nullable|numeric',
            'profile_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $oldValues = $driver->toArray();

            if ($request->hasFile('profile_photo')) {
                if ($driver->profile_photo) {
                    Storage::disk('public')->delete($driver->profile_photo);
                }
                $validated['profile_photo'] = $request->file('profile_photo')->store('drivers/photos', 'public');
            }

            $driver->update($validated);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'model_type' => 'User',
                'model_id' => $driver->id,
                'description' => 'Updated driver: ' . $driver->first_name . ' ' . $driver->last_name,
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($driver->toArray()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Notification::create([
                'user_id' => $driver->id,
                'title' => 'Profile Updated',
                'message' => 'Your driver profile has been updated by an administrator.',
                'type' => 'info',
                'channel' => 'system',
            ]);

            DB::commit();

            return redirect()->route('drivers.index')->with('success', 'Driver updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating driver: ' . $e->getMessage());
        }
    }

    public function driverdestroy($id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);

        DB::beginTransaction();
        try {
            $driverName = $driver->first_name . ' ' . $driver->last_name;

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'model_type' => 'User',
                'model_id' => $driver->id,
                'description' => 'Removed driver: ' . $driverName,
                'old_values' => json_encode($driver->toArray()),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            Vehicle::where('assigned_driver_id', $driver->id)->update(['assigned_driver_id' => null]);

            $driver->delete();

            Notification::create([
                'user_id' => auth()->id(),
                'title' => 'Driver Removed',
                'message' => 'Driver ' . $driverName . ' has been removed from the fleet.',
                'type' => 'warning',
                'channel' => 'system',
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Driver removed successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error removing driver: ' . $e->getMessage()], 500);
        }
    }

    public function driverassign(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'route_name' => 'nullable|string',
            'start_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $driver = User::findOrFail($validated['driver_id']);
            $vehicle = Vehicle::findOrFail($validated['vehicle_id']);

            if ($vehicle->assigned_driver_id && $vehicle->assigned_driver_id != $driver->id) {
                return redirect()->back()->with('error', 'Vehicle is already assigned to another driver!');
            }

            $vehicle->update([
                'assigned_driver_id' => $driver->id,
                'status' => 'active',
            ]);

            $driver->update([
                'vehicle_number' => $vehicle->vehicle_number,
                'vehicle_type' => $vehicle->vehicle_type,
                'status' => 'active',
                'is_available' => false,
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'assigned',
                'model_type' => 'User',
                'model_id' => $driver->id,
                'description' => "Assigned driver {$driver->first_name} {$driver->last_name} to vehicle {$vehicle->vehicle_number}",
                'new_values' => json_encode($validated),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Notification::create([
                'user_id' => $driver->id,
                'title' => 'New Assignment',
                'message' => "You have been assigned to vehicle {$vehicle->vehicle_number}" . 
                           ($validated['route_name'] ? " for route {$validated['route_name']}" : ''),
                'type' => 'info',
                'channel' => 'system',
                'data' => json_encode([
                    'vehicle_id' => $vehicle->id,
                    'route_name' => $validated['route_name'] ?? null,
                    'start_date' => $validated['start_date'],
                ]),
            ]);

            DB::commit();

            return redirect()->route('drivers.index')->with('success', 'Driver assigned successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error assigning driver: ' . $e->getMessage());
        }
    }

    public function driversendMessage(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,error',
            'channels' => 'required|array',
            'channels.*' => 'in:system,email,sms,push',
        ]);

        DB::beginTransaction();
        try {
            $driver = User::findOrFail($validated['driver_id']);

            foreach ($validated['channels'] as $channel) {
                Notification::create([
                    'user_id' => $driver->id,
                    'title' => $validated['title'],
                    'message' => $validated['message'],
                    'type' => $validated['type'],
                    'channel' => $channel,
                ]);
            }

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'sent_message',
                'model_type' => 'User',
                'model_id' => $driver->id,
                'description' => "Sent message to driver {$driver->first_name} {$driver->last_name}",
                'new_values' => json_encode($validated),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('drivers.index')->with('success', 'Message sent successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error sending message: ' . $e->getMessage());
        }
    }

    public function driverexport(Request $request)
    {
        $format = $request->get('format', 'csv');
        $drivers = User::where('role', 'driver')->get();

        switch ($format) {
            case 'csv':
                return $this->exportCSV($drivers);
            case 'excel':
                return $this->exportExcel($drivers);
            case 'pdf':
                return $this->exportPDF($drivers);
            default:
                return redirect()->back()->with('error', 'Invalid export format');
        }
    }

    private function exportCSV($drivers)
    {
        $filename = 'drivers_' . date('Y-m-d_His') . '.csv';
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, [
            'Employee ID', 'First Name', 'Last Name', 'Email', 'Phone', 
            'License Number', 'Status', 'Vehicle', 'Rating', 'Total Deliveries'
        ]);

        foreach ($drivers as $driver) {
            fputcsv($handle, [
                $driver->employee_id,
                $driver->first_name,
                $driver->last_name,
                $driver->email,
                $driver->phone,
                $driver->license_number,
                $driver->status,
                $driver->vehicle_number ?? 'N/A',
                $driver->rating,
                $driver->total_deliveries,
            ]);
        }

        fclose($handle);
        exit;
    }

    private function exportExcel($drivers)
    {
        return redirect()->back()->with('info', 'Excel export requires PhpSpreadsheet library');
    }

    private function exportPDF($drivers)
    {
        return redirect()->back()->with('info', 'PDF export requires dompdf library');
    }

   















    public function driverreport($id)
{
    // Redirect to performance page with driver ID
    return redirect()->route('admin.performance.show', ['driver_id' => $id]);;
}

private function getDelayReasons($driverIds, $dateFrom, $dateTo)
{
    $query = DB::table('shipment_delays')
        ->select(
            'delay_reason',
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('created_at', [$dateFrom, $dateTo]);

    // Filter by driver(s) if provided - ensure it's an array
    if (!empty($driverIds) && (is_array($driverIds) && count($driverIds) > 0)) {
        $query->whereIn('driver_id', $driverIds);
    }

    $results = $query->groupBy('delay_reason')->get();

    $total = $results->sum('count');

    // Convert enum values to readable names
    $labels = [
        'traffic_congestion' => 'Traffic Congestion',
        'weather_conditions' => 'Weather Conditions',
        'vehicle_issues' => 'Vehicle Issues',
        'address_issues' => 'Address Issues',
        'customer_unavailable' => 'Customer Unavailable',
        'customs_delay' => 'Customs Delay',
        'port_congestion' => 'Port Congestion',
        'documentation_issues' => 'Documentation Issues',
        'mechanical_failure' => 'Mechanical Failure',
        'road_closure' => 'Road Closure',
        'other' => 'Other',
    ];

    // Format results with percentage
    return $results->map(function ($item) use ($total, $labels) {
        return [
            'reason' => $labels[$item->delay_reason] ?? ucfirst(str_replace('_', ' ', $item->delay_reason)),
            'count' => $item->count,
            'percentage' => $total > 0 ? round(($item->count / $total) * 100, 1) : 0,
        ];
    });
}
// Update the driver performance metrics daily (can be called via scheduler)
public function updateDriverPerformanceMetrics($driverId = null, $date = null)
{
    $date = $date ?? Carbon::today()->format('Y-m-d');
    $driverIds = $driverId ? [$driverId] : User::where('role', 'driver')->pluck('id');
    
    foreach ($driverIds as $dId) {
        $shipments = DB::table('shipments')
            ->where('assigned_driver_id', $dId)
            ->whereDate('created_at', $date)
            ->get();
        
        $completed = $shipments->where('status', 'delivered')->count();
        $failed = $shipments->where('status', 'failed')->count();
        $cancelled = $shipments->where('status', 'cancelled')->count();
        
        $onTime = $shipments->filter(function($s) {
            return $s->status === 'delivered' && $s->actual_delivery_date <= $s->expected_delivery_date;
        })->count();
        
        $onTimePercentage = $completed > 0 ? ($onTime / $completed) * 100 : 0;
        
        // Calculate other metrics (this would come from vehicle tracking, etc.)
        $distance = rand(50, 200); // Replace with actual GPS tracking data
        $hours = rand(6, 10); // Replace with actual time tracking
        $earnings = $completed * 10; // Replace with actual earnings calculation
        
        DB::table('driver_performance_metrics')->updateOrInsert(
            ['driver_id' => $dId, 'date' => $date],
            [
                'deliveries_completed' => $completed,
                'deliveries_failed' => $failed,
                'deliveries_cancelled' => $cancelled,
                'on_time_percentage' => $onTimePercentage,
                'distance_travelled' => $distance,
                'hours_worked' => $hours,
                'earnings' => $earnings,
                'average_rating' => User::find($dId)->rating ?? 0,
                'updated_at' => now(),
            ]
        );
    }
}










public function getCarrierPerformance($dateFrom, $dateTo)
{
    $carriers = DB::table('carriers')
        ->leftJoin('shipments', 'carriers.id', '=', 'shipments.carrier_id')
        ->whereBetween('shipments.created_at', [$dateFrom, $dateTo])
        ->select(
            'carriers.id',
            'carriers.name',
            DB::raw('COUNT(shipments.id) as total_deliveries'),
            DB::raw('SUM(CASE WHEN shipments.status = "delivered" THEN 1 ELSE 0 END) as delivered'),
            DB::raw('SUM(CASE WHEN shipments.status = "delivered" AND shipments.actual_delivery_date <= shipments.expected_delivery_date THEN 1 ELSE 0 END) as on_time'),
            DB::raw('AVG(CASE WHEN shipments.status = "delivered" THEN DATEDIFF(shipments.actual_delivery_date, shipments.pickup_date) ELSE NULL END) as avg_delay_days')
        )
        ->groupBy('carriers.id', 'carriers.name')
        ->having('total_deliveries', '>', 0)
        ->get();
    
    return $carriers->map(function($carrier) {
        $onTimeRate = $carrier->delivered > 0 ? ($carrier->on_time / $carrier->delivered) * 100 : 0;
        return [
            'id' => $carrier->id,
            'name' => $carrier->name,
            'total_deliveries' => $carrier->total_deliveries,
            'on_time_rate' => round($onTimeRate, 1),
            'avg_delay' => round($carrier->avg_delay_days ?? 0, 1),
        ];
    })->toArray();
}

public function getDeliveryStatusDistribution($dateFrom, $dateTo)
{
    $total = DB::table('shipments')
        ->whereBetween('created_at', [$dateFrom, $dateTo])
        ->count();
    
    $onTime = DB::table('shipments')
        ->whereBetween('created_at', [$dateFrom, $dateTo])
        ->where('status', 'delivered')
        ->whereColumn('actual_delivery_date', '<=', 'expected_delivery_date')
        ->count();
    
    $delayed = DB::table('shipments')
        ->whereBetween('created_at', [$dateFrom, $dateTo])
        ->where('status', 'delivered')
        ->whereColumn('actual_delivery_date', '>', 'expected_delivery_date')
        ->count();
    
    return [
        'on_time_percentage' => $total > 0 ? round(($onTime / $total) * 100, 1) : 0,
        'delayed_percentage' => $total > 0 ? round(($delayed / $total) * 100, 1) : 0,
    ];
}

public function getDelayReasonsData($dateFrom, $dateTo)
{
    // Query the shipment_delays table
    $results = DB::table('shipment_delays')
        ->select(
            'delay_reason',
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('created_at', [$dateFrom, $dateTo])
        ->groupBy('delay_reason')
        ->get();

    $total = $results->sum('count');

    // Human-readable labels for delay reasons
    $labels = [
        'traffic_congestion' => 'Traffic Congestion',
        'weather_conditions' => 'Weather Conditions',
        'vehicle_issues' => 'Vehicle Issues',
        'address_issues' => 'Address Issues',
        'customer_unavailable' => 'Customer Unavailable',
        'customs_delay' => 'Customs Delay',
        'port_congestion' => 'Port Congestion',
        'documentation_issues' => 'Documentation Issues',
        'mechanical_failure' => 'Mechanical Failure',
        'road_closure' => 'Road Closure',
        'other' => 'Other',
    ];

    // Transform data into percentage structure
    return $results->map(function ($item) use ($total, $labels) {
        $percentage = $total > 0 ? round(($item->count / $total) * 100, 1) : 0;

        return [
            'reason' => $labels[$item->delay_reason] ?? ucfirst(str_replace('_', ' ', $item->delay_reason)),
            'percentage' => $percentage,
        ];
    })->toArray();
}

public function getRecentDeliveries($dateFrom, $dateTo)
{
    return DB::table('shipments')
        ->join('users', 'shipments.customer_id', '=', 'users.id')
        ->leftJoin('carriers', 'shipments.carrier_id', '=', 'carriers.id')
        ->whereBetween('shipments.created_at', [$dateFrom, $dateTo])
        ->select(
            'shipments.id',
            'shipments.tracking_number',
            'shipments.reference_number',
            DB::raw("CONCAT(users.first_name, ' ', users.last_name) as customer_name"),
            'shipments.delivery_city',
            'shipments.delivery_state',
            'carriers.name as carrier_name',
            'shipments.status',
            'shipments.pickup_scheduled_date',
            'shipments.actual_delivery_date',
            DB::raw('CASE WHEN shipments.status = "delivered" AND shipments.actual_delivery_date <= shipments.expected_delivery_date THEN "yes" ELSE "no" END as on_time')
        )
        ->orderBy('shipments.created_at', 'desc')
        ->limit(50)
        ->get();
}

// Update showPerformance method to include all data
public function showPerformance(Request $request)
{
    $driverId = $request->get('driver_id');
    $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
    $dateTo = $request->get('date_to', Carbon::now()->endOfMonth()->format('Y-m-d'));
    
    if ($driverId) {
        // Single driver view
        $driver = User::where('role', 'driver')->findOrFail($driverId);
        $drivers = collect([$driver]);
        $isSingleDriver = true;
    } else {
        // All drivers view
        $drivers = User::where('role', 'driver')
            ->where('status', 'active')
            ->get();
        $isSingleDriver = false;
    }
    
    // Calculate individual driver metrics
    $drivers = $drivers->map(function($driver) use ($dateFrom, $dateTo) {
        // Get shipments for THIS specific driver in the date range
        $shipments = Shipment::where('assigned_driver_id', $driver->id)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();
        
        // Calculate metrics for THIS driver
        $driver->total_deliveries = $shipments->count();
        
        $driver->successful_deliveries = $shipments->whereIn('status', ['delivered', 'completed'])->count();
        
        $driver->failed_deliveries = $shipments->whereIn('status', ['failed', 'cancelled'])->count();
        
        // Calculate on-time deliveries for THIS driver
        $onTimeDeliveries = $shipments->filter(function($shipment) {
            if (!in_array($shipment->status, ['delivered', 'completed'])) {
                return false;
            }
            if (!$shipment->actual_delivery_date || !$shipment->expected_delivery_date) {
                return false;
            }
            return Carbon::parse($shipment->actual_delivery_date)
                ->lte(Carbon::parse($shipment->expected_delivery_date));
        })->count();
        
        $driver->on_time_deliveries = $onTimeDeliveries;
        $driver->on_time_rate = $driver->total_deliveries > 0 
            ? round(($onTimeDeliveries / $driver->total_deliveries) * 100, 1) 
            : 0;
        
        // Calculate average rating from customer ratings on shipments
        $shipmentsWithRatings = $shipments->filter(function($shipment) {
            return !is_null($shipment->customer_rating) && $shipment->customer_rating > 0;
        });
        
        $driver->rating = $shipmentsWithRatings->count() > 0 
            ? round($shipmentsWithRatings->avg('customer_rating'), 1)
            : 0;
        
        // Update the user record with the calculated rating
        $driver->update(['rating' => $driver->rating]);
        
        // Calculate weekly hours for THIS driver
        $driver->weekly_hours = $this->calculateWeeklyHours($driver->id);
        
        // Calculate monthly earnings for THIS driver
        $driver->monthly_earnings = $this->calculateMonthlyEarnings($driver->id);
        
        return $driver;
    });
    
    // Get overall performance data (for charts and overview)
    $performanceData = $this->getPerformanceData($drivers, $dateFrom, $dateTo);
    
    // Add additional aggregate data for Overview tab
    $performanceData['carriers'] = $this->getCarrierPerformance($dateFrom, $dateTo);
    $performanceData['status_distribution'] = $this->getDeliveryStatusDistribution($dateFrom, $dateTo);
    $performanceData['delay_reasons'] = $this->getDelayReasonsData($dateFrom, $dateTo);
    $performanceData['recent_deliveries'] = $this->getRecentDeliveries($dateFrom, $dateTo);
    
    // Calculate overview metrics (aggregate across all drivers or single driver)
    $totalShipments = DB::table('shipments')
        ->when($driverId, function($query) use ($driverId) {
            return $query->where('assigned_driver_id', $driverId);
        })
        ->whereBetween('created_at', [$dateFrom, $dateTo])
        ->count();
    
    $avgDeliveryTime = DB::table('shipments')
        ->when($driverId, function($query) use ($driverId) {
            return $query->where('assigned_driver_id', $driverId);
        })
        ->whereBetween('created_at', [$dateFrom, $dateTo])
        ->where('status', 'delivered')
        ->selectRaw('AVG(DATEDIFF(actual_delivery_date, pickup_date)) as avg_days')
        ->value('avg_days');
    
    $customerSatisfaction = DB::table('shipments')
        ->when($driverId, function($query) use ($driverId) {
            return $query->where('assigned_driver_id', $driverId);
        })
        ->whereBetween('created_at', [$dateFrom, $dateTo])
        ->where('status', 'delivered')
        ->avg('customer_rating') ?? 4.2;
    
    // Calculate on-time rate for overview
    $totalDelivered = DB::table('shipments')
        ->when($driverId, function($query) use ($driverId) {
            return $query->where('assigned_driver_id', $driverId);
        })
        ->whereBetween('created_at', [$dateFrom, $dateTo])
        ->whereIn('status', ['delivered', 'completed'])
        ->count();
    
    $onTimeDelivered = DB::table('shipments')
        ->when($driverId, function($query) use ($driverId) {
            return $query->where('assigned_driver_id', $driverId);
        })
        ->whereBetween('created_at', [$dateFrom, $dateTo])
        ->whereIn('status', ['delivered', 'completed'])
        ->whereRaw('actual_delivery_date <= expected_delivery_date')
        ->count();
    
    $onTimeRate = $totalDelivered > 0 
        ? round(($onTimeDelivered / $totalDelivered) * 100, 1) 
        : 0;
    
    $performanceData['overview_metrics'] = [
        'on_time_rate' => $onTimeRate,
        'avg_delivery_time' => round($avgDeliveryTime ?? 2.3, 1),
        'customer_satisfaction' => round($customerSatisfaction, 1),
        'total_deliveries' => $totalShipments,
    ];
    
    // Add individual driver metrics to performance data
    $performanceData['drivers_metrics'] = $drivers->map(function($driver) {
        return [
            'id' => $driver->id,
            'name' => $driver->first_name . ' ' . $driver->last_name,
            'total_deliveries' => $driver->total_deliveries,
            'successful_deliveries' => $driver->successful_deliveries,
            'failed_deliveries' => $driver->failed_deliveries,
            'on_time_rate' => $driver->on_time_rate,
            'weekly_hours' => $driver->weekly_hours,
            'monthly_earnings' => $driver->monthly_earnings,
            'rating' => $driver->rating ?? 0,
        ];
    });
    
    ActivityLog::create([
        'user_id' => auth()->id(),
        'action' => 'viewed',
        'model_type' => 'Report',
        'model_id' => $driverId ?? 0,
        'description' => $isSingleDriver 
            ? "Viewed performance report for driver {$driver->first_name} {$driver->last_name}"
            : "Viewed overall performance report",
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);
    
    return view('backend.reports.delivery', compact(
        'performanceData',
        'drivers',
        'isSingleDriver',
        'dateFrom',
        'dateTo',
        'driverId'
    ));
}

private function getPerformanceData($drivers, $dateFrom, $dateTo)
{
    $data = [
        'overview' => [],
        'drivers' => [],
        'trends' => [],
        'regions' => [],
        'delays' => [],
    ];
    
    foreach ($drivers as $driver) {
        $shipments = DB::table('shipments')
            ->where('assigned_driver_id', $driver->id)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();
        
        $totalDeliveries = $shipments->count();
        $delivered = $shipments->where('status', 'delivered')->count();
        $failed = $shipments->where('status', 'failed')->count();
        $inTransit = $shipments->whereIn('status', ['in_transit', 'out_for_delivery'])->count();
        
        $onTimeDeliveries = $shipments->filter(function($shipment) {
            return $shipment->status === 'delivered' && 
                   $shipment->actual_delivery_date <= $shipment->expected_delivery_date;
        })->count();
        
        $onTimeRate = $delivered > 0 ? ($onTimeDeliveries / $delivered) * 100 : 0;
        
        $avgDeliveryTime = $shipments->where('status', 'delivered')
            ->map(function($shipment) {
                $pickup = Carbon::parse($shipment->pickup_date);
                $delivery = Carbon::parse($shipment->actual_delivery_date);
                return $pickup->diffInDays($delivery);
            })->avg() ?? 0;
        
        $performanceMetrics = DB::table('driver_performance_metrics')
            ->where('driver_id', $driver->id)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->get();
        
        $totalDistance = $performanceMetrics->sum('distance_travelled');
        $totalHours = $performanceMetrics->sum('hours_worked');
        $totalEarnings = $performanceMetrics->sum('earnings');
        $avgRating = $performanceMetrics->avg('average_rating') ?? $driver->rating ?? 0;
        
        $complaints = $performanceMetrics->sum('customer_complaints');
        $compliments = $performanceMetrics->sum('customer_compliments');
        
        $driverData = [
            'id' => $driver->id,
            'name' => "{$driver->first_name} {$driver->last_name}",
            'employee_id' => $driver->employee_id,
            'profile_photo' => $driver->profile_photo,
            'vehicle_number' => $driver->vehicle_number ?? 'Not Assigned',
            'total_deliveries' => $totalDeliveries,
            'successful_deliveries' => $delivered,
            'failed_deliveries' => $failed,
            'in_transit' => $inTransit,
            'on_time_rate' => round($onTimeRate, 1),
            'avg_delivery_time' => round($avgDeliveryTime, 1),
            'rating' => round($avgRating, 1),
            'distance_travelled' => round($totalDistance, 2),
            'hours_worked' => round($totalHours, 2),
            'earnings' => round($totalEarnings, 2),
            'complaints' => $complaints,
            'compliments' => $compliments,
        ];
        
        $data['drivers'][] = $driverData;
    }
    
    $data['overview'] = [
        'total_deliveries' => array_sum(array_column($data['drivers'], 'total_deliveries')),
        'successful_deliveries' => array_sum(array_column($data['drivers'], 'successful_deliveries')),
        'failed_deliveries' => array_sum(array_column($data['drivers'], 'failed_deliveries')),
        'in_transit' => array_sum(array_column($data['drivers'], 'in_transit')),
        'avg_on_time_rate' => count($data['drivers']) > 0 
            ? round(array_sum(array_column($data['drivers'], 'on_time_rate')) / count($data['drivers']), 1) 
            : 0,
        'avg_delivery_time' => count($data['drivers']) > 0 
            ? round(array_sum(array_column($data['drivers'], 'avg_delivery_time')) / count($data['drivers']), 1) 
            : 0,
        'avg_rating' => count($data['drivers']) > 0 
            ? round(array_sum(array_column($data['drivers'], 'rating')) / count($data['drivers']), 1) 
            : 0,
    ];
    
    $data['trends'] = $this->getMonthlyTrends($drivers->pluck('id')->toArray(), $dateFrom, $dateTo);
    $data['regions'] = $this->getRegionalPerformance($drivers->pluck('id')->toArray(), $dateFrom, $dateTo);
    $data['delays'] = $this->getDelayReasons($drivers->pluck('id')->toArray(), $dateFrom, $dateTo);
    
    return $data;
}

private function getMonthlyTrends($driverIds, $dateFrom, $dateTo)
{
    $trends = [];
    $startDate = Carbon::parse($dateFrom);
    $endDate = Carbon::parse($dateTo);
    
    // Get last 12 months
    for ($i = 11; $i >= 0; $i--) {
        $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
        $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
        
        $shipments = DB::table('shipments')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->get();
        
        $delivered = $shipments->where('status', 'delivered')->count();
        $onTime = $shipments->filter(function($s) {
            return $s->status === 'delivered' && $s->actual_delivery_date <= $s->expected_delivery_date;
        })->count();
        
        $trends[] = [
            'month' => $monthStart->format('M'),
            'total' => $shipments->count(),
            'delivered' => $delivered,
            'on_time' => $onTime,
            'delayed' => $delivered - $onTime,
            'on_time_rate' => $delivered > 0 ? round(($onTime / $delivered) * 100, 1) : 0,
        ];
    }
    
    return $trends;
}

private function getRegionalPerformance($driverIds, $dateFrom, $dateTo)
{
    $regions = DB::table('shipments')
        ->whereBetween('created_at', [$dateFrom, $dateTo])
        ->select(
            'delivery_state as region',
            DB::raw('COUNT(*) as total_deliveries'),
            DB::raw('SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as delivered'),
            DB::raw('SUM(CASE WHEN status = "delivered" AND actual_delivery_date <= expected_delivery_date THEN 1 ELSE 0 END) as on_time'),
            DB::raw('AVG(CASE WHEN status = "delivered" THEN DATEDIFF(actual_delivery_date, pickup_date) ELSE NULL END) as avg_days')
        )
        ->groupBy('delivery_state')
        ->get();
    
    return $regions->map(function($region) {
        return [
            'region' => $region->region,
            'total' => $region->total_deliveries,
            'delivered' => $region->delivered,
            'on_time_rate' => $region->delivered > 0 ? round(($region->on_time / $region->delivered) * 100, 1) : 0,
            'avg_delay' => round($region->avg_days ?? 0, 1),
        ];
    })->toArray();
}


public function exportPerformance(Request $request)
{
    $format = $request->get('format', 'csv');
    $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
    $dateTo = $request->get('date_to', Carbon::now()->endOfMonth()->format('Y-m-d'));
    $driverId = $request->get('driver_id');
    
    if ($driverId) {
        $driver = User::where('role', 'driver')->findOrFail($driverId);
        $drivers = collect([$driver]);
    } else {
        $drivers = User::where('role', 'driver')->where('status', 'active')->get();
    }
    
    $performanceData = $this->getPerformanceData($drivers, $dateFrom, $dateTo);
    $performanceData['carriers'] = $this->getCarrierPerformance($dateFrom, $dateTo);
    $performanceData['regions'] = $this->getRegionalPerformance($drivers->pluck('id'), $dateFrom, $dateTo);
    
    switch ($format) {
        case 'csv':
            return $this->exportAsCSV($performanceData, $dateFrom, $dateTo);
        case 'excel':
            return $this->exportAsExcel($performanceData, $dateFrom, $dateTo);
        case 'pdf':
            return $this->exportAsPDF($performanceData, $dateFrom, $dateTo);
        default:
            return redirect()->back()->with('error', 'Invalid export format');
    }
}

private function exportAsCSV($performanceData, $dateFrom, $dateTo)
{
    $filename = 'delivery_performance_' . date('Y-m-d_His') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];
    
    $callback = function() use ($performanceData) {
        $file = fopen('php://output', 'w');
        
        // Add header with report info
        fputcsv($file, ['Delivery Performance Report']);
        fputcsv($file, ['Generated on: ' . now()->format('Y-m-d H:i:s')]);
        fputcsv($file, []);
        
        // Overview metrics
        fputcsv($file, ['Overview Metrics']);
        fputcsv($file, ['Metric', 'Value']);
        fputcsv($file, ['On-Time Delivery Rate', $performanceData['overview']['avg_on_time_rate'] . '%']);
        fputcsv($file, ['Average Delivery Time', $performanceData['overview']['avg_delivery_time'] . ' days']);
        fputcsv($file, ['Total Deliveries', $performanceData['overview']['total_deliveries']]);
        fputcsv($file, ['Successful Deliveries', $performanceData['overview']['successful_deliveries']]);
        fputcsv($file, ['Failed Deliveries', $performanceData['overview']['failed_deliveries']]);
        fputcsv($file, []);
        
        // Driver Performance
        fputcsv($file, ['Driver Performance']);
        fputcsv($file, ['Driver Name', 'Employee ID', 'Total Deliveries', 'On-Time Rate (%)', 'Rating', 'Distance (km)', 'Hours Worked', 'Earnings']);
        
        foreach ($performanceData['drivers'] as $driver) {
            fputcsv($file, [
                $driver['name'],
                $driver['employee_id'],
                $driver['total_deliveries'],
                $driver['on_time_rate'],
                $driver['rating'],
                $driver['distance_travelled'],
                $driver['hours_worked'],
                $driver['earnings']
            ]);
        }
        
        fputcsv($file, []);
        
        // Carrier Performance
        if (!empty($performanceData['carriers'])) {
            fputcsv($file, ['Carrier Performance']);
            fputcsv($file, ['Carrier Name', 'Total Deliveries', 'On-Time Rate (%)', 'Avg Delay (days)']);
            
            foreach ($performanceData['carriers'] as $carrier) {
                fputcsv($file, [
                    $carrier['name'],
                    $carrier['total_deliveries'],
                    $carrier['on_time_rate'],
                    $carrier['avg_delay']
                ]);
            }
            
            fputcsv($file, []);
        }
        
        // Regional Performance
        if (!empty($performanceData['regions'])) {
            fputcsv($file, ['Regional Performance']);
            fputcsv($file, ['Region', 'Total Deliveries', 'Delivered', 'On-Time Rate (%)', 'Avg Delay (days)']);
            
            foreach ($performanceData['regions'] as $region) {
                fputcsv($file, [
                    $region['region'],
                    $region['total'],
                    $region['delivered'],
                    $region['on_time_rate'],
                    $region['avg_delay']
                ]);
            }
        }
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}

private function exportAsExcel($performanceData, $dateFrom, $dateTo)
{
    // For now, export as CSV with .xlsx extension
    // You can install PhpSpreadsheet later for true Excel format
    return $this->exportAsCSV($performanceData, $dateFrom, $dateTo);
}

private function exportAsPDF($performanceData, $dateFrom, $dateTo)
{
    // Using DomPDF or similar library
    // For now, return a simple HTML to PDF conversion
    
    $html = view('backend.reports.performance_pdf', compact('performanceData', 'dateFrom', 'dateTo'))->render();
    
    // If you have DomPDF installed:
    // $pdf = PDF::loadHTML($html);
    // return $pdf->download('delivery_performance_' . date('Y-m-d') . '.pdf');
    
    // Simple HTML response for now
    $filename = 'delivery_performance_' . date('Y-m-d_His') . '.pdf';
    
    return response($html)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
}
  
}
