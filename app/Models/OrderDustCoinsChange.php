<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDustCoinsChange extends Model
{
    public $fillable = ['amount', 'order_id'];

    /* Relations */

    public function dustCoinTransaction()
    {
        return $this->morphOne(DustCoinTransaction::class, 'operation');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
