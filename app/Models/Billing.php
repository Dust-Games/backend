<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\HasUuidPrimaryKey;
use App\Concerns\HasDustTokens;
use App\Models\User;

class Billing extends Model
{
	use HasUuidPrimaryKey, HasDustTokens;

	protected $table = 'billing';

	protected $guarded = [];



	public function getDustTokensNumColumnName()
	{
		return 'dust_tokens_num';
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
