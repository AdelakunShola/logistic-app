<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'admin' || auth()->user()->role === 'manager';
    }

    public function rules(): array
    {
        $driverId = $this->route('id');

        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $driverId,
            'phone' => 'required|string|max:20',
            'status' => 'required|in:active,inactive,suspended,on_leave',
            'license_number' => 'required|string|unique:users,license_number,' . $driverId,
            'license_expiry' => 'nullable|date',
            'experience_years' => 'nullable|numeric|min:0',
            'specializations' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric|min:0',
            'profile_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
