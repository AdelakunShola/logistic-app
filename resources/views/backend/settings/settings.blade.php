@extends('admin.admin_dashboard')
@section('admin')

<div class="min-h-screen bg-background">
    <div class="flex flex-1 flex-col ml-0 lg:ml-4">
        <main class="flex-1 overflow-y-auto p-4 md:p-6">
            <div>
                <div class="space-y-6">
                    <!-- Header Section -->
                    <div class="flex flex-wrap gap-3 items-center justify-between">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold tracking-tight">Settings</h1>
                            <p class="text-muted-foreground">Manage your account settings and preferences</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4 mr-2" aria-hidden="true">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5v14"></path>
                                </svg>
                                Add Vehicle
                            </button>
                            <button class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 rounded-md px-3" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4 mr-2" aria-hidden="true">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5v14"></path>
                                </svg>
                                New Shipment
                            </button>
                        </div>
                    </div>

                    <!-- Tabs Container -->
                    <div dir="ltr" data-orientation="horizontal" class="space-y-6">
                        <!-- Tab List -->
                        <div role="tablist" aria-orientation="horizontal" class="items-center rounded-md bg-muted p-1 text-muted-foreground flex flex-wrap gap-2 justify-start h-max" tabindex="0" data-orientation="horizontal">
                            <button type="button" role="tab" aria-selected="true" aria-controls="general-content" data-state="active" id="general-trigger" class="justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm flex items-center gap-2" tabindex="0" data-orientation="horizontal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings h-4 w-4" aria-hidden="true">
                                    <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                General
                            </button>
                            <button type="button" role="tab" aria-selected="false" aria-controls="profile-content" data-state="inactive" id="profile-trigger" class="justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm flex items-center gap-2" tabindex="-1" data-orientation="horizontal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user h-4 w-4" aria-hidden="true">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                Profile
                            </button>
                            <button type="button" role="tab" aria-selected="false" aria-controls="notifications-content" data-state="inactive" id="notifications-trigger" class="justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm flex items-center gap-2" tabindex="-1" data-orientation="horizontal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell h-4 w-4" aria-hidden="true">
                                    <path d="M10.268 21a2 2 0 0 0 3.464 0"></path>
                                    <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path>
                                </svg>
                                Notifications
                            </button>
                            <button type="button" role="tab" aria-selected="false" aria-controls="security-content" data-state="inactive" id="security-trigger" class="justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm flex items-center gap-2" tabindex="-1" data-orientation="horizontal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield h-4 w-4" aria-hidden="true">
                                    <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                                </svg>
                                Security
                            </button>
                            <button type="button" role="tab" aria-selected="false" aria-controls="system-content" data-state="inactive" id="system-trigger" class="justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm flex items-center gap-2" tabindex="-1" data-orientation="horizontal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-monitor h-4 w-4" aria-hidden="true">
                                    <rect width="20" height="14" x="2" y="3" rx="2"></rect>
                                    <line x1="8" x2="16" y1="21" y2="21"></line>
                                    <line x1="12" x2="12" y1="17" y2="21"></line>
                                </svg>
                                System
                            </button>
                            
                        <button type="button" role="tab" aria-selected="false" aria-controls="pricing-content" data-state="inactive" id="pricing-trigger" class="justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm flex items-center gap-2" tabindex="-1" data-orientation="horizontal">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dollar-sign h-4 w-4" aria-hidden="true">
        <line x1="12" x2="12" y1="2" y2="22"></line>
        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
    </svg>
    Pricing & Billing
</button>
                            <button type="button" role="tab" aria-selected="false" aria-controls="integrations-content" data-state="inactive" id="integrations-trigger" class="justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm flex items-center gap-2" tabindex="-1" data-orientation="horizontal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-database h-4 w-4" aria-hidden="true">
                                    <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                    <path d="M3 5V19A9 3 0 0 0 21 19V5"></path>
                                    <path d="M3 12A9 3 0 0 0 21 12"></path>
                                </svg>
                                Integrations
                            </button>
                        </div>

                        <!-- General Tab Content -->
                        <div data-state="active" data-orientation="horizontal" role="tabpanel" aria-labelledby="general-trigger" id="general-content" tabindex="0" class="mt-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 space-y-6">
                            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                                <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                                    <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building h-5 w-5" aria-hidden="true">
                                            <rect width="16" height="20" x="4" y="2" rx="2" ry="2"></rect>
                                            <path d="M9 22v-4h6v4"></path>
                                            <path d="M8 6h.01"></path>
                                            <path d="M16 6h.01"></path>
                                            <path d="M12 6h.01"></path>
                                            <path d="M12 10h.01"></path>
                                            <path d="M12 14h.01"></path>
                                            <path d="M16 10h.01"></path>
                                            <path d="M16 14h.01"></path>
                                            <path d="M8 10h.01"></path>
                                            <path d="M8 14h.01"></path>
                                        </svg>
                                        Company Information
                                    </h3>
                                    <div class="text-sm text-muted-foreground">Update your company details and contact information</div>
                                </div>
                                <form id="companyForm" class="p-4 md:p-6 pt-0 space-y-4">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="company_name">Company Name</label>
                                            <input class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="company_name" name="company_name" value="{{ $companySettings['company_name'] }}">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="company_tax_id">Tax ID</label>
                                            <input class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="company_tax_id" name="company_tax_id" value="{{ $companySettings['company_tax_id'] }}">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="company_email">Email</label>
                                            <input class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="company_email" name="company_email" type="email" value="{{ $companySettings['company_email'] }}">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="company_phone">Phone</label>
                                            <input class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="company_phone" name="company_phone" value="{{ $companySettings['company_phone'] }}">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="company_website">Website</label>
                                            <input class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="company_website" name="company_website" value="{{ $companySettings['company_website'] }}">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="company_tax_percentage">Tax Percentage%</label>
                                            <input class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="company_tax_percentage" name="company_tax_percentage" value="{{ $companySettings['company_tax_percentage'] }}"%>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="company_address">Address</label>
                                        <textarea class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="company_address" name="company_address" rows="3">{{ $companySettings['company_address'] }}</textarea>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Profile Tab Content -->
                        <div data-state="inactive" data-orientation="horizontal" role="tabpanel" aria-labelledby="profile-trigger" id="profile-content" tabindex="0" class="mt-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 space-y-6" hidden>
                            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                                <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                                    <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user h-5 w-5" aria-hidden="true">
                                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        Personal Information
                                    </h3>
                                    <div class="text-sm text-muted-foreground">Manage your personal profile and account details</div>
                                </div>
                                <form id="profileForm" enctype="multipart/form-data" class="p-4 md:p-6 pt-0 space-y-6">
                                    @csrf
                                    <!-- Profile Photo Section -->
                                    <div class="flex items-start gap-4">
                                        <div class="relative">
                                            <div id="profilePhotoPreview" class="h-20 w-20 rounded-full bg-muted flex items-center justify-center overflow-hidden">
                                                @if($user->profile_photo)
                                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" class="h-full w-full object-cover">
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4"></circle>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png,image/gif" class="hidden">
                                            <button type="button" id="uploadPhotoBtn" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                                                    <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"></path>
                                                    <circle cx="12" cy="13" r="3"></circle>
                                                </svg>
                                                Upload Photo
                                            </button>
                                            <div class="space-y-1 text-sm text-muted-foreground">
                                                <p class="flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                    JPG, PNG or GIF formats supported
                                                </p>
                                                <p class="flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                    Maximum file size: 2MB
                                                </p>
                                                <p class="flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                    Recommended: Square aspect ratio (1:1)
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Personal Details Form -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="first_name">First Name</label>
                                            <input class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="first_name" name="first_name" value="{{ $user->first_name }}">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="last_name">Last Name</label>
                                            <input class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="last_name" name="last_name" value="{{ $user->last_name }}">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="email">Email</label>
                                            <input class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="email" name="email" type="email" value="{{ $user->email }}">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="phone">Phone</label>
                                            <input class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="phone" name="phone" value="{{ $user->phone }}">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="designation">Role</label>
                                            <input class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="designation" name="designation" value="{{ $user->designation }}">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="department">Department</label>
                                            <input class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="department" name="department" value="{{ $user->department }}">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Notifications Tab Content -->
                        <div data-state="inactive" data-orientation="horizontal" role="tabpanel" aria-labelledby="notifications-trigger" id="notifications-content" tabindex="0" class="mt-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 space-y-6" hidden>
                            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                                <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                                    <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell h-5 w-5" aria-hidden="true">
                                            <path d="M10.268 21a2 2 0 0 0 3.464 0"></path>
                                            <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path>
                                        </svg>
                                        Notification Preferences
                                    </h3>
                                    <div class="text-sm text-muted-foreground">Configure how you want to receive notifications</div>
                                </div>
                                <form id="notificationsForm" class="p-4 md:p-6 pt-0 space-y-6">
                                    @csrf
                                    <!-- Communication Channels -->
                                    <div>
                                        <h4 class="font-semibold mb-4">Communication Channels</h4>
                                        <div class="space-y-4">
                                            <!-- Email Notifications -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-start gap-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 mt-0.5">
                                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                                    </svg>
                                                    <div>
                                                        <div class="font-medium">Email Notifications</div>
                                                        <div class="text-sm text-muted-foreground">Receive notifications via email</div>
                                                    </div>
                                                </div>
                                                <button type="button" role="switch" aria-checked="{{ $user->email_notifications ? 'true' : 'false' }}" data-state="{{ $user->email_notifications ? 'checked' : 'unchecked' }}" data-field="email_notifications" class="notification-toggle peer inline-flex h-6 w-11 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:cursor-not-allowed disabled:opacity-50 {{ $user->email_notifications ? 'bg-primary' : 'bg-input' }}">
                                                    <span class="pointer-events-none block h-5 w-5 rounded-full bg-background shadow-lg ring-0 transition-transform {{ $user->email_notifications ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                                </button>
                                            </div>

                                            <!-- SMS Notifications -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-start gap-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 mt-0.5">
                                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                                    </svg>
                                                    <div>
                                                        <div class="font-medium">SMS Notifications</div>
                                                        <div class="text-sm text-muted-foreground">Receive notifications via SMS</div>
                                                    </div>
                                                </div>
                                                <button type="button" role="switch" aria-checked="{{ $user->sms_notifications ? 'true' : 'false' }}" data-state="{{ $user->sms_notifications ? 'checked' : 'unchecked' }}" data-field="sms_notifications" class="notification-toggle peer inline-flex h-6 w-11 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:cursor-not-allowed disabled:opacity-50 {{ $user->sms_notifications ? 'bg-primary' : 'bg-input' }}">
                                                    <span class="pointer-events-none block h-5 w-5 rounded-full bg-background shadow-lg ring-0 transition-transform {{ $user->sms_notifications ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                                </button>
                                            </div>

                                            <!-- Push Notifications -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1">
                                                    <div class="font-medium">Push Notifications</div>
                                                    <div class="text-sm text-muted-foreground">Receive push notifications in your browser</div>
                                                </div>
                                                <button type="button" role="switch" aria-checked="{{ $user->push_notifications ? 'true' : 'false' }}" data-state="{{ $user->push_notifications ? 'checked' : 'unchecked' }}" data-field="push_notifications" class="notification-toggle peer inline-flex h-6 w-11 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:cursor-not-allowed disabled:opacity-50 {{ $user->push_notifications ? 'bg-primary' : 'bg-input' }}">
                                                    <span class="pointer-events-none block h-5 w-5 rounded-full bg-background shadow-lg ring-0 transition-transform {{ $user->push_notifications ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Alert Types -->
                                    <div>
                                        <h4 class="font-semibold mb-4">Alert Types</h4>
                                        <div class="space-y-4">
                                            <!-- Delivery Alerts -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-start gap-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 mt-0.5">
                                                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                                                        <path d="M15 18H9"></path>
                                                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                                                        <circle cx="17" cy="18" r="2"></circle>
                                                        <circle cx="7" cy="18" r="2"></circle>
                                                    </svg>
                                                    <div>
                                                        <div class="font-medium">Delivery Alerts</div>
                                                        <div class="text-sm text-muted-foreground">Notifications about delivery status changes</div>
                                                    </div>
                                                </div>
                                                <button type="button" role="switch" aria-checked="{{ $notificationPreferences->delivery_alerts ? 'true' : 'false' }}" data-state="{{ $notificationPreferences->delivery_alerts ? 'checked' : 'unchecked' }}" data-field="delivery_alerts" class="notification-toggle peer inline-flex h-6 w-11 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:cursor-not-allowed disabled:opacity-50 {{ $notificationPreferences->delivery_alerts ? 'bg-primary' : 'bg-input' }}">
                                                    <span class="pointer-events-none block h-5 w-5 rounded-full bg-background shadow-lg ring-0 transition-transform {{ $notificationPreferences->delivery_alerts ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                                </button>
                                            </div>

                                            <!-- Maintenance Alerts -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1">
                                                    <div class="font-medium">Maintenance Alerts</div>
                                                    <div class="text-sm text-muted-foreground">Notifications about vehicle maintenance schedules</div>
                                                </div>
                                                <button type="button" role="switch" aria-checked="{{ $notificationPreferences->maintenance_alerts ? 'true' : 'false' }}" data-state="{{ $notificationPreferences->maintenance_alerts ? 'checked' : 'unchecked' }}" data-field="maintenance_alerts" class="notification-toggle peer inline-flex h-6 w-11 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:cursor-not-allowed disabled:opacity-50 {{ $notificationPreferences->maintenance_alerts ? 'bg-primary' : 'bg-input' }}">
                                                    <span class="pointer-events-none block h-5 w-5 rounded-full bg-background shadow-lg ring-0 transition-transform {{ $notificationPreferences->maintenance_alerts ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                                </button>
                                            </div>

                                            <!-- Low Inventory Alerts -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1">
                                                    <div class="font-medium">Low Inventory Alerts</div>
                                                    <div class="text-sm text-muted-foreground">Notifications when inventory levels are low</div>
                                                </div>
                                                <button type="button" role="switch" aria-checked="{{ $notificationPreferences->low_inventory_alerts ? 'true' : 'false' }}" data-state="{{ $notificationPreferences->low_inventory_alerts ? 'checked' : 'unchecked' }}" data-field="low_inventory_alerts" class="notification-toggle peer inline-flex h-6 w-11 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:cursor-not-allowed disabled:opacity-50 {{ $notificationPreferences->low_inventory_alerts ? 'bg-primary' : 'bg-input' }}">
                                                    <span class="pointer-events-none block h-5 w-5 rounded-full bg-background shadow-lg ring-0 transition-transform {{ $notificationPreferences->low_inventory_alerts ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Security Tab Content -->
                        <div data-state="inactive" data-orientation="horizontal" role="tabpanel" aria-labelledby="security-trigger" id="security-content" tabindex="0" class="mt-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 space-y-6" hidden>
                            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                                <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                                    <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield h-5 w-5" aria-hidden="true">
                                            <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                                        </svg>
                                        Security Settings
                                    </h3>
                                    <div class="text-sm text-muted-foreground">Manage your account security and authentication</div>
                                </div>
                                <div class="p-4 md:p-6 pt-0 space-y-4">
                                    <!-- Password -->
                                    <div class="flex items-center justify-between p-4 rounded-lg border">
                                        <div>
                                            <div class="font-medium">Password</div>
                                            <div class="text-sm text-muted-foreground">Last changed {{ $user->updated_at->diffForHumans() }}</div>
                                        </div>
                                        <button id="openChangePasswordModal" type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                            Change Password
                                        </button>
                                    </div>

                                    <!-- Two-Factor Authentication -->
                                    <div class="flex items-center justify-between p-4 rounded-lg border">
                                        <div>
                                            <div class="font-medium">Two-Factor Authentication</div>
                                            <div class="text-sm text-muted-foreground">Add an extra layer of security to your account</div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-muted-foreground">{{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }}</span>
                                            <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                                {{ $user->two_factor_enabled ? 'Disable' : 'Enable' }}
                                            </button>
                                        </div>
                                    </div>

                                    <!-- API Keys -->
                                    <div class="flex items-center justify-between p-4 rounded-lg border">
                                        <div>
                                            <div class="font-medium">API Keys</div>
                                            <div class="text-sm text-muted-foreground">Manage API keys for integrations</div>
                                        </div>
                                        <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                                                <path d="m15 2 6 6-6 6"></path>
                                                <path d="M9 22v-4c0-2.5 2-4 4-4h8"></path>
                                            </svg>
                                            Manage Keys
                                        </button>
                                    </div>

                                    <!-- Active Sessions -->
                                    <div class="flex items-center justify-between p-4 rounded-lg border">
                                        <div>
                                            <div class="font-medium">Active Sessions</div>
                                            <div class="text-sm text-muted-foreground">View and manage your active sessions</div>
                                        </div>
                                        <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                            View Sessions
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Tab Content -->
                        <div data-state="inactive" data-orientation="horizontal" role="tabpanel" aria-labelledby="system-trigger" id="system-content" tabindex="0" class="mt-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 space-y-6" hidden>
                            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                                <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                                    <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-monitor h-5 w-5" aria-hidden="true">
                                            <rect width="20" height="14" x="2" y="3" rx="2"></rect>
                                            <line x1="8" x2="16" y1="21" y2="21"></line>
                                            <line x1="12" x2="12" y1="17" y2="21"></line>
                                        </svg>
                                        System Preferences
                                    </h3>
                                    <div class="text-sm text-muted-foreground">Configure system-wide settings and preferences</div>
                                </div>
                                <form id="systemForm" class="p-4 md:p-6 pt-0 space-y-6">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Appearance -->
                                        <div class="space-y-4">
                                            <h4 class="font-semibold flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                                    <circle cx="12" cy="12" r="4"></circle>
                                                    <path d="M12 2v2"></path>
                                                    <path d="M12 20v2"></path>
                                                    <path d="m4.93 4.93 1.41 1.41"></path>
                                                    <path d="m17.66 17.66 1.41 1.41"></path>
                                                    <path d="M2 12h2"></path>
                                                    <path d="M20 12h2"></path>
                                                    <path d="m6.34 17.66-1.41 1.41"></path>
                                                    <path d="m19.07 4.93-1.41 1.41"></path>
                                                </svg>
                                                Appearance
                                            </h4>
                                            <div class="space-y-2">
                                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="theme">Theme</label>
                                                <select name="theme" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="theme">
                                                    <option value="system" {{ $systemSettings['theme'] == 'system' ? 'selected' : '' }}>System</option>
                                                    <option value="light" {{ $systemSettings['theme'] == 'light' ? 'selected' : '' }}>Light</option>
                                                    <option value="dark" {{ $systemSettings['theme'] == 'dark' ? 'selected' : '' }}>Dark</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Localization -->
                                        <div class="space-y-4">
                                            <h4 class="font-semibold flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
                                                    <path d="M2 12h20"></path>
                                                </svg>
                                                Localization
                                            </h4>
                                            <div class="space-y-2">
                                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="language">Language</label>
                                                <select name="language" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="language">
                                                    <option value="en" {{ $systemSettings['language'] == 'en' ? 'selected' : '' }}>English</option>
                                                    <option value="es" {{ $systemSettings['language'] == 'es' ? 'selected' : '' }}>Spanish</option>
                                                    <option value="fr" {{ $systemSettings['language'] == 'fr' ? 'selected' : '' }}>French</option>
                                                    <option value="de" {{ $systemSettings['language'] == 'de' ? 'selected' : '' }}>German</option>
                                                </select>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="timezone">Timezone</label>
                                                <select name="timezone" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="timezone">
                                                    <option value="America/New_York" {{ $systemSettings['timezone'] == 'America/New_York' ? 'selected' : '' }}>Eastern Time (UTC-5)</option>
                                                    <option value="America/Chicago" {{ $systemSettings['timezone'] == 'America/Chicago' ? 'selected' : '' }}>Central Time (UTC-6)</option>
                                                    <option value="America/Denver" {{ $systemSettings['timezone'] == 'America/Denver' ? 'selected' : '' }}>Mountain Time (UTC-7)</option>
                                                    <option value="America/Los_Angeles" {{ $systemSettings['timezone'] == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time (UTC-8)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Date & Currency -->
                                        <div class="space-y-4">
                                            <h4 class="font-semibold">Date & Currency</h4>
                                            <div class="space-y-2">
                                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="date_format">Date Format</label>
                                                <select name="date_format" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="date_format">
                                                    <option value="MM/DD/YYYY" {{ $systemSettings['date_format'] == 'MM/DD/YYYY' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                                    <option value="DD/MM/YYYY" {{ $systemSettings['date_format'] == 'DD/MM/YYYY' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                                    <option value="YYYY/MM/DD" {{ $systemSettings['date_format'] == 'YYYY/MM/DD' ? 'selected' : '' }}>YYYY/MM/DD</option>
                                                </select>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="currency">Currency</label>
                                                <select name="currency" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="currency">
                                                    <option value="USD" {{ $systemSettings['currency'] == 'NGN' ? 'selected' : '' }}>NGN (₦)</option>
                                                    <option value="USD" {{ $systemSettings['currency'] == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                                    <option value="EUR" {{ $systemSettings['currency'] == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                                    <option value="GBP" {{ $systemSettings['currency'] == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                                    <option value="JPY" {{ $systemSettings['currency'] == 'JPY' ? 'selected' : '' }}>JPY (¥)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Units -->
                                        <div class="space-y-4">
                                            <h4 class="font-semibold">Units</h4>
                                            <div class="space-y-2">
                                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="distance_unit">Distance Unit</label>
                                                <select name="distance_unit" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="distance_unit">
                                                    <option value="miles" {{ $systemSettings['distance_unit'] == 'miles' ? 'selected' : '' }}>Miles</option>
                                                    <option value="kilometers" {{ $systemSettings['distance_unit'] == 'kilometers' ? 'selected' : '' }}>Kilometers</option>
                                                </select>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="weight_unit">Weight Unit</label>
                                                <select name="weight_unit" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm" id="weight_unit">
                                                    <option value="lbs" {{ $systemSettings['weight_unit'] == 'lbs' ? 'selected' : '' }}>Pounds (lbs)</option>
                                                    <option value="kg" {{ $systemSettings['weight_unit'] == 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


<!-- Pricing & Billing Tab Content -->
<div data-state="inactive" data-orientation="horizontal" role="tabpanel" aria-labelledby="pricing-trigger" id="pricing-content" tabindex="0" class="mt-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 space-y-6" hidden>
    
    <!-- Currency Settings -->
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-4 md:p-6">
            <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
                    <path d="M2 12h20"></path>
                </svg>
                Currency Configuration
            </h3>
            <div class="text-sm text-muted-foreground">Configure currency display settings</div>
        </div>
        <form id="currencyForm" class="p-4 md:p-6 pt-0">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none" for="pricing_currency">Currency Code</label>
                    <select name="pricing_currency" id="pricing_currency" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 md:text-sm" onchange="updateCurrencySymbol(this)">
                        <option value="NGN" data-symbol="₦" {{ $pricingSettings['pricing_currency'] == 'NGN' ? 'selected' : '' }}>NGN - Nigerian Naira (₦)</option>
                        <option value="USD" data-symbol="$" {{ $pricingSettings['pricing_currency'] == 'USD' ? 'selected' : '' }}>USD - US Dollar ($)</option>
                        <option value="EUR" data-symbol="€" {{ $pricingSettings['pricing_currency'] == 'EUR' ? 'selected' : '' }}>EUR - Euro (€)</option>
                        <option value="GBP" data-symbol="£" {{ $pricingSettings['pricing_currency'] == 'GBP' ? 'selected' : '' }}>GBP - British Pound (£)</option>
                        <option value="JPY" data-symbol="¥" {{ $pricingSettings['pricing_currency'] == 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen (¥)</option>
                        <option value="CAD" data-symbol="C$" {{ $pricingSettings['pricing_currency'] == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar (C$)</option>
                        <option value="AUD" data-symbol="A$" {{ $pricingSettings['pricing_currency'] == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar (A$)</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none" for="pricing_currency_symbol">Currency Symbol</label>
                    <input type="text" name="pricing_currency_symbol" id="pricing_currency_symbol" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_currency_symbol'] }}" readonly>
                    <p class="text-xs text-muted-foreground">Automatically set based on currency selection</p>
                </div>
            </div>
        </form>
    </div>

    <!-- Standard Package Pricing -->
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-4 md:p-6">
            <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                </svg>
                Standard Package Pricing
            </h3>
            <div class="text-sm text-muted-foreground">Configure pricing for standard packages</div>
        </div>
        <form id="standardPackageForm" class="p-4 md:p-6 pt-0">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium" for="pricing_standard_package_standard">Standard (5-7 days)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-sp-std">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                        <input type="number" name="pricing_standard_package_standard" id="pricing_standard_package_standard" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_standard_package_standard'] }}">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium" for="pricing_standard_package_express">Express (2-3 days)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-sp-exp">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                        <input type="number" name="pricing_standard_package_express" id="pricing_standard_package_express" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_standard_package_express'] }}">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium" for="pricing_standard_package_overnight">Overnight</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-sp-ovr">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                        <input type="number" name="pricing_standard_package_overnight" id="pricing_standard_package_overnight" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_standard_package_overnight'] }}">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Document Envelope Pricing -->
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-4 md:p-6">
            <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                Document Envelope Pricing
            </h3>
            <div class="text-sm text-muted-foreground">Configure pricing for document envelopes</div>
        </div>
        <form id="documentEnvelopeForm" class="p-4 md:p-6 pt-0">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium" for="pricing_document_envelope_standard">Standard (5-7 days)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-de-std">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                        <input type="number" name="pricing_document_envelope_standard" id="pricing_document_envelope_standard" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_document_envelope_standard'] }}">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium" for="pricing_document_envelope_express">Express (2-3 days)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-de-exp">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                        <input type="number" name="pricing_document_envelope_express" id="pricing_document_envelope_express" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_document_envelope_express'] }}">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium" for="pricing_document_envelope_overnight">Overnight</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-de-ovr">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                        <input type="number" name="pricing_document_envelope_overnight" id="pricing_document_envelope_overnight" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_document_envelope_overnight'] }}">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Freight/Pallet Pricing -->
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-4 md:p-6">
            <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                    <path d="M15 18H9"></path>
                    <circle cx="17" cy="18" r="2"></circle>
                    <circle cx="7" cy="18" r="2"></circle>
                </svg>
                Freight/Pallet Pricing
            </h3>
            <div class="text-sm text-muted-foreground">Configure pricing for freight and pallets</div>
        </div>
        <form id="freightPalletForm" class="p-4 md:p-6 pt-0">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium" for="pricing_freight_pallet_standard">Standard (5-7 days)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-fp-std">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                        <input type="number" name="pricing_freight_pallet_standard" id="pricing_freight_pallet_standard" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_freight_pallet_standard'] }}">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium" for="pricing_freight_pallet_express">Express (2-3 days)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-fp-exp">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                        <input type="number" name="pricing_freight_pallet_express" id="pricing_freight_pallet_express" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_freight_pallet_express'] }}">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium" for="pricing_freight_pallet_overnight">Overnight</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-fp-ovr">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                        <input type="number" name="pricing_freight_pallet_overnight" id="pricing_freight_pallet_overnight" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_freight_pallet_overnight'] }}">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Cargo Pricing -->
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-4 md:p-6">
            <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <rect width="18" height="18" x="3" y="3" rx="2"></rect>
                    <path d="M9 3v18"></path>
                    <path d="M15 3v18"></path>
                </svg>
                Bulk Cargo Pricing
            </h3>
            <div class="text-sm text-muted-foreground">Configure pricing for bulk cargo shipments</div>
        </div>
        <form id="bulkCargoForm" class="p-4 md:p-6 pt-0">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium" for="pricing_bulk_cargo_standard">Standard (5-7 days)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-bc-std">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                        <input type="number" name="pricing_bulk_cargo_standard" id="pricing_bulk_cargo_standard" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_bulk_cargo_standard'] }}">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium" for="pricing_bulk_cargo_express">Express (2-3 days)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-bc-exp">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                        <input type="number" name="pricing_bulk_cargo_express" id="pricing_bulk_cargo_express" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_bulk_cargo_express'] }}">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium" for="pricing_bulk_cargo_overnight">Overnight</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-bc-ovr">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                        <input type="number" name="pricing_bulk_cargo_overnight" id="pricing_bulk_cargo_overnight" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_bulk_cargo_overnight'] }}">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Additional Charges Configuration -->
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-4 md:p-6">
            <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                Additional Charges
            </h3>
            <div class="text-sm text-muted-foreground">Configure weight, distance, and service fees</div>
        </div>
        <form id="additionalChargesForm" class="p-4 md:p-6 pt-0 space-y-6">
            @csrf
            
            <!-- Weight Charges -->
            <div>
                <h4 class="font-semibold mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <path d="M12 2v20"></path>
                        <path d="m15 19-3 3-3-3"></path>
                        <path d="m19 9 3 3-3 3"></path>
                        <path d="M2 12h20"></path>
                        <path d="m5 9-3 3 3 3"></path>
                        <path d="m9 5 3-3 3 3"></path>
                    </svg>
                    Weight Charges
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium" for="pricing_weight_threshold">Weight Threshold (lbs)</label>
                        <input type="number" name="pricing_weight_threshold" id="pricing_weight_threshold" step="0.1" min="0" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_weight_threshold'] }}">
                        <p class="text-xs text-muted-foreground">Charges apply above this weight</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium" for="pricing_weight_rate_per_lb">Rate per lb (over threshold)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-weight">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                            <input type="number" name="pricing_weight_rate_per_lb" id="pricing_weight_rate_per_lb" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_weight_rate_per_lb'] }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Distance Charges - REPLACE THE EXISTING DISTANCE SECTION -->
<div>
    <h4 class="font-semibold mb-4 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
        Distance Charges
    </h4>
    
    <div class="space-y-4">
        <!-- Calculated Distance Rate -->
        <div class="p-4 rounded-lg border bg-muted/50">
            <div class="flex items-start gap-2 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-blue-600 mt-0.5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 16v-4"></path>
                    <path d="M12 8h.01"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-medium">Calculated Distance Rate</p>
                    <p class="text-xs text-muted-foreground">Applied when exact distance can be calculated from coordinates</p>
                </div>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-medium" for="pricing_distance_rate_per_mile">Rate per Mile</label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-distance">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                    <input type="number" name="pricing_distance_rate_per_mile" id="pricing_distance_rate_per_mile" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_distance_rate_per_mile'] }}">
                </div>
            </div>
        </div>

        <!-- Zone-Based Flat Rates -->
        <div class="p-4 rounded-lg border bg-muted/50">
            <div class="flex items-start gap-2 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-orange-600 mt-0.5">
                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-medium">Zone-Based Flat Rates</p>
                    <p class="text-xs text-muted-foreground">Applied when distance cannot be calculated (e.g., no coordinates, international shipping)</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium" for="pricing_zone_local">
                        <span class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-green-100 text-green-800">Local</span>
                            Within Same City
                        </span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-zone-local">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                        <input type="number" name="pricing_zone_local" id="pricing_zone_local" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_zone_local'] }}">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium" for="pricing_zone_regional">
                        <span class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-blue-100 text-blue-800">Regional</span>
                            Within Same State
                        </span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-zone-regional">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                        <input type="number" name="pricing_zone_regional" id="pricing_zone_regional" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_zone_regional'] }}">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium" for="pricing_zone_national">
                        <span class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-purple-100 text-purple-800">National</span>
                            Different States
                        </span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-zone-national">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                        <input type="number" name="pricing_zone_national" id="pricing_zone_national" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_zone_national'] }}">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium" for="pricing_zone_international">
                        <span class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-red-100 text-red-800">International</span>
                            Different Countries
                        </span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-zone-intl">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                        <input type="number" name="pricing_zone_international" id="pricing_zone_international" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_zone_international'] }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            <!-- Service Fees -->
            <div>
                <h4 class="font-semibold mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path>
                    </svg>
                    Additional Service Fees
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium" for="pricing_insurance_rate">Insurance Rate (%)</label>
                        <div class="relative">
                            <input type="number" name="pricing_insurance_rate" id="pricing_insurance_rate" step="0.1" min="0" max="100" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_insurance_rate'] }}">
                            <span class="absolute right-3 top-2.5 text-muted-foreground">%</span>
                        </div>
                        <p class="text-xs text-muted-foreground">Percentage of insured value</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium" for="pricing_signature_fee">Signature Required</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-sig">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
                            <input type="number" name="pricing_signature_fee" id="pricing_signature_fee" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_signature_fee'] }}">
</div>
</div>
<div class="space-y-2">
<label class="text-sm font-medium" for="pricing_temperature_controlled_fee">Temperature Controlled</label>
<div class="relative">
<span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-temp">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
<input type="number" name="pricing_temperature_controlled_fee" id="pricing_temperature_controlled_fee" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_temperature_controlled_fee'] }}">
</div>
</div>
<div class="space-y-2">
<label class="text-sm font-medium" for="pricing_fragile_handling_fee">Fragile Handling</label>
<div class="relative">
<span class="absolute left-3 top-2.5 text-muted-foreground" id="symbol-frag">{{ $pricingSettings['pricing_currency_symbol'] }}</span>
<input type="number" name="pricing_fragile_handling_fee" id="pricing_fragile_handling_fee" step="0.01" min="0" class="flex h-10 w-full rounded-md border border-input bg-background pl-8 pr-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_fragile_handling_fee'] }}">
</div>
</div>
</div>
</div>
        <!-- Tax Configuration -->
        <div>
            <h4 class="font-semibold mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                    <rect width="14" height="17" x="6" y="3" rx="2"></rect>
                    <path d="M12 11h2"></path>
                    <path d="M12 7h2"></path>
                    <path d="M12 15h2"></path>
                </svg>
                Tax Configuration
            </h4>
            <div class="space-y-2">
                <label class="text-sm font-medium" for="pricing_tax_percentage">Tax Percentage (%)</label>
                <div class="relative">
                    <input type="number" name="pricing_tax_percentage" id="pricing_tax_percentage" step="0.1" min="0" max="100" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring md:text-sm" value="{{ $pricingSettings['pricing_tax_percentage'] }}">
                    <span class="absolute right-3 top-2.5 text-muted-foreground">%</span>
                </div>
                <p class="text-xs text-muted-foreground">Applied to subtotal of all charges</p>
            </div>
        </div>
    </form>
</div>

<!-- Info Alert -->
<div class="rounded-lg bg-blue-50 border border-blue-200 p-4 flex gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-blue-600 flex-shrink-0">
        <circle cx="12" cy="12" r="10"></circle>
        <path d="M12 16v-4"></path>
        <path d="M12 8h.01"></path>
    </svg>
    <p class="text-sm text-blue-900">Pricing changes will take effect immediately for all new shipments. Existing shipments will retain their original pricing.</p>
</div>
</div>


                        <!-- Integrations Tab Content -->
                        <div data-state="inactive" data-orientation="horizontal" role="tabpanel" aria-labelledby="integrations-trigger" id="integrations-content" tabindex="0" class="mt-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 space-y-6" hidden>
                            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                                <div class="flex flex-col space-y-1.5 p-4 md:p-6">
                                    <h3 class="text-xl sm:text-2xl font-semibold leading-none tracking-tight flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-database h-5 w-5" aria-hidden="true">
                                            <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                            <path d="M3 5V19A9 3 0 0 0 21 19V5"></path>
                                            <path d="M3 12A9 3 0 0 0 21 12"></path>
                                        </svg>
                                        Third-Party Integrations
                                    </h3>
                                    <div class="text-sm text-muted-foreground">Manage connections to external services and APIs</div>
                                </div>
                                <div class="p-4 md:p-6 pt-0 space-y-4">
                                    <!-- Google Maps API -->
                                    <div class="flex items-center justify-between p-4 rounded-lg border">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-blue-600">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
                                                    <path d="M2 12h20"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium">Google Maps API</div>
                                                <div class="text-sm text-muted-foreground">Route optimization and tracking</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $integrations['google_maps'] ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground' }}">{{ $integrations['google_maps'] ? 'Connected' : 'Disconnected' }}</span>
                                            <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                                Configure
                                            </button>
                                        </div>
                                    </div>

                                    <!-- SendGrid -->
                                    <div class="flex items-center justify-between p-4 rounded-lg border">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-green-600">
                                                    <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium">SendGrid</div>
                                                <div class="text-sm text-muted-foreground">Email notifications and alerts</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $integrations['sendgrid'] ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground' }}">{{ $integrations['sendgrid'] ? 'Connected' : 'Disconnected' }}</span>
                                            <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                                Configure
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Twilio -->
                                    <div class="flex items-center justify-between p-4 rounded-lg border">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-purple-600">
                                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium">Twilio</div>
                                                <div class="text-sm text-muted-foreground">SMS notifications and alerts</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $integrations['twilio'] ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground' }}">{{ $integrations['twilio'] ? 'Connected' : 'Disconnected' }}</span>
                                            <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                                Connect
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Webhook Endpoints -->
                                    <div class="flex items-center justify-between p-4 rounded-lg border">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-orange-600">
                                                    <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                                    <path d="M3 5V19A9 3 0 0 0 21 19V5"></path>
                                                    <path d="M3 12A9 3 0 0 0 21 12"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium">Webhook Endpoints</div>
                                                <div class="text-sm text-muted-foreground">Custom webhook integrations</div>
                                            </div>
                                        </div>
                                        <button type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                            Manage
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Save Changes Button -->
                    <div class="flex justify-end">
                        <button id="saveSettingsBtn" class="justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save h-4 w-4" aria-hidden="true">
                                <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"></path>
                                <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"></path>
                                <path d="M7 3v4a1 1 0 0 0 1 1h7"></path>
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="fixed inset-0 z-50 hidden bg-black/80">
    <div class="fixed left-[50%] top-[50%] z-50 w-full max-w-lg translate-x-[-50%] translate-y-[-50%] gap-4 border bg-background p-6 shadow-lg duration-200 sm:rounded-lg">
        <div class="flex flex-col space-y-1.5 text-center sm:text-left">
            <h2 class="text-lg font-semibold leading-none tracking-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                Change Password
            </h2>
            <p class="text-sm text-muted-foreground">Enter your current password and choose a new password</p>
        </div>
        
        <form id="changePasswordForm" class="space-y-4 mt-4">
            @csrf
            <div class="space-y-2">
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="current_password">Current Password</label>
                <input type="password" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" id="current_password" name="current_password" placeholder="Enter current password" required>
                <span id="current_password_error" class="text-xs text-red-500 hidden"></span>
            </div>
            
            <div class="space-y-2">
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="new_password">New Password</label>
                <input type="password" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" id="new_password" name="new_password" placeholder="Enter new password" required>
                <span id="new_password_error" class="text-xs text-red-500 hidden"></span>
                <p class="text-xs text-muted-foreground">Must be at least 8 characters with uppercase, lowercase, and numbers</p>
            </div>
            
            <div class="space-y-2">
                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="new_password_confirmation">Confirm New Password</label>
                <input type="password" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm new password" required>
                <span id="new_password_confirmation_error" class="text-xs text-red-500 hidden"></span>
            </div>
            
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2 gap-2">
                <button type="button" id="closePasswordModal" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // CSRF Token Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Tab Switching Functionality
    const tabButtons = $('[role="tab"]');
    const tabPanels = $('[role="tabpanel"]');

    tabButtons.on('click', function(e) {
        const targetPanel = $(this).attr('aria-controls');
        
        if (!targetPanel) return;

        const $targetPanel = $(`#${targetPanel}`);
        if ($targetPanel.length === 0) return;

        // Remove active state from all tabs
        tabButtons.attr({
            'aria-selected': 'false',
            'data-state': 'inactive',
            'tabindex': '-1'
        });

        // Hide all panels
        tabPanels.attr('data-state', 'inactive').attr('hidden', '');

        // Activate clicked tab
        $(this).attr({
            'aria-selected': 'true',
            'data-state': 'active',
            'tabindex': '0'
        });

        // Show corresponding panel
        $targetPanel.attr('data-state', 'active').removeAttr('hidden');
    });

    // Profile Photo Upload
    $('#uploadPhotoBtn').on('click', function() {
        $('#profile_photo').click();
    });

    $('#profile_photo').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                $('#profilePhotoPreview').html(`<img src="${e.target.result}" alt="Profile" class="h-full w-full object-cover">`);
            };
            reader.readAsDataURL(file);
        }
    });

    // Toggle Switch Functionality
    $('.notification-toggle').on('click', function() {
        const isChecked = $(this).attr('aria-checked') === 'true';
        const newState = !isChecked;
        
        $(this).attr({
            'aria-checked': newState,
            'data-state': newState ? 'checked' : 'unchecked'
        });
        
        const thumb = $(this).find('span');
        if (newState) {
            $(this).removeClass('bg-input').addClass('bg-primary');
            thumb.removeClass('translate-x-0').addClass('translate-x-5');
        } else {
            $(this).removeClass('bg-primary').addClass('bg-input');
            thumb.removeClass('translate-x-5').addClass('translate-x-0');
        }
    });

    // Save Settings Button
    $('#saveSettingsBtn').on('click', function(e) {
        e.preventDefault();
        
        const activeTab = $('[role="tab"][aria-selected="true"]').attr('id');
        let formData, url, group;

        if (activeTab === 'general-trigger') {
            // Save Company Settings
            formData = $('#companyForm').serializeArray();
            group = 'company';
            url = '{{ route("settings.update") }}';
            
            let settings = {};
            formData.forEach(item => {
                if (item.name !== '_token') {
                    settings[item.name] = item.value;
                }
            });

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    group: group,
                    settings: settings
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Settings saved successfully!', 'success');
                    }
                },
                error: function(xhr) {
                    showNotification('Failed to save settings', 'error');
                }
            });

        } else if (activeTab === 'profile-trigger') {
            // Save Profile
            formData = new FormData($('#profileForm')[0]);
            url = '{{ route("settings.profile") }}';

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        showNotification('Profile updated successfully!', 'success');
                        if (response.user.profile_photo) {
                            $('#profilePhotoPreview').html(`<img src="${response.user.profile_photo}" alt="Profile" class="h-full w-full object-cover">`);
                        }
                    }
                },
                error: function(xhr) {
                    showNotification('Failed to update profile', 'error');
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        Object.keys(xhr.responseJSON.errors).forEach(key => {
                            showNotification(xhr.responseJSON.errors[key][0], 'error');
                        });
                    }
                }
            });

        } else if (activeTab === 'notifications-trigger') {
            // Save Notifications
            let notificationData = {};
            $('.notification-toggle').each(function() {
                const field = $(this).data('field');
                const value = $(this).attr('aria-checked') === 'true';
                notificationData[field] = value;
            });

            $.ajax({
                url: '{{ route("settings.notifications") }}',
                method: 'POST',
                data: notificationData,
                success: function(response) {
                    if (response.success) {
                        showNotification('Notification preferences updated successfully!', 'success');
                    }
                },
                error: function(xhr) {
                    showNotification('Failed to update notification preferences', 'error');
                }
            });

        } else if (activeTab === 'system-trigger') {
            // Save System Settings
            formData = $('#systemForm').serializeArray();
            group = 'system';
            url = '{{ route("settings.update") }}';
            
            let settings = {};
            formData.forEach(item => {
                if (item.name !== '_token') {
                    settings[item.name] = item.value;
                }
            });

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    group: group,
                    settings: settings
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('System settings saved successfully!', 'success');
                    }
                },
                error: function(xhr) {
                    showNotification('Failed to save system settings', 'error');
                }
            });

        } else if (activeTab === 'pricing-trigger') {
            // Save Pricing Settings
            let allPricingData = {};
            
            // Collect data from all pricing forms
            ['currencyForm', 'standardPackageForm', 'documentEnvelopeForm', 
             'freightPalletForm', 'bulkCargoForm', 'additionalChargesForm'].forEach(formId => {
                const formData = $(`#${formId}`).serializeArray();
                formData.forEach(item => {
                    if (item.name !== '_token') {
                        allPricingData[item.name] = item.value;
                    }
                });
            });

            $.ajax({
                url: '{{ route("settings.update") }}',
                method: 'POST',
                data: {
                    group: 'pricing',
                    settings: allPricingData
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Pricing settings saved successfully!', 'success');
                    }
                },
                error: function(xhr) {
                    showNotification('Failed to save pricing settings', 'error');
                }
            });
        }
    });

    // Change Password Modal
    $('#openChangePasswordModal').on('click', function() {
        $('#changePasswordModal').removeClass('hidden');
    });

    $('#closePasswordModal').on('click', function() {
        $('#changePasswordModal').addClass('hidden');
        $('#changePasswordForm')[0].reset();
        $('.text-red-500').addClass('hidden').text('');
    });

    // Close modal on backdrop click
    $('#changePasswordModal').on('click', function(e) {
        if (e.target === this) {
            $(this).addClass('hidden');
            $('#changePasswordForm')[0].reset();
            $('.text-red-500').addClass('hidden').text('');
        }
    });

    // Change Password Form Submit
    $('#changePasswordForm').on('submit', function(e) {
        e.preventDefault();
        
        $('.text-red-500').addClass('hidden').text('');
        const formData = $(this).serialize();

        $.ajax({
            url: '{{ route("password.change") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#changePasswordModal').addClass('hidden');
                    $('#changePasswordForm')[0].reset();
                    showNotification('Password changed successfully!', 'success');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        $(`#${key}_error`).removeClass('hidden').text(errors[key][0]);
                    });
                } else {
                    showNotification(xhr.responseJSON.message || 'Failed to change password', 'error');
                }
            }
        });
    });

    // Notification Function
    function showNotification(message, type = 'success') {
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        const notification = $(`
            <div class="fixed top-4 right-4 z-50 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 animate-slide-in">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    ${type === 'success' ? '<polyline points="20 6 9 17 4 12"></polyline>' : '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>'}
                </svg>
                <span>${message}</span>
            </div>
        `);

        $('body').append(notification);

        setTimeout(() => {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }

    // Keyboard Navigation for Tabs
    $(document).on('keydown', function(e) {
        const activeTab = $('[role="tab"][aria-selected="true"]');
        if (!activeTab.length) return;

        const tabs = $('[role="tab"]');
        const currentIndex = tabs.index(activeTab);

        if (e.key === 'ArrowRight') {
            e.preventDefault();
            const nextIndex = (currentIndex + 1) % tabs.length;
            tabs.eq(nextIndex).trigger('click').focus();
        } else if (e.key === 'ArrowLeft') {
            e.preventDefault();
            const prevIndex = (currentIndex - 1 + tabs.length) % tabs.length;
            tabs.eq(prevIndex).trigger('click').focus();
        }
    });

    // Add CSS animation for notification
    if (!$('#notification-styles').length) {
        $('head').append(`
            <style id="notification-styles">
                @keyframes slide-in {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                .animate-slide-in {
                    animation: slide-in 0.3s ease-out;
                }
            </style>
        `);
    }
});

// Currency Symbol Update Function
function updateCurrencySymbol(select) {
    const selectedOption = select.options[select.selectedIndex];
    const symbol = selectedOption.getAttribute('data-symbol');
    
    // Update the currency symbol input
    document.getElementById('pricing_currency_symbol').value = symbol;
    
    // Update all currency symbol spans in the pricing forms
    const symbolSpans = document.querySelectorAll('[id^="symbol-"]');
    symbolSpans.forEach(span => {
        span.textContent = symbol;
    });
}
</script>

@endsection