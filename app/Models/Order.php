<?php

namespace App\Models;

use App\Concerns\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasUuidPrimaryKey;

    public $fillable = ['currency_account_id', 'exchange_rate', 'closed'];

    public $with = ['currencyAccount', 'parentalCurrencyAccount'];

    /* Relations */

    public function parentalCurrencyAccount()
    {
        return $this->belongsTo(CurrencyAccount::class, 'currency_account_id');
    }

    public function currencyAccount()
    {
        return $this->morphOne(CurrencyAccount::class, 'owner');
    }
}
