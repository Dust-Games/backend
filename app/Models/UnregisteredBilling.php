<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\HasUuidPrimaryKey;
use App\Concerns\HasDustCoins;
use App\Models\OAuthAccount;

class UnregisteredBilling extends Model
{
    use HasUuidPrimaryKey, HasDustCoins;    
    
    protected $table = 'unregistered_billing';

    protected $guarded = [];

    protected $casts = [
        'dust_coins_num' => 'decimal:3',
    ];

    public function getDustCoinsNumColumn()
    {
        return 'dust_coins_num';
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
