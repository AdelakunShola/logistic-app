<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function orderindex(Request $request)
    {
        $query = Order::with(['customer', 'assignedDriver']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Priority filter
        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        // Payment status filter
        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        $orders = $query->latest('created_at')->paginate(15);

        // If AJAX request, return JSON
        if ($request->wantsJson()) {
            return response()->json([
                'orders' => $orders,
                'success' => true
            ]);
        }

        // Get statistics for the view
        $stats = $this->getStatistics();

        return view('backend.orders.orders', compact('orders', 'stats'));
    }


    public function ordershow(Order $order)
    {
        $order->load(['customer', 'assignedDriver']);
        
        // Get activity logs for this order
        $activityLogs = ActivityLog::with('user')
            ->where('model_type', 'Order')
            ->where('model_id', $order->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // If AJAX request, return JSON
        if (request()->wantsJson()) {
            return response()->json([
                'order' => $order,
                'activity_logs' => $activityLogs,
                'success' => true
            ]);
        }

        return view('admin.orders.show', compact('order', 'activityLogs'));
    }

    public function orderstore(Request $request)
    {
        DB::beginTransaction();
        try {
            // Get all request data
            $data = $request->all();
            
            // Generate unique order number
            $data['order_number'] = $this->generateOrderNumber();
            
            // Set default status if not provided
            if (!isset($data['status'])) {
                $data['status'] = 'pending';
            }

            // Convert items array to JSON
            if (isset($data['items'])) {
                $data['items'] = json_encode($data['items']);
            }

            // Get customer email if not provided
            if (!isset($data['customer_email']) && isset($data['customer_id'])) {
                $customer = User::find($data['customer_id']);
                $data['customer_email'] = $customer->email ?? null;
            }

            $order = Order::create($data);

            // Log activity
            $this->logActivity(
                'created',
                'Order',
                $order->id,
                "Order {$order->order_number} created",
                null,
                $order->toArray()
            );

            // Send notification to customer
            $this->sendNotification(
                $order->customer_id,
                'Order Created',
                "Your order {$order->order_number} has been created successfully.",
                'success',
                'system',
                ['order_id' => $order->id],
                route('admin.orders.show', $order->id),
                null,
                $order->id
            );

            // Notify assigned driver if exists
            if ($order->assigned_driver_id) {
                $this->sendNotification(
                    $order->assigned_driver_id,
                    'New Order Assigned',
                    "You have been assigned to order {$order->order_number}.",
                    'info',
                    'system',
                    ['order_id' => $order->id],
                    route('admin.orders.show', $order->id),
                    null,
                    $order->id
                );
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order created successfully!',
                    'order' => $order
                ], 201);
            }

            return redirect()->route('admin.orders.index')
                           ->with('success', 'Order created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating order: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error creating order: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function orderupdate(Request $request, Order $order)
    {
        DB::beginTransaction();
        try {
            $oldData = $order->toArray();
            $oldStatus = $order->status;
            $oldDriverId = $order->assigned_driver_id;
            
            // Get all request data
            $data = $request->all();
            
            // Convert items to JSON if provided
            if (isset($data['items'])) {
                $data['items'] = json_encode($data['items']);
            }

            $order->update($data);

            // Log activity
            $this->logActivity(
                'updated',
                'Order',
                $order->id,
                "Order {$order->order_number} updated",
                $oldData,
                $order->toArray()
            );

            // Send notification if status changed
            if (isset($data['status']) && $oldStatus !== $order->status) {
                $this->sendNotification(
                    $order->customer_id,
                    'Order Status Updated',
                    "Your order {$order->order_number} status has been updated to " . ucfirst(str_replace('_', ' ', $order->status)) . ".",
                    'info',
                    'system',
                    ['order_id' => $order->id, 'old_status' => $oldStatus, 'new_status' => $order->status],
                    route('admin.orders.show', $order->id),
                    null,
                    $order->id
                );
            }

            // Notify new driver if driver changed
            if (isset($data['assigned_driver_id']) && $oldDriverId !== $order->assigned_driver_id && $order->assigned_driver_id) {
                $this->sendNotification(
                    $order->assigned_driver_id,
                    'Order Assigned',
                    "You have been assigned to order {$order->order_number}.",
                    'info',
                    'system',
                    ['order_id' => $order->id],
                    route('admin.orders.show', $order->id),
                    null,
                    $order->id
                );
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order updated successfully!',
                    'order' => $order
                ]);
            }

            return redirect()->route('admin.orders.index')
                           ->with('success', 'Order updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating order: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error updating order: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function orderdestroy(Order $order)
    {
        DB::beginTransaction();
        try {
            $orderNumber = $order->order_number;
            $customerId = $order->customer_id;
            
            // Log activity before deletion
            $this->logActivity(
                'deleted',
                'Order',
                $order->id,
                "Order {$orderNumber} deleted",
                $order->toArray(),
                null
            );

            $order->delete();

            // Notify customer
            $this->sendNotification(
                $customerId,
                'Order Deleted',
                "Order {$orderNumber} has been deleted.",
                'warning',
                'system'
            );

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order deleted successfully!'
                ]);
            }

            return redirect()->route('admin.orders.index')
                           ->with('success', 'Order deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting order: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error deleting order: ' . $e->getMessage());
        }
    }

    public function ordercancel(Request $request, Order $order)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $order->status;
            
            $order->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->cancellation_reason,
            ]);

            // Log activity
            $this->logActivity(
                'cancelled',
                'Order',
                $order->id,
                "Order {$order->order_number} cancelled",
                ['status' => $oldStatus],
                ['status' => 'cancelled', 'cancellation_reason' => $request->cancellation_reason]
            );

            // Notify customer
            $this->sendNotification(
                $order->customer_id,
                'Order Cancelled',
                "Your order {$order->order_number} has been cancelled. Reason: {$request->cancellation_reason}",
                'warning',
                'system',
                ['order_id' => $order->id, 'reason' => $request->cancellation_reason],
                route('admin.orders.show', $order->id),
                null,
                $order->id
            );

            // Notify driver if assigned
            if ($order->assigned_driver_id) {
                $this->sendNotification(
                    $order->assigned_driver_id,
                    'Order Cancelled',
                    "Order {$order->order_number} has been cancelled.",
                    'warning',
                    'system',
                    ['order_id' => $order->id],
                    null,
                    null,
                    $order->id
                );
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order cancelled successfully!',
                    'order' => $order
                ]);
            }

            return back()->with('success', 'Order cancelled successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error cancelling order: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error cancelling order: ' . $e->getMessage());
        }
    }

    /**
     * Assign driver to order
     */
    public function orderassignDriver(Request $request, Order $order)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $oldDriverId = $order->assigned_driver_id;
            
            $order->update([
                'assigned_driver_id' => $request->driver_id,
                'status' => 'assigned',
            ]);

            // Log activity
            $this->logActivity(
                'assigned',
                'Order',
                $order->id,
                "Driver assigned to order {$order->order_number}",
                ['assigned_driver_id' => $oldDriverId],
                ['assigned_driver_id' => $request->driver_id]
            );

            // Notify new driver
            $this->sendNotification(
                $request->driver_id,
                'New Order Assigned',
                "You have been assigned to order {$order->order_number}.",
                'info',
                'system',
                ['order_id' => $order->id],
                route('admin.orders.show', $order->id),
                null,
                $order->id
            );

            // Notify customer
            $this->sendNotification(
                $order->customer_id,
                'Driver Assigned',
                "A driver has been assigned to your order {$order->order_number}.",
                'info',
                'system',
                ['order_id' => $order->id],
                route('admin.orders.show', $order->id),
                null,
                $order->id
            );

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Driver assigned successfully!',
                    'order' => $order->load('assignedDriver')
                ]);
            }

            return back()->with('success', 'Driver assigned successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error assigning driver: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error assigning driver: ' . $e->getMessage());
        }
    }

    /**
     * Filter orders by status
     */
    public function filterByStatus($status)
    {
        $query = Order::with(['customer', 'assignedDriver']);
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $orders = $query->latest('created_at')->paginate(15);
        
        return response()->json([
            'orders' => $orders,
            'count' => $orders->total(),
            'success' => true
        ]);
    }

    /**
     * Export orders
     */
    public function orderexport(Request $request, $format)
    {
        try {
            $query = Order::with(['customer', 'assignedDriver']);

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                      ->orWhere('customer_email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            if ($request->filled('priority') && $request->priority !== 'all') {
                $query->where('priority', $request->priority);
            }

            $orders = $query->get();

            switch ($format) {
                case 'csv':
                    return $this->exportToCsv($orders);
                    
                case 'excel':
                    return $this->exportToExcel($orders);
                    
                case 'pdf':
                    return $this->exportToPdf($orders);
                    
                default:
                    return back()->with('error', 'Invalid export format');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting orders: ' . $e->getMessage());
        }
    }

    /**
     * Export to CSV
     */
    protected function exportToCsv($orders)
    {
        $filename = 'orders_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Order Number', 'Customer', 'Email', 'Phone', 'Status', 
                'Priority', 'Payment Status', 'Total Amount', 'Order Date', 
                'Scheduled Date', 'Delivery Address'
            ]);

            // Data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->customer->name ?? 'N/A',
                    $order->customer_email,
                    $order->customer_phone,
                    $order->status,
                    $order->priority,
                    $order->payment_status,
                    $order->total_amount,
                    $order->order_date,
                    $order->scheduled_date,
                    $order->delivery_address
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to Excel (simple CSV with Excel mime type)
     */
    protected function exportToExcel($orders)
    {
        return $this->exportToCsv($orders);
    }

    /**
     * Export to PDF
     */
    protected function exportToPdf($orders)
    {
        return back()->with('error', 'PDF export requires DomPDF package. Please install: composer require barryvdh/laravel-dompdf');
    }

    /**
     * Generate unique order number
     */
    protected function generateOrderNumber()
    {
        $year = date('Y');
        $lastOrder = Order::whereYear('created_at', $year)
                         ->orderBy('id', 'desc')
                         ->first();
        
        $number = $lastOrder ? intval(substr($lastOrder->order_number, -3)) + 1 : 1;
        
        return 'ORD-' . $year . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }







    public function orderstatistics()
{
    $stats = [
        'all' => Order::count(),
        'pending' => Order::where('status', 'pending')->count(),
        'processing' => Order::where('status', 'processing')->count(),
        'confirmed' => Order::where('status', 'confirmed')->count(),
        'assigned' => Order::where('status', 'assigned')->count(),
        'in_transit' => Order::where('status', 'in_transit')->count(),
        'in_progress' => Order::where('status', 'in_progress')->count(),
        'delivered' => Order::where('status', 'delivered')->count(),
        'completed' => Order::where('status', 'completed')->count(),
        'delayed' => Order::where('status', 'delayed')->count(),
        'cancelled' => Order::where('status', 'cancelled')->count(),
        'revenue' => (float) Order::where('payment_status', 'paid')->sum('total_amount'),
    ];

    return response()->json($stats);
}

protected function getStatistics()
{
    return [
        'all' => Order::count(),
        'pending' => Order::where('status', 'pending')->count(),
        'processing' => Order::where('status', 'processing')->count(),
        'confirmed' => Order::where('status', 'confirmed')->count(),
        'assigned' => Order::where('status', 'assigned')->count(),
        'in_transit' => Order::where('status', 'in_transit')->count(),
        'in_progress' => Order::where('status', 'in_progress')->count(),
        'delivered' => Order::where('status', 'delivered')->count(),
        'completed' => Order::where('status', 'completed')->count(),
        'delayed' => Order::where('status', 'delayed')->count(),
        'cancelled' => Order::where('status', 'cancelled')->count(),
        'revenue' => (float) Order::where('payment_status', 'paid')->sum('total_amount'),
    ];
}

    protected function logActivity(
        string $action,
        ?string $modelType = null,
        ?int $modelId = null,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ) {
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model_type' => $modelType,
                'model_id' => $modelId,
                'description' => $description,
                'old_values' => $oldValues ? json_encode($oldValues) : null,
                'new_values' => $newValues ? json_encode($newValues) : null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the main operation
            Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }

    /**
     * Send notification (Internal method - no external service needed)
     */
    protected function sendNotification(
        ?int $userId,
        string $title,
        string $message,
        string $type = 'info',
        string $channel = 'system',
        ?array $data = null,
        ?string $actionUrl = null,
        ?int $shipmentId = null,
        ?int $orderId = null
    ) {
        try {
            Notification::create([
                'user_id' => $userId,
                'shipment_id' => $shipmentId,
                'order_id' => $orderId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'channel' => $channel,
                'data' => $data ? json_encode($data) : null,
                'action_url' => $actionUrl,
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the main operation
            Log::error('Failed to send notification: ' . $e->getMessage());
        }
    }
}