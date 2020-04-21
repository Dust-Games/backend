<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\HasUuidPrimaryKey;
use App\Concerns\HasDustTokens;
use App\Models\OAuthAccount;

class UnregisteredBilling extends Model
{
    use HasUuidPrimaryKey, HasDustTokens;    
    
    protected $table = 'unregistered_billing';

    protected $guarded = [];



    public function getDustTokensNumColumnName()
    {
        return 'dust_tokens_num';
    }

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
