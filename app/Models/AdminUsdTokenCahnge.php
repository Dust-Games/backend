<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUsdTokenCahnge extends Model
{
    public $fillable = ['user_id', 'amount'];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->with('usdTokenTransaction.billing')->find($value);
    }

    /* Setters */
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = abs($value);
    }

    /* Relations */

    public function usdTokenTransaction()
    {
        return $this->morphOne(UsdTokenTransaction::class, 'operation');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
