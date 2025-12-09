<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
class TicketMessage extends Model
{
    use HasFactory;
use LogsActivity;
     protected $guarded = [];
    protected $casts = [
        'attachments' => 'array',
        'is_internal' => 'boolean',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
