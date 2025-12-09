<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'admin' || auth()->user()->role === 'manager';
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'license_number' => 'required|string|unique:users,license_number',
            'license_expiry' => 'required|date|after:today',
            'driver_license' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'experience_years' => 'required|numeric|min:0|max:50',
            'medical_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'specializations' => 'nullable|string|max:500',
            'vehicle_type' => 'nullable|in:truck,van,bike,car,bicycle',
            'vehicle_number' => 'nullable|string|max:50',
            'vehicle_capacity' => 'nullable|numeric|min:0',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'id_proof_type' => 'nullable|in:passport,national_id,ssn,drivers_license',
            'id_proof_number' => 'nullable|string|max:50',
            'id_proof_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'address_proof_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'employee_id' => 'nullable|string|unique:users,employee_id',
            'joining_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'account_holder_name' => 'nullable|string|max:100',
            'ifsc_code' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
