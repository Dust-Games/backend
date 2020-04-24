<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\HasUuidPrimaryKey;
use App\Concerns\HasDustCoins;
use App\Models\User;

class Billing extends Model
{
	use HasUuidPrimaryKey, HasDustCoins;

	protected $table = 'billing';

	protected $guarded = [];

	protected $casts = [
		'dust_coins_num' => 'decimal:3',
	];

	public function getDustCoinsNumColumn()
	{
		return 'dust_coins_num';
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
