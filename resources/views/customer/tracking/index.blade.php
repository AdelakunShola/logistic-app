<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Shipment - FastFreight Logistics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), 
                        url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=2070');
            background-size: cover;
            background-position: center;
        }
        
        .top-bar {
            background: #1e293b;
            color: white;
        }
        
        .nav-bar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        @media print {
            .no-print { display: none !important; }
        }
        
        .tracking-input:focus {
            outline: none;
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
        
        .btn-red {
            background: #ef4444;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-red:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
        }
        
        .footer-dark {
            background: #1e1b4b;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-50">
    
    <!-- Top Bar -->
    <div class="top-bar py-2 no-print">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center justify-between text-sm">
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                        <span>Phone: +1 (800) 555-1234</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                        </svg>
                        <span>Email: info@fastfreight.com</span>
                    </div>
                </div>
                <div>
                    <a href="#" class="hover:text-red-400 transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Login & Register
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="nav-bar sticky top-0 z-40 no-print">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 11v8a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-8"></path>
                            <rect x="3" y="3" width="18" height="8" rx="1"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <div>
                            <h1 class="text-xl font-black text-gray-900 uppercase tracking-tight">FastFreight</h1>
                            <p class="text-xs text-gray-500">Logistics</p>
                        </div>
                    </div>
                </div>
                
                <div class="hidden lg:flex items-center gap-8">
                    <a href="#" class="text-gray-700 hover:text-red-500 font-medium transition-colors">Calculator</a>
                    <a href="#" class="text-gray-700 hover:text-red-500 font-medium transition-colors">Booking</a>
                    <a href="#" class="text-red-500 font-medium">Tracking</a>
                </div>
                
                <a href="#" class="btn-red px-6 py-3 rounded-lg font-bold text-sm uppercase tracking-wide">Request A Quote</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section py-32 md:py-40 lg:py-48">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4">Tracking</h1>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12 md:py-20">
        
        <!-- Track Your Order Section -->
        <div class="max-w-4xl mx-auto mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-8 uppercase">Track Your Package</h2>
            
            <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
                <form action="{{ route('tracking.search') }}" method="POST">
                    @csrf
                    
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="mb-6">
                        <input 
                            type="text" 
                            name="tracking_number"
                            placeholder="Please enter your tracking number!!!"
                            class="w-full px-6 py-4 border-2 border-gray-300 rounded-lg tracking-input text-lg"
                            value="{{ $shipment->tracking_number ?? old('tracking_number') }}"
                            required
                        />
                        @error('tracking_number')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn-red px-8 py-4 rounded-lg font-bold text-sm uppercase tracking-wide w-full md:w-auto">
                        Track Your Order
                    </button>
                </form>

                @if(!empty($recentTracking) && count($recentTracking) > 0)
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <h4 class="text-sm font-bold text-gray-700 mb-3 uppercase">Recent Searches</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($recentTracking as $trackingNum)
                                <a href="{{ route('tracking.show', $trackingNum) }}" class="px-4 py-2 bg-gray-100 hover:bg-red-500 hover:text-white text-gray-700 rounded-lg text-sm font-medium transition-all {{ isset($shipment) && $trackingNum == $shipment->tracking_number ? 'bg-red-500 text-white' : '' }}">
                                    {{ $trackingNum }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Shipment Details (if tracking found) -->
        @if($shipment)
        <div class="mb-16">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 md:p-8 text-white">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl md:text-3xl font-black mb-2">{{ $shipment->tracking_number }}</h2>
                            <div class="flex items-center gap-3 text-sm">
                                <span>{{ $shipment->shipment_type == 'standard' ? 'LTL' : strtoupper($shipment->shipment_type) }}</span>
                                <span>•</span>
                                <span>{{ $shipment->number_of_items }} {{ Str::plural('package', $shipment->number_of_items) }}</span>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-3 no-print">
                            <a href="{{ route('tracking.index') }}" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-white font-medium transition-all">New Search</a>
                            <button onclick="window.print()" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-white font-medium transition-all">Print</button>
                            <button onclick="copyTrackingNumber()" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-white font-medium transition-all">Copy</button>
                        </div>
                    </div>
                </div>

                <!-- Status Grid -->
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gray-50 p-5 rounded-lg border-l-4 border-blue-500">
                            <div class="text-sm font-semibold text-gray-600 mb-2">STATUS</div>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-bold @if($shipment->status == 'delivered') bg-green-100 text-green-800 @elseif($shipment->status == 'out_for_delivery') bg-blue-100 text-blue-800 @elseif($shipment->status == 'in_transit') bg-yellow-100 text-yellow-800 @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                            </span>
                        </div>
                        
                        <div class="bg-gray-50 p-5 rounded-lg border-l-4 border-green-500">
                            <div class="text-sm font-semibold text-gray-600 mb-2">ESTIMATED DELIVERY</div>
                            <div class="text-lg font-bold text-gray-900">{{ $shipment->expected_delivery_date ? $shipment->expected_delivery_date->format('D, M d') : $shipment->preferred_delivery_date->format('D, M d') }}</div>
                        </div>
                        
                        <!--<div class="bg-gray-50 p-5 rounded-lg border-l-4 border-red-500">
                            <div class="text-sm font-semibold text-gray-600 mb-2">CURRENT LOCATION</div>
                            <div class="text-base font-bold text-gray-900">{{ $currentLocation }}</div>
                        </div>-->
                        
                        <div class="bg-gray-50 p-5 rounded-lg border-l-4 border-orange-500">
                            <div class="text-sm font-semibold text-gray-600 mb-2">LAST UPDATED</div>
                            <div class="text-base font-bold text-gray-900">{{ $shipment->trackingHistory->first()->created_at->format('M d, h:i A') }}</div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    @php
                        $stages = ['pending' => 'Pending', 'picked_up' => 'Picked Up', 'in_transit' => 'In Transit', 'out_for_delivery' => 'Out for Delivery', 'delivered' => 'Delivered'];
                        $currentStageIndex = array_search($shipment->status, array_keys($stages));
                    @endphp

                    <div class="mb-8 bg-gray-50 p-6 rounded-lg">
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-base font-bold text-gray-900 uppercase">Shipment Progress</span>
                            <span class="text-sm font-bold text-blue-600">{{ $progress }}% Complete</span>
                        </div>
                        
                        <div class="relative">
                            <div class="absolute top-8 left-0 right-0 h-2 bg-gray-300 rounded-full">
                                <div class="bg-blue-600 h-full rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                            </div>
                            
                            <div class="relative flex justify-between">
                                @foreach($stages as $stageKey => $stageLabel)
                                    @php
                                        $stageIndex = array_search($stageKey, array_keys($stages));
                                        $isCompleted = $stageIndex < $currentStageIndex;
                                        $isCurrent = $stageKey === $shipment->status;
                                        $isPending = $stageIndex > $currentStageIndex;
                                    @endphp
                                    
                                    <div class="flex flex-col items-center" style="flex: 1;">
                                        <div class="relative z-10 flex-shrink-0 mb-3">
                                            @if($isCompleted)
                                                <div class="w-16 h-16 rounded-full bg-blue-600 flex items-center justify-center shadow-lg">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                </div>
                                            @elseif($isCurrent)
                                                <div class="w-16 h-16 rounded-full bg-blue-600 flex items-center justify-center shadow-lg animate-pulse">
                                                    <div class="w-8 h-8 rounded-full bg-white"></div>
                                                </div>
                                            @else
                                                <div class="w-16 h-16 rounded-full border-4 border-gray-300 bg-white flex items-center justify-center">
                                                    <span class="text-gray-400 font-bold text-xl">{{ $stageIndex + 1 }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="text-center">
                                            <div class="font-bold text-sm {{ $isPending ? 'text-gray-400' : 'text-gray-900' }} uppercase">{{ $stageLabel }}</div>
                                            @if($isCurrent)
                                                <div class="inline-block mt-2 px-3 py-1 bg-blue-600 text-white text-xs rounded-full font-bold uppercase">Active</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Origin/Destination -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 p-6 bg-gray-50 rounded-lg">
                        <div>
                            <div class="text-xs font-bold text-gray-500 mb-1 uppercase">FROM / ORIGIN</div>
                            <div class="font-black text-xl text-gray-900">{{ $shipment->pickup_city }}, {{ $shipment->pickup_state }}</div>
                            <div class="text-sm text-gray-600 mt-1">Shipped {{ $shipment->pickup_date ? $shipment->pickup_date->format('M d, Y') : 'Pending' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-gray-500 mb-1 uppercase">TO / DESTINATION</div>
                            <div class="font-black text-xl text-gray-900">{{ $shipment->delivery_address }}, {{ $shipment->delivery_address_line2 }}, {{ $shipment->delivery_city }}, {{ $shipment->delivery_state }}, {{ $shipment->delivery_country }}</div>
                            <div class="text-sm text-gray-600 mt-1">Expected {{ $shipment->expected_delivery_date ? $shipment->expected_delivery_date->format('M d, Y') : 'TBD' }}</div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-black text-gray-900 mb-6 uppercase">Shipment Timeline</h3>
                        
                        <div class="space-y-4">
                            @foreach($shipment->trackingHistory as $index => $tracking)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    @if($index === 0)
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white shadow-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-bold text-sm">{{ $index + 1 }}</div>
                                    @endif
                                    @if($index < count($shipment->trackingHistory) - 1)
                                        <div class="w-0.5 h-full bg-gray-300 mt-2"></div>
                                    @endif
                                </div>
                                <div class="flex-1 pb-6">
                                    <div class="bg-white border-2 border-gray-200 rounded-lg p-4 hover:border-blue-500 transition-colors">
                                        <div class="font-bold text-gray-900 mb-1">{{ $tracking->description ?? ucfirst(str_replace('_', ' ', $tracking->status)) }}</div>
                                        <!--<div class="text-sm text-gray-600">{{ $tracking->location ?? 'N/A' }}</div>-->
                                        <div class="text-xs text-gray-500 mt-2">{{ $tracking->created_at->format('l, F d, Y - h:i A') }}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Contact Forms Section -->
        <div class="max-w-2xl mx-auto mb-16">
            <div class="grid grid-cols-1 gap-8">
                <!-- Contact Our Team -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h3 class="text-2xl md:text-3xl font-black text-gray-900 mb-6 uppercase text-center">Contact Our Team</h3>
                    
                    <form action="{{ route('tracking.reportIssue', $shipment->tracking_number ?? 'default') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Your Name</label>
                            <input type="text" name="name" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-500 focus:outline-none" />
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Your Email</label>
                            <input type="email" name="email" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-500 focus:outline-none" />
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Subject</label>
                            <input type="text" name="issue_type" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-500 focus:outline-none" />
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Your Message (Optional)</label>
                            <textarea name="description" rows="4" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-500 focus:outline-none resize-none"></textarea>
                        </div>
                        
                        <button type="submit" class="btn-red px-8 py-4 rounded-lg font-bold text-sm uppercase tracking-wide w-full">Submit</button>
                    </form>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="footer-dark py-12">
        <div class="container mx-auto px-4 max-w-6xl">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8 text-center md:text-left">
                <!-- Logo & Description -->
                <div class="flex flex-col items-center md:items-start">
                    <div class="flex items-center gap-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 11v8a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-8"></path>
                            <rect x="3" y="3" width="18" height="8" rx="1"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <h3 class="text-xl font-black uppercase">FastFreight</h3>
                    </div>
                    <p class="text-gray-400 text-sm">Lorem ipsum is simply dummy text of printing and typesetting industry. Lo Ipsum has been the industry' dummy text.</p>
                </div>

                <!-- About -->
                <div class="flex flex-col items-center md:items-start">
                    <h4 class="text-lg font-bold mb-4 uppercase">About</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-red-500 transition-colors">About us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-500 transition-colors">Contact us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-500 transition-colors">FAQs</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-500 transition-colors">Terms</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-500 transition-colors">Secure Shopping</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-500 transition-colors">Our Network</a></li>
                    </ul>
                </div>

                <!-- Links -->
                <div class="flex flex-col items-center md:items-start">
                    <h4 class="text-lg font-bold mb-4 uppercase">Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-red-500 transition-colors">Services</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-500 transition-colors">Tracking</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-500 transition-colors">Booking</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-500 transition-colors">Calculator</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-500 transition-colors">Support</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-500 transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 pt-8 text-center">
                <p class="text-gray-400 text-sm">© 2024 FastFreight Logistics. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>