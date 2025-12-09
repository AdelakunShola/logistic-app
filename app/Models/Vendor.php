<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
class Vendor extends Model
{
    use HasFactory, SoftDeletes;
use LogsActivity;
     protected $guarded = [];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'rating' => 'decimal:2',
    ];
}