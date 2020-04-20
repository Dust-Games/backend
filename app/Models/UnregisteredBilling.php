<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OAuthAccount;

class UnregisteredBilling extends Model
{
    protected $table = 'unregistered_billing';

    protected $guarded = [];

    /*|==========| Scopes |==========|*/

	public function scopeWhereAccount($query, $acc_key)
	{
		return $query->where('oauth_account_id', $acc_key);
	}

    /*|==========| Relationships |==========|*/

    public function account()
    {
    	return $this->belongsTo(OAuthAccount::class, 'oauth_account_id', 'id');
    }
}
