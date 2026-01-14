@extends('admin.admin_dashboard')
@section('admin')

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Support Ticket</h1>
                    <p class="text-gray-600 mt-1">Edit ticket: {{ $ticket->ticket_number }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.support-tickets.show', $ticket->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border rounded-lg hover:bg-gray-50 transition-colors">
                        View Ticket
                    </a>
                    <a href="{{ route('admin.support-tickets.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border rounded-lg hover:bg-gray-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        Back to Tickets
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.support-tickets.update', $ticket->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="text-red-800">
                        <strong>Validation Errors:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- Ticket Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="mb-4 pb-4 border-b">
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-500">Ticket Number:</span>
                        <span class="font-mono font-medium">{{ $ticket->ticket_number }}</span>
                    </div>
                </div>

                <h3 class="text-lg font-semibold mb-4">Customer Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Customer (User Account)</label>
                        <select name="user_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('user_id') border-red-500 @enderror">
                            <option value="">Select Customer (Optional)</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $ticket->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Related Shipment</label>
                        <select name="shipment_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('shipment_id') border-red-500 @enderror">
                            <option value="">Select Shipment (Optional)</option>
                            @foreach($shipments as $shipment)
                                <option value="{{ $shipment->id }}" {{ old('shipment_id', $ticket->shipment_id) == $shipment->id ? 'selected' : '' }}>
                                    {{ $shipment->tracking_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('shipment_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Customer Name</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', $ticket->customer_name) }}" placeholder="Customer full name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_name') border-red-500 @enderror"/>
                        @error('customer_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Customer Email</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email', $ticket->customer_email) }}" placeholder="customer@example.com" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_email') border-red-500 @enderror"/>
                        @error('customer_email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Customer Phone</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone', $ticket->customer_phone) }}" placeholder="+1234567890" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_phone') border-red-500 @enderror"/>
                        @error('customer_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Ticket Details -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold mb-4">Ticket Details</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Category <span class="text-red-500">*</span></label>
                            <select name="category" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror">
                                <option value="">Select Category</option>
                                <option value="delivery_issue" {{ old('category', $ticket->category) == 'delivery_issue' ? 'selected' : '' }}>Delivery Issue</option>
                                <option value="payment_issue" {{ old('category', $ticket->category) == 'payment_issue' ? 'selected' : '' }}>Payment Issue</option>
                                <option value="tracking" {{ old('category', $ticket->category) == 'tracking' ? 'selected' : '' }}>Tracking</option>
                                <option value="complaint" {{ old('category', $ticket->category) == 'complaint' ? 'selected' : '' }}>Complaint</option>
                                <option value="inquiry" {{ old('category', $ticket->category) == 'inquiry' ? 'selected' : '' }}>Inquiry</option>
                                <option value="technical" {{ old('category', $ticket->category) == 'technical' ? 'selected' : '' }}>Technical</option>
                                <option value="other" {{ old('category', $ticket->category) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Priority <span class="text-red-500">*</span></label>
                            <select name="priority" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('priority') border-red-500 @enderror">
                                <option value="">Select Priority</option>
                                <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority', $ticket->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priority')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                            <option value="open" {{ old('status', $ticket->status) == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ old('status', $ticket->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ old('status', $ticket->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="escalated" {{ old('status', $ticket->status) == 'escalated' ? 'selected' : '' }}>Escalated</option>
                            <option value="closed" {{ old('status', $ticket->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Subject <span class="text-red-500">*</span></label>
                        <input type="text" name="subject" value="{{ old('subject', $ticket->subject) }}" required placeholder="Brief description of the issue" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('subject') border-red-500 @enderror"/>
                        @error('subject')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="6" required placeholder="Detailed description of the issue..." class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none @error('description') border-red-500 @enderror">{{ old('description', $ticket->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Resolution</label>
                        <textarea name="resolution" rows="4" placeholder="Resolution details (if resolved)..." class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none @error('resolution') border-red-500 @enderror">{{ old('resolution', $ticket->resolution) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">This will be visible to the customer if the ticket is resolved</p>
                        @error('resolution')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Assign To</label>
                        <select name="assigned_to" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('assigned_to') border-red-500 @enderror">
                            <option value="">Leave Unassigned</option>
                            @foreach($supportUsers as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to', $ticket->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('admin.support-tickets.show', $ticket->id) }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Update Ticket
                </button>
            </div>
        </form>
    </div>
</div>

@endsection