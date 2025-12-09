<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array(auth()->user()->role, ['admin', 'manager', 'dispatcher']);
    }

    public function rules(): array
    {
        return [
            'driver_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'route_name' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
