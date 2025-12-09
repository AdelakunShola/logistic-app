<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array(auth()->user()->role, ['admin', 'manager', 'dispatcher']);
    }

    public function rules(): array
    {
        return [
            'driver_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:info,success,warning,error',
            'channels' => 'required|array|min:1',
            'channels.*' => 'in:system,email,sms,push',
        ];
    }
}
