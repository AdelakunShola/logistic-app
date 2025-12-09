
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo and Header -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 gradient-bg rounded-full flex items-center justify-center">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">CargoMax Logistics</h2>
                <p class="mt-2 text-sm text-gray-600">Sign in to your account</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white py-8 px-4 shadow-lg rounded-lg sm:px-10">

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <div class="mt-1">
                            <input id="email"
                                   name="email"
                                   type="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   autocomplete="username"
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                          placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                                          @error('email') border-red-500 @enderror">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1">
                            <input id="password"
                                   name="password"
                                   type="password"
                                   required
                                   autocomplete="current-password"
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                          placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                                          @error('password') border-red-500 @enderror">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me + Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                   name="remember">
                            <span class="ml-2 text-sm text-gray-900">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <x-primary-button
                            class="w-full flex justify-center py-2 px-4 rounded-md shadow-sm text-sm font-medium text-white gradient-bg hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            {{ __('Sign in') }}
                        </x-primary-button>
                    </div>
                </form>

                <!-- Support Section -->
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Need help?</span>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Contact support at 
                            <a href="mailto:support@cargomax.com" class="font-medium text-indigo-600 hover:text-indigo-500">
                                support@cargomax.com
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>

