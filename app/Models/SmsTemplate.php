<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
class SmsTemplate extends Model
{
    use LogsActivity;
    protected $guarded = [];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function render(array $data): string
    {
        $message = $this->message;

        foreach ($data as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }

        return $message;
    }
}
