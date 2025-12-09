<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\UserNotificationPreference;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all settings grouped
        $companySettings = [
            'company_name' => Setting::get('company_name', 'CargoMax Logistics'),
            'company_tax_id' => Setting::get('company_tax_id', 'TAX123456789'),
            'company_email' => Setting::get('company_email', 'admin@cargomax.com'),
            'company_phone' => Setting::get('company_phone', '+1 (555) 123-4567'),
            'company_website' => Setting::get('company_website', 'www.cargomax.com'),
            'company_address' => Setting::get('company_address', '123 Logistics Ave, Transport City, TC 12345'),
            'company_tax_percentage' => Setting::get('company_tax_percentage', '8.5'),
        ];
        
        $systemSettings = [
            'theme' => Setting::get('theme', 'system'),
            'language' => Setting::get('language', 'en'),
            'timezone' => Setting::get('timezone', 'America/New_York'),
            'date_format' => Setting::get('date_format', 'MM/DD/YYYY'),
            'currency' => Setting::get('currency', 'USD'),
            'distance_unit' => Setting::get('distance_unit', 'miles'),
            'weight_unit' => Setting::get('weight_unit', 'lbs'),
        ];
        
        // Pricing Settings
        $pricingSettings = [
            'pricing_currency' => Setting::get('pricing_currency', 'USD'),
            'pricing_currency_symbol' => Setting::get('pricing_currency_symbol', '$'),
            
            // Standard Package
            'pricing_standard_package_standard' => Setting::get('pricing_standard_package_standard', '15.99'),
            'pricing_standard_package_express' => Setting::get('pricing_standard_package_express', '29.99'),
            'pricing_standard_package_overnight' => Setting::get('pricing_standard_package_overnight', '49.99'),
            
            // Document Envelope
            'pricing_document_envelope_standard' => Setting::get('pricing_document_envelope_standard', '9.99'),
            'pricing_document_envelope_express' => Setting::get('pricing_document_envelope_express', '19.99'),
            'pricing_document_envelope_overnight' => Setting::get('pricing_document_envelope_overnight', '34.99'),
            
            // Freight/Pallet
            'pricing_freight_pallet_standard' => Setting::get('pricing_freight_pallet_standard', '99.99'),
            'pricing_freight_pallet_express' => Setting::get('pricing_freight_pallet_express', '149.99'),
            'pricing_freight_pallet_overnight' => Setting::get('pricing_freight_pallet_overnight', '249.99'),
            
            // Bulk Cargo
            'pricing_bulk_cargo_standard' => Setting::get('pricing_bulk_cargo_standard', '199.99'),
            'pricing_bulk_cargo_express' => Setting::get('pricing_bulk_cargo_express', '299.99'),
            'pricing_bulk_cargo_overnight' => Setting::get('pricing_bulk_cargo_overnight', '449.99'),

            

            
            // Additional Charges
            'pricing_weight_threshold' => Setting::get('pricing_weight_threshold', '10'),
            'pricing_weight_rate_per_lb' => Setting::get('pricing_weight_rate_per_lb', '0.50'),
            'pricing_distance_rate_per_mile' => Setting::get('pricing_distance_rate_per_mile', '0.75'),
            'pricing_zone_local' => Setting::get('pricing_zone_local', '5.00'),
'pricing_zone_regional' => Setting::get('pricing_zone_regional', '15.00'),
'pricing_zone_national' => Setting::get('pricing_zone_national', '35.00'),
'pricing_zone_international' => Setting::get('pricing_zone_international', '100.00'),
            'pricing_insurance_rate' => Setting::get('pricing_insurance_rate', '2'),
            'pricing_signature_fee' => Setting::get('pricing_signature_fee', '5.00'),
            'pricing_temperature_controlled_fee' => Setting::get('pricing_temperature_controlled_fee', '25.00'),
            'pricing_fragile_handling_fee' => Setting::get('pricing_fragile_handling_fee', '10.00'),
            'pricing_tax_percentage' => Setting::get('pricing_tax_percentage', '10'),
        ];
        
        $integrations = [
            'google_maps' => Setting::get('google_maps_enabled', true),
            'sendgrid' => Setting::get('sendgrid_enabled', true),
            'twilio' => Setting::get('twilio_enabled', false),
        ];
        
        // Get or create notification preferences
        $notificationPreferences = $user->notificationPreferences ?? $user->notificationPreferences()->create([
            'delivery_alerts' => true,
            'maintenance_alerts' => true,
            'low_inventory_alerts' => false,
        ]);

        return view('backend.settings.settings', compact(
            'user',
            'companySettings',
            'systemSettings',
            'pricingSettings',
            'integrations',
            'notificationPreferences'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'group' => 'required|string|in:company,system,pricing,integrations',
            'settings' => 'required|array',
        ]);

        try {
            foreach ($validated['settings'] as $key => $value) {
                Setting::set($key, $value, $validated['group']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        try {
            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                    Storage::disk('public')->delete($user->profile_photo);
                }

                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $validated['profile_photo'] = $path;
            }

            // Update user name
            $validated['user_name'] = $validated['first_name'] . ' ' . $validated['last_name'];

            $user->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => [
                    'name' => $user->user_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'profile_photo' => $user->profile_photo ? asset('storage/' . $user->profile_photo) : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateNotifications(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'delivery_alerts' => 'boolean',
            'maintenance_alerts' => 'boolean',
            'low_inventory_alerts' => 'boolean',
        ]);

        try {
            // Update user table notifications
            $user->update([
                'email_notifications' => $validated['email_notifications'] ?? $user->email_notifications,
                'sms_notifications' => $validated['sms_notifications'] ?? $user->sms_notifications,
                'push_notifications' => $validated['push_notifications'] ?? $user->push_notifications,
            ]);

            // Update notification preferences table
            $preferences = $user->notificationPreferences()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'delivery_alerts' => $validated['delivery_alerts'] ?? false,
                    'maintenance_alerts' => $validated['maintenance_alerts'] ?? false,
                    'low_inventory_alerts' => $validated['low_inventory_alerts'] ?? false,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Notification preferences updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update preferences: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
 * Get pricing settings for shipment creation
 */
public function getPricingSettings()
{
    try {
        $pricingSettings = [
            'currency' => Setting::get('pricing_currency', 'USD'),
            'currency_symbol' => Setting::get('pricing_currency_symbol', '$'),
            
            // Standard Package
            'standard_package' => [
                'standard' => floatval(Setting::get('pricing_standard_package_standard', '15.99')),
                'express' => floatval(Setting::get('pricing_standard_package_express', '29.99')),
                'overnight' => floatval(Setting::get('pricing_standard_package_overnight', '49.99')),
            ],
            
            // Document Envelope
            'document_envelope' => [
                'standard' => floatval(Setting::get('pricing_document_envelope_standard', '9.99')),
                'express' => floatval(Setting::get('pricing_document_envelope_express', '19.99')),
                'overnight' => floatval(Setting::get('pricing_document_envelope_overnight', '34.99')),
            ],
            
            // Freight/Pallet
            'freight_pallet' => [
                'standard' => floatval(Setting::get('pricing_freight_pallet_standard', '99.99')),
                'express' => floatval(Setting::get('pricing_freight_pallet_express', '149.99')),
                'overnight' => floatval(Setting::get('pricing_freight_pallet_overnight', '249.99')),
            ],
            
            // Bulk Cargo
            'bulk_cargo' => [
                'standard' => floatval(Setting::get('pricing_bulk_cargo_standard', '199.99')),
                'express' => floatval(Setting::get('pricing_bulk_cargo_express', '299.99')),
                'overnight' => floatval(Setting::get('pricing_bulk_cargo_overnight', '449.99')),
            ],
            
            // Additional Charges
            'weight_threshold' => floatval(Setting::get('pricing_weight_threshold', '10')),
            'weight_rate_per_lb' => floatval(Setting::get('pricing_weight_rate_per_lb', '0.50')),
            'distance_rate_per_mile' => floatval(Setting::get('pricing_distance_rate_per_mile', '0.75')),
'zone_local' => floatval(Setting::get('pricing_zone_local', '5.00')),
'zone_regional' => floatval(Setting::get('pricing_zone_regional', '15.00')),
'zone_national' => floatval(Setting::get('pricing_zone_national', '35.00')),
'zone_international' => floatval(Setting::get('pricing_zone_international', '100.00')),
            'insurance_rate' => floatval(Setting::get('pricing_insurance_rate', '2')),
            'signature_fee' => floatval(Setting::get('pricing_signature_fee', '5.00')),
            'temperature_controlled_fee' => floatval(Setting::get('pricing_temperature_controlled_fee', '25.00')),
            'fragile_handling_fee' => floatval(Setting::get('pricing_fragile_handling_fee', '10.00')),
            'tax_percentage' => floatval(Setting::get('pricing_tax_percentage', '10')),
        ];

        return response()->json([
            'success' => true,
            'pricing' => $pricingSettings
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to load pricing settings: ' . $e->getMessage()
        ], 500);
    }
}
}

   