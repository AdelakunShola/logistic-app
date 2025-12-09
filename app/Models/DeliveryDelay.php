<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;
class DeliveryDelay extends Model
{ 
    use LogsActivity;
    protected $guarded = [];

    
}
