<?php

namespace App\Models;

use App\Concerns\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    use HasUuidPrimaryKey;

    public $fillable = ['debt_id', 'credit_id', 'amount', 'operation_id', 'operation_type'];

    /* Relations */

    public function debtCurrencyAccount()
    {
        return $this->belongsTo(CurrencyAccount::class, 'debt_id');
    }

    public function creditCurrencyAccount()
    {
        return $this->belongsTo(CurrencyAccount::class, 'credit_id');
    }

    public function operation()
    {
        return $this->morphTo();
    }
}
