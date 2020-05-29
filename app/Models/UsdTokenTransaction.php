<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsdTokenTransaction extends Model
{
    public $fillable = ['operation_id', 'operation_type', 'billing_id', 'debt'];

    /*  debt where true mean minus or false mean plus  */
    public $casts = [
        'debt' => 'boolean'
    ];

    /* Setters */

    public function setDebtAttribute($value)
    {
        $this->attributes['debt'] = boolval($value);
    }

    /* Relations */
    public function operation()
    {
        return $this->morphTo();
    }

    public function billing()
    {
        return $this->belongsTo(Billing::class, 'billing_id', 'id');
    }
}
