<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\HasUuidPrimaryKey;
use App\Models\User;

class Billing extends Model
{
	use HasUuidPrimaryKey;

	protected $table = 'billing';

	protected $guarded = ['id'];

	/*|==========| Relationships |==========|*/

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
}
