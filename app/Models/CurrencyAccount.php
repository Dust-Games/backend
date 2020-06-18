<?php

namespace App\Models;

use App\Concerns\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Model;

class CurrencyAccount extends Model
{
    use HasUuidPrimaryKey;

    public $fillable = ['owner_id', 'owner_type', 'currency_id', 'balance', 'id', 'closed'];

    public $casts = ['closed' => 'boolean'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->currency_id = $model->currency_id
                ?? Currency::query()->firstWhere('alias', config('app.default_currency'))->id;
        });
    }

    /* Relations */
    public function owner()
    {
        return $this->morphTo();
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
