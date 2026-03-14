<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YardSlotHistory extends Model
{
    use HasFactory;

    protected $table = 'yard_slot_history';

    protected $guarded = [];

    public function slot()
    {
        return $this->belongsTo(YardSlot::class, 'yard_slot_id');
    }

    public function visit()
    {
        return $this->belongsTo(YardVisit::class, 'yard_visit_id');
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
