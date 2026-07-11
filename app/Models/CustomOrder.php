<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomOrder extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'category',
        'material',
        'quantity',
        'user_design_path',
        'notes',
        'admin_design_path',
        'price_per_item',
        'status',
        'transaction_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function revisions()
    {
        return $this->hasMany(CustomOrderRevision::class);
    }
}
