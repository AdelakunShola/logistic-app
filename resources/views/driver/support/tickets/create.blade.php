@extends('driver.driver_dashboard')
@section('driver')

<main class="flex-1 overflow-y-auto p-4 md:p-6">
    <div class="space-y-6 max-w-3xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-3">
            <a href="{{ route('driver.support-tickets.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight">New Support Ticket</h1>
                <p class="text-muted-foreground">Describe your issue and we'll get back to you</p>
            </div>
        </div>

        <!-- Form -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <form action="{{ route('driver.support-tickets.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                        <select name="category" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select a category</option>
                            <option value="delivery_issue" {{ old('category') === 'delivery_issue' ? 'selected' : '' }}>Delivery Issue</option>
                            <option value="payment_issue" {{ old('category') === 'payment_issue' ? 'selected' : '' }}>Payment Issue</option>
                            <option value="tracking" {{ old('category') === 'tracking' ? 'selected' : '' }}>Tracking</option>
                            <option value="complaint" {{ old('category') === 'complaint' ? 'selected' : '' }}>Complaint</option>
                            <option value="inquiry" {{ old('category') === 'inquiry' ? 'selected' : '' }}>Inquiry</option>
                            <option value="technical" {{ old('category') === 'technical' ? 'selected' : '' }}>Technical</option>
                            <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
                        <select name="priority" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Related Shipment</label>
                    <select name="shipment_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">None</option>
                        @foreach($shipments as $shipment)
                            <option value="{{ $shipment->id }}" {{ old('shipment_id') == $shipment->id ? 'selected' : '' }}>
                                {{ $shipment->tracking_number }} - {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject *</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required placeholder="Brief summary of your issue" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <textarea name="description" rows="5" required placeholder="Please describe your issue in detail..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('driver.support-tickets.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Submit Ticket</button>
                </div>
            </form>
        </div>
    </div>
</main>

@endsection
