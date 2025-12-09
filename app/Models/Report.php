<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
class Report extends Model
{
 use HasFactory;
use LogsActivity;
    protected $guarded = [];

     protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'filters' => 'array',
    ];

    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }
}



