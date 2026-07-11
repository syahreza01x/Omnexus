<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomOrderRevision extends Model
{
    protected $fillable = [
        'custom_order_id',
        'sender_type',
        'message',
        'attachment_path',
    ];

    public function customOrder()
    {
        return $this->belongsTo(CustomOrder::class);
    }
}
