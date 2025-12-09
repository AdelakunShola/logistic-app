<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CargoMax Logistics - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="min-h-screen flex bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-800 p-12 flex-col justify-between relative overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-white opacity-5 rounded-full -ml-40 -mb-40"></div>
            
            <div class="relative z-10">
                <!-- Logo Area - Replace with your logo -->
                <div class="mb-8">
                    <div class="bg-white p-4 rounded-xl shadow-2xl inline-block">
                        <!-- REPLACE THIS IMG TAG WITH YOUR LOGO -->
                        <img src="{{ asset('logoo.png') }}" alt="CargoMax Logo" class="h-12 w-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <!-- Placeholder if logo doesn't load -->
                        <div class="h-12 w-32 bg-gradient-to-r from-indigo-500 to-purple-500 rounded items-center justify-center hidden">
                            <span class="text-white font-bold text-xl">LOGO</span>
                        </div>
                    </div>
                </div>
                
                <h1 class="text-5xl font-bold text-white mb-6 leading-tight">
                    Welcome to<br/>CargoMax Logistics
                </h1>
                <p class="text-xl text-indigo-100 leading-relaxed">
                    Streamline your logistics operations with our comprehensive freight management platform.
                </p>
            </div>
            
            <div class="relative z-10">
                <div class="grid grid-cols-3 gap-6 text-white">
                    <div class="text-center">
                        <div class="text-3xl font-bold">24/7</div>
                        <div class="text-sm text-indigo-200 mt-1">Support</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold">99.9%</div>
                        <div class="text-sm text-indigo-200 mt-1">Uptime</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold">500+</div>
                        <div class="text-sm text-indigo-200 mt-1">Clients</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="flex-1 flex items-center justify-center p-8 sm:p-12">
            <div class="w-full max-w-md">
                
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="inline-block bg-white p-4 rounded-xl shadow-lg">
                        <!-- REPLACE THIS IMG TAG WITH YOUR LOGO FOR MOBILE -->
                        <img src="{{ asset('logoo.png') }}" alt="CargoMax Logo" class="h-10 w-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <!-- Placeholder -->
                        <div class="h-10 w-28 bg-gradient-to-r from-indigo-500 to-purple-500 rounded items-center justify-center hidden">
                            <span class="text-white font-bold text-lg">LOGO</span>
                        </div>
                    </div>
                    <h2 class="mt-4 text-2xl font-bold text-gray-900">CargoMax Logistics</h2>
                </div>

                <!-- Login Card -->
                <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-10">
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900">Sign In</h3>
                        <p class="mt-2 text-sm text-gray-600">Enter your credentials to access your account</p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Address
                            </label>
                            <input id="email"
                                   name="email"
                                   type="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   autocomplete="username"
                                   placeholder="you@company.com"
                                   class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password
                            </label>
                            <input id="password"
                                   name="password"
                                   type="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 @error('password') border-red-500 @enderror">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me + Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="flex items-center">
                                <input id="remember_me" 
                                       type="checkbox"
                                       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                       name="remember">
                                <span class="ml-2 text-sm text-gray-700">Remember me</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                   class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition duration-200">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full flex justify-center py-3 px-4 rounded-lg shadow-lg text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition duration-200 hover:scale-105">
                                Sign In
                            </button>
                        </div>
                    </form>

                    <!-- Support Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-center text-sm text-gray-600">
                            Need help? Contact 
                            <a href="mailto:support@cargomax.com" 
                               class="font-semibold text-indigo-600 hover:text-indigo-700 transition duration-200">
                                support@cargomax.com
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <p class="mt-8 text-center text-xs text-gray-500">
                    © 2024 CargoMax Logistics. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</body>
</html>