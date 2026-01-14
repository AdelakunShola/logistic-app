<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerTrackingController;
use App\Http\Controllers\Driver\DriverDeliveryController;
use App\Http\Controllers\Driver\DriverVehicleController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\LiveTrackingController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseInventoryController;
use App\Http\Controllers\WarehouseTransferController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;




// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authenticated routes
// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});



Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('users')->name('users.')->group(function () {
        // List and create routes (no parameters)
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        
        // Export route (specific, no conflict)
        Route::get('/export/csv', [UserController::class, 'export'])->name('export');
        
        // Bulk actions (specific, no conflict)
        Route::post('/bulk-delete', [UserController::class, 'bulkDelete'])->name('bulk.delete');
        
        // Special Actions WITH PARAMETERS - MUST come BEFORE generic {id} routes
        Route::post('/{id}/update-status', [UserController::class, 'updateStatus'])->name('update.status');
        Route::post('/{id}/update-availability', [UserController::class, 'updateAvailability'])->name('update.availability');
        Route::post('/{id}/assign-warehouse', [UserController::class, 'assignWarehouse'])->name('assign.warehouse');
        Route::post('/{id}/reset-password', [UserController::class, 'resetPassword'])->name('reset.password');
        
        // Generic CRUD routes with {id} parameter - MUST come LAST
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });
});




// Notification Routes (inside admin middleware group)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Notification routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        // IMPORTANT: Put specific routes BEFORE the generic ones
        
        // View all notifications page (must come first)
        Route::get('/all', [NotificationController::class, 'all'])->name('all');
        
        // Get notifications for dropdown (AJAX)
        Route::get('/dropdown', [NotificationController::class, 'dropdown'])->name('dropdown');
        
        // Alternative: you can also use this route pattern
        // Route::get('/list', [NotificationController::class, 'dropdown'])->name('list');
        
        // Get unread count for badge
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        
        // Mark all as read
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        
        // Mark single notification as read
        Route::post('/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        
        // Delete notification
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');

        Route::get('/{id}/details', [NotificationController::class, 'details'])
    ->name('details');

    Route::get('/{id}/view', [NotificationController::class, 'view'])->name('view');
    });
});










Route::middleware(['auth'])->prefix('driver')->name('driver.')->group(function () {
    
    // Notification routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        // IMPORTANT: Put specific routes BEFORE the generic ones
        
        // View all notifications page (must come first)
        Route::get('/all', [NotificationController::class, 'alldriver'])->name('all');
        
        // Get notifications for dropdown (AJAX)
        Route::get('/dropdown', [NotificationController::class, 'dropdowndriver'])->name('dropdown');
        
        // Alternative: you can also use this route pattern
        // Route::get('/list', [NotificationController::class, 'dropdown'])->name('list');
        
        // Get unread count for badge
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCountdriver'])->name('unread-count');
        
        // Mark all as read
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsReaddriver'])->name('mark-all-as-read');
        
        // Mark single notification as read
        Route::post('/{id}/mark-as-read', [NotificationController::class, 'markAsReaddriver'])->name('mark-as-read');
        
        // Delete notification
        Route::delete('/{id}', [NotificationController::class, 'destroydriver'])->name('destroy');

        Route::get('/{id}/details', [NotificationController::class, 'detailsdriver'])
    ->name('details');

    Route::get('/{id}/view', [NotificationController::class, 'viewdriver'])->name('view');
    });
});









// Add to web.php (inside admin middleware group)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Activity Logs Routes
    Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/export', [ActivityLogController::class, 'export'])->name('export');
        Route::post('/cleanup', [ActivityLogController::class, 'cleanup'])->name('cleanup');
        Route::get('/{id}', [ActivityLogController::class, 'show'])->name('show');
    });
});



// User Profile Routes (single page for view and edit)
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [UserController::class, 'profile'])->name('index');
    Route::put('/update', [UserController::class, 'updateProfile'])->name('update');
    Route::put('/password', [UserController::class, 'updatePassword'])->name('password.update');
});



// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Logout route (accessible to all authenticated users)

    Route::get('/admin/logout', [AuthController::class, 'AdminDestroy'])->name('admin.logout');
    
    
    // Change password route
    Route::get('/admin/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/admin/settings/update', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/profile', [SettingController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/notifications', [SettingController::class, 'updateNotifications'])->name('settings.notifications');
    Route::post('/password/change', [AuthController::class, 'changePassword'])->name('password.change');
    Route::get('/settings/pricing', [SettingController::class, 'getPricingSettings'])->name('settings.pricing');






    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'AdminDashboard'])->name('dashboard');
        
        
       


        

//// Performance/Report routes
Route::get('/performance', [DriverController::class, 'showPerformance'])->name('performance.show');
Route::get('/drivers/{id}/report', [DriverController::class, 'driverreport'])->name('drivers.report');

// Driver management routes
Route::get('/drivers', [DriverController::class, 'driverindex'])->name('drivers.index');
Route::get('/drivers/export', [DriverController::class, 'driverexport'])->name('drivers.export');
Route::post('/drivers/assign', [DriverController::class, 'driverassign'])->name('drivers.assign');
Route::post('/drivers/send-message', [DriverController::class, 'driversendMessage'])->name('drivers.send-message');
Route::post('/drivers/reassign', [DriverController::class, 'driverreassign'])->name('drivers.reassign');
Route::post('/drivers/view-document', [DriverController::class, 'viewDocument'])->name('drivers.view-document');

// Performance data routes
Route::get('/drivers/{id}/performance', [DriverController::class, 'driverperformance'])->name('drivers.performance');
Route::get('/drivers/{id}/history', [DriverController::class, 'driverhistory'])->name('drivers.history');
Route::get('/drivers/{id}/edit', [DriverController::class, 'driveredit'])->name('drivers.edit');
Route::get('/admin/performance/export', [DriverController::class, 'exportPerformance'])->name('performance.export');

// CRUD routes (must be last)
Route::post('/drivers', [DriverController::class, 'driverstore'])->name('drivers.store');
Route::get('/drivers/{id}', [DriverController::class, 'drivershow'])->name('drivers.show');
Route::put('/drivers/{id}', [DriverController::class, 'driverupdate'])->name('drivers.update');
Route::delete('/drivers/{id}', [DriverController::class, 'driverdestroy'])->name('drivers.destroy');


// Optional: Get assignment details (if you want to show current assignment info in reassign modal)
Route::get('/drivers/assignment/{id}', function($id) {
    $assignment = DB::table('driver_assignments')
        ->join('users', 'driver_assignments.driver_id', '=', 'users.id')
        ->join('vehicles', 'driver_assignments.vehicle_id', '=', 'vehicles.id')
        ->where('driver_assignments.id', $id)
        ->select(
            'driver_assignments.*',
            'users.first_name',
            'users.last_name',
            'vehicles.vehicle_number',
            'vehicles.vehicle_name'
        )
        ->first();
    
    return response()->json($assignment);
})->name('drivers.assignment.details');






/////////VEHICLE MAINTENANCE
        Route::get('/maintenance', [MaintenanceController::class, 'indexMaintenance'])->name('maintenance.index');
        Route::post('/store/maintenance', [MaintenanceController::class, 'storeMaintenance'])->name('maintenance.store');
        Route::get('/maintenance/{id}', [MaintenanceController::class, 'showMaintenance'])->name('maintenance.show');
        Route::put('/maintenance/{id}', [MaintenanceController::class, 'updateMaintenance'])->name('maintenance.update');
        Route::delete('/maintenance/{id}', [MaintenanceController::class, 'destroyMaintenance'])->name('maintenance.destroy');
        
        // Additional routes
        Route::post('/maintenance/{id}/follow-up', [MaintenanceController::class, 'scheduleFollowUpMaintenance'])->name('maintenance.follow-up');
        Route::get('/maintenance/export/{format}', [MaintenanceController::class, 'exportMaintenance'])->name('maintenance.export');








 // Shipment management routes
       Route::get('/shipments', [ShipmentController::class, 'indexshipments'])->name('shipments.index');
        Route::get('/shipments/create', [ShipmentController::class, 'createshipments'])->name('shipments.create');
        Route::post('/shipments/store', [ShipmentController::class, 'storeshipments'])->name('shipments.store');
        Route::get('/shipments/{shipment}/edit', [ShipmentController::class, 'editshipments'])->name('shipments.edit');
        Route::put('/shipments/{shipment}', [ShipmentController::class, 'updateshipments'])->name('shipments.update');
        Route::delete('/shipments/{shipment}', [ShipmentController::class, 'destroyshipments'])->name('shipments.destroy');
        Route::get('/shipments/{shipment}', [ShipmentController::class, 'showshipments'])->name('shipments.show');
        
        // AJAX endpoints for dynamic functionality
        Route::post('/shipments/calculate-pricing', [ShipmentController::class, 'calculatePricingshipments'])->name('shipments.calculate-pricing');
        Route::post('/shipments/save-draft', [ShipmentController::class, 'saveDraftshipments'])->name('shipments.save-draft');
        Route::get('/shipments/get-distance', [ShipmentController::class, 'getDistanceshipments'])->name('shipments.get-distance');
        Route::post('/shipments/validate-address', [ShipmentController::class, 'validateAddressshipments'])->name('shipments.validate-address');

  //  AJAX ENDPOINTS FOR SHIPMENTS INDEX PAGE
    Route::post('/shipments/bulk-delete', [ShipmentController::class, 'bulkDelete'])->name('shipments.bulk-delete');
    Route::post('/shipments/bulk-update', [ShipmentController::class, 'bulkUpdate'])->name('shipments.bulk-update');
    Route::get('/shipments/bulk-print', [ShipmentController::class, 'bulkPrint'])->name('shipments.bulk-print');
    Route::get('/shipments/bulk-export', [ShipmentController::class, 'bulkExport'])->name('shipments.bulk-export');
    Route::post('/shipments/{shipment}/duplicate', [ShipmentController::class, 'duplicateShipment'])->name('shipments.duplicate');
    Route::get('/shipments/export/{format}', [ShipmentController::class, 'exportShipments'])->name('shipments.export');
    Route::get('/shipments/filter', [ShipmentController::class, 'filterShipments'])->name('shipments.filter');
    Route::get('/shipments/{shipment}/quick-view', [ShipmentController::class, 'quickView'])->name('shipments.quick-view');

        Route::post('/shipments/{shipment}/record-delay', [ShipmentController::class, 'recordDelayForShipment'])
    ->name('shipments.record-delay');

// Get delays for a shipment (AJAX)
Route::get('/shipments/{shipment}/delays', [ShipmentController::class, 'getShipmentDelays'])
    ->name('shipments.get-delays');


    ///return shipment
    Route::get('/shipments/{shipment}/create-return', [ShipmentController::class, 'createReturn'])
    ->name('shipments.create-return');
Route::post('/shipments/{shipment}/store-return', [ShipmentController::class, 'storeReturn'])
    ->name('shipments.store-return');
    Route::post('/returns/{return}/reject', [ReturnController::class, 'rejectReturn'])
    ->name('returns.reject');
    Route::post('/returns/{return}/approve', [ReturnController::class, 'approveReturn'])
    ->name('returns.approve');


   Route::get('/track', [ShipmentController::class, 'trackindex'])->name('shipment.track.index');
Route::post('/track/search', [ShipmentController::class, 'tracksearch'])->name('shipment.track.search');
Route::get('/track/{tracking_number}', [ShipmentController::class, 'trackshow'])->name('shipment.track.show');
Route::post('/track/{tracking_number}/report-issue', [ShipmentController::class, 'trackreportIssue'])->name('shipment.track.reportIssue');


Route::get('issues', [IssueController::class, 'issuesindex'])->name('issues.index');
    Route::get('issues/{issue}', [IssueController::class, 'issuesshow'])->name('issues.show');
    Route::post('issues/{issue}/assign', [IssueController::class, 'issuesassign'])->name('issues.assign');
    Route::post('issues/{issue}/update-status', [IssueController::class, 'issuesupdateStatus'])->name('issues.update-status');
    Route::get('issues-export', [IssueController::class, 'issuesexport'])->name('issues.export');

        // Support Tickets Routes
        Route::prefix('support-tickets')->name('support-tickets.')->group(function () {
            Route::get('/', [SupportTicketController::class, 'index'])->name('index');
            Route::get('/create', [SupportTicketController::class, 'create'])->name('create');
            Route::post('/', [SupportTicketController::class, 'store'])->name('store');
            Route::get('/{id}', [SupportTicketController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [SupportTicketController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SupportTicketController::class, 'update'])->name('update');
            Route::delete('/{id}', [SupportTicketController::class, 'destroy'])->name('destroy');
            
            // Special actions
            Route::post('/{id}/assign', [SupportTicketController::class, 'assign'])->name('assign');
            Route::post('/{id}/update-status', [SupportTicketController::class, 'updateStatus'])->name('update-status');
            Route::post('/{id}/messages', [SupportTicketController::class, 'addMessage'])->name('add-message');
            Route::get('/export/csv', [SupportTicketController::class, 'export'])->name('export');
        });



      
        Route::get('/delayed-shipments', [ShipmentController::class, 'index'])->name('delayed-shipments.index');
        Route::get('/delayed-shipments/{delay}', [ShipmentController::class, 'show'])->name('delayed-shipments.show');
        Route::get('/delayed-shipments/{delay}/details', [ShipmentController::class, 'getDetails'])->name('delayed-shipments.details');
        Route::post('/delayed-shipments/{delay}/start-resolution', [ShipmentController::class, 'startResolution'])->name('delayed-shipments.start-resolution');
        Route::post('/delayed-shipments/{delay}/contact-customer', [ShipmentController::class, 'contactCustomer'])->name('delayed-shipments.contact-customer');
        Route::post('/delayed-shipments/{delay}/resolve', [ShipmentController::class, 'resolve'])->name('delayed-shipments.resolve');
        Route::get('/delayed-shipments/export/{format}', [ShipmentController::class, 'export'])->name('delayed-shipments.export');


        // Schedule delivery routes
Route::get('/schedule-delivery', [ShipmentController::class, 'scheduleIndex'])->name('schedule.index');
Route::get('/shipments/{shipment}/details', [ShipmentController::class, 'getShipmentDetails'])->name('shipments.details');
Route::post('/shipments/{shipment}/reschedule', [ShipmentController::class, 'rescheduleDelivery'])->name('shipments.reschedule');
Route::post('/shipments/{shipment}/assign-driver', [ShipmentController::class, 'assignDriverToShipment'])->name('shipments.assign-driver');

        
  
      

Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
    // View single return
    Route::get('/returns/{return}', [ReturnController::class, 'show'])->name('returns.show');
    
    // Update & Delete
    Route::put('/returns/{return}', [ReturnController::class, 'update'])->name('returns.update');
    Route::delete('/returns/{return}', [ReturnController::class, 'destroy'])->name('returns.destroy');
    
    // AJAX Endpoints
    Route::get('/returns/{return}/details', [ReturnController::class, 'getDetails'])->name('returns.details');
    Route::post('/returns/{return}/approve', [ReturnController::class, 'approve'])->name('returns.approve');
    Route::post('/returns/{return}/reject', [ReturnController::class, 'reject'])->name('returns.reject');
    Route::post('/returns/{return}/processing', [ReturnController::class, 'updateToProcessing'])->name('returns.processing');
    Route::post('/returns/{return}/complete', [ReturnController::class, 'complete'])->name('returns.complete');
    
    // Export
    Route::get('/returns/export/{format}', [ReturnController::class, 'export'])->name('returns.export');



        
        // Fleet Vehicles management routes
        Route::get('/vehicles/', [VehicleController::class, 'index'])->name('vehicles.index'); // List all vehicles
        Route::get('/vehicles/create', [VehicleController::class, 'create'])->name('vehicles.create'); // Create form
        Route::post('/store/vehicles', [VehicleController::class, 'store'])->name('vehicles.store'); // Store new
        Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.show'); // View details
        Route::get('/vehicles/{id}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit'); // Edit form
        Route::put('/vehicles/{id}', [VehicleController::class, 'update'])->name('vehicles.update'); // Update
        Route::delete('/vehicles/{id}', [VehicleController::class, 'destroy'])->name('vehicles.destroy'); // Delete

        Route::get('/vehicles/export/{format}', [VehicleController::class, 'export'])->name('vehicles.export'); // Export CSV, Excel, PDF
        Route::post('/vehicles/{id}/assign-driver', [VehicleController::class, 'assignDriver'])->name('vehicles.assign-driver'); // Assign driver
        Route::get('/vehicles/{id}/track', [VehicleController::class, 'track'])->name('vehicles.track'); // View live location
        Route::post('/vehicles/{id}/update-location', [VehicleController::class, 'updateLocation'])->name('vehicles.update-location'); // Update via API
        Route::post('/vehicles/{id}/maintenance', [VehicleController::class, 'scheduleMaintenance'])->name('vehicles.maintenance'); // Maintenance schedule
     
        Route::get('/vehicles/stats/dashboard', [VehicleController::class, 'dashboardStats'])->name('vehicles.stats'); // Dashboard analytics
        Route::post('/vehicles/bulk/delete', [VehicleController::class, 'bulkDelete'])->name('vehicles.bulk.delete'); // Bulk delete
        Route::post('/vehicles/bulk/status', [VehicleController::class, 'bulkUpdateStatus'])->name('vehicles.bulk.status'); 
        


        // Shipment management routes
       
        // Reports routes
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports.index');
        Route::get('/reports/export', [AdminController::class, 'exportReport'])->name('reports.export');








        Route::get('/fleet/status', [FleetController::class, 'indexfleet'])
        ->name('fleet.status');
    
    // Dashboard data endpoint (AJAX)
    Route::get('/fleet/dashboard-data', [FleetController::class, 'getDashboardDatafleet'])
        ->name('fleet.dashboard-data');
    
    // Schedule by date endpoint (AJAX)
    Route::get('/fleet/schedule/{date?}', [FleetController::class, 'getScheduleByDatefleet'])
        ->name('fleet.schedule');
    








        Route::get('/orders', [OrderController::class, 'orderindex'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'ordershow'])->name('orders.show');
        Route::post('/orders/store', [OrderController::class, 'orderstore'])->name('orders.store');
        Route::put('/orders/{order}', [OrderController::class, 'orderupdate'])->name('orders.update');
        Route::delete('/orders/{order}', [OrderController::class, 'orderdestroy'])->name('orders.destroy');
        Route::get('/orders-statistics', [OrderController::class, 'orderstatistics'])->name('orders.statistics');
        Route::post('/orders/{order}/cancel', [OrderController::class, 'ordercancel'])->name('orders.cancel');
        Route::post('/orders/{order}/assign-driver', [OrderController::class, 'orderassignDriver'])->name('orders.assign-driver');
        Route::get('/orders/export/{format}', [OrderController::class, 'orderexport'])->name('orders.export');

    });
    







    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Warehouses
    Route::get('warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
    Route::get('warehouses/create', [WarehouseController::class, 'create'])->name('warehouses.create');
    Route::post('warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');
    Route::get('warehouses/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show');
    Route::get('warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('warehouses.edit');
    Route::put('warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('warehouses.update');
    Route::delete('warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy');
    Route::post('warehouses/bulk-delete', [WarehouseController::class, 'bulkDelete'])->name('warehouses.bulk.delete');
    Route::get('warehouses/{warehouse}/stats', [WarehouseController::class, 'getStats'])->name('warehouses.stats');
    Route::post('warehouses/{warehouse}/update-status', [WarehouseController::class, 'updateStatus'])->name('warehouses.update.status');
    Route::get('warehouses/export/{format}', [WarehouseController::class, 'export'])->name('warehouses.export');
    
    // Warehouse Inventory
    Route::prefix('warehouse-inventory')->name('warehouse-inventory.')->group(function () {
        Route::get('/', [WarehouseInventoryController::class, 'index'])->name('index');
        Route::get('/create', [WarehouseInventoryController::class, 'create'])->name('create');
        Route::post('/', [WarehouseInventoryController::class, 'store'])->name('store');
        Route::get('/{inventory}', [WarehouseInventoryController::class, 'show'])->name('show');
        Route::put('/{inventory}', [WarehouseInventoryController::class, 'update'])->name('update');
        Route::delete('/{inventory}', [WarehouseInventoryController::class, 'destroy'])->name('destroy');
        Route::post('/check-in', [WarehouseInventoryController::class, 'checkIn'])->name('check-in');
        Route::post('/check-out/{inventorsy}', [WarehouseInventoryController::class, 'checkOut'])->name('check-out');
        Route::get('/warehouse/{warehouse}', [WarehouseInventoryController::class, 'byWarehouse'])->name('by-warehouse');
        Route::get('/export/{format}', [WarehouseInventoryController::class, 'export'])->name('export');
    });
    
    // Warehouse Transfers
    Route::prefix('warehouse-transfers')->name('warehouse.transfers.')->group(function () {
        Route::get('/', [WarehouseTransferController::class, 'index'])->name('index');
        Route::get('/create', [WarehouseTransferController::class, 'create'])->name('create');
        Route::post('/', [WarehouseTransferController::class, 'store'])->name('store');
        Route::get('/{id}', [WarehouseTransferController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [WarehouseTransferController::class, 'edit'])->name('edit');
        Route::put('/{id}', [WarehouseTransferController::class, 'update'])->name('update');
        Route::delete('/{id}', [WarehouseTransferController::class, 'destroy'])->name('destroy');
        
        // Special Actions
        Route::post('/{id}/assign-driver', [WarehouseTransferController::class, 'assignDriver'])->name('assign.driver');
        Route::post('/{id}/update-status', [WarehouseTransferController::class, 'updateStatus'])->name('update.status');
        Route::get('/{id}/manifest', [WarehouseTransferController::class, 'generateManifest'])->name('manifest');
        Route::get('/{id}/print', [WarehouseTransferController::class, 'printTransfer'])->name('print');
        Route::post('/bulk-delete', [WarehouseTransferController::class, 'bulkDelete'])->name('bulk.delete');
        
        // Get shipments for warehouse (AJAX)
        Route::get('/warehouse/{warehouseId}/shipments', [WarehouseTransferController::class, 'getWarehouseShipments'])->name('warehouse.shipments');
    });
});















































    // Driver routes
    Route::middleware('role:driver')->prefix('driver')->name('driver.')->group(function () {
        Route::get('/dashboard', [DriverController::class, 'DriverDashboard'])->name('dashboard');
        Route::get('/deliveries', [DriverController::class, 'deliveries'])->name('deliveries.index');
        Route::get('/deliveries/{delivery}', [DriverController::class, 'showDelivery'])->name('deliveries.show');
        Route::post('/deliveries/{delivery}/accept', [DriverController::class, 'acceptDelivery'])->name('deliveries.accept');
        
        Route::post('/location/update', [DriverController::class, 'updateLocation'])->name('location.update');
        Route::get('/earnings', [DriverController::class, 'earnings'])->name('earnings');
        Route::get('/profile', [DriverController::class, 'profile'])->name('profile');










    Route::get('/active-deliveries', [DriverDeliveryController::class, 'activeDeliveries'])->name('active-deliveries');
    Route::get('/completed-deliveries', [DriverDeliveryController::class, 'completedDeliveries'])->name('completed-deliveries');
    Route::get('/delayed-deliveries', [DriverDeliveryController::class, 'delayedDeliveries'])->name('delayed-deliveries');
    
    // AJAX Routes
    Route::get('/deliveries/{shipment}/quick-view', [DriverDeliveryController::class, 'quickView'])->name('deliveries.quick-view');
    Route::post('/deliveries/{shipment}/update-status', [DriverDeliveryController::class, 'updateStatus'])->name('deliveries.update-status');
    Route::post('/deliveries/{shipment}/report-delay', [DriverDeliveryController::class, 'reportDelay'])->name('deliveries.report-delay');
    Route::post('/deliveries/{shipment}/resolve-delay', [DriverDeliveryController::class, 'resolveDelay'])->name('deliveries.resolve-delay');
    Route::post('/deliveries/{shipment}/complete', [DriverDeliveryController::class, 'completeDelivery'])->name('deliveries.complete');
    
    // Export Routes
    Route::get('/deliveries/export/{type}', [DriverDeliveryController::class, 'export'])->name('deliveries.export');






        Route::get('/vehicle/details', [DriverVehicleController::class, 'vehicleDetails'])
        ->name('vehicle.details');
        Route::get('/maintenance', [DriverVehicleController::class, 'maintenanceIndex'])
        ->name('maintenance.index');
        Route::get('/maintenance/{id}', [DriverVehicleController::class, 'maintenanceShow'])
        ->name('maintenance.show');
        Route::post('/maintenance/report', [DriverVehicleController::class, 'reportMaintenance'])
        ->name('maintenance.report');
        Route::put('/maintenance/{id}/update', [DriverVehicleController::class, 'updateMaintenanceReport'])
        ->name('maintenance.update');
        Route::get('/maintenance/history/all', [DriverVehicleController::class, 'maintenanceHistory'])
        ->name('maintenance.history');
        Route::get('/maintenance/export/{format}', [DriverVehicleController::class, 'exportMaintenanceReports'])
        ->name('maintenance.export');
    });
    
    // Customer/User routes
    Route::middleware('role:customer')->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [UserController::class, 'orders'])->name('orders.index');
        Route::get('/orders/create', [UserController::class, 'createOrder'])->name('orders.create');
        Route::post('/orders', [UserController::class, 'storeOrder'])->name('orders.store');
        Route::get('/orders/{order}', [UserController::class, 'showOrder'])->name('orders.show');
        Route::get('/track/{order}', [UserController::class, 'trackOrder'])->name('orders.track');
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        
        // Support Tickets Routes (Customer-facing)
        Route::prefix('support-tickets')->name('support-tickets.')->group(function () {
            Route::get('/', [SupportTicketController::class, 'customerIndex'])->name('index');
            Route::get('/create', [SupportTicketController::class, 'customerCreate'])->name('create');
            Route::post('/', [SupportTicketController::class, 'customerStore'])->name('store');
            Route::get('/{id}', [SupportTicketController::class, 'customerShow'])->name('show');
            Route::post('/{id}/messages', [SupportTicketController::class, 'customerAddMessage'])->name('add-message');
        });
    });
});

// Password reset routes (if you want to implement forgot password)
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// API routes for AJAX requests
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::post('/notifications/mark-read', [AdminController::class, 'markNotificationAsRead'])->name('notifications.markRead');
    Route::get('/notifications', [AdminController::class, 'getNotifications'])->name('notifications.get');
});







// Customer Shipment Tracking Routes
Route::prefix('tracking')->name('tracking.')->group(function () {
    Route::get('/', [CustomerTrackingController::class, 'index'])->name('index');
    Route::post('/search', [CustomerTrackingController::class, 'search'])->name('search');
    Route::get('/{tracking_number}', [CustomerTrackingController::class, 'show'])->name('show');
    Route::post('/{tracking_number}/report-issue', [CustomerTrackingController::class, 'reportIssue'])->name('reportIssue');
});

///////////SHIPMENT TRACKING
Route::get('/track', [ShipmentController::class, 'track'])->name('track');




require __DIR__.'/auth.php';