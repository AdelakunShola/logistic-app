<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
class EmailTemplate extends Model
{
    use LogsActivity;
    protected $guarded = [];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function render(array $data): array
    {
        $subject = $this->subject;
        $body = $this->body;

        foreach ($data as $key => $value) {
            $subject = str_replace('{' . $key . '}', $value, $subject);
            $body = str_replace('{' . $key . '}', $value, $body);
        }

        return [
            'subject' => $subject,
            'body' => $body,
        ];
    }
}