<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $casts = [
        'closed' => 'boolean'
    ];

    public $fillable = ['billing_id', 'balance', 'exchange_rate', 'amount'];

    /* Setters */

    public function setBalanceAttribure($value)
    {
        if ($value === 0) {
            $this->attributes['closed'] = true;
        }
        $this->attributes['balance'] = $value;
    }

    /* Relations */

    public function billing()
    {
        return $this->belongsTo(Billing::class, 'billing_id', 'id');
    }
}
