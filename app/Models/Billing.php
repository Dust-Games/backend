<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\HasUuidPrimaryKey;
use App\Models\User;

class Billing extends Model
{
	use HasUuidPrimaryKey;

	protected $table = 'billing';

	protected $guarded = [];

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
