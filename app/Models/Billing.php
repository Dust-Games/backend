<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\HasUuidPrimaryKey;
use App\Concerns\HasDustCoins;

/**
 * @property float usd_tokens_num
 */
class Billing extends Model
{
	use HasUuidPrimaryKey, HasDustCoins;

	protected $table = 'billing';

	protected $guarded = [];

	protected $casts = [
		'dust_coins_num' => 'decimal:3',
	];

	/* Setters */

    public function setUsdTokensNumAttribute($value)
    {
        $this->attributes['usd_tokens_num'] = round($value, 2);
    }

	/* Getters */

	public function getDustCoinsNumColumn()
	{
		return 'dust_coins_num';
	}

	/* Helpers */

    /**
     * @param array $request
     * @return bool
     */
	public function isPossibleChangeUsdToken($request)
    {
        return !$request['debt'] || $this->usd_tokens_num - $request['amount'] >= 0;
    }

	/*|==========| Scopes |==========|*/

	public function scopeWhereUser($query, $user_key)
	{
		return $query->where('user_id', $user_key);
	}

	/*|==========| Relationships |==========|*/

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
}
