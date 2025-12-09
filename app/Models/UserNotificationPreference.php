<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
class UserNotificationPreference extends Model
{
    use HasFactory;
use LogsActivity;
    protected $guarded = [];

    protected $casts = [
        'delivery_alerts' => 'boolean',
        'maintenance_alerts' => 'boolean',
        'low_inventory_alerts' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}