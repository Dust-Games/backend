<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\HasUuidPrimaryKey;


class OAuthAccount extends Model
{
	use HasUuidPrimaryKey;

	protected $table = 'oauth_account';

    protected $fillable = [
    	'user_id', 'oauth_provider_id', 'account_id', 'username', 'avatar'
    ];

    public function getUserKey()
    {
        return $this->getAttributeFromArray('user_id');
    }

    public function getAccountKey()
    {
        return $this->getAttributeFromArray('account_id');
    }

    public function getUsername()
    {
        return $this->getAttributeFromArray('username');
    }

    public function hasUser()
    {
        return (bool) $this->getUserKey();
    }

    /*|==========| Scopes |==========|*/

    public function scopeUnregistered($query)
    {
        return $query->where('user_id', null);
    }

    public function scopeRegistered($query)
    {
        return $query->where('user_id', '!=', null);
    }

    /*|==========| Relationships |==========|*/

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function billing()
    {
        if ($this->hasUser()) {
            return null;
        } else {
            return $this->hasOne(UnregisteredBilling::class, 'oauth_account_id', 'id');
        }
    }

    public function currencyAccounts()
    {
        return $this->morphMany(CurrencyAccount::class, 'owner');
    }
}
